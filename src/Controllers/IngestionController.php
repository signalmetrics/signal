<?php

namespace Signalmetrics\Signal\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Signalmetrics\Signal\Actions\Ingestion\CreateHashes;
use Signalmetrics\Signal\Actions\Ingestion\DetectCountry;
use Signalmetrics\Signal\Actions\Ingestion\DetectCrawler;
use Signalmetrics\Signal\Actions\Ingestion\DetectSpam;
use Signalmetrics\Signal\Actions\Ingestion\DetectUserAgent;
use Signalmetrics\Signal\Actions\Ingestion\GetIP;
use Signalmetrics\Signal\Actions\Ingestion\PersistEvent;
use Signalmetrics\Signal\Actions\Ingestion\RemovePersonalDetails;
use Signalmetrics\Signal\Actions\Ingestion\StoreDuration;
use Signalmetrics\Signal\Drawer\Pipeline;
use Signalmetrics\Signal\Models\SignalEvent;

class IngestionController extends Controller {

    public function store(): SignalEvent
    {
        $event = $this->initializeEvent();

        return match ($event->type) {
            'page_view' => $this->processPageView($event),
            'page_unload' => $this->processPageUnload($event),
            default => $this->processEvent($event)
        };
    }

    /**
     * Regardless of what kind of event we get, we always initialize the
     * same metadata for consistency. This metadata mostly comes from
     * the frontend, but some is determined by the server.
     */
    public function initializeEvent(): SignalEvent
    {
        /**
         * POST comes from beacon, and is stored as json data.
         * GET comes from pageview, and is stored differently.
         */
        if (request()->isMethod('post')) {
            $metadata = request()->json('metadata');
            $type = request()->json('type');
            $data = request()->json('data');
        } else {
            $metadata = json_decode(request()->get('metadata'), true); // the "true" is to an array
            $type = request()->get('type');
            $data = request()->get('data');
        }

        /**
         * The UTM codes get automatically parsed, since this is such a significant
         * convention for marketing sites.
         */
        $utm = request()->only(['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content']);

        /**
         * We pull out the epochal timestamp to set the created_at time to the exact
         * moment the event was fired in the frontend. This is useful since we
         * may be queuing events for quite a long time.
         */
        $timestampMilliseconds = substr($metadata['dispatch_moment'], 0, 13);

        $parsedUrl = parse_url($metadata['url']);

        return new SignalEvent([
            'visit_signature' => $metadata['visit_signature'],
            'type' => $type,
            'custom_user_id' => $metadata['custom_user_id'] ?? null,
            'title' => $metadata['title'],
            'referrer' => $metadata['referrer'],
            'host_name' => $parsedUrl['host'],
            'path_name' => $parsedUrl['path'],
//            'viewport' => new XYDimension($metadata['viewport']['x'], $metadata['viewport']['y']),
//            'screen_resolution' => new XYDimension($metadata['screen_resolution']['x'], $metadata['screen_resolution']['y']),
//            'connection_type' => $metadata['connection_type'],
            'user_agent' => request()->headers->get('user_agent'),
            'language' => $metadata['language'] ?? null,
            'page_load_time_ms' => $metadata['page_load_time_ms'],
            'data' => $data,
            'utm_source' => $utm['utm_source'] ?? null,
            'utm_medium' => $utm['utm_medium'] ?? null,
            'utm_campaign' => $utm['utm_campaign'] ?? null,
            'utm_term' => $utm['utm_term'] ?? null,
            'utm_content' => $utm['utm_content'] ?? null,

            'created_at' => Carbon::createFromTimestampMs($timestampMilliseconds)
        ]);
    }

    /**
     * Process page_view events.
     */
    protected function processPageView(SignalEvent $event): SignalEvent
    {
        return Pipeline::send($event)
            ->through([
                GetIP::class,
                DetectSpam::class,
                DetectUserAgent::class,
                CreateHashes::class,

                DetectCountry::class,


                // Clear IP for privacy folks.
                RemovePersonalDetails::class,

                PersistEvent::class
            ])
            ->thenReturn();
    }

    /**
     * When someone closes out of a tab, we store the duration of
     * their visit on the original pageview.
     *
     * I think the only thing we need to do for this is store duration.
     */
    protected function processPageUnload(SignalEvent $event): SignalEvent
    {
        return Pipeline::send($event)
            ->through([
                StoreDuration::class,
            ])
            ->thenReturn();
    }

    protected function processEvent(SignalEvent $event): SignalEvent
    {
        return Pipeline::send($event)
            ->through([
                GetIP::class,
                DetectCrawler::class,
                DetectSpam::class,
                CreateHashes::class,

                // Get Country
                // Get mobile vs desktop
                // Get unique

                // Clear IP when in Privacy Mode
                RemovePersonalDetails::class,

                // Save the event
                PersistEvent::class
            ])
            ->thenReturn();
    }


}
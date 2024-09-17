<?php

namespace Signalmetrics\Signal\Actions\Ingestion;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Signalmetrics\Signal\Drawer\PipeInterface;
use Signalmetrics\Signal\Exceptions\SpamDetectedException;
use Signalmetrics\Signal\Models\SignalEvent;

class DetectCrawler implements PipeInterface {

    /**
     * @param SignalEvent $event
     * @param $next
     * @return mixed
     * @throws SpamDetectedException
     */
    public function handle(SignalEvent $event, $next)
    {
        $CrawlerDetect = new CrawlerDetect;

        // Pass a user agent as a string
        if ($CrawlerDetect->isCrawler($event->user_agent)) {
            $event->crawler = $CrawlerDetect->getMatches();
        }

        return $next($event);
    }

}
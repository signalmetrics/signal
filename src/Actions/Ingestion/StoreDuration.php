<?php

namespace Signalmetrics\Signal\Actions\Ingestion;

use Signalmetrics\Signal\Drawer\PipeInterface;
use Signalmetrics\Signal\Models\SignalEvent;

class StoreDuration implements PipeInterface {

    /**
     * @TODO this could be more efficient. Could we use SQL to update directly?
     */
    public function handle(SignalEvent $event, $next)
    {
        /**
         * Find the event where the visit_signature was first created. This is the starting pageview
         */
        $original_event = SignalEvent::where('visit_signature', $event->visit_signature)->first();

        /**
         * Calculate how much time has passed since that visit kicked off.
         */
        $duration = $original_event->created_at->diffInSeconds($event->created_at);

        /**
         * Update the duration of the original event.
         * No visit can be longer than 60 minutes * 60 seconds = 3600 seconds
         */
        if ($duration < 3600) {
            $original_event->update([
                'duration' => $duration
            ]);
        }

        return $next($event);
    }


}
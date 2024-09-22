<?php

namespace Signalmetrics\Signal\Actions\Ingestion;

use Signalmetrics\Signal\Drawer\PipeInterface;
use Signalmetrics\Signal\Models\SignalEvent;
use Signalmetrics\Signal\Models\SignalToday;

class StoreDuration implements PipeInterface {

    /**
     * @TODO this could be more efficient. Could we use SQL to update directly?
     */
    public function handle(SignalToday $event, $next)
    {
        /**
         * Find the event where the visit_signature was first created. This is the starting pageview.
         * We need to check both potential tables for the visit signature.
         */
        $original_event = SignalToday::where('visit_signature', $event->visit_signature)->first();
        if (!$original_event) {
            $original_event = SignalEvent::where('visit_signature', $event->visit_signature)->first();
        }

        // Possible we couldn't find event, so continue.
        if (!$original_event) return $next($event);

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
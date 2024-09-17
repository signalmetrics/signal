<?php

namespace Signalmetrics\Signal\Actions\Ingestion;

use Signalmetrics\Signal\Drawer\PipeInterface;
use Signalmetrics\Signal\Models\SignalEvent;

class RemovePersonalDetails implements PipeInterface {

    public function handle(SignalEvent $event, $next)
    {
        if (config('signal.privacy_mode')) {
            $event->ip = null;
            $event->user_agent = null;
        }

        return $next($event);
    }


}
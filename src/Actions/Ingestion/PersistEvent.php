<?php

namespace Signalmetrics\Signal\Actions\Ingestion;

use Signalmetrics\Signal\Drawer\PipeInterface;
use Signalmetrics\Signal\Models\SignalEvent;

class PersistEvent implements PipeInterface {

    public function handle(SignalEvent $event, $next)
    {

        if ($event->crawler === null) {
            $event->save();
        }

        return $next($event);
    }


}
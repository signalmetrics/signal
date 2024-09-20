<?php

namespace Signalmetrics\Signal\Actions\Ingestion;

use Illuminate\Support\Facades\DB;
use Signalmetrics\Signal\Drawer\PipeInterface;
use Signalmetrics\Signal\Models\SignalToday;

class PersistEvent implements PipeInterface {

    public function handle(SignalToday $event, $next)
    {

        if ($event->crawler === null) {
            DB::connection('signal')->table('today')->insert($event->getAttributes());
        }

        return $next($event);
    }


}
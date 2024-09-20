<?php

namespace Signalmetrics\Signal\Drawer;

use Signalmetrics\Signal\Models\SignalEvent;
use Signalmetrics\Signal\Models\SignalToday;

interface PipeInterface {

    public function handle(SignalToday $event, $next);

}
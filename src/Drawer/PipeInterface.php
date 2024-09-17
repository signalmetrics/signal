<?php

namespace Signalmetrics\Signal\Drawer;

use Signalmetrics\Signal\Models\SignalEvent;

interface PipeInterface {

    public function handle(SignalEvent $event, $next);

}
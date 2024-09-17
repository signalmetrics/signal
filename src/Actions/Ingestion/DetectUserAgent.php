<?php

namespace Signalmetrics\Signal\Actions\Ingestion;

use Jenssegers\Agent\Agent;
use Signalmetrics\Signal\Drawer\PipeInterface;
use Signalmetrics\Signal\DTO\DeviceType;
use Signalmetrics\Signal\Exceptions\SpamDetectedException;
use Signalmetrics\Signal\Models\SignalEvent;

class DetectUserAgent implements PipeInterface {

    /**
     * @param SignalEvent $event
     * @param $next
     * @return mixed
     * @throws SpamDetectedException
     */
    public function handle(SignalEvent $event, $next)
    {
        $agent = new Agent();

        $agent->setUserAgent($event->user_agent);

        $event->device_type = match (true) {
            $agent->isDesktop() => DeviceType::Desktop,
            $agent->isTablet() => DeviceType::Tablet,
            $agent->isPhone() => DeviceType::Mobile,
            default => null
        };

        $event->device_name = $agent->device(); // "Macintosh"
        $event->platform = $agent->platform(); // "OS X"
        $event->browser = $agent->browser(); // "Chrome"

        if ($agent->isRobot()) {
            $event->crawler = $agent->robot();
        }

        return $next($event);
    }

}
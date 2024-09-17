<?php

namespace Signalmetrics\Signal\Actions\Ingestion;

use Signalmetrics\Signal\Drawer\PipeInterface;
use Signalmetrics\Signal\Exceptions\SpamDetectedException;
use Signalmetrics\Signal\Models\IPAddress;
use Signalmetrics\Signal\Models\SignalEvent;

class DetectSpam implements PipeInterface {

    /**
     * @param SignalEvent $event
     * @param $next
     * @return mixed
     * @throws SpamDetectedException
     */
    public function handle(SignalEvent $event, $next)
    {
        $ip = IPAddress::updateOrCreate(['ip' => $event->ip]);

        $ip->increment('visits');

        if ($ip->visits > config('signal.spam_threshold')) {
            $this->handleSpam();
        };

        return $next($event);
    }

    /**
     * @return mixed
     * @throws SpamDetectedException
     * @TODO we need to catch the spam and remove other visits which correspond to this user.
     */
    protected function handleSpam()
    {
        throw new SpamDetectedException();
    }

}
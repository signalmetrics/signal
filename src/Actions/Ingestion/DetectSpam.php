<?php

namespace Signalmetrics\Signal\Actions\Ingestion;

use Signalmetrics\Signal\Drawer\PipeInterface;
use Signalmetrics\Signal\Exceptions\SpamDetectedException;
use Signalmetrics\Signal\Models\IPAddress;
use Signalmetrics\Signal\Models\SignalEvent;
use Signalmetrics\Signal\Models\SignalToday;

class DetectSpam implements PipeInterface {

    /**
     * @param SignalToday $event
     * @param $next
     * @return mixed
     * @throws SpamDetectedException
     */
    public function handle(SignalToday $event, $next)
    {
        $ip = IPAddress::updateOrCreate(['ip' => $event->ip]);

        $ip->increment('visits');

        if ($ip->visits > config('signal.spam_threshold')) {
            $this->handleSpam($ip, $event);
        };

        return $next($event);
    }

    /**
     * @return mixed
     * @throws SpamDetectedException
     * Deletes the visits we had from that user.
     */
    protected function handleSpam(IPAddress $IPAddress, SignalToday $event)
    {
        // Delete old events which came from the spamming user.
        SignalToday::where('user_hash', $event->user_hash)->delete();

        // Blacklist for a week
        $IPAddress->update(['blacklist_at' => now(), 'blacklist_expires_at' => now()->addWeek()]);

        // Throw exception to stop executing this request.
        throw new SpamDetectedException();
    }

}
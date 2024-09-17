<?php

namespace Signalmetrics\Signal\Actions\Ingestion;

use Signalmetrics\Signal\Drawer\PipeInterface;
use Signalmetrics\Signal\Models\SignalEvent;

class CreateHashes implements PipeInterface {

    /**
     * We are getting the IP and host name and
     * hashing that together to pseudoanonymize for GDPR.
     *
     * Then, we store the user + path together to determine
     * if this user has visited this page before.
     */
    public function handle(SignalEvent $event, $next)
    {
        $event->user_hash = hash('xxh128', $event->ip . $event->host_name);
        $event->page_view_hash = hash('xxh128', $event->user_hash . $event->path_name);

        return $next($event);
    }

}
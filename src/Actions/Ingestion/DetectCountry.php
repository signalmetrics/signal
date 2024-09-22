<?php

namespace Signalmetrics\Signal\Actions\Ingestion;

use GeoIp2\Database\Reader;
use MaxMind\Db\Reader\InvalidDatabaseException;
use Signalmetrics\Signal\Drawer\PipeInterface;
use Signalmetrics\Signal\Models\SignalEvent;
use Signalmetrics\Signal\Models\SignalToday;

class DetectCountry implements PipeInterface {

    public Reader $reader;

    /**
     * @throws InvalidDatabaseException
     */
    public function __construct()
    {
        $location_database_path = base_path('vendor/signalmetrics/signal/ip_service/locations.mmdb');
        $this->reader = new Reader($location_database_path);
    }

    public function handle(SignalToday $event, $next)
    {

        try {
            $record = $this->reader->city($event->ip);
        } catch (\Exception $e) {
            info("IP address was not valid – {$event->ip}");
        }

        if (isset($record)) {
            $event->country_code = $record->country->isoCode;
            $event->city = $record->city->name;
        }

        return $next($event);

    }

}
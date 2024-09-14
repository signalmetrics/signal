<?php

namespace Signalmetrics\Signal\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Signalmetrics\Signal\Models\IPAddress;
use Signalmetrics\Signal\Models\SignalEvent;

class IngestionController extends Controller {

    public function store()
    {

        /**
         * POST comes from beacon, and is stored as json data.
         * GET comes from pageview, and is stored differently.
         */
        if (request()->isMethod('post')) {
            $metadata = request()->json('metadata');
            $type = request()->json('type');
            $data = request()->json('data');
        } else {
            $metadata = json_decode(request()->get('metadata'), true); // the "true" is to an array
            $type = request()->get('type');
            $data = request()->get('data');
        }


        $metadata['ip'] = $this->getIp();
        $metadata['languages'] = request()->getLanguages();
        $metadata['user'] = $this->getUserHash();
        $metadata['referer_2'] = request()->headers->get('referer'); // null if made directly.

        if ($this->detectSpam($metadata['ip'])) return;

        SignalEvent::insert([
            [
                'type' => $type ?? 'broken',
                'data' => json_encode($data),
                'metadata' => json_encode($metadata),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

    }

    private function getUserHash(): string
    {
        $ip = $this->getIp();
        $userAgent = request()->headers->get('user_agent');
        $hostname = request()->getHost();

        $uniqueData = $ip . $userAgent . $hostname;

        return hash('xxh128', $uniqueData);

    }

    private function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return the server IP if the client IP is not found using this method.
    }

    /**
     * Determines if an IP address has more than the number of allowed daily visits.
     */
    public function detectSpam($ip): bool
    {
        $ip = IPAddress::updateOrCreate([
            ['address' => $ip],
            ['visits' => DB::raw('visits + 1')]
        ]);

        return ($ip->visits > config('signal.spam_threshold'));
    }

}
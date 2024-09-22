<?php

namespace Signalmetrics\Signal\Factories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Signalmetrics\Signal\Models\SignalToday;

/**
 * We have to create fake requests for testing.
 */
class MockRequest {

    public static function get(?array $metadata = []): Request
    {

        // Get defaults from the factory.
        $default = SignalToday::factory()->make();

        // Define the request data
        $requestData = [
            'type' =>  $metadata['type'] ?? 'page_view',
            'metadata' => json_encode(array_merge($default->toArray(), $metadata)), // Either use the defaults from the factory or the defined metadata
            'data' => json_encode([
                'key' => 'value'
            ]),
        ];

        // Set custom headers, including User-Agent and HTTP_CLIENT_IP
        $serverHeaders = [
            'HTTP_USER_AGENT' => $metadata['user_agent'] ?? $default->user_agent,
            'HTTP_CLIENT_IP' => $metadata['ip'] ?? $default->ip, // Simulate client IP
            'REMOTE_ADDR' => $metadata['ip'] ?? $default->ip, // Simulate remote IP
        ];

        // Create a custom request object with headers
        $fake_request = Request::create('/analytics/event', 'GET', $requestData, [], [], $serverHeaders);
        App::instance('request', $fake_request);  // IMPORTANT! this is what makes request() work.
        return $fake_request;
    }

}
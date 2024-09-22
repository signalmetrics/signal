<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Signalmetrics\Signal\Controllers\IngestionController;
use Signalmetrics\Signal\Models\SignalToday;
use Carbon\Carbon;

// This trait rolls back the database after each test.
// For whatever reason, using it isn't actually working.
//uses(RefreshDatabase::class);

it('stores a SignalToday record when a POST request is made', function () {

    $moment = now();

    /**
     * Assemble. This sets up request() to get sent to controller.
     */
    Signalmetrics\Signal\Factories\MockRequest::get([
        'visit_signature' => 999123,
        'dispatch_moment' => $moment->timestamp,
        'title' => 'Ahoy tester!',
        'type' => 'page_view',
        'custom_user_id' => 43,
        'ip' => '74.115.209.58', // for San Diego
        'url' => 'https://iambateman.com/smile'
    ]);

    /**
     * Act. This ingests the event.
     */
    (new IngestionController())->store();

    /**
     * Assert. The database has everything.
     */
    $this->assertDatabaseHas('today', [
        'visit_signature' => 999123,
        'type' => 'page_view',
        'custom_user_id' => 43,
        'ip' => '74.115.209.58',
        'country_code' => 'US',
        'city' => 'San Diego',
        'title' => 'Ahoy tester!',
//        'referrer' => $metadata['referrer'],
        'host_name' => 'iambateman.com',  // Extracted from URL
        'path_name' => '/smile',   // Extracted from URL
//        'user_agent' => 'Mozilla/5.0',
//        'utm_source' => $utm['utm_source'],
//        'utm_medium' => $utm['utm_medium'],
//        'utm_campaign' => $utm['utm_campaign'],
//        'utm_term' => $utm['utm_term'],
//        'utm_content' => $utm['utm_content'],
        'created_at' => Carbon::createFromTimestampMs(now()->timestamp),
    ]);
});

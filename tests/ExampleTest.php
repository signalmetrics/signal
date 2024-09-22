<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Signalmetrics\Signal\Models\SignalToday;

//uses(RefreshDatabase::class);

it('can test', function () {
    expect(true)->toBeTrue();
});

it('can store', function () {

    $event = SignalToday::factory()->create();
    $event = SignalToday::factory()->create();
    expect(SignalToday::count())->toEqual(1);
});

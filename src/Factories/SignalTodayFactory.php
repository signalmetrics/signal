<?php

namespace Signalmetrics\Signal\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalmetrics\Signal\Models\SignalEvent;
use Signalmetrics\Signal\Models\SignalToday;

class SignalTodayFactory extends Factory {

    protected $model = SignalToday::class;

    use SignalEventFactoryTrait;

}

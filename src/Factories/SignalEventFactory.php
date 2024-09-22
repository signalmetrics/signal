<?php

namespace Signalmetrics\Signal\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalmetrics\Signal\Models\SignalEvent;

class SignalEventFactory extends Factory {

    protected $model = SignalEvent::class;

    use SignalEventFactoryTrait;

}

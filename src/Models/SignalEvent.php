<?php

namespace Signalmetrics\Signal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalmetrics\Signal\Factories\SignalEventFactory;

class SignalEvent extends Model {

    use HasFactory;

    protected $connection = 'signal';
    protected $table = 'events';
    protected $primaryKey = 'visit_signature';

    protected $guarded = [];


    protected function casts(): array
    {
        return [
//            'data' => 'json',
//            'screen_resolution' => XYDimension::class,
//            'viewport' => XYDimension::class,
        ];
    }

    protected static function newFactory(): SignalEventFactory
    {
        return new SignalEventFactory();
    }

    /**
     * This is used for the factory to get a random visit signature.
     */
    public static function generateRandomVisitSignature($max_days = 90)
    {
        // Get a random point in time in the past 90
        $random_day_int = random_int(0, $max_days);
        $random_hour_int = random_int(0, 23);
        $random_minute = random_int(0, 59);
        $random_second = random_int(0, 59);
        $date = now()
            ->subDay($random_day_int)
            ->subHours($random_hour_int)
            ->subMinutes($random_minute)
            ->subSeconds($random_second);

        // Add a random number that's 5 digits.
        $random_int = random_int(10000, 99999);

        return ['date' => $date, 'signature' => $date->timestamp . $random_int];
    }

}

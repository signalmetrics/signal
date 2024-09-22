<?php

namespace Signalmetrics\Signal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalmetrics\Signal\Factories\SignalEventFactory;
use Signalmetrics\Signal\Factories\SignalTodayFactory;

class SignalToday extends Model {

    use HasFactory;

    protected $connection = 'signal';
    protected $table = 'today';
    protected $primaryKey = 'visit_signature';
    protected $guarded = [];

    protected static function newFactory(): SignalTodayFactory
    {
        return new SignalTodayFactory();
    }

}

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

    protected static function newFactory(): SignalEventFactory
    {
        return new SignalEventFactory();
    }

}

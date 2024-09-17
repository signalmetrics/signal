<?php

namespace Signalmetrics\Signal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Signalmetrics\Signal\DTO\XYDimension;

class SignalEvent extends Model {

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
}

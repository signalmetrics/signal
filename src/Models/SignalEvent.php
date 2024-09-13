<?php

namespace Signalmetrics\Signal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignalEvent extends Model {

    protected $connection = 'signal';
    protected $table = 'signal_events';

    protected $guarded = [];


    protected function casts(): array
    {
        return [
            'data' => 'json',
            'metadata' => 'json',
            'ends_at' => 'datetime',
        ];
    }
}

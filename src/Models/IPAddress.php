<?php

namespace Signalmetrics\Signal\Models;

use Illuminate\Database\Eloquent\Model;

class IPAddress extends Model {

    protected $connection = 'signal';
    protected $table = 'ip_addresses';

    protected $guarded = [];

}

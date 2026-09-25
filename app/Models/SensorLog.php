<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
    protected $fillable = [
        'voltage',
        'current',
        'power_watt',
        'kwh_incremental',
    ];

    public $timestamps = true;
}

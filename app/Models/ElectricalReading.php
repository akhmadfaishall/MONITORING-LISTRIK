<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectricalReading extends Model
{
    protected $fillable = [
        'device_id',
        'voltage',
        'current',
        'power',
        'energy',
        'frequency',
        'power_factor',
        'apparent_power',
    ];

    protected $casts = [
        'voltage' => 'float',
        'current' => 'float',
        'power' => 'float',
        'energy' => 'float',
        'frequency' => 'float',
        'power_factor' => 'float',
        'apparent_power' => 'float',
    ];
}
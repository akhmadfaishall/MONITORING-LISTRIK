<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyLog extends Model
{
    protected $fillable = [
        'gas_ppm',
        'temperature',
        'humidity',
        'gas_danger',
        'buzzer_state',
        'relay_state',
    ];
    protected $casts = [
        'gas_danger' => 'boolean',
    ];
}

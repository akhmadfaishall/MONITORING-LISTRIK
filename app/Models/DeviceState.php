<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceState extends Model
{
    protected $fillable = [
        'device_name',
        'state',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $fillable = [
        'date',
        'kwh_predict',
    ];

    public $timestamps = false;
}

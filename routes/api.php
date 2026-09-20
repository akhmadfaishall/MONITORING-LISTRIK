<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ElectricalReadingController;
use App\Http\Controllers\Api\PredictionController;

Route::post('/readings', [ElectricalReadingController::class, 'store']);

Route::get('/readings/latest', [ElectricalReadingController::class, 'latest']);

Route::get('/readings/history', [ElectricalReadingController::class, 'history']);

Route::get('/predict', [PredictionController::class, 'predict']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
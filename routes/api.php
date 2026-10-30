<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ElectricalReadingController;
use App\Http\Controllers\Api\PredictionController;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\SystemApiController;

Route::post('/readings', [ElectricalReadingController::class, 'store']);

Route::get('/readings/latest', [ElectricalReadingController::class, 'latest']);

Route::get('/readings/history', [ElectricalReadingController::class, 'history']);

// Endpoint untuk diakses oleh ESP32 (POST)
Route::post('/sensor-data', [SensorController::class, 'store']);

Route::get('/predict', [PredictionController::class, 'predict']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Ringkasan status untuk n8n AI & Polling Dashboard
Route::get('/system/status-summary', [SystemApiController::class, 'getStatusSummary']);

// Kontrol perangkat dari tombol Web atau n8n Action
Route::post('/device/control', [SystemApiController::class, 'controlDevice']);
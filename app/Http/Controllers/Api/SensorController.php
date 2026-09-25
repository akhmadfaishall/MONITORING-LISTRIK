<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorLog;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'power_watt' => 'required|numeric',
        ]);

        $powerWatt = $request->power_watt;

        // ASUMSI: ESP32 mengirim data setiap 1 menit (60 detik)
        // Rumus: Watt / 60.000 = kWh per 1 menit
        $kwhIncremental = $powerWatt / 60000;

        SensorLog::create([
            'voltage'         => $request->voltage,
            'current'         => $request->current,
            'power_watt'      => $powerWatt,
            'kwh_incremental' => $kwhIncremental,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data sensor berhasil disimpan'
        ], 201);
    }
}

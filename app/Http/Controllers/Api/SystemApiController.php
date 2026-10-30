<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorLog;
use App\Models\SafetyLog;
use App\Models\DeviceState;
use App\Models\Prediction;
use App\Services\MqttService;

class SystemApiController extends Controller
{
    /**
     * Endpoint untuk n8n AI & Dashboard Web
     * Mengembalikan ringkasan data sensor terkini & status perangkat
     */
    public function getStatusSummary()
    {
        // 1. Ambil data listrik terbaru dari ESP32 #1
        $latestElectric = SensorLog::latest()->first();

        // 2. Ambil data keamanan terbaru dari ESP32 #2
        $latestSafety = SafetyLog::latest()->first();

        // 3. Ambil status saklar/aktuator terkini
        $deviceStates = DeviceState::pluck('state', 'device_name')->toArray();

        // 4. Ambil prediksi kWh terbaru jika ada
        $latestPrediction = Prediction::latest()->first();

        return response()->json([
            'status' => 'success',
            'timestamp' => now()->toDateTimeString(),
            'listrik' => [
                'voltage'      => $latestElectric ? (float) $latestElectric->voltage : 0,
                'current'      => $latestElectric ? (float) $latestElectric->current : 0,
                'power_watt'   => $latestElectric ? (float) $latestElectric->power_watt : 0,
                'energy_kwh'   => $latestElectric ? (float) $latestElectric->kwh_incremental : 0,
                'last_updated' => $latestElectric ? $latestElectric->created_at->diffForHumans() : 'Belum ada data',
            ],
            'keamanan' => [
                'gas_ppm'      => $latestSafety ? (float) $latestSafety->gas_ppm : 0,
                'temperature'  => $latestSafety ? (float) $latestSafety->temperature : 0,
                'humidity'     => $latestSafety ? (float) $latestSafety->humidity : 0,
                'gas_danger'   => $latestSafety ? (bool) $latestSafety->gas_danger : false,
                'status'       => ($latestSafety && $latestSafety->gas_danger) ? 'BAHAYA' : 'AMAN',
                'last_updated' => $latestSafety ? $latestSafety->created_at->diffForHumans() : 'Belum ada data',
            ],
            'perangkat' => [
                'relay_led' => $deviceStates['relay_led'] ?? 'OFF',
                'buzzer'    => $deviceStates['buzzer'] ?? 'OFF',
            ],
            'prediksi' => [
                'predicted_kwh' => $latestPrediction ? (float) $latestPrediction->predicted_kwh : null,
            ]
        ]);
    }

    /**
     * Endpoint untuk mengontrol Relay/LED/Buzzer
     * Dipanggil dari Tombol Web Dashboard atau n8n Action Tool
     */
    public function controlDevice(Request $request)
    {
        $request->validate([
            'device' => 'required|string',         // misal: 'relay_led', 'buzzer'
            'state'  => 'required|in:ON,OFF,on,off' // status yang diinginkan
        ]);

        $device = strtolower($request->device);
        $state  = strtoupper($request->state);

        // 1. Simpan perkiraan state ke database agar respon web instan
        DeviceState::updateOrCreate(
            ['device_name' => $device],
            ['state' => $state]
        );

        // 2. Publish perintah ke MQTT Broker agar diterima oleh ESP32 #2
        $payload = [
            'target'    => $device,
            'action'    => $state,
            'timestamp' => now()->toIso8601String()
        ];

        try {
            MqttService::publish('kelompok20/control/command', $payload);

            return response()->json([
                'status'  => 'success',
                'message' => "Perintah {$state} untuk {$device} berhasil dikirim ke MQTT",
                'data'    => $payload
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengirim instruksi ke MQTT: ' . $e->getMessage()
            ], 500);
        }
    }
}
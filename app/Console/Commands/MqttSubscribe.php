<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MqttService;
use App\Models\SensorLog;
use App\Models\SafetyLog;
use App\Models\DeviceState;
use App\Models\ElectricalReading;

class MqttSubscribe extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Mendengarkan topic MQTT dari ESP32 dan menyimpannya ke database';

    public function handle()
    {
        $this->info("Menghubungkan ke EMQX Cloud...");

        try {
            $client = MqttService::getClient('laravel_listener');
            $this->info("Berhasil terhubung! Mulai mendengarkan topic: kelompok20/#");

            $client->subscribe('kelompok20/#', function (string $topic, string $message) {
                $this->info("\n[PESAN DITERIMA] Topic: $topic");
                $data = json_decode($message, true);

                if (!$data) {
                    $this->warn("Payload bukan JSON valid: $message");
                    return;
                }

                // 1. DATA LISTRIK DARI ESP32 #1 (PZEM)
                if (str_contains($topic, 'pzem/data')) {
                    $powerWatt = $data['power'] ?? $data['power_watt'] ?? 0;
                    $kwhInc = $data['kwh_incremental'] ?? ($powerWatt / 60000);

                    // A. Simpan ke sensor_logs (untuk Machine Learning & Prediksi Token)
                    SensorLog::create([
                        'voltage'         => $data['voltage'] ?? 0,
                        'current'         => $data['current'] ?? 0,
                        'power_watt'      => $powerWatt,
                        'kwh_incremental' => $kwhInc,
                    ]);

                    // B. Simpan ke electrical_readings (untuk Card Metrik Tegangan/Watt & Grafik Realtime Dashboard)
                    $volt = $data['voltage'] ?? 0;
                    $curr = $data['current'] ?? 0;
                    ElectricalReading::create([
                        'device_id'      => $data['device_id'] ?? 'ESP32_PZEM_01',
                        'voltage'        => $volt,
                        'current'        => $curr,
                        'power'          => $powerWatt,
                        'energy'         => $data['energy'] ?? null,
                        'frequency'      => $data['frequency'] ?? 50.0,
                        'power_factor'   => $data['pf'] ?? $data['power_factor'] ?? null,
                        'apparent_power' => round($volt * $curr, 2),
                    ]);

                    $this->info("-> Tersimpan ke sensor_logs & electrical_readings (Daya: {$powerWatt} W | Volt: {$volt} V)");
                }

                // 2. DATA KEAMANAN DARI ESP32 #2 (MQ2 & DHT22)
                elseif (str_contains($topic, 'safety/data')) {
                    SafetyLog::create([
                        'gas_ppm'      => $data['gas_ppm'] ?? 0,
                        'temperature'  => $data['temperature'] ?? 0,
                        'humidity'     => $data['humidity'] ?? 0,
                        'gas_danger'   => $data['gas_danger'] ?? false,
                        'buzzer_state' => $data['buzzer'] ?? 'OFF',
                        'relay_state'  => $data['relay_led'] ?? 'OFF',
                    ]);

                    // Update status perangkat terkini
                    if (isset($data['relay_led'])) {
                        DeviceState::updateOrCreate(['device_name' => 'relay_led'], ['state' => $data['relay_led']]);
                    }
                    if (isset($data['buzzer'])) {
                        DeviceState::updateOrCreate(['device_name' => 'buzzer'], ['state' => $data['buzzer']]);
                    }
                    $this->info("-> Tersimpan ke safety_logs & status terupdate");

                    // AUTO-CLEANUP: Hapus data log keamanan yang lebih tua dari 24 jam (dijalankan berkala)
                    static $lastCleanup = 0;
                    if (time() - $lastCleanup > 3600) { // Cek tiap 1 jam sekali
                        $lastCleanup = time();
                        $deleted = SafetyLog::where('created_at', '<', now()->subDay())->delete();
                        if ($deleted > 0) {
                            $this->info("-> [AUTO-CLEANUP] Berhasil menghapus {$deleted} data safety_logs lama (> 24 jam).");
                        }
                    }
                }

                // 3. STATUS FEEDBACK KONTROL
                elseif (str_contains($topic, 'control/status')) {
                    if (isset($data['device']) && isset($data['state'])) {
                        DeviceState::updateOrCreate(
                            ['device_name' => $data['device']],
                            ['state' => $data['state']]
                        );
                        $this->info("-> DeviceState diupdate: {$data['device']} = {$data['state']}");
                    }
                }
            }, 0);

            $client->loop(true);

        } catch (\Throwable $e) {
            $this->error("Gagal terhubung ke MQTT: " . $e->getMessage());
        }
    }
}
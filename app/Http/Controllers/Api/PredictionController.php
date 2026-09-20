<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ElectricalReading;
use Symfony\Component\Process\Process;

class PredictionController extends Controller
{
    // Menjalankan model AI prediksi 
    public function predict(){
        // Ambil data dari MySQL
        $latest = ElectricalReading::orderBy('created_at', 'desc')->first();

        $now = Carbon::now('Asia/Jakarta');

        // Jika sensor belum mengirim data, gunakan fallback standar 900 VA
        $voltage = $latest && $latest->voltage ? (float) $latest->voltage : 220.0;
        $current = $latest && $latest->current ? (float) $latest->current : 1.8;
        $power   = $latest && $latest->power   ? (float) $latest->power   : 380.0;

        $dayOfWeek = $now->dayOfWeekIso - 1; // 0 = Senin dst
        $isWeekend = $now->isWeekend() ? 1 : 0;
        $month     = $now->month;

        // Hitung konsumsi kemarin dan riwayat yang ada di MySQL
        $lag1Energy     = 7.5;
        $rollingMean7d  = 8.0; // (Tip: perbaikan typo nama variabel)

        $yesterday = $now->copy()->subDay()->toDateString();
        $yesterdayReadings = ElectricalReading::whereDate('created_at', $yesterday);
        
        if($yesterdayReadings->count() > 0){
            $minEnergy = $yesterdayReadings->min('energy');
            $maxEnergy = $yesterdayReadings->max('energy');
            if($maxEnergy !== null && $minEnergy !== null && $maxEnergy >= $minEnergy){
                $lag1Energy = round($maxEnergy - $minEnergy, 2);
            }
        }

        // Susun 8 fitur yang dibutuhkan
        $inputData = [
            'voltage'         => $voltage,
            'current'         => $current,
            'power'           => $power,
            'day_of_week'     => $dayOfWeek,
            'is_weekend'      => $isWeekend,
            'month'           => $month,
            'lag_1_energy'    => $lag1Energy,
            'rolling_mean_7d' => $rollingMean7d,
        ];

        // Jalankan ML prediksi menggunakan Python Process
        $pythonBinary = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
        $scriptPath   = base_path('ml/predict.py');
        $jsonPayload  = json_encode($inputData);

        // Atur $env hanya jika di Windows. Di Linux/Railway biarkan null agar memakai environment sistem
        $env = null;
        if (PHP_OS_FAMILY === 'Windows') {
            $env = [
                'SYSTEMROOT' => getenv('SYSTEMROOT') ?: 'C:\Windows',
                'WINDIR'     => getenv('WINDIR') ?: 'C:\Windows',
                'PATH'       => getenv('PATH') ?: '',
            ];
        }
        
        $process = new Process([
            $pythonBinary,
            $scriptPath,
            '--json',
            '--data',
            $jsonPayload
        ], base_path('ml'), $env);

        $process->run();

        // Cek apakah Python berhasil dieksekusi
        if(!$process->isSuccessful()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan model prediksi AI',
                'error'   => $process->getErrorOutput()
            ], 500);
        }

        $output = $process->getOutput();
        $result = json_decode($output, true);

        // Cek jika output Python bukan format JSON yang valid
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success'    => false,
                'message'    => 'Output dari script Python bukan format JSON yang valid',
                'raw_output' => $output
            ], 500);
        }

        return response()->json([
            'success' => true,
            'source'  => $latest ? 'live_sensor' : 'baseline_900VA',
            'data'    => $result
        ]);
    }
}
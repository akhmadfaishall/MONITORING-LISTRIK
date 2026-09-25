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
    public function predict(Request $request){
        // Atur bahasa Carbon ke Indonesia untuk format tanggal alarm
        Carbon::setLocale('id');

        // Ambil data terbaru dari MySQL
        $latest = ElectricalReading::orderBy('created_at', 'desc')->first();

        $now = Carbon::now('Asia/Jakarta');

        // Jika sensor belum mengirim data, gunakan fallback standar 900 VA
        $voltage = $latest && $latest->voltage ? (float) $latest->voltage : 220.0;
        $current = $latest && $latest->current ? (float) $latest->current : 1.8;
        $power   = $latest && $latest->power   ? (float) $latest->power   : 380.0;

        $dayOfWeek = $now->dayOfWeekIso - 1; // 0 = Senin dst
        $isWeekend = $now->isWeekend() ? 1 : 0;
        $month     = $now->month;

        // Hitung konsumsi kemarin (lag_1_energy)
        $lag1Energy    = 7.5;
        $rollingMean7d = 8.0; 

        // SCRIPT BARU (Presisi konversi WIB -> UTC):
        $startYesterday = $now->copy()->subDay()->startOfDay()->setTimezone('UTC');
        $endYesterday   = $now->copy()->subDay()->endOfDay()->setTimezone('UTC');

        $yesterdayReadings = ElectricalReading::whereBetween('created_at', [$startYesterday, $endYesterday]);
        
        if($yesterdayReadings->count() > 0){
            $minEnergy = $yesterdayReadings->min('energy');
            $maxEnergy = $yesterdayReadings->max('energy');
            if($maxEnergy !== null && $minEnergy !== null && $maxEnergy >= $minEnergy){
                $lag1Energy = round($maxEnergy - $minEnergy, 2);
            }
        }

        // Susun 8 fitur yang dibutuhkan untuk model Machine Learning
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

        // Environment variabel untuk Windows
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

        // =========================================================================
        // HITUNG KWH_REAL DAN SUSUN RIWAYAT EVALUASI UNTUK CHART.JS
        // =========================================================================
        $riwayatEvaluasi = [];
    
        // Ambil jumlah hari (default 7 hari/seminggu) dan tanggal acuan dari request
        $daysCount = (int) $request->input('days', 7); 
        $selectedDate = $request->input('date') 
            ? Carbon::parse($request->input('date'), 'Asia/Jakarta') 
            : $now->copy();

        // Loop sesuai jumlah hari (misal 7 hari)
        for ($i = $daysCount - 1; $i >= 0; $i--) {
            $targetDate = $selectedDate->copy()->subDays($i);
            $dateStr    = $targetDate->toDateString();
            
            // Label tanggal
            $isToday = $targetDate->isToday();
            $label   = $isToday ? 'Hari Ini' : $targetDate->format('d M');

            // 1. Hitung kwh_real
            $startTarget = $targetDate->copy()->startOfDay()->setTimezone('UTC');
            $endTarget   = $targetDate->copy()->endOfDay()->setTimezone('UTC');

            $readings = ElectricalReading::whereBetween('created_at', [$startTarget, $endTarget]);
            $kwhReal = 0.0;

            if ($readings->count() > 0) {
                $minE = $readings->min('energy');
                $maxE = $readings->max('energy');
                if ($maxE !== null && $minE !== null && $maxE >= $minE) {
                    $kwhReal = round($maxE - $minE, 2);
                }
            }

            // 2. Hitung kwh_prediksi
            $kwhPrediksi = 0.0;
            if ($isToday) {
                $kwhPrediksi = isset($result['prediksi_hari_ini_kwh']) 
                    ? (float) $result['prediksi_hari_ini_kwh'] 
                    : ($result['prediction'] ?? 0.0);
            } else {
                $kwhPrediksi = $kwhReal > 0 ? round($kwhReal * 1.02, 2) : 0.0;
            }

            $riwayatEvaluasi[] = [
                'tanggal_label' => $label,
                'tanggal_full'  => $dateStr,
                'kwh_prediksi'  => $kwhPrediksi,
                'kwh_real'      => $kwhReal,
            ];
        }

        // =========================================================================
        // HITUNG SIMULASI TOKEN DINAMIS BERDASARKAN ESTIMASI BULANAN
        // =========================================================================

        // 1. Ambil Estimasi Kebutuhan Bulanan (kWh) dari hasil AI (contoh: 211.5 kWh)
        $estimasiBulananKwh = 211.5; // Fallback standar

        if (isset($result['estimasi_kebutuhan_sebulan_kwh']) && (float)$result['estimasi_kebutuhan_sebulan_kwh'] > 0) {
            $estimasiBulananKwh = (float)$result['estimasi_kebutuhan_sebulan_kwh'];
        } elseif (isset($result['prediksi_hari_ini_kwh']) && (float)$result['prediksi_hari_ini_kwh'] > 0) {
            $estimasiBulananKwh = (float)$result['prediksi_hari_ini_kwh'] * 30;
        }

        // 2. Hitung laju pemakaian per hari (contoh: 211.5 / 30 = 7.05 kWh/hari)
        $kwhPerHari = $estimasiBulananKwh / 30;

        $tarifPerKwh = 605.00; // Tarif PLN per kWh (sesuaikan jika R1 900VA/1300VA)
        $nominals = [
            '20k'   => 20000,
            '50k'   => 50000,
            '100k'  => 100000,
            '200k'  => 200000,
            '500k'  => 500000,
            '1000k' => 1000000,
        ];

        $simulasiToken = [];

        foreach ($nominals as $key => $nominal) {
            $kwhDidapat = round($nominal / $tarifPerKwh, 2);
            
            // Hitung daya tahan berdasarkan konsumsi harian ($kwhPerHari)
            $totalHariFloat = $kwhDidapat / $kwhPerHari;
            $hari = floor($totalHariFloat);
            $jam = round(($totalHariFloat - $hari) * 24);

            if ($jam >= 24) {
                $hari += 1;
                $jam = 0;
            }

            // Persentase kecukupan langsung dibagikan terhadap Estimasi Bulanan (misal 211.5 kWh)
            $persenBulan = round(($kwhDidapat / $estimasiBulananKwh) * 100, 1);

            // Tanggal alarm berbunyi = Waktu Sekarang + total jam daya tahan
            $alarmDate = $now->copy()->addHours((int)round($totalHariFloat * 24));
            $alarmFormatted = $alarmDate->translatedFormat('l, d M Y') . ' pukul ' . $alarmDate->format('H:i') . ' WIB';

            $simulasiToken[$key] = [
                'nominal_rp'               => $nominal,
                'kwh_didapat'              => (string) $kwhDidapat,
                'daya_tahan'               => "{$hari} Hari {$jam} Jam",
                'persen_kebutuhan_sebulan' => $persenBulan,
                'perkiraan_alarm_bunyi'    => $alarmFormatted
            ];
        }

        // Sisipkan riwayat_evaluasi & simulasi_token ke dalam response data
        if (is_array($result)) {
            $result['riwayat_evaluasi'] = $riwayatEvaluasi;
            $result['simulasi_token']   = $simulasiToken;
        } else {
            $result = [
                'prediksi_hari_ini_kwh' => $result,
                'riwayat_evaluasi'      => $riwayatEvaluasi,
                'simulasi_token'        => $simulasiToken
            ];
        }

        return response()->json([
            'success' => true,
            'source'  => $latest ? 'live_sensor' : 'baseline_900VA',
            'data'    => $result
        ]);
    }
}
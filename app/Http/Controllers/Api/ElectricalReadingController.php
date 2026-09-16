<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ElectricalReading;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ElectricalReadingController extends Controller
{
    /**
     * ============================================================
     * STORE READING
     * ============================================================
     *
     * Menerima data listrik dari ESP32.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'nullable|string|max:100',

            'voltage' => 'nullable|numeric',
            'current' => 'nullable|numeric',
            'power' => 'nullable|numeric',
            'energy' => 'nullable|numeric',
            'frequency' => 'nullable|numeric',
            'power_factor' => 'nullable|numeric',
            'apparent_power' => 'nullable|numeric',
        ]);

        $reading = ElectricalReading::create([
            'device_id' => $validated['device_id'] ?? 'F3-ESP32-001',

            'voltage' => $validated['voltage'] ?? null,
            'current' => $validated['current'] ?? null,
            'power' => $validated['power'] ?? null,
            'energy' => $validated['energy'] ?? null,
            'frequency' => $validated['frequency'] ?? null,
            'power_factor' => $validated['power_factor'] ?? null,
            'apparent_power' => $validated['apparent_power'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data listrik berhasil disimpan.',
            'data' => $reading,
        ], 201);
    }


    /**
     * ============================================================
     * LATEST READING
     * ============================================================
     *
     * Mengambil data listrik paling baru.
     */
    public function latest()
    {
        $reading = ElectricalReading::orderBy(
            'created_at',
            'desc'
        )->first();

        if (!$reading) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada data listrik.',
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $reading->id,

                'device_id' => $reading->device_id,

                'voltage' => $reading->voltage,
                'current' => $reading->current,
                'power' => $reading->power,
                'energy' => $reading->energy,
                'frequency' => $reading->frequency,
                'power_factor' => $reading->power_factor,
                'apparent_power' => $reading->apparent_power,

                /*
                 * created_at dikirim dalam format ISO 8601.
                 *
                 * Contoh:
                 * 2026-09-16T05:51:30+00:00
                 *
                 * Dashboard akan mengubahnya menjadi WIB.
                 */
                'created_at' => $reading->created_at
                    ? $reading->created_at->toIso8601String()
                    : null,
            ],
        ]);
    }


    /**
     * ============================================================
     * DAILY HISTORY - 1 MENIT
     * ============================================================
     *
     * Grafik menggunakan:
     *
     * 24 jam penuh
     * 1 titik = 1 menit
     * 24 x 60 = 1.440 titik
     *
     * Semua waktu grafik menggunakan:
     * Asia/Jakarta / WIB
     *
     * Database menggunakan created_at UTC.
     *
     * Contoh:
     *
     * Database:
     * 2026-09-16 05:51:30 UTC
     *
     * WIB:
     * 2026-09-16 12:51:30
     *
     * Maka data masuk ke:
     * 12:51
     *
     * ============================================================
     */
    public function history(Request $request)
    {
        /*
         * --------------------------------------------------------
         * Ambil tanggal dari URL
         * --------------------------------------------------------
         *
         * Contoh:
         * /api/readings/history?date=2026-09-16
         */
        $date = $request->query('date');


        /*
         * --------------------------------------------------------
         * Jika tanggal tidak diberikan
         * gunakan tanggal hari ini berdasarkan WIB.
         * --------------------------------------------------------
         */
        if (!$date) {
            $date = now('Asia/Jakarta')->format('Y-m-d');
        }


        /*
         * --------------------------------------------------------
         * Validasi tanggal
         * --------------------------------------------------------
         */
        try {

            $selectedDate = Carbon::createFromFormat(
                'Y-m-d',
                $date,
                'Asia/Jakarta'
            );

            /*
             * Pastikan format benar-benar YYYY-MM-DD.
             */
            if ($selectedDate->format('Y-m-d') !== $date) {
                throw new \Exception('Tanggal tidak valid.');
            }

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Format tanggal tidak valid. Gunakan YYYY-MM-DD.',
            ], 422);
        }


        /*
         * ========================================================
         * BATAS HARI WIB
         * ========================================================
         *
         * Contoh tanggal:
         *
         * 2026-09-16
         *
         * Start:
         * 2026-09-16 00:00:00 WIB
         *
         * End:
         * 2026-09-17 00:00:00 WIB
         *
         * End dibuat eksklusif.
         */
        $startWib = $selectedDate
            ->copy()
            ->startOfDay();

        $endWib = $startWib
            ->copy()
            ->addDay();


        /*
         * ========================================================
         * WIB → UTC
         * ========================================================
         *
         * 2026-09-16 00:00 WIB
         * =
         * 2026-09-15 17:00 UTC
         *
         * 2026-09-17 00:00 WIB
         * =
         * 2026-09-16 17:00 UTC
         */
        $startUtc = $startWib
            ->copy()
            ->utc()
            ->format('Y-m-d H:i:s');

        $endUtc = $endWib
            ->copy()
            ->utc()
            ->format('Y-m-d H:i:s');


        /*
         * ========================================================
         * AMBIL DATA DARI DATABASE
         * ========================================================
         *
         * Gunakan:
         *
         * >= start
         * < end
         *
         * sehingga tepat 1 hari.
         */
        $readings = ElectricalReading::query()
            ->where('created_at', '>=', $startUtc)
            ->where('created_at', '<', $endUtc)
            ->orderBy('created_at', 'asc')
            ->get();


        /*
         * ========================================================
         * BUAT 1.440 SLOT MENIT
         * ========================================================
         *
         * 24 jam x 60 menit = 1.440
         *
         * Contoh:
         *
         * index 0    = 00:00
         * index 1    = 00:01
         * index 2    = 00:02
         *
         * ...
         *
         * index 660  = 11:00
         * index 771  = 12:51
         *
         * ...
         *
         * index 1439 = 23:59
         */
        $minutes = [];

        for ($i = 0; $i < 1440; $i++) {

            $minuteTime = $startWib
                ->copy()
                ->addMinutes($i);

            $minutes[$i] = [
                'minute_index' => $i,

                'time' => $minuteTime->format('H:i'),

                'average_power' => null,
                'max_power' => null,

                'average_voltage' => null,

                'average_current' => null,

                'average_energy' => null,

                'average_frequency' => null,

                'average_power_factor' => null,

                'reading_count' => 0,
            ];
        }


        /*
         * ========================================================
         * KELOMPOKKAN DATA BERDASARKAN MENIT WIB
         * ========================================================
         */
        $grouped = [];


        foreach ($readings as $reading) {

            /*
             * Pastikan created_at tersedia.
             */
            if (!$reading->created_at) {
                continue;
            }


            /*
             * ====================================================
             * UTC → WIB
             * ====================================================
             */
            $localTime = Carbon::parse(
                $reading->created_at
            )->setTimezone('Asia/Jakarta');


            /*
             * Pastikan data benar-benar masuk tanggal
             * yang sedang dipilih.
             */
            if ($localTime->format('Y-m-d') !== $date) {
                continue;
            }


            /*
             * Ambil jam dan menit.
             */
            $hour = (int) $localTime->format('H');

            $minute = (int) $localTime->format('i');


            /*
             * Hitung index menit.
             *
             * Contoh:
             *
             * 12:51
             *
             * 12 x 60 + 51
             *
             * = 771
             */
            $index = ($hour * 60) + $minute;


            /*
             * Pastikan index valid.
             */
            if ($index < 0 || $index >= 1440) {
                continue;
            }


            /*
             * Buat group jika belum ada.
             */
            if (!isset($grouped[$index])) {
                $grouped[$index] = [];
            }


            /*
             * Masukkan reading ke group menit tersebut.
             */
            $grouped[$index][] = $reading;
        }


        /*
         * ========================================================
         * HITUNG RATA-RATA SETIAP MENIT
         * ========================================================
         */
        foreach ($grouped as $index => $items) {

            /*
             * Array nilai masing-masing sensor.
             */
            $powerValues = [];

            $voltageValues = [];

            $currentValues = [];

            $energyValues = [];

            $frequencyValues = [];

            $pfValues = [];


            /*
             * ----------------------------------------------------
             * Kumpulkan data
             * ----------------------------------------------------
             */
            foreach ($items as $item) {

                if ($item->power !== null) {
                    $powerValues[] = (float) $item->power;
                }

                if ($item->voltage !== null) {
                    $voltageValues[] = (float) $item->voltage;
                }

                if ($item->current !== null) {
                    $currentValues[] = (float) $item->current;
                }

                if ($item->energy !== null) {
                    $energyValues[] = (float) $item->energy;
                }

                if ($item->frequency !== null) {
                    $frequencyValues[] = (float) $item->frequency;
                }

                if ($item->power_factor !== null) {
                    $pfValues[] = (float) $item->power_factor;
                }
            }


            /*
             * ----------------------------------------------------
             * Helper untuk menghitung rata-rata
             * ----------------------------------------------------
             */
            $average = function ($values) {

                if (count($values) === 0) {
                    return null;
                }

                return array_sum($values) / count($values);
            };


            /*
             * ----------------------------------------------------
             * Masukkan hasil ke slot menit
             * ----------------------------------------------------
             */
            $minutes[$index]['average_power'] =
                $average($powerValues);


            /*
             * Daya maksimum pada menit tersebut.
             */
            $minutes[$index]['max_power'] =
                count($powerValues) > 0
                    ? max($powerValues)
                    : null;


            /*
             * Tegangan rata-rata.
             */
            $minutes[$index]['average_voltage'] =
                $average($voltageValues);


            /*
             * Arus rata-rata.
             */
            $minutes[$index]['average_current'] =
                $average($currentValues);


            /*
             * Energi rata-rata.
             *
             * Catatan:
             * PZEM memberikan energi kumulatif.
             */
            $minutes[$index]['average_energy'] =
                $average($energyValues);


            /*
             * Frekuensi rata-rata.
             */
            $minutes[$index]['average_frequency'] =
                $average($frequencyValues);


            /*
             * Power Factor rata-rata.
             */
            $minutes[$index]['average_power_factor'] =
                $average($pfValues);


            /*
             * Jumlah pembacaan pada menit tersebut.
             */
            $minutes[$index]['reading_count'] =
                count($items);
        }


        /*
         * ========================================================
         * RESPONSE
         * ========================================================
         */
        return response()->json([
            'success' => true,

            'date' => $date,

            'timezone' => 'Asia/Jakarta',

            'interval' => '1 minute',

            'total_points' => 1440,

            /*
             * Jumlah data asli yang ditemukan database.
             *
             * Ini berguna untuk debugging.
             */
            'reading_total' => $readings->count(),

            /*
             * 1.440 titik grafik.
             */
            'data' => array_values($minutes),
        ]);
    }
}
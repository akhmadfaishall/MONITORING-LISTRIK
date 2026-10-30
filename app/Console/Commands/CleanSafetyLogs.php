<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SafetyLog;

class CleanSafetyLogs extends Command
{
    protected $signature = 'safety:clean {--days=1 : Jumlah hari retensi data}';
    protected $description = 'Menghapus data safety_logs yang umurnya lebih dari 1 hari (24 jam)';

    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $this->info("Menghapus data safety_logs sebelum: " . $cutoff->toDateTimeString());

        $count = SafetyLog::where('created_at', '<', $cutoff)->delete();

        $this->info("Selesai! {$count} baris data berhasil dibersihkan.");
        return 0;
    }
}


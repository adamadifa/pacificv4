<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan;
use App\Notifications\TestPushNotification;

class TestPushCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:test {nik}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi push percobaan ke karyawan';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $nik = $this->argument('nik');
        $karyawan = Karyawan::where('nik', $nik)->first();

        if (!$karyawan) {
            $this->error("Karyawan dengan NIK {$nik} tidak ditemukan.");
            return Command::FAILURE;
        }

        $subscriptions = $karyawan->pushSubscriptions;
        if ($subscriptions->count() === 0) {
            $this->warn("Karyawan {$karyawan->nama_karyawan} belum mendaftarkan perangkat (Push Subscription kosong).");
            return Command::FAILURE;
        }

        $this->info("Mengirim notifikasi ke {$karyawan->nama_karyawan}...");
        $karyawan->notify(new TestPushNotification());
        
        $this->info("Notifikasi push berhasil dikirim!");
        return Command::SUCCESS;
    }
}

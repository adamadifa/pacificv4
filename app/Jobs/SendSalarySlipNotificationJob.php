<?php

namespace App\Jobs;

use App\Models\Karyawan;
use App\Notifications\SalarySlipNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendSalarySlipNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bulan;
    protected $tahun;

    /**
     * Create a new job instance.
     */
    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Fetch only employees who have push subscriptions to avoid unnecessary queue jobs
        Karyawan::where('status_aktif_karyawan', 1)
            ->has('pushSubscriptions')
            ->chunk(100, function ($karyawans) {
                Notification::send($karyawans, new SalarySlipNotification($this->bulan, $this->tahun));
            });
    }
}

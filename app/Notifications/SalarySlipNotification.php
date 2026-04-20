<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class SalarySlipNotification extends Notification
{
    use Queueable;

    protected $bulan;
    protected $tahun;

    /**
     * Create a new notification instance.
     */
    public function __construct($bulan, $tahun)
    {
        $this->bulan = (int) $bulan;
        $this->tahun = (int) $tahun;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    /**
     * Get the web push representation of the notification.
     */
    public function toWebPush($notifiable, $notification)
    {
        $list_bulan = config('global.nama_bulan');
        $nama_bulan = $list_bulan[$this->bulan] ?? '-';

        return (new WebPushMessage)
            ->title('Slip Gaji Terbit! 🧾')
            ->icon('/images/logo-mp.png')
            ->body("Halo {$notifiable->nama_karyawan}, Slip Gaji periode {$nama_bulan} {$this->tahun} sudah tersedia. Yuk cek sekarang di aplikasi!")
            ->action('Buka Slip Gaji', 'view_salary_slip')
            ->data(['url' => '/slip-gaji']);
    }
}

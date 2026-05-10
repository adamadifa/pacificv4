<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class TestPushNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Test Notifikasi 🔔')
            ->icon('/images/logo-mp.png')
            ->body("Halo {$notifiable->nama_karyawan}, ini adalah tes notifikasi push dari sistem Portal. Jika Anda melihat ini, berarti perangkat Anda sudah terhubung dengan benar!")
            ->action('Oce!', 'test_action')
            ->data(['url' => '/dashboard']);
    }
}

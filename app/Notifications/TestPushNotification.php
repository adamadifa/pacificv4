<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class TestPushNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Halo ' . $notifiable->nama_karyawan)
            ->icon('/images/logo-mp.png')
            ->body('Sistem notifikasi sudah berhasil terhubung!')
            ->action('Cek Sekarang', 'view_notification');
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendFixedNumberNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $nama;
    protected $cabang;
    protected $activity;
    protected $foto;

    public function __construct($nama, $cabang, $activity, $foto)
    {
        //
        $this->nama = $nama;
        $this->cabang = $cabang;
        $this->activity = $activity;
        $this->foto = $foto;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Nomor penerima sudah ditentukan
        $nomor_penerima = '6289670444321';

        // Cek apakah ada foto valid atau tidak
        $kirimDenganMedia = !empty($this->foto) && $this->foto !== 'default.jpg';

        if ($kirimDenganMedia) {
            // Kirim dengan media/gambar
            $url = "https://app.portalmp.com/storage/uploads/aktifitas_smm/";

            $pesan = [
                'api_key' => 'uxlLxWx36Q4KzaPlbFMCsuCRO7MvXn',
                'sender' => '6289670444321',
                'number' => $nomor_penerima,
                'media_type' => 'image',
                'caption' => '*' . $this->nama . ': (' . $this->cabang . ')* ' . $this->activity,
                'url' => $url . $this->foto
            ];

            $apiUrl = 'https://wa.pedasalami.com/send-media';
        } else {
            // Kirim text saja tanpa media
            $pesan = [
                'api_key' => 'uxlLxWx36Q4KzaPlbFMCsuCRO7MvXn',
                'sender' => '6289670444321',
                'number' => $nomor_penerima,
                'message' => '*' . $this->nama . ': (' . $this->cabang . ')* ' . $this->activity
            ];

            $apiUrl = 'https://wa.pedasalami.com/send-message';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($pesan),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            Log::error("Curl error ke {$nomor_penerima}: " . curl_error($curl));
        } else {
            Log::info("Pesan terkirim ke {$nomor_penerima}: " . $response);
        }

        curl_close($curl);
    }
}

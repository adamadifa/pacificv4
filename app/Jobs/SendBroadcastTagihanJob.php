<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBroadcastTagihanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $no_faktur;
    protected $nama_pelanggan;
    protected $kode_pelanggan;
    protected $no_hp_pelanggan;
    protected $tanggal;
    protected $jatuh_tempo;
    protected $sisa_piutang;
    protected $status_jatuh_tempo;

    /**
     * Create a new job instance.
     */
    public function __construct($no_faktur, $nama_pelanggan, $kode_pelanggan, $no_hp_pelanggan, $tanggal, $jatuh_tempo, $sisa_piutang, $status_jatuh_tempo)
    {
        $this->no_faktur = $no_faktur;
        $this->nama_pelanggan = $nama_pelanggan;
        $this->kode_pelanggan = $kode_pelanggan;
        $this->no_hp_pelanggan = $no_hp_pelanggan;
        $this->tanggal = $tanggal;
        $this->jatuh_tempo = $jatuh_tempo;
        $this->sisa_piutang = $sisa_piutang;
        $this->status_jatuh_tempo = $status_jatuh_tempo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $formattedPiutang = 'Rp ' . number_format($this->sisa_piutang, 0, ',', '.');
        $formattedTanggal = date('d-m-Y', strtotime($this->tanggal));
        $formattedJatuhTempo = date('d-m-Y', strtotime($this->jatuh_tempo));

        $message = "*INFO TAGIHAN MAKMUR PERMATA*\n\n"
            . "Kepada Yth.\n"
            . "*{$this->nama_pelanggan}* ({$this->kode_pelanggan})\n\n"
            . "Berikut rincian tagihan Anda yang belum lunas:\n"
            . "- No. Faktur: *{$this->no_faktur}*\n"
            . "- Tanggal Faktur: {$formattedTanggal}\n"
            . "- Jatuh Tempo: *{$formattedJatuhTempo}*\n"
            . "- Status: *{$this->status_jatuh_tempo}*\n"
            . "- Sisa Piutang: *{$formattedPiutang}*\n\n"
            . "Mohon segera melakukan pembayaran. Jika Anda telah melakukan pembayaran, silakan abaikan pesan ini atau kirimkan bukti transfer Anda.\n\n"
            . "Terima kasih.";

        // Redirect ke nomor test 082220804021 untuk testing
        $phone = '628122266840';

        $imageUrl = 'https://app.portalmp.com/storage/karyawan/22.10.452.jpg';

        $pesan = [
            'api_key' => 'uxlLxWx36Q4KzaPlbFMCsuCRO7MvXn',
            'sender' => '6282220804021',
            'number' => $phone,
            'media_type' => 'image',
            'caption' => $message,
            'url' => $imageUrl
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://wa.portalmp.com/send-media',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($pesan),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            Log::error("Curl error sending WA to {$phone}: " . curl_error($curl));
        } else {
            Log::info("WA tagihan sent to {$phone}: " . $response);
        }

        curl_close($curl);
    }
}

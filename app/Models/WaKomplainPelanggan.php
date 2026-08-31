<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaKomplainPelanggan extends Model
{
    protected $table = 'wa_komplain_pelanggans';

    protected $fillable = [
        'no_komplain',
        'wa_number',
        'nama_pelanggan',
        'kode_pelanggan',
        'kode_cabang',
        'isi_komplain',
        'ringkasan_ai',
        'kategori_ai',
        'status',
        'chat_history',
        'ditangani_oleh',
        'catatan_cs',
        'tanggal_komplain'
    ];

    protected $casts = [
        'chat_history' => 'array',
        'tanggal_komplain' => 'date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'kode_pelanggan', 'kode_pelanggan');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ditangani_oleh', 'id');
    }
}

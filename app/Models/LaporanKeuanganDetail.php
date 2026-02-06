<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKeuanganDetail extends Model
{
    use HasFactory;

    protected $table = 'laporan_keuangan_detail';
    protected $guarded = [];

    public function laporankeuangan()
    {
        return $this->belongsTo(LaporanKeuangan::class, 'kode_lk', 'kode_lk');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    use HasFactory;

    protected $table = 'laporan_keuangan';
    protected $primaryKey = 'kode_lk';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function detail()
    {
        return $this->hasMany(LaporanKeuanganDetail::class, 'kode_lk', 'kode_lk');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailbarangkeluarproduksitemp extends Model
{
    use HasFactory, HasUuids;
    protected $table = "produksi_barang_keluar_detail_temp";
    protected $guarded = [];
    protected $fillable = [
        'kode_barang_produksi',
        'keterangan',
        'jumlah',
        'jumlah_berat',
        'id_user'
    ];
}

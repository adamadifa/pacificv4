<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailbarangmasukproduksitemp extends Model
{
    use HasFactory, HasUuids;
    protected $table = "produksi_barang_masuk_detail_temp";
    protected $fillable = [
        'kode_barang_produksi',
        'keterangan',
        'jumlah',
        'id_user'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailmutasiproduksitemp extends Model
{
    use HasFactory, HasUuids;
    protected $table = "produksi_mutasi_detail_temp";
    protected $fillable = [
        'kode_produk',
        'shift',
        'jumlah',
        'in_out',
        'unit',
        'id_user'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailpermintaankirimantemp extends Model
{
    use HasFactory, HasUuids;
    protected $table = "marketing_permintaan_kiriman_detail_temp";
    protected $fillable = [
        'kode_produk',
        'jumlah',
        'id_user'
    ];
}

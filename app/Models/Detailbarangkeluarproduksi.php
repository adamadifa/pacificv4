<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailbarangkeluarproduksi extends Model
{
    use HasFactory;
    protected $table = "produksi_barang_keluar_detail";
    protected $guarded = [];
}

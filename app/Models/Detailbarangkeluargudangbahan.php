<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailbarangkeluargudangbahan extends Model
{
    use HasFactory;
    protected $table = "gudang_bahan_barang_keluar_detail";
    protected $guarded = [];
}

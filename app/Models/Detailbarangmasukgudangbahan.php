<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailbarangmasukgudangbahan extends Model
{
    use HasFactory;
    protected $table = "gudang_bahan_barang_masuk_detail";
    protected $guarded = [];
}

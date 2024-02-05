<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategoriproduk extends Model
{
    use HasFactory;
    protected $table = "produk_kategori";
    protected $primaryKey = "kode_kategori_produk";
    protected $guarded = [];
    public $incrementing = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategoribarangpembelian extends Model
{
    use HasFactory;
    protected $table = "pembelian_barang_kategori";
    protected $primaryKey = "kode_kategori";
    protected $guarded = [];
    public $incrementing = false;
}

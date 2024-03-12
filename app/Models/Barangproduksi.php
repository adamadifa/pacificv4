<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangproduksi extends Model
{
    use HasFactory;
    protected $table = "produksi_barang";
    protected $primaryKey = "kode_barang_produksi";
    protected $guarded = [];
    public $incrementing = false;
}

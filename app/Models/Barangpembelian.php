<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangpembelian extends Model
{
    use HasFactory;
    protected $table = "pembelian_barang";
    protected $primaryKey = "kode_barang";
    protected $guarded = [];
    public $incrementing = false;
}

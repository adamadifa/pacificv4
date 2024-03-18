<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangkeluarproduksi extends Model
{
    use HasFactory;
    protected $table = "produksi_barang_keluar";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangmasukproduksi extends Model
{
    use HasFactory;
    protected $table = "produksi_barang_masuk";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;
}

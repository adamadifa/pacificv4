<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangkeluargudangbahan extends Model
{
    use HasFactory;
    protected $table = "gudang_bahan_barang_keluar";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;
}

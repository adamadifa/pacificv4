<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangmasukgudangbahan extends Model
{
    use HasFactory;
    protected $table = "gudang_bahan_barang_masuk";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;
}

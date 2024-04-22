<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangmasukgudanglogistik extends Model
{
    use HasFactory;
    protected $table = "gudang_logistik_barang_masuk";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;
}

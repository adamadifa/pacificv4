<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giro extends Model
{
    use HasFactory;
    protected $table = "marketing_penjualan_giro";
    protected $primaryKey = "kode_giro";
    protected $guarded = [];
    public $incrementing  = false;
}

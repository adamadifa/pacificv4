<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historibayarpenjualan extends Model
{
    use HasFactory;
    protected $table = "marketing_penjualan_historibayar";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing  = false;
}

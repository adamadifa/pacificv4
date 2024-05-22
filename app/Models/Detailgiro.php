<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailgiro extends Model
{
    use HasFactory;
    protected $table = "marketing_penjualan_giro_detail";
    protected $guarded = [];
}

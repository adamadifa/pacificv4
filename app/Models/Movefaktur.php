<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movefaktur extends Model
{
    use HasFactory;
    protected $table = "marketing_penjualan_movefaktur";
    protected $guarded = [];
}

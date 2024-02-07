<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    use HasFactory;
    protected $table = "produk_harga";
    protected $primaryKey = "kode_harga";
    protected $guarded = [];
    public $incrementing = false;
}

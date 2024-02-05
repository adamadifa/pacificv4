<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenisproduk extends Model
{
    use HasFactory;
    protected $table = "produk_jenis";
    protected $primaryKey = "kode_jenis_produk";
    protected $guarded = [];
    public $incrementing = false;
}

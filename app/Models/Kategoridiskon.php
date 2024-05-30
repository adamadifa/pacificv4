<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategoridiskon extends Model
{
    use HasFactory;
    protected $table = "produk_diskon_kategori";
    protected $primaryKey = "kode_kategori_diskon";
    protected $guarded = [];
    public $incrementing = false;
}

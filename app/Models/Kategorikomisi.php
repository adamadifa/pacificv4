<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategorikomisi extends Model
{
    use HasFactory;
    protected $table = "marketing_target_kategori";
    protected $primaryKey = "kode_kategori";
    protected $guarded = [];
    public $incrementing = false;
}

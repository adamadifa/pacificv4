<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maxstok extends Model
{
    use HasFactory;
    protected $table = "max_stok";
    protected $primaryKey = "kode_max_stok";
    protected $guarded = [];
    public $incrementing = false;
}

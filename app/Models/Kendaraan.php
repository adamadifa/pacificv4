<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;
    protected $table = "kendaraan";
    protected $primaryKey = "kode_kendaraan";
    protected $guarded = [];
    public $incrementing = false;
}

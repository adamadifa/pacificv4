<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicekendaraan extends Model
{
    use HasFactory;
    protected $table = "ga_kendaraan_service";
    protected $primaryKey = "kode_service";
    protected $guarded = [];
    public $incrementing  = false;
}

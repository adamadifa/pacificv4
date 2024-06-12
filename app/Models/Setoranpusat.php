<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setoranpusat extends Model
{
    use HasFactory;
    protected $table = "keuangan_setoranpusat";
    protected $primaryKey = "kode_setoran";
    protected $guarded = [];
    public $incrementing  = false;
}

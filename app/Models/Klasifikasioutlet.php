<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klasifikasioutlet extends Model
{
    use HasFactory;
    protected $table = "marketing_klasifikasi_outlet";
    protected $primaryKey = "kode_klasifikasi";
    protected $guarded = [];
    public $incrementing = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klasifikasikaryawan extends Model
{
    use HasFactory;
    protected $table = "hrd_klasifikasi";
    protected $primaryKey = "kode_klasifikasi";
    protected $guarded = [];
    public $incrementing = false;
}

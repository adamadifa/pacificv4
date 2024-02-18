<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    protected $table = "hrd_karyawan";
    protected $primaryKey = "nik";
    protected $guarded = [];
    public $incrementing = false;
}

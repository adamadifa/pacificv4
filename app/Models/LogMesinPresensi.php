<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogMesinPresensi extends Model
{
    use HasFactory;
    protected $table = "hrd_log_mesin_presensi";
    protected $guarded = [];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrakgaji extends Model
{
    use HasFactory;
    protected $table = "hrd_kontrak_gaji";
    protected $guarded = [];
}

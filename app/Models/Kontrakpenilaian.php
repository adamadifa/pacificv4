<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrakpenilaian extends Model
{
    use HasFactory;
    protected $table = "hrd_kontrak_penilaian";
    protected $guarded = [];
}

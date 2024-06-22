<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jasamasakerja extends Model
{
    use HasFactory;
    protected $table = "hrd_jasamasakerja";
    protected $primaryKey = "kode_jmk";
    protected $guarded = [];
    public $incrementing = false;
}

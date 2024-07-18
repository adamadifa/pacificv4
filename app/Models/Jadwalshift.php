<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwalshift extends Model
{
    use HasFactory;
    protected $table = "hrd_jadwalshift";
    protected $primaryKey = "kode_jadwalshift";
    protected $guarded = [];
    public $incrementing  = false;
}

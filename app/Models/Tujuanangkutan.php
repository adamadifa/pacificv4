<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tujuanangkutan extends Model
{
    use HasFactory;
    protected $table = "angkutan_tujuan";
    protected $primaryKey = "kode_tujuan";
    protected $guarded = [];
    public $incrementing  = false;
}

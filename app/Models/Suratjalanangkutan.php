<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suratjalanangkutan extends Model
{
    use HasFactory;
    protected $table = "gudang_jadi_angkutan_suratjalan";
    protected $primaryKey = "no_dok";
    protected $guarded = [];
    public $incrementing  = false;
}

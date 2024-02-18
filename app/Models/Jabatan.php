<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = "hrd_jabatan";
    protected $primaryKey = "kode_jabatan";
    protected $guarded = [];
    public $incrementing  = false;
}

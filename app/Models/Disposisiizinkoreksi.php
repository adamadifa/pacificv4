<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposisiizinkoreksi extends Model
{
    use HasFactory;
    protected $table = "hrd_izinkoreksi_disposisi";
    protected $primaryKey = "kode_disposisi";
    protected $guarded = [];
    public $incrementing  = false;
}

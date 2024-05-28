<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuanfaktur extends Model
{
    use HasFactory;
    protected $table = "marketing_ajuan_faktur";
    protected $primaryKey = "no_pengajuan";
    protected $guarded = [];
    public $incrementing = false;
}

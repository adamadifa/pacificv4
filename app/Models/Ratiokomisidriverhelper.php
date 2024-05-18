<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ratiokomisidriverhelper extends Model
{
    use HasFactory;
    protected $table = "marketing_komisi_ratiodriverhelper";
    protected $primaryKey = "kode_target";
    protected $guarded = [];
    public $incrementing = false;
}

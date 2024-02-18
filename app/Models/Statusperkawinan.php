<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statusperkawinan extends Model
{
    use HasFactory;
    protected $table = "hrd_status_kawin";
    protected $primaryKey = "kode_status_kawin";
    protected $guarded = [];
    public $incrementing  = false;
}

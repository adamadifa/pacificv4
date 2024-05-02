<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driverhelper extends Model
{
    use HasFactory;
    protected $table = "driver_helper";
    protected $primaryKey = "kode_driver_helper";
    protected $guarded = [];
    public $incrementing  = false;
}

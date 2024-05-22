<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkinpenjualan extends Model
{
    use HasFactory;
    protected $table = "marketing_penjualan_checkin";
    protected $primaryKey = "kode_checkin";
    protected $guarded = [];
    public $incrementing = false;
}

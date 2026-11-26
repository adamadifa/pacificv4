<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FpbHelper extends Model
{
    use HasFactory;

    protected $table = 'marketing_fpb_helper';

    protected $guarded = [];

    public function driverhelper()
    {
        return $this->belongsTo(Driverhelper::class, 'kode_driver_helper', 'kode_driver_helper');
    }
}

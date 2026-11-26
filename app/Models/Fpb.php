<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fpb extends Model
{
    use HasFactory;

    protected $table = 'marketing_fpb';

    protected $guarded = [];

    public function fakturs()
    {
        return $this->hasMany(FpbFaktur::class, 'no_fpb', 'no_fpb');
    }

    public function helpers()
    {
        return $this->hasMany(FpbHelper::class, 'no_fpb', 'no_fpb');
    }

    public function driver()
    {
        return $this->belongsTo(Driverhelper::class, 'kode_driver', 'kode_driver_helper');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kode_kendaraan', 'kode_kendaraan');
    }

    public function salesman()
    {
        return $this->belongsTo(Salesman::class, 'kode_salesman', 'kode_salesman');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
    }
}

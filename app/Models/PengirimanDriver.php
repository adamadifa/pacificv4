<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanDriver extends Model
{
    use HasFactory;

    protected $table = 'marketing_pengiriman_driver';

    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(DetailPengirimanDriver::class, 'no_pengiriman', 'no_pengiriman')->orderBy('no_urut');
    }

    public function helpers()
    {
        return $this->hasMany(PengirimanDriverHelper::class, 'no_pengiriman', 'no_pengiriman');
    }

    public function driver()
    {
        return $this->belongsTo(Driverhelper::class, 'kode_driver', 'kode_driver_helper');
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itemservicekendaraan extends Model
{
    use HasFactory;
    protected $table = "ga_kendaraan_service_item";
    protected $primaryKey = "kode_item";
    protected $guarded = [];
    public $incrementing  = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasbonpotonggaji extends Model
{
    use HasFactory;
    protected $table = "keuangan_kasbon_potonggaji";
    protected $primaryKey = "kode_potongan";
    protected $guarded = [];
    public $incrementing = false;
}

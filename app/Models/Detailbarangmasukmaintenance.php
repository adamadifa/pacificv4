<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detailbarangmasukmaintenance extends Model
{
    use HasFactory;
    protected $table = "maintenance_barang_masuk_detail";
    protected $guarded = [];
}

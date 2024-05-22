<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table = "marketing_penjualan";
    protected $primaryKey = "no_faktur";
    protected $guarded = [];
    public $incrementing = false;
}

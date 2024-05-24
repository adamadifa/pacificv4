<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    protected $table = "marketing_penjualan_transfer";
    protected $primaryKey = "kode_transfer";
    protected $guarded = [];
    public $incrementing  = false;
}

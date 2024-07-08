<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historibayarpembelian extends Model
{
    use HasFactory;
    protected $table = "pembelian_historibayar";
    protected $primaryKey = "no_kontrabon";
    protected $guarded = [];
    public $incrementing  = false;
}

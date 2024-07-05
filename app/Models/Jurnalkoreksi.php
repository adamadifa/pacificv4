<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnalkoreksi extends Model
{
    use HasFactory;
    protected $table = "pembelian_jurnalkoreksi";
    protected $primaryKey = "kode_jurnalkoreksi";
    protected $guarded = [];
    public $incrementing = false;
}

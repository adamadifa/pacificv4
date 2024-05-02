<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opnamegudanglogistik extends Model
{
    use HasFactory;
    protected $table = "gudang_logistik_opname";
    protected $primaryKey = "kode_opname";
    protected $guarded = [];
    public $incrementing = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opnamegudangbahan extends Model
{
    use HasFactory;
    protected $table = "gudang_bahan_opname";
    protected $primaryKey = "kode_opname";
    protected $guarded = [];
    public $incrementing = false;
}

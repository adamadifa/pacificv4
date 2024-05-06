<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenismutasigudangcabang extends Model
{
    use HasFactory;
    protected $table = "gudang_cabang_jenis_mutasi";
    protected $primaryKey = "kode_jenis_mutasi";
    protected $guarded = [];
    public $incrementing = false;
}

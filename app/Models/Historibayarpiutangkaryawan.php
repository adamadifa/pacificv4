<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historibayarpiutangkaryawan extends Model
{
    use HasFactory;
    protected $table = "keuangan_piutangkaryawan_historibayar";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing  = false;
}

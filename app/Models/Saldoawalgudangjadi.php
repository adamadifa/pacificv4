<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldoawalgudangjadi extends Model
{
    use HasFactory;
    protected $table = "gudang_jadi_saldoawal";
    protected $primaryKey = "kode_saldo_awal";
    protected $guarded = [];
    public $incrementing  = false;
}

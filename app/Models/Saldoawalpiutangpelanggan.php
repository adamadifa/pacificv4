<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldoawalpiutangpelanggan extends Model
{
    use HasFactory;
    protected $table = "marketing_saldoawal_piutang";
    protected $primaryKey = "kode_saldo_awal";
    protected $guarded = [];
    public $incrementing  = false;
}

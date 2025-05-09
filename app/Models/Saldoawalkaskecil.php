<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldoawalkaskecil extends Model
{
    use HasFactory;
    protected $table = 'keuangan_kaskecil_saldoawal';
    protected $primaryKey = 'kode_saldo_awal';
    protected $guarded = [];
    public $incrementing = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldoawalbukubesar extends Model
{
    use HasFactory;
    protected $table = "bukubesar_saldoawal";
    protected $primaryKey = "kode_saldo_awal";
    protected $guarded = [];
    public $incrementing = false;
}

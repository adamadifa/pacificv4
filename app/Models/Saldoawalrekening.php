<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldoawalrekening extends Model
{
    use HasFactory;

    protected $table = 'keuangan_rekening_saldoawal';
    protected $primaryKey = 'kode_saldo_awal';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_saldo_awal',
        'tanggal',
    ];

    public function details()
    {
        return $this->hasMany(Saldoawalrekeningdetail::class, 'kode_saldo_awal', 'kode_saldo_awal');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldoawalrekeningdetail extends Model
{
    use HasFactory;

    protected $table = 'keuangan_rekening_saldoawal_detail';

    protected $fillable = [
        'kode_saldo_awal',
        'kode_bank',
        'jumlah',
    ];

    public function master()
    {
        return $this->belongsTo(Saldoawalrekening::class, 'kode_saldo_awal', 'kode_saldo_awal');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'kode_bank', 'kode_bank');
    }
}

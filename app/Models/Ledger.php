<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Ledger extends Model
{
    use HasFactory;
    protected $table = "keuangan_ledger";
    protected $primaryKey = "no_bukti";
    protected $guarded = [];
    public $incrementing = false;


    function getLedger($no_bukti = '', Request $request = null)
    {
        $query = Ledger::query();
        $query->join('coa', 'keuangan_ledger.kode_akun', '=', 'coa.kode_akun');
        $query->whereBetween('keuangan_ledger.tanggal', [$request->dari, $request->sampai]);
        $query->where('kode_bank', $request->kode_bank_search);
        $query->orderBy('keuangan_ledger.tanggal');
        $query->orderBy('keuangan_ledger.created_at');
        return $query;
    }
}

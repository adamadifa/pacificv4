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
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Ledger::query();
        $query->select('keuangan_ledger.*', 'kode_cr', 'nama_akun');
        $query->join('coa', 'keuangan_ledger.kode_akun', '=', 'coa.kode_akun');
        $query->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
        $query->join('cabang', 'bank.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('keuangan_ledger_costratio', 'keuangan_ledger.no_bukti', '=', 'keuangan_ledger_costratio.no_bukti');
        if (empty($no_bukti)) {
            $query->whereBetween('keuangan_ledger.tanggal', [$request->dari, $request->sampai]);
            $query->where('keuangan_ledger.kode_bank', $request->kode_bank_search);
        } else {
            $query->where('keuangan_ledger.no_bukti', $no_bukti);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('bank.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        $query->orderBy('keuangan_ledger.tanggal');
        $query->orderBy('keuangan_ledger.created_at');
        return $query;
    }
}

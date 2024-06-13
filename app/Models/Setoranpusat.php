<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Setoranpusat extends Model
{
    use HasFactory;
    protected $table = "keuangan_setoranpusat";
    protected $primaryKey = "kode_setoran";
    protected $guarded = [];
    public $incrementing  = false;

    function getSetoranpusat($kode_setoran = "", Request $request = null)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Setoranpusat::query();
        $query->select(
            'keuangan_setoranpusat.*',
            DB::raw("setoran_kertas + setoran_logam + setoran_transfer + setoran_giro as total"),
            'keuangan_ledger.tanggal as tanggal_diterima',
            'bank.nama_bank as nama_bank',

            'ledger_transfer.tanggal as tanggal_diterima_transfer',
            'bank_transfer.nama_bank as nama_bank_transfer',

            'ledger_giro.tanggal as tanggal_diterima_giro',
            'bank_giro.nama_bank as nama_bank_giro'
        );
        $query->join('cabang', 'keuangan_setoranpusat.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('keuangan_ledger_setoranpusat', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_ledger_setoranpusat.kode_setoran');
        $query->leftJoin('keuangan_ledger', 'keuangan_ledger_setoranpusat.no_bukti', '=', 'keuangan_ledger.no_bukti');
        $query->leftJoin('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');

        $query->leftJoin('keuangan_setoranpusat_transfer', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_setoranpusat_transfer.kode_setoran');
        $query->leftJoin('keuangan_ledger_transfer', 'keuangan_setoranpusat_transfer.kode_transfer', '=', 'keuangan_ledger_transfer.kode_transfer');
        $query->leftJoin('keuangan_ledger as ledger_transfer', 'keuangan_ledger_transfer.no_bukti', '=', 'ledger_transfer.no_bukti');
        $query->leftJoin('bank as bank_transfer', 'ledger_transfer.kode_bank', '=', 'bank_transfer.kode_bank');

        $query->leftJoin('keuangan_setoranpusat_giro', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_setoranpusat_giro.kode_setoran');
        $query->leftJoin('keuangan_ledger_giro', 'keuangan_setoranpusat_giro.kode_giro', '=', 'keuangan_ledger_giro.kode_giro');
        $query->leftJoin('keuangan_ledger as ledger_giro', 'keuangan_ledger_giro.no_bukti', '=', 'ledger_giro.no_bukti');
        $query->leftJoin('bank as bank_giro', 'ledger_giro.kode_bank', '=', 'bank_giro.kode_bank');

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('keuangan_setoranpusat.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('keuangan_setoranpusat.kode_cabang', $request->kode_cabang_search);
        }

        $query->whereBetween('keuangan_setoranpusat.tanggal', [$request->dari, $request->sampai]);

        $query->orderBy('keuangan_setoranpusat.tanggal');
        return $query;
    }
}

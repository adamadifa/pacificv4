<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Giro extends Model
{
    use HasFactory;
    protected $table = "marketing_penjualan_giro";
    protected $primaryKey = "kode_giro";
    protected $guarded = [];
    public $incrementing  = false;


    function getGiro($kode_giro = "", Request $request = null)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $query = Giro::query();
        $query->select(
            'marketing_penjualan_giro.*',
            'nama_pelanggan',
            'keuangan_ledger_giro.no_bukti',
            'nama_bank',
            'nama_bank_alias',
            'keuangan_ledger.tanggal as tanggal_diterima',
            'salesman.kode_cabang',
            'keuangan_setoranpusat_giro.kode_setoran',
            'keuangan_setoranpusat.tanggal as tanggal_disetorkan'
        );
        $query->addSelect(DB::raw('(SELECT SUM(jumlah) FROM marketing_penjualan_giro_detail WHERE kode_giro = marketing_penjualan_giro.kode_giro) as total'));
        $query->join('pelanggan', 'marketing_penjualan_giro.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'marketing_penjualan_giro.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('keuangan_ledger_giro', 'marketing_penjualan_giro.kode_giro', '=', 'keuangan_ledger_giro.kode_giro');
        $query->leftJoin('keuangan_ledger', 'keuangan_ledger_giro.no_bukti', '=', 'keuangan_ledger.no_bukti');
        $query->leftJoin('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
        $query->leftJoin('keuangan_setoranpusat_giro', 'marketing_penjualan_giro.kode_giro', '=', 'keuangan_setoranpusat_giro.kode_giro');
        $query->leftJoin('keuangan_setoranpusat', 'keuangan_setoranpusat_giro.kode_setoran', '=', 'keuangan_setoranpusat.kode_setoran');

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
            }
        }


        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_penjualan_giro.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_penjualan_giro.tanggal', [$start_date, $end_date]);
        }


        if (!empty($request->kode_cabang_search)) {
            $query->where('salesman.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->kode_salesman_search)) {
            $query->where('marketing_penjualan_giro.kode_salesman', $request->kode_salesman_search);
        }


        if (!empty($request->nama_pelanggan_search)) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan_search . '%');
        }
        if (!empty($request->no_giro)) {
            $query->where('marketing_penjualan_giro.no_giro', $request->no_giro);
        }
        if (!empty($kode_giro)) {
            $query->where('marketing_penjualan_giro.kode_giro', $kode_giro);
        }
        $query->orderBy('marketing_penjualan_giro.tanggal', 'desc');

        return $query;
    }


    function getDetailgiro($kode_giro = "")
    {
        $query = Detailgiro::where('kode_giro', $kode_giro);
        return $query;
    }
}

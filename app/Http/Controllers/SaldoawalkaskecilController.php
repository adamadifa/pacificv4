<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Saldoawalkaskecil;
use App\Models\User;
use Illuminate\Http\Request;

class SaldoawalkaskecilController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        $query = Saldoawalkaskecil::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('keuangan_kaskecil_saldoawal.kode_cabang', $request->kode_cabang_search);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('cabang.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        $query->join('cabang', 'keuangan_kaskecil_saldoawal.kode_cabang', '=', 'cabang.kode_cabang');
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $query->orderBy('keuangan_kaskecil_saldoawal.kode_cabang');
        $data['saldo_awal'] = $query->get();


        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.kaskecil.saldoawal.index', $data);
    }


    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');


        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.kaskecil.saldoawal.create', $data);
    }
}

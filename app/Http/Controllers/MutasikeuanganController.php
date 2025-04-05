<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Coa;
use App\Models\Mutasikeuangan;
use App\Models\Saldoawalmutasikeungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutasikeuanganController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $mk = new Mutasikeuangan();
        $data['mutasikeuangan'] = $mk->getMutasi(request: $request)->get();
        $data['bank'] = Bank::orderBy('nama_bank')->get();

        $bulan = !empty($request->dari) ? date('m', strtotime($request->dari)) : '';
        $tahun = !empty($request->dari) ? date('Y', strtotime($request->dari)) : '';

        $data['saldo_awal']  = Saldoawalmutasikeungan::where('bulan', $bulan)->where('tahun', $tahun)->where('kode_bank', $request->kode_bank_search)->first();
        $start_date = $tahun . "-" . $bulan . "-01";
        if (!empty($request->dari && !empty($request->sampai))) {
            $data['mutasi']  = Mutasikeuangan::select(
                DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as kredit"),
                DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as debet"),
            )
                ->where('tanggal', '>=', $start_date)
                ->where('tanggal', '<', $request->dari)
                ->where('kode_bank', $request->kode_bank_search)
                ->first();
        } else {
            $data['mutasi'] = null;
        }



        return view('keuangan.mutasikeuangan.index', $data);
    }

    public function create()
    {
        $data['bank'] = Bank::orderBy('nama_bank')->get();
        $data['coa'] = Coa::orderby('kode_akun')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('keuangan.mutasikeuangan.create', $data);
    }
}

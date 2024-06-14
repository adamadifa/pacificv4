<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Logamtokertas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LogamtokertasController extends Controller
{
    public function index(Request $request)
    {

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $lg = new Logamtokertas();
        $data['logamtokertas'] = $lg->getLogamtokertas(request: $request)->get();

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;


        return view('keuangan.kasbesar.logamtokertas.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.kasbesar.logamtokertas.create', $data);
    }

    public function store(Request $request)
    {

        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'kode_cabang' => 'required',
                'jumlah' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'jumlah' => 'required'
            ]);
        }

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            //Generate Kode Logamtokertas
            $lastlogamtokertas = Logamtokertas::whereRaw('YEAR(tanggal)=' . date('Y'))->orderBy('kode_logamtokertas', 'desc')->first();
            $last_kode_logamtokertas = $lastlogamtokertas != null ? $lastlogamtokertas->kode_logamtokertas : '';
            $kode_logamtokertas = buatkode($last_kode_logamtokertas, "LG" . substr(date('Y'), 2, 2), 4);
            Logamtokertas::create([
                'kode_logamtokertas' => $kode_logamtokertas,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'jumlah' => toNumber($request->jumlah)
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_logamtokertas)
    {
        $kode_logamtokertas = Crypt::decrypt($kode_logamtokertas);

        $lg = new Logamtokertas();
        $data['logamtokertas'] = $lg->getLogamtokertas(kode_logamtokertas: $kode_logamtokertas)->first();

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.kasbesar.logamtokertas.edit', $data);
    }

    // Start Generation Here
    public function update($kode_logamtokertas, Request $request)
    {
        $kode_logamtokertas = Crypt::decrypt($kode_logamtokertas);
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'kode_cabang' => 'required',
                'jumlah' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'jumlah' => 'required'
            ]);
        }

        DB::beginTransaction();
        try {
            $logamtokertas = Logamtokertas::where('kode_logamtokertas', $kode_logamtokertas)->first();
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektutuplaporanlogamtokertas = cektutupLaporan($logamtokertas->tanggal, "penjualan");
            if ($cektutuplaporanlogamtokertas > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            Logamtokertas::where('kode_logamtokertas', $kode_logamtokertas)->update([
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'jumlah' => toNumber($request->jumlah)
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_logamtokertas)
    {
        $kode_logamtokertas = Crypt::decrypt($kode_logamtokertas);
        DB::beginTransaction();
        try {
            $logamtokertas = Logamtokertas::where('kode_logamtokertas', $kode_logamtokertas)->first();
            $cektutuplaporan = cektutupLaporan($logamtokertas->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            Logamtokertas::where('kode_logamtokertas', $kode_logamtokertas)->delete();

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

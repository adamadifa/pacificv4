<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Setoranpusat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SetoranpusatController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $sp = new Setoranpusat();
        $data['setoran_pusat'] = $sp->getSetoranpusat(request: $request)->get();

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;

        return view('keuangan.kasbesar.setoranpusat.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('keuangan.kasbesar.setoranpusat.create', $data);
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
                'total_setoran' => 'required|numeric|min:1',
                'keterangan' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'total_setoran' => 'required|numeric|min:1',
                'keterangan' => 'required'
            ]);
        }

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $setoranpusat = Setoranpusat::select('kode_setoran')
                ->whereRaw('LEFT(kode_setoran,4)="SB' . date('y') . '"')
                ->orderBy('kode_setoran', 'desc')
                ->first();
            $last_kode_setoran = $setoranpusat != null ? $setoranpusat->kode_setoran : '';
            $kode_setoran   = buatkode($last_kode_setoran, 'SB' . date('y'), 5);

            Setoranpusat::create([
                'kode_setoran' => $kode_setoran,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'setoran_kertas' => toNumber($request->setoran_kertas),
                'setoran_logam' => toNumber($request->setoran_logam),
                'keterangan' => $request->keterangan,
                'status' => 0
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_setoran)
    {
        $kode_setoran = Crypt::decrypt($kode_setoran);
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;

        $data['setoranpusat'] = Setoranpusat::where('kode_setoran', $kode_setoran)->first();
        return view('keuangan.kasbesar.setoranpusat.edit', $data);
    }

    public function update($kode_setoran, Request $request)
    {
        $kode_setoran = Crypt::decrypt($kode_setoran);
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'kode_cabang' => 'required',
                'total_setoran' => 'required|numeric|min:1',
                'keterangan' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                'total_setoran' => 'required|numeric|min:1',
                'keterangan' => 'required'
            ]);
        }

        DB::beginTransaction();
        try {

            $setoranpusat = Setoranpusat::where('kode_setoran', $kode_setoran)->first();
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektutuplaporansetoranpusat = cektutupLaporan($setoranpusat->tanggal, "penjualan");
            if ($cektutuplaporansetoranpusat > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }




            Setoranpusat::where('kode_setoran', $kode_setoran)->update([
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'setoran_kertas' => toNumber($request->setoran_kertas),
                'setoran_logam' => toNumber($request->setoran_logam),
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_setoran)
    {
        $kode_setoran = Crypt::decrypt($kode_setoran);
        $setoranpusat = Setoranpusat::where('kode_setoran', $kode_setoran)->first();

        if (!$setoranpusat) {
            return Redirect::back()->with(messageError('Data Setoran Penjualan tidak ditemukan'));
        }

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($setoranpusat->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            Setoranpusat::where('kode_setoran', $kode_setoran)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

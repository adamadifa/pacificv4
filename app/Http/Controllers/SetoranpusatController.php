<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Ledger;
use App\Models\Ledgersetoranpusat;
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
                // 'total_setoran' => 'required|numeric|min:1',
                'keterangan' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'tanggal' => 'required',
                // 'total_setoran' => 'required|numeric|min:1',
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


    public function approve($kode_setoran)
    {
        $kode_setoran = Crypt::decrypt($kode_setoran);
        $sp = new Setoranpusat();
        $data['setoranpusat'] = $sp->getSetoranpusat(kode_setoran: $kode_setoran)->first();
        $data['bank'] = Bank::orderBy('nama_bank')->get();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('keuangan.kasbesar.setoranpusat.approve', $data);
    }

    public function approvestore($kode_setoran, Request $request)
    {

        $request->validate([
            'tanggal' => 'required',
            'kode_bank' => 'required',
            'omset_bulan' => 'required',
            'omset_tahun' => 'required'
        ]);

        $kode_setoran = Crypt::decrypt($kode_setoran);
        $tahun = date('y', strtotime($request->tanggal));
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $sp = new Setoranpusat();
            $setoranpusat = $sp->getSetoranpusat(kode_setoran: $kode_setoran)->first();
            $total_setoran = $setoranpusat->setoran_kertas + $setoranpusat->setoran_logam;
            if ($setoranpusat->kode_cabang == 'PST') {
                $lastledger = Ledger::select('no_bukti')
                    ->whereRaw('LEFT(no_bukti,7) ="LR' . $setoranpusat->kode_cabang . $tahun . '"')
                    ->whereRaw('LENGTH(no_bukti)=12')
                    ->orderBy('no_bukti', 'desc')
                    ->first();
                $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
                $no_bukti = buatkode($last_no_bukti, 'LR' . $setoranpusat->kode_cabang . $tahun, 5);
            } else {
                $lastledger = Ledger::select('no_bukti')
                    ->whereRaw('LEFT(no_bukti,7) ="LR' . $setoranpusat->kode_cabang . $tahun . '"')
                    ->orderBy('no_bukti', 'desc')
                    ->first();
                $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
                $no_bukti = buatkode($last_no_bukti, 'LR' . $setoranpusat->kode_cabang . $tahun, 4);
            }

            Ledger::create([
                'no_bukti' => $no_bukti,
                'tanggal' => $request->tanggal,
                'kode_bank' => $request->kode_bank,
                'keterangan' => "SETORAN CAB " . $setoranpusat->kode_cabang,
                'kode_akun' => getAkunpiutangcabang($setoranpusat->kode_cabang),
                'jumlah' => $total_setoran,
                'debet_kredit' => 'K'
            ]);

            Ledgersetoranpusat::create([
                'no_bukti' => $no_bukti,
                'kode_setoran' => $kode_setoran
            ]);

            Setoranpusat::where('kode_setoran', $kode_setoran)->update([
                'status' => 1,
                'omset_bulan' => $request->omset_bulan,
                'omset_tahun' => $request->omset_tahun
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //throw $th;
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cancel($kode_setoran)
    {
        $kode_setoran = Crypt::decrypt($kode_setoran);
        DB::beginTransaction();
        try {
            $setoranpusat = Setoranpusat::where('kode_setoran', $kode_setoran)->first();
            if (!$setoranpusat) {
                return Redirect::back()->with(messageError('Data Setoran Penjualan tidak ditemukan'));
            }

            $cektutuplaporan = cektutupLaporan($setoranpusat->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $ledgersetoranpusat  = Ledgersetoranpusat::where('kode_setoran', $kode_setoran)->first();

            //Hapus Ledger
            Ledger::where('no_bukti', $ledgersetoranpusat->no_bukti)->delete();

            //Update Setoran PUsat

            Setoranpusat::where('kode_setoran', $kode_setoran)->update([
                'status' => 0,
                'omset_bulan' => NULL,
                'omset_tahun' => NULL
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //throw $th;

            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    public function destroy($kode_setoran)
    {
        $kode_setoran = Crypt::decrypt($kode_setoran);

        DB::beginTransaction();
        try {

            $setoranpusat = Setoranpusat::where('kode_setoran', $kode_setoran)->first();

            if (!$setoranpusat) {
                return Redirect::back()->with(messageError('Data Setoran Penjualan tidak ditemukan'));
            }

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

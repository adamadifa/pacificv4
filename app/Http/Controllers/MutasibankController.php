<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Coa;
use App\Models\Coacabang;
use App\Models\Ledger;
use App\Models\Saldoawalledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MutasibankController extends Controller
{
    public function index(Request $request)
    {

        $lg = new Ledger();
        $data['ledger'] = $lg->getLedger(request: $request)->get();
        $bnk = new Bank();
        $data['bank'] = $bnk->getMutasibank()->get();

        $bulan = !empty($request->dari) ? date('m', strtotime($request->dari)) : '';
        $tahun = !empty($request->dari) ? date('Y', strtotime($request->dari)) : '';

        $data['saldo_awal']  = Saldoawalledger::where('bulan', $bulan)->where('tahun', $tahun)->where('kode_bank', $request->kode_bank_search)->first();
        $start_date = $tahun . "-" . $bulan . "-01";
        if (!empty($request->dari && !empty($request->sampai))) {
            $data['mutasi']  = Ledger::select(
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

        return view('keuangan.mutasibank.index', $data);
    }


    public function create()
    {
        $bnk = new Bank();
        $data['bank'] = $bnk->getBank()->get();
        $coacabang = new Coacabang();
        $data['coa'] = $coacabang->getCoacabang()->get();
        return view('keuangan.mutasibank.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_bank' => 'required',
            'tanggal' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required',
            'kode_akun' => 'required',
            'debet_kredit' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $bank = Bank::where('kode_bank', $request->kode_bank)->first();
            // dd($bank);
            //Generate No. Bukti
            $tahun = date('y', strtotime($request->tanggal));
            $lastledger = Ledger::select('no_bukti')
                ->whereRaw('LEFT(no_bukti,7) ="LR' . $bank->kode_cabang . $tahun . '"')
                ->whereRaw('LENGTH(no_bukti)=12')
                ->orderBy('no_bukti', 'desc')
                ->first();
            $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
            $no_bukti = buatkode($last_no_bukti, 'LR' . $bank->kode_cabang . $tahun, 5);

            Ledger::create([
                'no_bukti' => $no_bukti,
                'tanggal' => $request->tanggal,
                'kode_bank' => $request->kode_bank,
                'kode_akun' => $request->kode_akun,
                'keterangan' => $request->keterangan,
                'jumlah' => toNumber($request->jumlah),
                'debet_kredit' => $request->debet_kredit,
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $bnk = new Bank();
        $data['bank'] = $bnk->getBank()->get();
        $coacabang = new Coacabang();
        $data['coa'] = $coacabang->getCoacabang()->get();
        $data['mutasibank'] = Ledger::where('no_bukti', $no_bukti)->first();
        return view('keuangan.mutasibank.edit', $data);
    }


    public function update($no_bukti, Request $request)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        DB::beginTransaction();
        try {

            $mutasibank = Ledger::where('no_bukti', $no_bukti)->first();
            $cektutuplaporan = cektutupLaporan($request->tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektutuplaporanmutasibank = cektutupLaporan($mutasibank->tanggal, "ledger");
            if ($cektutuplaporanmutasibank > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            Ledger::where('no_bukti', $no_bukti)->update([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'kode_akun' => $request->kode_akun,
                'jumlah' => toNumber($request->jumlah),
                'debet_kredit' => $request->debet_kredit
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        DB::beginTransaction();
        try {
            $mutasibank = Ledger::where('no_bukti', $no_bukti)->first();
            if (!$mutasibank) {
                return Redirect::back()->with(messageError('Data tidak ditemukan'));
            }

            $cektutuplaporan = cektutupLaporan($mutasibank->tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $mutasibank->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

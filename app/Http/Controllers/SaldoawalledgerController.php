<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Ledger;
use App\Models\Saldoawalledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalledgerController extends Controller
{
    public function index(Request $request)
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        $query = Saldoawalledger::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!empty($request->kode_bank_search)) {
            $query->where('keuangan_ledger_saldoawal.kode_bank', $request->kode_bank_search);
        }
        $query->join('bank', 'keuangan_ledger_saldoawal.kode_bank', '=', 'bank.kode_bank');
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $query->orderBy('keuangan_ledger_saldoawal.kode_bank');
        $data['saldo_awal'] = $query->get();

        $data['bank'] = Bank::orderBy('nama_bank')->get();
        return view('keuangan.ledger.saldoawal.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['bank'] = Bank::orderBy('nama_bank')->get();
        return view('keuangan.ledger.saldoawal.create', $data);
    }

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        DB::beginTransaction();
        try {
            $saldoawalledger = Saldoawalledger::where('kode_saldo_awal', $kode_saldo_awal)->firstOrFail();
            $cektutuplaporan = cektutupLaporan($saldoawalledger->tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $saldoawalledger->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getsaldo(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $kode_bank = $request->kode_bank;

        $bulanlalu = getbulandantahunlalu($bulan, $tahun, "bulan");
        $tahunlalu = getbulandantahunlalu($bulan, $tahun, "tahun");

        $start_date = $tahunlalu . "-" . $bulanlalu . "-01";
        $end_date = date('Y-m-t', strtotime($start_date));
        //Cek Apakah Sudah Ada Saldo Atau Belum
        $ceksaldo = Saldoawalledger::where('kode_bank', $kode_bank)->count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalledger::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->where('kode_bank', $kode_bank)->count();




        $saldobulanlalu = Saldoawalledger::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->where('kode_bank', $kode_bank)->first();

        $mutasi  = Ledger::select(
            DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as kredit"),
            DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as debet"),
        )
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->where('kode_bank', $kode_bank)
            ->first();

        $lastsaldo = $saldobulanlalu != null ? $saldobulanlalu->jumlah : 0;
        if ($mutasi != null) {
            $debet = $mutasi->debet;
            $kredit = $mutasi->kredit;
        } else {
            $debet = 0;
            $kredit = 0;
        }
        $saldoawal = $lastsaldo + $kredit - $debet;

        $data = [
            'ceksaldo' => $ceksaldo,
            'ceksaldobulanlalu' => $ceksaldobulanlalu,
            'saldo' => $saldoawal
        ];
        return response()->json([
            'success' => true,
            'message' => 'Saldo Awal Ledger',
            'data'    => $data
        ]);
    }
}

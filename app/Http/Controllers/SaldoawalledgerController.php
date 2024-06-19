<?php

namespace App\Http\Controllers;

use App\Models\Bank;
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
        $data['saldo_awal'] = $query->get();

        $data['bank'] = Bank::orderBy('nama_bank')->get();
        return view('keuangan.ledger.saldoawal.index', $data);
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
}

<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Coa;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LedgerController extends Controller
{
    public function index(Request $request)
    {

        $lg = new Ledger();
        $data['ledger'] = $lg->getLedger(request: $request)->get();
        $data['bank'] = Bank::orderBy('nama_bank')->get();
        return view('keuangan.ledger.index', $data);
    }

    public function create(Request $request)
    {
        $data['bank'] = Bank::orderBy('nama_bank')->get();
        $data['coa'] = Coa::orderby('kode_akun')->get();
        return view('keuangan.ledger.create', $data);
    }

    public function store(Request $request)
    {

        $kode_bank = $request->kode_bank;
        $tanggal = $request->tanggal_item;
        $pelanggan = $request->pelanggan_item;
        $keterangan = $request->keterangan_item;
        $jumlah = $request->jumlah_item;
        $kode_akun = $request->kode_akun_item;
        $debet_kredit = $request->debet_kredit_item;
        $kode_peruntukan = $request->kode_peruntukan_item;
        // dd($kode_akun);
        DB::beginTransaction();
        try {
            if (count($tanggal) === 0) {
                return Redirect::back()->with(messageError('Data Masih Kosong'));
            }

            for ($i = 0; $i < count($tanggal); $i++) {
                $tahun = date('y', strtotime($tanggal[$i]));
                $lastledger = Ledger::select('no_bukti')
                    ->whereRaw('LEFT(no_bukti,7) ="LRPST' . $tahun . '"')
                    ->whereRaw('LENGTH(no_bukti)=12')
                    ->orderBy('no_bukti', 'desc')
                    ->first();
                $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
                $no_bukti = buatkode($last_no_bukti, 'LRPST' . $tahun, 5);

                Ledger::create([
                    'no_bukti' => $no_bukti,
                    'tanggal' => $tanggal[$i],
                    'pelanggan' => $pelanggan[$i],
                    'kode_bank' => $kode_bank,
                    'kode_akun' => $kode_akun[$i],
                    'keterangan' => $keterangan[$i],
                    'jumlah' => toNumber($jumlah[$i]),
                    'debet_kredit' => $debet_kredit[$i],
                    'kode_peruntukan' => $kode_peruntukan[$i]

                ]);

                DB::commit();
                return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back() - with(messageError($e->getMessage()));
        }
    }
}

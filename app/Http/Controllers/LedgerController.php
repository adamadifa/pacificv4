<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Coa;
use App\Models\Costratio;
use App\Models\Ledger;
use App\Models\Ledgercostratio;
use App\Models\Saldoawalledger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $lg = new Ledger();
        $data['ledger'] = $lg->getLedger(request: $request)->get();
        if ($user->hasRole('admin pusat')) {
            $data['bank'] = Bank::where('kode_cabang', '!=', 'PST')->orderBy('nama_bank')->get();
        } else {

            $data['bank'] = Bank::orderBy('nama_bank')->get();
        }

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



        return view('keuangan.ledger.index', $data);
    }

    public function create()
    {
        $data['bank'] = Bank::orderBy('nama_bank')->get();
        $data['coa'] = Coa::orderby('kode_akun')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
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
        $ket_peruntukan = $request->kode_cabang_item;
        $kode_akun_cr = ['6-1', '6-2'];

        DB::beginTransaction();
        try {
            if (count($tanggal) === 0) {
                return Redirect::back()->with(messageError('Data Masih Kosong'));
            }

            for ($i = 0; $i < count($tanggal); $i++) {
                $tahun = date('y', strtotime($tanggal[$i]));
                $bulan = date('m', strtotime($tanggal[$i]));
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
                    'kode_peruntukan' => $kode_peruntukan[$i],
                    'keterangan_peruntukan' => $ket_peruntukan[$i]

                ]);
                //Jika Kode akun Masuk kedalam Kateogri Cost Ratio
                if ($debet_kredit[$i] == 'D' && in_array(substr($kode_akun[$i], 0, 3), $kode_akun_cr) && $kode_peruntukan[$i] == 'PC') {

                    //Generate Kode Cost Ratio
                    $kode = "CR" . $bulan . $tahun;
                    $costratio = Costratio::select('kode_cr')
                        ->whereRaw('LEFT(kode_cr,6) ="' . $kode . '"')
                        ->orderBy('kode_cr', 'desc')
                        ->first();

                    $last_kode_cr = $costratio != null ? $costratio->kode_cr : '';
                    $kode_cr = buatkode($last_kode_cr, "CR" . $bulan . $tahun, 4);

                    Costratio::create([
                        'kode_cr' => $kode_cr,
                        'tanggal' => $tanggal[$i],
                        'kode_akun' => $kode_akun[$i],
                        'keterangan' => $keterangan[$i],
                        'kode_cabang' => $ket_peruntukan[$i],
                        'kode_sumber' => 2,
                        'jumlah' => toNumber($jumlah[$i])
                    ]);

                    Ledgercostratio::create([
                        'no_bukti' => $no_bukti,
                        'kode_cr' => $kode_cr,
                    ]);
                }
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($no_bukti)
    {

        $no_bukti = Crypt::decrypt($no_bukti);
        $lg = new Ledger();
        $data['ledger'] =  $lg->getLedger(no_bukti: $no_bukti)->first();
        $data['bank'] = Bank::orderBy('nama_bank')->get();
        $data['coa'] = Coa::orderby('kode_akun')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();

        return view('keuangan.ledger.edit', $data);
    }


    public function update($no_bukti, Request $request)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $ledger = Ledger::where('no_bukti', $no_bukti)->first();
        $kode_akun_cr = ['6-1', '6-2'];
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektutuplaporanledger = cektutupLaporan($ledger->tanggal, "ledger");
            if ($cektutuplaporanledger > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            Ledger::where('no_bukti', $no_bukti)->update([
                'tanggal' => $request->tanggal,
                'pelanggan' => $request->pelanggan,
                'keterangan' => $request->keterangan,
                'kode_bank' => $request->kode_bank,
                'kode_akun' => $request->kode_akun,
                'jumlah' => toNumber($request->jumlah),
                'debet_kredit' => $request->debet_kredit,
                'kode_peruntukan' => $request->kode_peruntukan,
                'keterangan_peruntukan' => $request->kode_peruntukan == 'PC' ? $request->kode_cabang :  null,
            ]);
            $cekcostratio = Ledgercostratio::where('no_bukti', $no_bukti)->first();
            if ($request->debet_kredit == 'D' && in_array(substr($request->kode_akun, 0, 3), $kode_akun_cr) && $request->kode_peruntukan == 'PC') {
                //Cek Jika Sudah Ada di Cost Ratio
                if ($cekcostratio != null) {
                    Costratio::where('kode_cr', $cekcostratio->kode_cr)->update([
                        'tanggal' => $request->tanggal,
                        'kode_akun' => $request->kode_akun,
                        'keterangan' => $request->keterangan,
                        'kode_cabang' => $request->kode_cabang,
                        'jumlah' => toNumber($request->jumlah)
                    ]);
                } else {
                    $bulan = date('m', strtotime($request->tanggal));
                    $tahun = substr(date('Y', strtotime($request->tanggal)), 2, 2);
                    //Generate Kode Cost Ratio
                    $kode = "CR" . $bulan . $tahun;
                    //dd($kode);
                    $costratio = Costratio::select('kode_cr')
                        ->whereRaw('LEFT(kode_cr,6) ="' . $kode . '"')
                        ->orderBy('kode_cr', 'desc')
                        ->first();

                    $last_kode_cr = $costratio != null ? $costratio->kode_cr : '';
                    $kode_cr = buatkode($last_kode_cr, "CR" . $bulan . $tahun, 4);

                    Costratio::create([
                        'kode_cr' => $kode_cr,
                        'tanggal' => $request->tanggal,
                        'kode_akun' => $request->kode_akun,
                        'keterangan' => $request->keterangan,
                        'kode_cabang' => $request->kode_cabang,
                        'kode_sumber' => 2,
                        'jumlah' => toNumber($request->jumlah)
                    ]);

                    Ledgercostratio::create([
                        'no_bukti' => $no_bukti,
                        'kode_cr' => $kode_cr,
                    ]);
                }
            } else {
                if ($cekcostratio != null) {
                    Ledgercostratio::where('no_bukti', $no_bukti)->delete();
                    Costratio::where('kode_cr', $cekcostratio->kode_cr)->delete();
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        try {
            $ledger = Ledger::where('no_bukti', $no_bukti)->first();
            $cekcostratio = Ledgercostratio::where('no_bukti', $no_bukti)->first();
            $cektutuplaporanledger = cektutupLaporan($ledger->tanggal, "ledger");
            if ($cektutuplaporanledger > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            Ledger::where('no_bukti', $no_bukti)->delete();
            Costratio::where('kode_cr', $cekcostratio->kode_cr)->delete();

            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

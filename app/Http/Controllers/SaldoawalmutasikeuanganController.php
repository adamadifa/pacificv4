<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Mutasikeuangan;
use App\Models\Saldoawalmutasikeungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalmutasikeuanganController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        $query = Saldoawalmutasikeungan::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!empty($request->kode_bank_search)) {
            $query->where('keuangan_mutasi_saldoawal.kode_bank', $request->kode_bank_search);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('bank.kode_cabang', auth()->user()->kode_cabang);
                $query->where('nama_bank', 'not like', '%giro%');
            }
        }
        $query->join('bank', 'keuangan_mutasi_saldoawal.kode_bank', '=', 'bank.kode_bank');
        $query->join('cabang', 'bank.kode_cabang', '=', 'cabang.kode_cabang');
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $query->orderBy('keuangan_mutasi_saldoawal.kode_bank');
        $data['saldo_awal'] = $query->get();


        $bnk = new Bank();
        $data['bank'] = $bnk->getMutasibank()->get();
        return view('keuangan.mutasikeuangan.saldoawal.index', $data);
    }


    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');


        $bnk = new Bank();
        $data['bank'] = $bnk->getBank()->get();
        return view('keuangan.mutasikeuangan.saldoawal.create', $data);
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
        $ceksaldo = Saldoawalmutasikeungan::where('kode_bank', $kode_bank)->count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalmutasikeungan::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->where('kode_bank', $kode_bank)->count();

        //Cek Saldo Bulan Ini
        $ceksaldobulanini = Saldoawalmutasikeungan::where('bulan', $bulan)->where('tahun', $tahun)->where('kode_bank', $kode_bank)->count();


        $saldobulanlalu = Saldoawalmutasikeungan::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->where('kode_bank', $kode_bank)->first();

        $mutasi  = Mutasikeuangan::select(
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
            'ceksaldobulanini' => $ceksaldobulanini,
            'ceksaldobulanlalu' => $ceksaldobulanlalu,
            'saldo' => $saldoawal
        ];
        return response()->json([
            'success' => true,
            'message' => 'Saldo Awal Ledger',
            'data'    => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
            'kode_bank' => 'required',
            'jumlah' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $bulan = $request->bulan < 10 ? "0" . $request->bulan : $request->bulan;
            $kode_saldo_awal = "SA" . $bulan . substr($request->tahun, 2, 2) . $request->kode_bank;
            // dd($kode_saldo_awal);
            $tanggal = $request->tahun . "-" . $request->bulan . "-01";
            $cektutuplaporan = cektutupLaporan($tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            //Cek Jika Saldo Sudah Pernah Diinputkan
            $ceksaldo = Saldoawalmutasikeungan::where('kode_saldo_awal', $kode_saldo_awal)->count();

            $bulanlalu = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
            $tahunlalu = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");
            $ceksaldobulanlalu = Saldoawalmutasikeungan::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->where('kode_bank', $request->kode_bank)->count();

            $ceksaldobank = Saldoawalmutasikeungan::where('kode_bank', $request->kode_bank)->count();

            if ($ceksaldobulanlalu === 0 && $ceksaldobank > 0 && $ceksaldo === 0) {
                return Redirect::back()->with(messageError('Saldo Sebelumnya Belum Di Set'));
            }

            if ($ceksaldo > 0) {
                Saldoawalmutasikeungan::where('kode_saldo_awal', $kode_saldo_awal)->update([
                    'tanggal' => $tanggal,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'jumlah' => toNumber($request->jumlah),
                ]);
            } else {
                Saldoawalmutasikeungan::create([
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'tanggal' => $tanggal,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'kode_bank' => $request->kode_bank,
                    'jumlah' => toNumber($request->jumlah),
                ]);
            }

            DB::commit();

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

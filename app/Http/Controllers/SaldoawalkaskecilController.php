<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Kaskecil;
use App\Models\Saldoawalkaskecil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalkaskecilController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        $query = Saldoawalkaskecil::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('keuangan_kaskecil_saldoawal.kode_cabang', $request->kode_cabang_search);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('cabang.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        $query->join('cabang', 'keuangan_kaskecil_saldoawal.kode_cabang', '=', 'cabang.kode_cabang');
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $query->orderBy('keuangan_kaskecil_saldoawal.kode_cabang');
        $data['saldo_awal'] = $query->get();


        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.kaskecil.saldoawal.index', $data);
    }


    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');


        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.kaskecil.saldoawal.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
            'kode_cabang' => 'required',
            'jumlah' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $bulan = $request->bulan < 10 ? "0" . $request->bulan : $request->bulan;
            $kode_saldo_awal = "S" . $bulan . substr($request->tahun, 2, 2) . $request->kode_cabang;
            $tanggal = $request->tahun . "-" . $request->bulan . "-01";
            $cektutuplaporan = cektutupLaporan($tanggal, "kaskecil");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            //dd($kode_saldo_awal);
            //Cek Jika Saldo Sudah Pernah Diinputkan
            $ceksaldo = Saldoawalkaskecil::where('kode_saldo_awal', $kode_saldo_awal)->count();

            $bulanlalu = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
            $tahunlalu = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");
            $ceksaldobulanlalu = Saldoawalkaskecil::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->where('kode_cabang', $request->kode_cabang)->count();

            $ceksaldocabang = Saldoawalkaskecil::where('kode_cabang', $request->kode_cabang)->count();

            if ($ceksaldobulanlalu === 0 && $ceksaldocabang > 0 && $ceksaldo === 0) {
                return Redirect::back()->with(messageError('Saldo Sebelumnya Belum Di Set'));
            }

            if ($ceksaldo > 0) {
                Saldoawalkaskecil::where('kode_saldo_awal', $kode_saldo_awal)->update([
                    'tanggal' => $tanggal,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'jumlah' => toNumber($request->jumlah),
                ]);
            } else {
                Saldoawalkaskecil::create([
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'tanggal' => $tanggal,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'kode_cabang' => $request->kode_cabang,
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

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        DB::beginTransaction();
        try {
            $saldoawalkaskecil = Saldoawalkaskecil::where('kode_saldo_awal', $kode_saldo_awal)->firstOrFail();
            $cektutuplaporan = cektutupLaporan($saldoawalkaskecil->tanggal, "kaskecil");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $saldoawalkaskecil->delete();
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
        $kode_cabang = $request->kode_cabang;

        $bulanlalu = getbulandantahunlalu($bulan, $tahun, "bulan");
        $tahunlalu = getbulandantahunlalu($bulan, $tahun, "tahun");

        $start_date = $tahunlalu . "-" . $bulanlalu . "-01";
        $end_date = date('Y-m-t', strtotime($start_date));
        //Cek Apakah Sudah Ada Saldo Atau Belum
        $ceksaldo = Saldoawalkaskecil::where('kode_cabang', $kode_cabang)->count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalkaskecil::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->where('kode_cabang', $kode_cabang)->count();

        //Cek Saldo Bulan Ini
        $ceksaldobulanini = Saldoawalkaskecil::where('bulan', $bulan)->where('tahun', $tahun)->where('kode_cabang', $kode_cabang)->count();


        $saldobulanlalu = Saldoawalkaskecil::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->where('kode_cabang', $kode_cabang)->first();

        $mutasi  = Kaskecil::select(
            DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as kredit"),
            DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as debet"),
        )
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->where('kode_cabang', $kode_cabang)
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
            'message' => 'Saldo Awal Kas Kecil',
            'data'    => $data
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Kuranglebihsetor;
use App\Models\Logamtokertas;
use App\Models\Saldoawalkasbesar;
use App\Models\Setoranpenjualan;
use App\Models\Setoranpusat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalkasbesarController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');


        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalkasbesar::query();
        $query->join('cabang', 'keuangan_kasbesar_saldoawal.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('keuangan_kasbesar_saldoawal.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $saldo_awal = $query->get();
        return view('keuangan.kasbesar.saldoawal.index', compact('list_bulan', 'start_year', 'saldo_awal', 'nama_bulan'));
    }


    public function create()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();

        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('keuangan.kasbesar.saldoawal.create', $data);
    }

    public function getsaldo(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $bulanlalu = getbulandantahunlalu($bulan, $tahun, "bulan");
        $tahunlalu = getbulandantahunlalu($bulan, $tahun, "tahun");

        $bulanberikutnya = getbulandantahunberikutnya($bulan, $tahun, "bulan");
        $tahunberikutnya = getbulandantahunberikutnya($bulan, $tahun, "tahun");

        $lastmonth = getbulandantahunlalu($bulanlalu, $tahunlalu, "bulan");
        $lastyear = getbulandantahunlalu($bulanlalu, $tahunlalu, "tahun");

        $tgl_dari_bulanlalu = $tahunlalu . "-" . $bulanlalu . "-01";
        $tgl_sampai_bulanlalu = date('Y-m-t', strtotime($tgl_dari_bulanlalu));

        //Cek Apakah Sudah Ada Saldo Atau Belum
        $ceksaldo = Saldoawalkasbesar::count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalkasbesar::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->count();

        //Cek Saldo Bulan Ini
        $ceksaldobulanini = Saldoawalkasbesar::where('bulan', $bulan)->where('tahun', $tahun)->count();


        //Cek Setoran Bulan Depan Yang Masuk Ke Omset Bulan Ini
        $sp = new Setoranpusat();
        $ceksetoranbulanberikutnya = $sp->cekOmsetsetoranpusatbulandepan($bulanlalu, $tahunlalu, $bulan, $tahun, $kode_cabang)->first();

        //Cek Setoran Bulan Lalu yang Mausk Ke Omset Bulan Ini
        $ceksetoranbulansebelumnya = $sp->cekOmsetsetoranpusatbulansebelumnya($lastmonth, $lastyear, $bulan, $tahun, $kode_cabang)->first();

        if ($ceksetoranbulanberikutnya != null) {
            if (!empty($ceksetoranbulanberikutnya->tanggal_diterima)) {
                $tanggal_diterima = $ceksetoranbulanberikutnya->tanggal_diterima;
            } else if (!empty($ceksetoranbulanberikutnya->tanggal_diterima_transfer)) {
                $tanggal_diterima = $ceksetoranbulanberikutnya->tanggal_diterima_transfer;
            } else if (!empty($ceksetoranbulanberikutnya->tanggal_diterima_giro)) {
                $tanggal_diterima = $ceksetoranbulanberikutnya->tanggal_diterima_giro;
            }
            $sampai = $tanggal_diterima;
        } else {
            $sampai = date("Y-m-t", strtotime($tgl_dari_bulanlalu));
        }

        if ($ceksetoranbulansebelumnya != null) {
            $dari = $ceksetoranbulansebelumnya->tanggal;
        } else {
            $dari = $tgl_dari_bulanlalu;
        }


        //Jika Saldo BUlan Lalu Kosong dan Saldo Bulan Ini Ada Maka Di Ambil Saldo BUlan Ini
        if (empty($ceksaldobulanlalu) && !empty($ceksaldobulanini)) {
            $saldo = Saldoawalkasbesar::where('bulan', $bulan)->where('tahun', $tahun)->first();
        } else {
            $saldobulanlalu = Saldoawalkasbesar::where('bulan', $bulanlalu)
                ->where('tahun', $tahunlalu)
                ->where('kode_cabang', $kode_cabang)->first();
            $setoranpenjualanbulanlalu = Setoranpenjualan::select(
                DB::raw("SUM(setoran_kertas) as setoran_kertas"),
                DB::raw("SUM(setoran_logam) as setoran_logam"),
                DB::raw("SUM(setoran_transfer) as setoran_transfer"),
                DB::raw("SUM(setoran_giro) as setoran_giro"),
                DB::raw("SUM(giro_to_cash) as giro_to_cash"),
                DB::raw("SUM(giro_to_transfer) as giro_to_transfer")
            )
                ->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman')
                ->where('salesman.kode_cabang', $kode_cabang)
                ->whereBetween('keuangan_setoranpenjualan.tanggal', [$tgl_dari_bulanlalu, $tgl_sampai_bulanlalu])
                ->first();

            $setoranpusatbulanlalu = Setoranpusat::select(
                DB::raw("SUM(setoran_kertas) as setoran_kertas"),
                DB::raw("SUM(setoran_logam) as setoran_logam"),
                DB::raw("SUM(setoran_transfer) as setoran_transfer"),
                DB::raw("SUM(setoran_giro) as setoran_giro")
            )
                ->where('kode_cabang', $kode_cabang)
                ->whereBetween('tanggal', [$dari, $sampai])
                ->where('omset_bulan', $bulanlalu)
                ->where('omset_tahun', $tahunlalu)
                ->where('status', 1)
                ->first();


            $kurangsetorbulanlalu = Kuranglebihsetor::select(
                DB::raw("SUM(uang_kertas) as setoran_kertas"),
                DB::raw("SUM(uang_logam) as setoran_logam")
            )
                ->join('salesman', 'keuangan_kuranglebihsetor.kode_salesman', '=', 'salesman.kode_salesman')
                ->where('salesman.kode_cabang', $kode_cabang)
                ->whereBetween('tanggal', [$dari, $sampai])
                ->where('jenis_bayar', 2)
                ->first();


            $lebihsetorbulanlalu = Kuranglebihsetor::select(
                DB::raw("SUM(uang_kertas) as setoran_kertas"),
                DB::raw("SUM(uang_logam) as setoran_logam")
            )
                ->join('salesman', 'keuangan_kuranglebihsetor.kode_salesman', '=', 'salesman.kode_salesman')
                ->where('salesman.kode_cabang', $kode_cabang)
                ->whereBetween('tanggal', [$dari, $sampai])
                ->where('jenis_bayar', 1)
                ->first();

            $gantilogamtokertas = Logamtokertas::select(
                DB::raw("SUM(jumlah) as jml_gantikertas")
            )
                ->where('kode_cabang', $kode_cabang)
                ->whereBetween('tanggal', [$dari, $sampai])
                ->first();


            //Saldo Sebelumnya
            $saldo_kertas = $saldobulanlalu->uang_kertas  ? $saldobulanlalu->uang_kertas : 0;
            $saldo_logam  = $saldobulanlalu->uang_logam != null ? $saldobulanlalu->uang_logam : 0;
            $saldo_giro   = $saldobulanlalu->giro != null ? $saldobulanlalu->giro : 0;
            $saldo_transfer  = $saldobulanlalu->transfer != null ? $saldobulanlalu->transfer : 0;


            //Setoran Penjualan
            $setoran_penjualan_kertas = $setoranpenjualanbulanlalu->setoran_kertas;
            $setoran_penjualan_logam  = $setoranpenjualanbulanlalu->setoran_logam;
            $setoran_penjualan_giro  = $setoranpenjualanbulanlalu->setoran_giro;
            $setoran_penjualan_transfer   = $setoranpenjualanbulanlalu->setoran_transfer;
            $giro_to_cash  = $setoranpenjualanbulanlalu->giro_to_cash;
            $giro_to_transfer  = $setoranpenjualanbulanlalu->giro_to_transfer;
            //Kurang Lebih Setor
            $kurang_kertas = $kurangsetorbulanlalu->setoran_kertas;
            $kurang_logam  = $kurangsetorbulanlalu->setoran_logam;

            $lebih_kertas = $lebihsetorbulanlalu->setoran_kertas;
            $lebih_logam  = $lebihsetorbulanlalu->setoran_logam;

            $gantikertas = $gantilogamtokertas->jml_gantikertas;


            $setoranpusat_kertas = $setoranpusatbulanlalu->setoran_kertas;
            $setoranpusat_logam = $setoranpusatbulanlalu->setoran_logam;
            $setoranpusat_giro = $setoranpusatbulanlalu->setoran_giro;
            $setoranpusat_transfer = $setoranpusatbulanlalu->setoran_transfer;

            $uang_kertas = $saldo_kertas + $setoran_penjualan_kertas + $kurang_kertas - $lebih_kertas + $gantikertas + $giro_to_cash - $setoranpusat_kertas;
            $uang_logam = $saldo_logam + $setoran_penjualan_logam + $kurang_logam - $lebih_logam - $gantikertas - $setoranpusat_logam;
            $giro = $saldo_giro + $setoran_penjualan_giro - $setoranpusat_giro - $giro_to_cash - $giro_to_transfer;
            $transfer = $saldo_transfer + $setoran_penjualan_transfer - $setoranpusat_transfer + $giro_to_transfer;

            $data = [
                'uang_kertas' => $uang_kertas,
                'uang_logam' => $uang_logam,
                'giro' => $giro,
                'transfer' => $transfer
            ];
            return response()->json([
                'success' => true,
                'message' => 'Saldo Awal Kas Besar',
                'data'    => $data
            ]);
        }
    }

    public function store(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }
        $kode_saldo_awal = "SA" . $kode_cabang . $request->bulan . substr($request->tahun, 2, 2);
        $tanggal = $request->tahun . "-" . $request->bulan . "-01";
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            //Cek Jika Saldo Sudah Pernah Diinputkan
            $ceksaldo = Saldoawalkasbesar::where('kode_saldo_awal', $kode_saldo_awal)->count();
            if ($ceksaldo > 0) {
                Saldoawalkasbesar::where('kode_saldo_awal', $kode_saldo_awal)->update([
                    'tanggal' => $tanggal,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'uang_kertas' => toNumber($request->uang_kertas),
                    'uang_logam' => toNumber($request->uang_logam),
                    'giro'  => toNumber($request->giro),
                    'transfer' => toNumber($request->transfer),
                    'kode_cabang' => $kode_cabang,

                ]);
            } else {
                Saldoawalkasbesar::create([
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'tanggal' => $tanggal,
                    'bulan' => $request->bulan,
                    'tahun' => $request->tahun,
                    'uang_kertas' => toNumber($request->uang_kertas),
                    'uang_logam' => toNumber($request->uang_logam),
                    'giro'  => toNumber($request->giro),
                    'transfer' => toNumber($request->transfer),
                    'kode_cabang' => $kode_cabang,
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
            $saldoawalkasbesar = Saldoawalkasbesar::where('kode_saldo_awal', $kode_saldo_awal)->firstOrFail();
            $cektutuplaporan = cektutupLaporan($saldoawalkasbesar->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $saldoawalkasbesar->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

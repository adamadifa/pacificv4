<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Saldoawalkasbesar;
use App\Models\Setoranpenjualan;
use App\Models\Setoranpusat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaldoawalkasbesarController extends Controller
{
    public function index(Request $request)
    {
        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalkasbesar::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->join('cabang', 'keuangan_kasbesar_saldoawal.kode_cabang', '=', 'cabang.kode_cabang');
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
        $ceksetoranbulanberikutnya = $sp->cekOmsetsetoranpusat($bulanberikutnya, $tahunberikutnya, $request->kode_cabang)->first();

        //Cek Setoran Bulan Lalu yang Mausk Ke Omset Bulan Ini
        $ceksetoranbulansebelumnya = $sp->cekOmsetsetoranpusat($bulanlalu, $tahunlalu, $request->kode_cabang)->first();

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
            $saldobulanlalu = Saldoawalkasbesar::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->first();
            $setoranpenjualanbulanlalu = Setoranpenjualan::select(
                DB::raw("SUM(setoran_kertas) as setoran_kertas"),
                DB::raw("SUM(setoran_logam) as setoran_logam"),
                DB::raw("SUM(setoran_transfer) as setoran_transfer"),
                DB::raw("SUM(setoran_giro) as setoran_giro")
            )
                ->join('salesman', 'setoran_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                ->where('salesman.kode_cabang', $request->kode_cabang)
                ->whereBetween('keuangan_setoranpenjualan.tanggal', [$tgl_dari_bulanlalu, $tgl_sampai_bulanlalu])
                ->first();

            $setoranpusatbulanlalu = Setoranpusat::select(
                DB::raw("SUM(setoran_kertas) as setoran_kertas"),
                DB::raw("SUM(setoran_logam) as setoran_logam"),
                DB::raw("SUM(setoran_transfer) as setoran_transfer"),
                DB::raw("SUM(setoran_giro) as setoran_giro")
            )
                ->where('kode_cabang', $request->kode_cabang)
                ->whereBetween('tanggal', [$dari, $sampai])
                ->where('omset_bulan', $bulanlalu)
                ->where('omset_bulan', $tahunlalu)
                ->where('status', 1)
                ->first();
        }
    }
}

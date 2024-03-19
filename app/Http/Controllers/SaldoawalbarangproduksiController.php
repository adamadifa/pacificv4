<?php

namespace App\Http\Controllers;

use App\Models\Barangproduksi;
use App\Models\Detailsaldoawalbarangproduksi;
use App\Models\Saldoawalbarangproduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SaldoawalbarangproduksiController extends Controller
{
    public function index(Request $request)
    {

        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalbarangproduksi::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $saldo_awal = $query->get();
        return view('produksi.saldoawalbarangproduksi.index', compact('list_bulan', 'start_year', 'saldo_awal', 'nama_bulan'));
    }

    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        return view('produksi.saldoawalbarangproduksi.create', compact('list_bulan', 'start_year'));
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalbarangproduksi::where('kode_saldo_awal', $kode_saldo_awal)->first();
        $detail = Detailsaldoawalbarangproduksi::where('kode_saldo_awal', $kode_saldo_awal)
            ->join('produksi_barang', 'produksi_barang_saldoawal_detail.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->get();
        $nama_bulan = config('global.nama_bulan');
        return view('produksi.saldoawalbarangproduksi.show', compact('saldo_awal', 'nama_bulan', 'detail'));
    }

    //AJAX REQUEST
    public function getdetailsaldo(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $bulanlalu = getbulandantahunlalu($bulan, $tahun, "bulan");
        $tahunlalu = getbulandantahunlalu($bulan, $tahun, "tahun");

        $tgl_dari_bulanlalu = $tahunlalu . "-" . $bulanlalu . "-01";
        $tgl_sampai_bulanlalu = date('Y-m-t', strtotime($tgl_dari_bulanlalu));

        //Cek Apakah Sudah Ada Saldo Atau Belum
        $ceksaldo = Saldoawalbarangproduksi::count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalbarangproduksi::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->count();

        //Cek Saldo Bulan Ini
        $ceksaldobulanini = Saldoawalbarangproduksi::where('bulan', $bulan)->where('tahun', $tahun)->count();
        //Get Produk

        //Jika Saldo BUlan Lalu Kosong dan Saldo Bulan Ini Ada Maka Di Ambil Saldo BUlan Ini
        if (empty($ceksaldobulanlalu) && !empty($ceksaldobulanini)) {
            // echo 1;
            // die;
            $barangproduksi = Barangproduksi::selectRaw(
                'produksi_barang.kode_barang_produksi,
                nama_barang,
                saldo_awal as saldo_akhir'
            )
                ->where('status_aktif_barang', 1)
                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_barang_produksi,
                        jumlah as saldo_awal
                    FROM
                        produksi_barang_saldoawal_detail
                    INNER JOIN produksi_barang_saldoawal ON produksi_barang_saldoawal_detail.kode_saldo_awal = produksi_barang_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulan' AND tahun='$tahun'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('produksi_barang.kode_barang_produksi', '=', 'saldo_awal.kode_barang_produksi');
                    }
                )
                ->orderBy('kode_produk')->get();
        } else {
            // echo 2;
            // die;
            //Jika Saldo Bulan Lalu Ada Maka Hitung Saldo Awal Bulan Lalu - Mutasi Bulan Lalu
            $barangproduksi = Barangproduksi::selectRaw("
            produksi_barang.kode_barang_produksi,
            nama_barang,
            jml_saldoawal,
            jml_pemasukan,
            jml_pengeluaran,
            IFNULL(jml_saldoawal,0) + IFNULL(jml_pemasukan,0) - IFNULL(jml_pengeluaran,0) as saldo_akhir
            ")
                ->leftJoin(
                    DB::raw("(
                    SELECT kode_barang_produksi,SUM( jumlah ) AS jml_saldoawal
                    FROM produksi_barang_saldoawal_detail
                    INNER JOIN produksi_barang_saldoawal ON produksi_barang_saldoawal_detail.kode_saldo_awal=produksi_barang_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulanlalu' AND tahun = '$tahunlalu'
                    GROUP BY kode_barang_produksi
                ) saldo_awal"),
                    function ($join) {
                        $join->on('produksi_barang.kode_barang_produksi', '=', 'saldo_awal.kode_barang_produksi');
                    }
                )
                ->leftJoin(
                    DB::raw("(
                        SELECT kode_barang_produksi,
                        SUM( jumlah ) AS jml_pemasukan
                        FROM produksi_barang_masuk_detail
                        INNER JOIN produksi_barang_masuk ON produksi_barang_masuk_detail.no_bukti = produksi_barang_masuk.no_bukti
                        WHERE MONTH(tanggal) = '$bulanlalu' AND YEAR(tanggal) = '$tahunlalu'
                        GROUP BY produksi_barang_masuk_detail.kode_barang_produksi
                    ) pemasukan"),
                    function ($join) {
                        $join->on('produksi_barang.kode_barang_produksi', '=', 'pemasukan.kode_barang_produksi');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT kode_barang_produksi,
                        SUM( jumlah ) AS jml_pengeluaran FROM produksi_barang_keluar_detail
                        INNER JOIN produksi_barang_keluar ON produksi_barang_keluar_detail.no_bukti = produksi_barang_keluar.no_bukti
                        WHERE MONTH(tanggal) = '$bulanlalu' AND YEAR(tanggal) = '$tahunlalu'
                        GROUP BY produksi_barang_keluar_detail.kode_barang_produksi
                    ) pengeluaran"),
                    function ($join) {
                        $join->on('produksi_barang.kode_barang_produksi', '=', 'pengeluaran.kode_barang_produksi');
                    }
                )

                ->where('produksi_barang.status_aktif_barang', '1')
                ->get();
        }



        $data = ['barangproduksi', 'readonly'];

        if (empty($ceksaldo)) {
            $readonly = false;
            return view('produksi.saldoawalbarangproduksi.getdetailsaldo', compact($data));
        } else {
            if (empty($ceksaldobulanlalu) && empty($ceksaldobulanini)) {
                return 1;
            } else {
                $readonly = true;
                return view('produksi.saldoawalbarangproduksi.getdetailsaldo', compact($data));
            }
        }
    }
}

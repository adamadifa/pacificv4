<?php

namespace App\Http\Controllers;

use App\Models\Barangproduksi;
use App\Models\Detailsaldoawalbarangproduksi;
use App\Models\Saldoawalbarangproduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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
            $barangproduksi = Barangproduksi::selectRaw(
                'produk.kode_produk,
                nama_produk,
                saldo_awal as saldo_akhir'
            )
                ->where('status_aktif_produk', 1)
                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_produk,
                        jumlah as saldo_awal
                    FROM
                        produksi_mutasi_saldoawal_detail
                    INNER JOIN produksi_mutasi_saldoawal ON produksi_mutasi_saldoawal_detail.kode_saldo_awal = produksi_mutasi_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulan' AND tahun='$tahun'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'saldo_awal.kode_produk');
                    }
                )
                ->orderBy('kode_produk')->get();
        } else {

            //Jika Saldo Bulan Lalu Ada Maka Hitung Saldo Awal Bulan Lalu - Mutasi Bulan Lalu
            $produk = Produk::selectRaw(
                'produk.kode_produk,
                nama_produk,
                IFNULL(saldo_awal,0) - IFNULL(sisamutasi,0) as saldo_akhir'
            )
                ->where('status_aktif_produk', 1)
                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_produk,
                        jumlah as saldo_awal
                    FROM
                        produksi_mutasi_saldoawal_detail
                    INNER JOIN produksi_mutasi_saldoawal ON produksi_mutasi_saldoawal_detail.kode_saldo_awal = produksi_mutasi_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulanlalu' AND tahun='$tahunlalu'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'saldo_awal.kode_produk');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT kode_produk,
                        SUM(IF( in_out = 'IN', jumlah, 0)) - SUM(IF( in_out = 'OUT', jumlah, 0)) as sisamutasi
                        FROM produksi_mutasi_detail
                        INNER JOIN produksi_mutasi
                        ON produksi_mutasi_detail.no_mutasi = produksi_mutasi.no_mutasi
                        WHERE tanggal_mutasi BETWEEN '$tgl_dari_bulanlalu' AND '$tgl_sampai_bulanlalu'  GROUP BY kode_produk
                    ) mutasi"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi.kode_produk');
                    }
                )
                ->orderBy('kode_produk')->get();
        }



        $data = ['produk', 'readonly'];

        if (empty($ceksaldo)) {
            $readonly = false;
            return view('produksi.saldoawalmutasiproduksi.getdetailsaldo', compact($data));
        } else {
            if (empty($ceksaldobulanlalu) && empty($ceksaldobulanini)) {
                return 1;
            } else {
                $readonly = true;
                return view('produksi.saldoawalmutasiproduksi.getdetailsaldo', compact($data));
            }
        }
    }
}

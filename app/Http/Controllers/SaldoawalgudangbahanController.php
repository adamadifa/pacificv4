<?php

namespace App\Http\Controllers;

use App\Models\Barangpembelian;
use App\Models\Detailsaldoawalgudangbahan;
use App\Models\Saldoawalgudangbahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SaldoawalgudangbahanController extends Controller
{
    public function index(Request $request)
    {

        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalgudangbahan::query();
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
        return view('gudangbahan.saldoawal.index', compact('list_bulan', 'start_year', 'saldo_awal', 'nama_bulan'));
    }

    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        return view('gudangbahan.saldoawal.create', compact('list_bulan', 'start_year'));
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalgudangbahan::where('kode_saldo_awal', $kode_saldo_awal)->first();
        $detail = Detailsaldoawalgudangbahan::where('kode_saldo_awal', $kode_saldo_awal)
            ->join('pembelian_barang', 'gudang_bahan_saldoawal_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori')
            ->orderBy('pembelian_barang.kode_jenis_barang')
            ->orderByRaw('cast(substr(gudang_bahan_saldoawal_detail.kode_barang from 4) AS UNSIGNED)')
            ->get();
        $nama_bulan = config('global.nama_bulan');
        return view('gudangbahan.saldoawal.show', compact('saldo_awal', 'nama_bulan', 'detail'));
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
        $ceksaldo = Saldoawalgudangbahan::count();
        // Cek Saldo Bulan Lalu
        $ceksaldobulanlalu = Saldoawalgudangbahan::where('bulan', $bulanlalu)->where('tahun', $tahunlalu)->count();

        //Cek Saldo Bulan Ini
        $ceksaldobulanini = Saldoawalgudangbahan::where('bulan', $bulan)->where('tahun', $tahun)->count();
        //Get Produk

        //Jika Saldo BUlan Lalu Kosong dan Saldo Bulan Ini Ada Maka Di Ambil Saldo BUlan Ini
        if (empty($ceksaldobulanlalu) && !empty($ceksaldobulanini)) {
            $barang = Barangpembelian::select(
                'pembelian_barang.kode_barang',
                'nama_barang',
                'nama_kategori',
                'saldo_awal_unit as saldo_akhir_unit',
                'saldo_awal_berat as saldo_akhir_berat'
            )
                ->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori')

                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_barang,
                        qty_unit as saldo_awal_unit,
                        qty_Berat as saldo_awal_berat
                    FROM
                        gudang_bahan_saldoawal_detail
                    INNER JOIN gudang_bahan_saldoawal ON gudang_bahan_saldoawal_detail.kode_saldo_awal = gudang_bahan_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulan' AND tahun='$tahun'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal.kode_barang');
                    }
                )
                ->where('status', 1)
                ->where('pembelian_barang.kode_group', 'GDB')
                ->where('pembelian_barang.kode_kategori', '!=', 'K002')
                ->orderBy('pembelian_barang.kode_jenis_barang')
                ->orderByRaw('cast(substr(pembelian_barang.kode_barang FROM 4) AS UNSIGNED)')
                ->get();
        } else {

            //Jika Saldo Bulan Lalu Ada Maka Hitung Saldo Awal Bulan Lalu - Mutasi Bulan Lalu
            $barang = Barangpembelian::select(
                'pembelian_barang.kode_barang',
                'nama_barang',
                'nama_kategori',
                DB::raw('IFNULL(saldo_awal_unit,0) + IFNULL(bm_qty_unit,0) - IFNULL(bk_qty_unit,0) as saldo_unit'),
                DB::raw('IFNULL(saldo_awal_berat,0) + IFNULL(bm_qty_berat,0) - IFNULL(bk_qty_berat,0) as saldo_berat')
            )

                ->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori')
                ->leftJoin(
                    DB::raw("(
                    SELECT
                        kode_barang,
                        qty_unit as saldo_awal_unit,
                        qty_berat as saldo_awal_berat
                    FROM
                        gudang_bahan_saldoawal_detail
                    INNER JOIN gudang_bahan_saldoawal ON gudang_bahan_saldoawal_detail.kode_saldo_awal = gudang_bahan_saldoawal.kode_saldo_awal
                    WHERE bulan = '$bulanlalu' AND tahun='$tahunlalu'
                ) saldo_awal"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal.kode_barang');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT
                        gudang_bahan_barang_masuk_detail.kode_barang,
                        SUM(qty_unit) as bk_qty_unit,
                        SUM(qty_berat) as bk_qty_berat
                        FROM
                        gudang_bahan_barang_masuk_detail
                        INNER JOIN gudang_bahan_barang_masuk ON gudang_bahan_barang_masuk_detail.no_bukti = gudang_bahan_barang_masuk.no_bukti
                        WHERE tanggal BETWEEN '$tgl_dari_bulanlalu' AND '$tgl_sampai_bulanlalu'
                        GROUP BY gudang_bahan_barang_masuk_detail.kode_barang
                    ) barang_masuk"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'barang_masuk.kode_barang');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT
                        gudang_bahan_barang_keluar_detail.kode_barang,
                        SUM(qty_unit) as bm_qty_unit,
                        SUM(qty_berat) as bm_qty_berat
                        FROM
                        gudang_bahan_barang_keluar_detail
                        INNER JOIN gudang_bahan_barang_keluar ON gudang_bahan_barang_keluar_detail.no_bukti = gudang_bahan_barang_keluar.no_bukti
                        WHERE tanggal BETWEEN '$tgl_dari_bulanlalu' AND '$tgl_sampai_bulanlalu'
                        GROUP BY gudang_bahan_barang_keluar_detail.kode_barang
                    ) barang_keluar"),
                    function ($join) {
                        $join->on('pembelian_barang.kode_barang', '=', 'barang_keluar.kode_barang');
                    }
                )
                ->where('status', 1)
                ->where('pembelian_barang.kode_group', 'GDB')
                ->where('pembelian_barang.kode_kategori', '!=', 'K002')
                ->orderBy('pembelian_barang.kode_jenis_barang')
                ->orderByRaw('cast(substr(pembelian_barang.kode_barang FROM 4) AS UNSIGNED)')
                ->get();
        }



        $data = ['barang', 'readonly'];

        if (empty($ceksaldo)) {
            $readonly = false;
            return view('gudangbahan.saldoawal.getdetailsaldo', compact($data));
        } else {
            if (empty($ceksaldobulanlalu) && empty($ceksaldobulanini)) {
                return 1;
            } else {
                $readonly = true;
                return view('gudangbahan.saldoawal.getdetailsaldo', compact($data));
            }
        }
    }
}

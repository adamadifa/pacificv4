<?php

namespace App\Http\Controllers;

use App\Models\Barangproduksi;
use App\Models\Detailbarangmasukproduksi;
use App\Models\Detailmutasiproduksi;
use App\Models\Detailpermintaanproduksi;
use App\Models\Detailsaldoawalmutasiproduksi;
use App\Models\Produk;
use App\Models\Saldoawalmutasiproduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LaporanproduksiController extends Controller
{
    public function index()
    {
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        $data['barangproduksi'] = Barangproduksi::where('status_aktif_barang', 1)->orderBy('kode_barang_produksi')->get();
        return view('produksi.laporan.index', $data);
    }

    public function cetakmutasiproduksi(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $saldo_awal = Detailsaldoawalmutasiproduksi::select('kode_produk', 'tanggal', 'jumlah')
            ->join('produksi_mutasi_saldoawal', 'produksi_mutasi_saldoawal_detail.kode_saldo_awal', '=', 'produksi_mutasi_saldoawal.kode_saldo_awal')
            ->where('tanggal', '<=', $request->dari)
            ->where('kode_produk', $request->kode_produk)
            ->orderBy('tanggal', 'desc')
            ->first();

        if ($saldo_awal != null) {
            $tanggal_saldoawal = $saldo_awal->tanggal;
            $mutasi_saldoawal = Detailmutasiproduksi::selectRaw(
                "SUM(IF( in_out = 'IN', jumlah, 0)) -SUM(IF( in_out = 'OUT', jumlah, 0)) as jml_mutasi_saldoawal"
            )
                ->join('produksi_mutasi', 'produksi_mutasi_detail.no_mutasi', '=', 'produksi_mutasi.no_mutasi')
                ->where('tanggal_mutasi', '>=', $tanggal_saldoawal)
                ->where('tanggal_mutasi', '<', $request->dari)
                ->where('kode_produk', $request->kode_produk)
                ->first();


            $saldoawal = $saldo_awal->jumlah + $mutasi_saldoawal->jml_mutasi_saldoawal;
        } else {
            $mutasi_saldoawal = Detailmutasiproduksi::selectRaw(
                "SUM(IF( in_out = 'IN', jumlah, 0)) -SUM(IF( in_out = 'OUT', jumlah, 0)) as jml_mutasi_saldoawal"
            )
                ->join('produksi_mutasi', 'produksi_mutasi_detail.no_mutasi', '=', 'produksi_mutasi.no_mutasi')
                ->where('tanggal_mutasi', '<', $request->dari)
                ->where('kode_produk', $request->kode_produk)
                ->first();
            $saldoawal = $mutasi_saldoawal->jml_mutasi_saldoawal;
        }

        $data['saldoawal'] = $saldoawal;
        $data['mutasi'] = Detailmutasiproduksi::join('produksi_mutasi', 'produksi_mutasi_detail.no_mutasi', '=', 'produksi_mutasi.no_mutasi')
            ->whereBetween('tanggal_mutasi', [$request->dari, $request->sampai])
            ->where('kode_produk', $request->kode_produk)
            ->orderBy('tanggal_mutasi')
            ->orderBy('in_out')
            ->orderBy('shift')
            ->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Mutasi Produksi $request->dari-$request->sampai.xls");
        }
        return view('produksi.laporan.mutasiproduksi_cetak', $data);
    }


    //Cetak Rekap Mutasi Produksi
    public function cetakrekapmutasiproduksi(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $saldo_awal = Saldoawalmutasiproduksi::select("kode_saldo_awal", "tanggal")
            ->where('tanggal', '<=', $request->dari)
            ->orderBy('tanggal', 'desc')
            ->first();

        if ($saldo_awal != null) {
            $data['rekap'] = Produk::select(
                'produk.kode_produk',
                'nama_produk',
                DB::raw('IFNULL(jml_saldoawal,0) + IFNULL(jml_mutasi_saldoawal,0) as jml_saldo_awal'),
                'jml_bpbj',
                'jml_fsthp'
            )
                ->leftJoin(
                    DB::raw("(
                        SELECT
                        kode_produk,jumlah as jml_saldoawal
                        FROM
                        produksi_mutasi_saldoawal_detail
                        WHERE kode_saldo_awal = '$saldo_awal->kode_saldo_awal'
                    ) saldo_awal"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'saldo_awal.kode_produk');
                    }
                )
                ->leftJoin(
                    DB::raw("(
                        SELECT
                        kode_produk,
                        SUM(IF( in_out = 'IN', jumlah, 0)) -SUM(IF( in_out = 'OUT', jumlah, 0)) as jml_mutasi_saldoawal
                        FROM
                        produksi_mutasi_detail
                        INNER JOIN produksi_mutasi ON produksi_mutasi_detail.no_mutasi = produksi_mutasi.no_mutasi
                        WHERE tanggal_mutasi >= '$saldo_awal->tanggal' AND tanggal_mutasi < '$request->dari'                   
                        GROUP BY kode_produk
                    ) mutasi_saldo_awal"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi_saldo_awal.kode_produk');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT
                        kode_produk,
                        SUM(IF( jenis_mutasi = 'BPBJ', jumlah, 0 )) as jml_bpbj,
			            SUM(IF( jenis_mutasi = 'FSTHP', jumlah, 0 )) as jml_fsthp
                        FROM
                        produksi_mutasi_detail
                        INNER JOIN produksi_mutasi ON produksi_mutasi_detail.no_mutasi = produksi_mutasi.no_mutasi
                        WHERE tanggal_mutasi BETWEEN '$request->dari' AND '$request->sampai'          
                        GROUP BY kode_produk
                    ) mutasi_produksi"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi_produksi.kode_produk');
                    }
                )
                ->get();
        } else {
            $data['rekap'] = Produk::select(
                'produk.kode_produk',
                'nama_produk',
                'jml_saldo_awal',
                'jml_bpbj',
                'jml_fsthp'
            )

                ->leftJoin(
                    DB::raw("(
                        SELECT
                        kode_produk,
                        SUM(IF( in_out = 'IN', jumlah, 0)) -SUM(IF( in_out = 'OUT', jumlah, 0)) as jml_saldo_awal
                        FROM
                        produksi_mutasi_detail
                        INNER JOIN produksi_mutasi ON produksi_mutasi_detail.no_mutasi = produksi_mutasi.no_mutasi
                        WHERE tanggal_mutasi  < '$request->dari'                   
                        GROUP BY kode_produk
                    ) mutasi_saldo_awal"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi_saldo_awal.kode_produk');
                    }
                )

                ->leftJoin(
                    DB::raw("(
                        SELECT
                        kode_produk,
                        SUM(IF( jenis_mutasi = 'BPBJ', jumlah, 0 )) as jml_bpbj,
			            SUM(IF( jenis_mutasi = 'FSTHP', jumlah, 0 )) as jml_fsthp
                        FROM
                        produksi_mutasi_detail
                        INNER JOIN produksi_mutasi ON produksi_mutasi_detail.no_mutasi = produksi_mutasi.no_mutasi
                        WHERE tanggal_mutasi BETWEEN '$request->dari' AND '$request->sampai'          
                        GROUP BY kode_produk
                    ) mutasi_produksi"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'mutasi_produksi.kode_produk');
                    }
                )
                ->get();
        }

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Mutasi Produksi $request->dari-$request->sampai.xls");
        }

        return view('produksi.laporan.rekapmutasiproduksi_cetak', $data);
    }


    public function cetakbarangmasuk(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Detailbarangmasukproduksi::query();
        $query->select('produksi_barang_masuk_detail.*', 'produksi_barang_masuk.kode_asal_barang', 'tanggal', 'nama_barang', 'satuan');
        $query->join('produksi_barang', 'produksi_barang_masuk_detail.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi');
        $query->join('produksi_barang_masuk', 'produksi_barang_masuk_detail.no_bukti', '=', 'produksi_barang_masuk.no_bukti');
        $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_barang_produksi)) {
            $query->where('produksi_barang_masuk_detail.kode_barang_produksi', $request->kode_barang_produksi);
        }
        $query->orderBy('tanggal');
        $query->orderBy('produksi_barang_masuk_detail.kode_barang_produksi');
        $query->orderBy('produksi_barang_masuk.no_bukti');
        $data['barangmasuk'] = $query->get();


        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['asal_barang_produksi'] = config('produksi.asal_barang_produksi');

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Barang Masuk Produksi $request->dari-$request->sampai.xls");
        }

        return view('produksi.laporan.barangmasuk_cetak', $data);
    }
}

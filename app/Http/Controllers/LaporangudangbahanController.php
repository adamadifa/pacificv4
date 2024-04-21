<?php

namespace App\Http\Controllers;

use App\Models\Barangpembelian;
use App\Models\Detailbarangkeluargudangbahan;
use App\Models\Detailbarangmasukgudangbahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LaporangudangbahanController extends Controller
{
    public function index()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['barang'] = Barangpembelian::where('kode_group', 'GDB')->orderBy('nama_barang')->get();
        $data['list_asal_barang'] = config('gudangbahan.list_asal_barang');
        $data['list_jenis_pengeluaran'] = config('gudangbahan.list_jenis_pengeluaran');
        return view('gudangbahan.laporan.index', $data);
    }

    public function cetakbarangmasuk(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Detailbarangmasukgudangbahan::query();
        $query->select('gudang_bahan_barang_masuk_detail.*', 'tanggal', 'nama_barang', 'satuan', 'kode_asal_barang');
        $query->join('gudang_bahan_barang_masuk', 'gudang_bahan_barang_masuk_detail.no_bukti', '=', 'gudang_bahan_barang_masuk.no_bukti');
        $query->join('pembelian_barang', 'gudang_bahan_barang_masuk_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_barang_masuk)) {
            $query->where('gudang_bahan_barang_masuk_detail.kode_barang', $request->kode_barang_masuk);
        }
        if (!empty($request->kode_asal_barang)) {
            $query->where('gudang_bahan_barang_masuk.kode_asal_barang', $request->kode_asal_barang);
        }
        $query->orderBy('tanggal');
        $query->orderBy('gudang_bahan_barang_masuk.no_bukti');
        $query->orderByRaw('cast(substr(gudang_bahan_barang_masuk_detail.kode_barang FROM 4) AS UNSIGNED)');
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['barangmasuk'] = $query->get();
        $data['barang'] = Barangpembelian::where('kode_barang', $request->kode_barang_masuk)->first();
        $data['asal_barang'] = config('gudangbahan.asal_barang_gudang_bahan');
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Barang Masuk Gudang Bahan $request->dari-$request->sampai - $time.xls");
        }
        return view('gudangbahan.laporan.barangmasuk_cetak', $data);
    }

    public function cetakbarangkeluar(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Detailbarangkeluargudangbahan::query();
        $query->select('gudang_bahan_barang_keluar_detail.*', 'tanggal', 'nama_barang', 'satuan', 'kode_jenis_pengeluaran');
        $query->join('gudang_bahan_barang_keluar', 'gudang_bahan_barang_keluar_detail.no_bukti', '=', 'gudang_bahan_barang_keluar.no_bukti');
        $query->join('pembelian_barang', 'gudang_bahan_barang_keluar_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_barang_keluar)) {
            $query->where('gudang_bahan_barang_keluar_detail.kode_barang', $request->kode_barang_keluar);
        }
        if (!empty($request->kode_jenis_pengeluaran)) {
            $query->where('gudang_bahan_barang_keluar.kode_jenis_pengeluaran', $request->kode_jenis_pengeluaran);
        }
        $query->orderBy('tanggal');
        $query->orderBy('gudang_bahan_barang_keluar.no_bukti');
        $query->orderByRaw('cast(substr(gudang_bahan_barang_keluar_detail.kode_barang FROM 4) AS UNSIGNED)');
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['barangkeluar'] = $query->get();
        $data['barang'] = Barangpembelian::where('kode_barang', $request->kode_barang_keluar)->first();
        $data['jenis_pengeluaran'] = config('gudangbahan.jenis_pengeluaran');
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Barang Masuk Gudang Bahan $request->dari-$request->sampai - $time.xls");
        }
        return view('gudangbahan.laporan.barangkeluar_cetak', $data);
    }


    public function cetakpersediaan(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $data['persediaan'] = Barangpembelian::select(
            'pembelian_barang.kode_barang',
            'nama_barang',
            'satuan',
            'kode_jenis_barang',

            'saldo_awal_qty_unit',
            'saldo_awal_qty_berat',
            'saldo_awal_harga',

            'opname_qty_unit',
            'opname_qty_berat',

            'bm_qty_unit_pembelian',
            'bm_qty_unit_lainnya',
            'bm_qty_unit_returpengganti',

            'bm_qty_berat_pembelian',
            'bm_qty_berat_lainnya',
            'bm_qty_berat_returpengganti',

            'bk_qty_unit_produksi',
            'bk_qty_unit_seasoning',
            'bk_qty_unit_pdqc',
            'bk_qty_unit_susut',
            'bk_qty_unit_lainnya',
            'bk_qty_unit_cabang',

            'bk_qty_berat_produksi',
            'bk_qty_berat_seasoning',
            'bk_qty_berat_pdqc',
            'bk_qty_berat_susut',
            'bk_qty_berat_lainnya',
            'bk_qty_berat_cabang',

            'total_harga'
        )

            //Saldo Awal
            ->leftJoin(
                DB::raw("(
                SELECT gudang_bahan_saldoawal_detail.kode_barang,
                SUM( qty_unit ) AS saldo_awal_qty_unit,
                SUM( qty_berat ) AS saldo_awal_qty_berat
                FROM gudang_bahan_saldoawal_detail
                INNER JOIN gudang_bahan_saldoawal ON gudang_bahan_saldoawal_detail.kode_saldo_awal=gudang_bahan_saldoawal.kode_saldo_awal
                WHERE bulan = '$bulan' AND tahun = '$tahun'
                GROUP BY gudang_bahan_saldoawal_detail.kode_barang
            ) saldo_awal"),
                function ($join) {
                    $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal.kode_barang');
                }
            )
            //Opname
            ->leftJoin(
                DB::raw("(
                SELECT gudang_bahan_opname_detail.kode_barang,
                SUM( qty_unit ) AS opname_qty_unit,
                SUM( qty_berat ) AS opname_qty_berat
                FROM gudang_bahan_opname_detail
                INNER JOIN gudang_bahan_opname ON gudang_bahan_opname_detail.kode_opname=gudang_bahan_opname_detail.kode_opname
                WHERE bulan = '$bulan' AND tahun = '$tahun'
                GROUP BY gudang_bahan_opname_detail.kode_barang
            ) opname"),
                function ($join) {
                    $join->on('pembelian_barang.kode_barang', '=', 'opname.kode_barang');
                }
            )

            //Pembelian
            ->leftJoin(
                DB::raw("(
                SELECT kode_barang,SUM((jumlah*harga)+penyesuaian) as total_harga
                FROM pembelian_detail
                INNER JOIN pembelian ON pembelian_detail.no_bukti = pembelian.no_bukti
                WHERE tanggal BETWEEN '$dari' AND '$sampai'
                GROUP BY kode_barang
            ) pembelian"),
                function ($join) {
                    $join->on('pembelian_barang.kode_barang', '=', 'pembelian.kode_barang');
                }
            )


            //Saldo Awal Harga

            ->leftJoin(
                DB::raw("(
                SELECT kode_barang,harga as saldo_awal_harga
                FROM gudang_bahan_saldoawal_harga_detail
                INNER JOIN gudang_bahan_saldoawal_harga ON gudang_bahan_saldoawal_harga_detail.kode_saldo_awal = gudang_bahan_saldoawal_harga.kode_saldo_awal
                WHERE bulan = '$bulan' AND tahun = '$tahun'
            ) saldo_awal_harga"),
                function ($join) {
                    $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal_harga.kode_barang');
                }
            )

            ->leftJoin(
                DB::raw("(
                SELECT
                gudang_bahan_barang_masuk_detail.kode_barang,
                SUM( IF( kode_asal_barang = 'PMB',qty_unit ,0 )) AS bm_qty_unit_pembelian,
                SUM( IF( kode_asal_barang = 'LNY',qty_unit ,0 )) AS bm_qty_unit_lainnya,
                SUM( IF( kode_asal_barang = 'RTP',qty_unit ,0 )) AS bm_qty_unit_returpengganti,

                SUM( IF( kode_asal_barang = 'PMB',qty_berat ,0 )) AS bm_qty_berat_pembelian,
                SUM( IF( kode_asal_barang = 'LNY',qty_berat ,0 )) AS bm_qty_berat_lainnya,
                SUM( IF( kode_asal_barang = 'RTP',qty_berat ,0 )) AS bm_qty_berat_returpengganti
                FROM
                gudang_bahan_barang_masuk_detail
                INNER JOIN gudang_bahan_barang_masuk ON gudang_bahan_barang_masuk_detail.no_bukti = gudang_bahan_barang_masuk.no_bukti
                WHERE tanggal BETWEEN '$dari' AND '$sampai'
                GROUP BY gudang_bahan_barang_masuk_detail.kode_barang
            ) barangmasuk"),
                function ($join) {
                    $join->on('pembelian_barang.kode_barang', '=', 'barangmasuk.kode_barang');
                }
            )

            ->leftJoin(
                DB::raw("(
                SELECT
                gudang_bahan_barang_keluar_detail.kode_barang,
                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'PRD' , qty_unit ,0 )) AS bk_qty_unit_produksi,
                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'SSN' , qty_unit ,0 )) AS bk_qty_unit_seasoning,
                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'PDQ' , qty_unit ,0 )) AS bk_qty_unit_pdqc,
                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'SST' , qty_unit ,0 )) AS bk_qty_unit_susut,
                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'LNY' , qty_unit ,0 )) AS bk_qty_unit_lainnya,
                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'CBG' , qty_unit ,0 )) AS bk_qty_unit_cabang,


                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'PRD' , qty_berat ,0 )) AS bk_qty_berat_produksi,
                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'SSN' , qty_berat ,0 )) AS bk_qty_berat_seasoning,
                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'PDQ' , qty_berat ,0 )) AS bk_qty_berat_pdqc,
                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'SST' , qty_berat ,0 )) AS bk_qty_berat_susut,
                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'LNY' , qty_berat ,0 )) AS bk_qty_berat_lainnya,
                SUM( IF( gudang_bahan_barang_keluar.kode_jenis_pengeluaran = 'CBG' , qty_berat ,0 )) AS bk_qty_berat_cabang
                FROM gudang_bahan_barang_keluar_detail
                INNER JOIN gudang_bahan_barang_keluar ON gudang_bahan_barang_keluar_detail.no_bukti = gudang_bahan_barang_keluar.no_bukti
                WHERE tanggal BETWEEN '$dari' AND '$sampai'
                GROUP BY gudang_bahan_barang_keluar_detail.kode_barang
            ) barangkeluar"),
                function ($join) {
                    $join->on('pembelian_barang.kode_barang', '=', 'barangkeluar.kode_barang');
                }
            )
            ->where('pembelian_barang.kode_group', 'GDB')
            ->where('pembelian_barang.kode_kategori', $request->kode_kategori)
            ->orderBy('kode_jenis_barang')
            ->orderByRaw('cast(substr(pembelian_barang.kode_barang from 4) AS UNSIGNED)')
            ->orderBy('nama_barang')
            ->get();

            dd($data['persediaan']);
    }
}

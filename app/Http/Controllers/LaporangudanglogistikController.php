<?php

namespace App\Http\Controllers;

use App\Models\Barangpembelian;
use App\Models\Cabang;
use App\Models\Detailbarangkeluargudanglogistik;
use App\Models\Detailbarangmasukgudanglogistik;
use App\Models\Kategoribarangpembelian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LaporangudanglogistikController extends Controller
{
    public function index()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['barang'] = Barangpembelian::where('kode_group', 'GDL')->orderBy('nama_barang')->get();
        $data['kategori'] = Kategoribarangpembelian::where('kode_group', 'GDL')->orderBy('kode_kategori')->get();
        $data['list_jenis_pengeluaran'] = config('gudanglogistik.blade.list_jenis_pengeluaran');
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('gudanglogistik.laporan.index', $data);
    }

    public function cetakbarangmasuk(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Detailbarangmasukgudanglogistik::query();
        $query->select(
            'gudang_logistik_barang_masuk_detail.*',
            'gudang_logistik_barang_masuk.tanggal',
            'nama_barang',
            'satuan',
            'nama_kategori',
            'nama_akun',
            'nama_supplier'
        );
        $query->join('gudang_logistik_barang_masuk', 'gudang_logistik_barang_masuk_detail.no_bukti', '=', 'gudang_logistik_barang_masuk.no_bukti');
        $query->join('pembelian_barang', 'gudang_logistik_barang_masuk_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori');
        $query->join('coa', 'gudang_logistik_barang_masuk_detail.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoin('pembelian', 'gudang_logistik_barang_masuk.no_bukti', '=', 'pembelian.no_bukti');
        $query->leftJoin('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->whereBetween('gudang_logistik_barang_masuk.tanggal', [$request->dari, $request->sampai]);
        // $query->where('pembelian_barang.status', '1');
        $query->where('pembelian_barang.kode_group', 'GDL');
        if (!empty($request->kode_kategori)) {
            $query->where('pembelian_barang.kode_kategori', $request->kode_kategori);
        }

        if (!empty($request->kode_barang)) {
            $query->where('gudang_logistik_barang_masuk_detail.kode_barang', $request->kode_barang);
        }
        $query->orderBy('gudang_logistik_barang_masuk.tanggal');
        $query->orderBy('gudang_logistik_barang_masuk_detail.kode_barang');
        $query->orderBy('gudang_logistik_barang_masuk.no_bukti');
        $data['barangmasuk'] = $query->get();
        $data['kategori'] = Kategoribarangpembelian::where('kode_kategori', $request->kode_kategori)->first();
        $data['barang'] = Barangpembelian::where('kode_barang', $request->kode_barang)->first();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Barang Masuk Gudang Logistik $request->dari-$request->sampai - $time.xls");
        }
        return view('gudanglogistik.laporan.barangmasuk_cetak', $data);
    }

    public function cetakbarangkeluar(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Detailbarangkeluargudanglogistik::query();

        $query->select(
            'gudang_logistik_barang_keluar.tanggal',
            'kode_jenis_pengeluaran',
            'gudang_logistik_barang_keluar_detail.*',
            'nama_barang',
            'satuan',
            'nama_cabang'
        );
        $query->join('gudang_logistik_barang_keluar', 'gudang_logistik_barang_keluar_detail.no_bukti', '=', 'gudang_logistik_barang_keluar.no_bukti');
        $query->leftJoin('cabang', 'gudang_logistik_barang_keluar_detail.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('pembelian_barang', 'gudang_logistik_barang_keluar_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->leftJoin('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori');
        $query->whereBetween('gudang_logistik_barang_keluar.tanggal', [$request->dari, $request->sampai]);
        // $query->where('pembelian_barang.status', '1');
        $query->where('pembelian_barang.kode_group', "GDL");
        if (!empty($request->kode_jenis_pengeluaran)) {
            $query->where('gudang_logistik_barang_keluar.kode_jenis_pengeluaran', $request->kode_jenis_pengeluaran);
            // $query->whereNull('gudang_logistik_barang_keluar_detail.kode_cabang');
        }

        if (!empty($request->kode_cabang)) {
            $query->where('gudang_logistik_barang_keluar_detail.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_kategori)) {
            $query->where('pembelian_barang.kode_kategori', $request->kode_kategori);
        }

        if (!empty($request->kode_barang)) {
            $query->where('gudang_logistik_barang_keluar_detail.kode_barang', $request->kode_barang);
        }

        $query->orderBy('gudang_logistik_barang_keluar.tanggal');
        $query->orderBy('gudang_logistik_barang_keluar.no_bukti');
        $data['barangkeluar'] = $query->get();
        $data['kategori'] = Kategoribarangpembelian::where('kode_kategori', $request->kode_kategori)->first();
        $data['barang'] = Barangpembelian::where('kode_barang', $request->kode_barang)->first();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['kode_jenis_pengeluaran'] = $request->kode_jenis_pengeluaran;
        $data['jenis_pengeluaran'] = config('gudanglogistik.blade.jenis_pengeluaran');
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Barang Keluar Gudang Logistik $request->dari-$request->sampai - $time.xls");
        }
        return view('gudanglogistik.laporan.barangkeluar_cetak', $data);
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


        $data['persediaan'] = $this->persediaan($request);
        $data['kategori'] = Kategoribarangpembelian::where('kode_kategori', $request->kode_kategori)->first();
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Persediaan Gudang Logistik $dari-$sampai-$time.xls");
        }
        //dd($request->jenis_laporan);
        $data['group'] = config('pembelian.group');
        $user = User::findorfail(auth()->user()->id);

        if ($user->can('pembelian.harga')) {
            return view('gudanglogistik.laporan.persediaan_harga_cetak', $data);
        } else {
            return view('gudanglogistik.laporan.persediaan_cetak', $data);
        }
        // if ($request->jenis_laporan == '1') {
        // } else if ($request->jenis_laporan == '2') {
        // }
    }



    public function cetakpersediaanopname(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }


        $data['persediaan'] = $this->persediaan($request);
        $data['kategori'] = Kategoribarangpembelian::where('kode_kategori', $request->kode_kategori)->first();
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Opname Gudang Logistik $dari-$sampai-$time.xls");
        }

        if ($user->can('pembelian.harga')) {
            return view('gudanglogistik.laporan.opname_cetak', $data);
        } else {
            return view('gudanglogistik.laporan.opname_tanpaharga_cetak', $data);
        }
    }


    public function persediaan($request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        if (lockreport($dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query  = Barangpembelian::query();
        $query->select(
            'pembelian_barang.kode_barang',
            'nama_barang',
            'nama_kategori',
            'satuan',
            'pembelian_barang.kode_group',
            'saldo_awal_qty',
            'saldo_awal_harga',
            'saldo_awal_totalharga',

            'bm_jumlah',
            'bm_harga',
            'bm_penyesuaian',
            'bm_totalharga',
            'bk_jumlah',
            'opname_qty'

        );
        $query->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori');
        $query->leftJoin(
            DB::raw("(
                SELECT gudang_logistik_saldoawal_detail.kode_barang,
                jumlah as saldo_awal_qty,
                harga as saldo_awal_harga,
                jumlah * harga as saldo_awal_totalharga
                FROM gudang_logistik_saldoawal_detail
                INNER JOIN gudang_logistik_saldoawal ON gudang_logistik_saldoawal_detail.kode_saldo_awal=gudang_logistik_saldoawal.kode_saldo_awal
                WHERE bulan = '$bulan' AND tahun = '$tahun'
            ) saldo_awal"),
            function ($join) {
                $join->on('pembelian_barang.kode_barang', '=', 'saldo_awal.kode_barang');
            }
        );

        $query->leftJoin(
            DB::raw("(
                SELECT gudang_logistik_opname_detail.kode_barang,
                jumlah as opname_qty
                FROM gudang_logistik_opname_detail
                INNER JOIN gudang_logistik_opname ON gudang_logistik_opname_detail.kode_opname=gudang_logistik_opname.kode_opname
                WHERE bulan = '$bulan' AND tahun = '$tahun'
            ) opname"),
            function ($join) {
                $join->on('pembelian_barang.kode_barang', '=', 'opname.kode_barang');
            }
        );
        $query->leftJoin(
            DB::raw("(
                SELECT gudang_logistik_barang_masuk_detail.kode_barang,
                SUM(jumlah) as bm_jumlah,
                SUM(harga) as bm_harga,
                SUM(penyesuaian) as bm_penyesuaian,
                SUM(jumlah * harga) as bm_totalharga
                FROM
                gudang_logistik_barang_masuk_detail
                INNER JOIN gudang_logistik_barang_masuk ON gudang_logistik_barang_masuk_detail.no_bukti = gudang_logistik_barang_masuk.no_bukti
                WHERE gudang_logistik_barang_masuk.tanggal BETWEEN '$dari' AND '$sampai'
                GROUP BY gudang_logistik_barang_masuk_detail.kode_barang
            ) barangmasuk"),
            function ($join) {
                $join->on('pembelian_barang.kode_barang', '=', 'barangmasuk.kode_barang');
            }
        );
        $query->leftJoin(
            DB::raw("(
                SELECT gudang_logistik_barang_keluar_detail.kode_barang,
                SUM( jumlah ) as bk_jumlah
                FROM gudang_logistik_barang_keluar_detail
                INNER JOIN gudang_logistik_barang_keluar ON gudang_logistik_barang_keluar_detail.no_bukti = gudang_logistik_barang_keluar.no_bukti
                WHERE gudang_logistik_barang_keluar.tanggal BETWEEN '$dari' AND '$sampai'
                GROUP BY gudang_logistik_barang_keluar_detail.kode_barang
            ) barangkeluar"),
            function ($join) {
                $join->on('pembelian_barang.kode_barang', '=', 'barangkeluar.kode_barang');
            }
        );

        $query->where('pembelian_barang.kode_group', 'GDL');
        $query->where('pembelian_barang.status', '1');

        if (!empty($request->kode_kategori)) {
            $query->where('pembelian_barang.kode_kategori', $request->kode_kategori);
        }
        $query->orderBy('nama_barang');
        return $query->get();
    }
}

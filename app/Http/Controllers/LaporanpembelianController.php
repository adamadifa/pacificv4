<?php

namespace App\Http\Controllers;

use App\Models\Detailkontrabonpembelian;
use App\Models\Detailpembelian;
use App\Models\Historibayarpembelian;
use App\Models\Jurnalkoreksi;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LaporanpembelianController extends Controller
{
    public function index()
    {
        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        $data['asal_ajuan'] = config('pembelian.list_asal_pengajuan');
        $data['list_jenis_barang'] = config('pembelian.list_jenis_barang');
        return view('pembelian.laporan.index', $data);
    }

    public function cetakpembelian(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Detailpembelian::query();
        $query->select(
            'pembelian_detail.*',
            'tanggal',
            'pembelian.kode_supplier',
            'nama_supplier',
            'nama_barang',
            'kode_asal_pengajuan',
            'keterangan',
            'keterangan_penjualan',
            'nama_akun',
            'ppn',
            'kategori_transaksi',
            'jenis_transaksi',
            'pembelian.created_at',
            'pembelian.updated_at'
        );
        $query->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $query->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->join('coa', 'pembelian_detail.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoin('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_supplier)) {
            $query->where('pembelian.kode_supplier', $request->kode_supplier);
        }

        if ($request->ppn === "0") {
            $query->where('pembelian.ppn', 0);
        } else if ($request->ppn == "1") {
            $query->where('pembelian.ppn', 1);
        }

        if (!empty($request->kode_asal_pengajuan)) {
            $query->where('pembelian.kode_asal_pengajuan', $request->kode_asal_pengajuan);
        }

        // if (Auth::user()->level == "general affair") {
        //     $query->whereIn('detail_pembelian.kode_akun', $akun_ga);
        // }
        $query->orderBy('tanggal');
        $query->orderBy('pembelian_detail.no_bukti');
        $query->orderBy('pembelian_detail.kode_transaksi');

        $pmb = $query->get();
        $data['pembelian'] = $pmb;

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['supplier'] = Supplier::where('kode_supplier', $request->kode_supplier)->first();
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Pembelian $request->dari-$request->sampai.xls");
        }
        return view('pembelian.laporan.pembelian_cetak', $data);
    }

    public function cetakpembayaran(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $bank = Historibayarpembelian::select('pembelian_historibayar.kode_bank', 'nama_bank')
            ->join('bank', 'pembelian_historibayar.kode_bank', '=', 'bank.kode_bank')
            ->whereBetween('tanggal', [$request->dari, $request->sampai])
            ->groupBy('kode_bank', 'nama_bank')
            ->get();

        $selectColumnsbank = [];
        foreach ($bank as $b) {
            $selectColumnsbank[] = DB::raw('SUM(IF(kode_bank="' . $b->kode_bank . '",pembelian_kontrabon_detail.jumlah,0)) as ' . $b->kode_bank);
        }


        $query = Detailkontrabonpembelian::select(
            'pembelian_kontrabon_detail.no_bukti',
            'pembelian_kontrabon_detail.no_kontrabon',
            'nama_supplier',
            'pembelian_historibayar.tanggal as tglbayar',
            ...$selectColumnsbank
        );
        $query->join('pembelian_historibayar', 'pembelian_kontrabon_detail.no_kontrabon', '=', 'pembelian_historibayar.no_kontrabon');
        $query->join('pembelian_kontrabon', 'pembelian_kontrabon_detail.no_kontrabon', '=', 'pembelian_kontrabon.no_kontrabon');
        $query->join('supplier', 'pembelian_kontrabon.kode_supplier', '=', 'supplier.kode_supplier');
        $query->whereBetween('pembelian_historibayar.tanggal', [$request->dari, $request->sampai]);
        $query->orderBy('pembelian_historibayar.tanggal');
        $query->groupBy('pembelian_kontrabon_detail.no_kontrabon', 'pembelian_kontrabon_detail.no_bukti', 'pembelian_historibayar.tanggal', 'nama_supplier');
        if (!empty($request->kode_supplier_pembayaran)) {
            $query->where('pembelian_kontrabon.kode_supplier', $request->kode_supplier_pembayaran);
        }
        $data['pembayaran'] = $query->get();
        $data['supplier'] = Supplier::where('kode_supplier', $request->kode_supplier_pembayaran)->first();
        $data['bank'] = $bank;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Pembelian $request->dari-$request->sampai.xls");
        }
        return view('pembelian.laporan.pembayaran_cetak', $data);
    }

    public function cetakrekapsupplier(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }


        $subqueryJurnalkoreksi = Jurnalkoreksi::select('pembelian_jurnalkoreksi.no_bukti', 'kode_barang', DB::raw('SUM(jumlah*harga) as jml_jk'))
            ->where('debet_kredit', 'K')
            ->where('kode_akun', '5-1101')
            ->whereBetween('tanggal', [$request->dari, $request->sampai])
            ->groupBy('pembelian_jurnalkoreksi.no_bukti', 'kode_barang');

        $query = Detailpembelian::query();
        $query->select(
            'pembelian.kode_supplier',
            'nama_supplier',
            DB::raw('SUM(IF(kode_transaksi="PMB",(jumlah*harga)+penyesuaian,0)) - SUM(IFNULL(jml_jk,0)) as total'),
        );
        $query->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $query->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->leftJoinSub($subqueryJurnalkoreksi, 'subqueryJurnalkoreksi', function ($join) {
            $join->on('pembelian_detail.no_bukti', '=', 'subqueryJurnalkoreksi.no_bukti');
            $join->on('pembelian_detail.kode_barang', '=', 'subqueryJurnalkoreksi.kode_barang');
        });
        $query->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai]);
        $query->groupByRaw('pembelian.kode_supplier, nama_supplier');
        $query->orderBy('pembelian.kode_supplier');
        $data['rekapsupplier'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Pembelian Supplier $request->dari-$request->sampai.xls");
        }
        return view('pembelian.laporan.rekapsupplier_cetak', $data);
    }


    public function cetakrekappembelian(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $subqueryJurnalkoreksi = Jurnalkoreksi::select('pembelian_jurnalkoreksi.no_bukti', 'kode_barang', DB::raw('SUM(jumlah*harga) as jml_jk'))
            ->where('debet_kredit', 'K')
            ->where('kode_akun', '5-1101')
            ->whereBetween('tanggal', [$request->dari, $request->sampai])
            ->groupBy('pembelian_jurnalkoreksi.no_bukti', 'kode_barang');
        $query = Detailpembelian::query();
        $query->select(
            'pembelian_detail.*',
            'pembelian.tanggal',
            'pembelian.kode_supplier',
            'nama_supplier',
            'nama_barang',
            'kode_jenis_barang',
            'pembelian.kode_asal_pengajuan',
            'nama_akun',
            'ppn',
            DB::raw('IFNULL(jml_jk,0) as jml_jk'),
        );
        $query->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $query->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->join('coa', 'pembelian_detail.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoinSub($subqueryJurnalkoreksi, 'subqueryJurnalkoreksi', function ($join) {
            $join->on('pembelian_detail.no_bukti', '=', 'subqueryJurnalkoreksi.no_bukti');
            $join->on('pembelian_detail.kode_barang', '=', 'subqueryJurnalkoreksi.kode_barang');
        });
        $query->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai]);
        if ($request->sortby == "supplier") {
            $query->orderBy('pembelian.kode_supplier');
        } else {
            $query->orderBy('kode_jenis_barang');
            $query->orderBy('pembelian.kode_supplier');
        }
        $query->where('kode_transaksi', 'PMB');
        $data['rekappembelian'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['jenis_barang'] = config('pembelian.jenis_barang');
        if ($request->sortby == "supplier") {
            return view('pembelian.laporan.rekappembelian_cetak', $data);
        } else {
            return view('pembelian.laporan.rekappembelian_jenisbarang_cetak', $data);
        }
    }
}

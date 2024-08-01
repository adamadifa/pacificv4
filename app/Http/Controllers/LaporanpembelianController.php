<?php

namespace App\Http\Controllers;

use App\Models\Detailkontrabonpembelian;
use App\Models\Detailpembelian;
use App\Models\Historibayarpembelian;
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
}

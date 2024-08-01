<?php

namespace App\Http\Controllers;

use App\Models\Detailpembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;

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
        if (!empty($kode_supplier)) {
            $query->where('pembelian.kode_supplier', $kode_supplier);
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

        return view('pembelian.laporan.pembelian_cetak', $data);
    }
}

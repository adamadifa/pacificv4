<?php

namespace App\Http\Controllers;

use App\Models\Detailmutasiproduksi;
use App\Models\Detailsaldoawalmutasiproduksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LaporanproduksiController extends Controller
{
    public function index()
    {
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
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
                ->whereBetween('tanggal_mutasi', [$tanggal_saldoawal, $request->dari])
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

        if ($request->export == '1') {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Mutasi Produksi $request->dari-$request->sampai.xls");
        }
        return view('produksi.laporan.mutasiproduksi_cetak', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Barangmasukmaintenance;
use App\Models\Barangpembelian;
use App\Models\Detailbarangmasukmaintenance;
use App\Models\Detailsaldoawalbahanbakar;
use Illuminate\Http\Request;

class LaporanmaintenanceController extends Controller
{
    public function index()
    {
        $kode_barang = ['GA-002', 'GA-007', 'GA-588'];
        $data['barang'] = Barangpembelian::whereIn('kode_barang', $kode_barang)->get();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('maintenance.laporan.index', $data);
    }

    public function cetakbahanbakar(Request $request)
    {
        $data['saldo_awal'] = Detailsaldoawalbahanbakar::join('maintenance_saldoawal_bahanbakar', 'maintenance_saldoawal_bahanbakar_detail.kode_saldo_awal', 'maintenance_saldoawal_bahanbakar.kode_saldo_awal')
            ->select('jumlah', 'harga')
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->where('kode_barang', $request->kode_barang)
            ->first();
    }
}

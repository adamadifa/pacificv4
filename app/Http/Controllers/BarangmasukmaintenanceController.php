<?php

namespace App\Http\Controllers;

use App\Models\Barangmasukmaintenance;
use App\Models\Detailbarangmasukmaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class BarangmasukmaintenanceController extends Controller
{
    public function index(Request $request)
    {

        $bm = new Barangmasukmaintenance();
        $barangmasuk = $bm->getBarangmasuk(request: $request)->simplePaginate(15);
        $barangmasuk->appends(request()->all());
        $data['barangmasuk'] = $barangmasuk;

        return view('maintenance.barangmasuk.index', $data);
    }

    public function create()
    {
        return view('maintenance.barangmasuk.create');
    }


    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $bm = new Barangmasukmaintenance();
        $data['barangmasuk'] = $bm->getBarangmasuk($no_bukti)->first();

        $data['detail'] = Detailbarangmasukmaintenance::where('no_bukti', $no_bukti)
            ->select('maintenance_barang_masuk_detail.*', 'pembelian_barang.nama_barang', 'pembelian_barang.satuan')
            ->join('pembelian_barang', 'maintenance_barang_masuk_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->get();
        return view('maintenance.barangmasuk.show', $data);
    }
}

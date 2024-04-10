<?php

namespace App\Http\Controllers;

use App\Models\Angkutan;
use App\Models\Detailpermintaankiriman;
use App\Models\Permintaankiriman;
use App\Models\Produk;
use App\Models\Tujuanangkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SuratjalanController extends Controller
{
    public function index()
    {
    }

    public function create($no_permintaan)
    {
        $no_permintaan = Crypt::decrypt($no_permintaan);
        $data['tujuan_angkutan'] = Tujuanangkutan::orderBy('kode_tujuan')->get();
        $data['angkutan'] = Angkutan::orderBy('kode_angkutan')->get();
        $data['pk'] = Permintaankiriman::where('no_permintaan', $no_permintaan)
            ->join('cabang', 'marketing_permintaan_kiriman.kode_cabang', '=', 'cabang.kode_cabang')
            ->leftJoin('salesman', 'marketing_permintaan_kiriman.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['detail'] = Detailpermintaankiriman::select('marketing_permintaan_kiriman_detail.kode_produk', 'nama_produk', 'jumlah')
            ->join('produk', 'marketing_permintaan_kiriman_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('no_permintaan', $no_permintaan)
            ->orderBy('marketing_permintaan_kiriman_detail.kode_produk')
            ->get();
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('gudangjadi.suratjalan.create', $data);
    }
}

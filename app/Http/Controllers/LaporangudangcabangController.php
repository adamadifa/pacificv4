<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Produk;
use Illuminate\Http\Request;

class LaporangudangcabangController extends Controller
{
    public function index()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('gudangcabang.laporan.index', $data);
    }
}

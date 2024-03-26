<?php

namespace App\Http\Controllers;

use App\Models\Permintaanproduksi;
use Illuminate\Http\Request;

class PermintaanproduksiController extends Controller
{
    public function index(Request $request)
    {

        $query = Permintaanproduksi::query();
        if (!empty($request->tahun_search)) {
            $query->where('tahun', $request->tahun_search);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->join('marketing_oman', 'produksi_permintaan.kode_oman', '=', 'marketing_oman.kode_oman');
        $pp = $query->get();

        return view('produksi.permintaanproduksi.index', compact('pp'));
    }
}

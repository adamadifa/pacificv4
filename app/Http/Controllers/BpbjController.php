<?php

namespace App\Http\Controllers;

use App\Models\Mutasiproduksi;
use App\Models\Produk;
use Illuminate\Http\Request;

class BpbjController extends Controller
{
    public function index(Request $request)
    {
        $query = Mutasiproduksi::query();
        $query->orderBy('tanggal_mutasi', 'desc');
        $query->orderBy('created_at', 'desc');
        if (!empty($request->tanggal_mutasi)) {
            $query->where('tanggal_mutasi', $request->tanggal_mutasi);
        }
        $bpbj = $query->simplePaginate(20);
        $bpbj->appends(request()->all());
        return view('produksi.bpbj.index', compact('bpbj'));
    }


    public function create()
    {
        $produk = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('produksi.bpbj.create', compact('produk'));
    }
}

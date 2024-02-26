<?php

namespace App\Http\Controllers;

use App\Models\Detailmutasiproduksi;
use App\Models\Mutasiproduksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class FsthpController extends Controller
{
    public function index(Request $request)
    {

        $query = Mutasiproduksi::query();
        $query->orderBy('tanggal_mutasi', 'desc');
        $query->orderBy('created_at', 'desc');
        if (!empty($request->tanggal_mutasi_search)) {
            $query->where('tanggal_mutasi', $request->tanggal_mutasi_search);
        }
        $query->where('jenis_mutasi', 'FSTHP');
        $fsthp = $query->simplePaginate(20);
        $fsthp->appends(request()->all());
        return view('produksi.fsthp.index', compact('fsthp'));
    }


    public function show($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $fsthp = Mutasiproduksi::where('no_mutasi', $no_mutasi)->first();
        $detail = Detailmutasiproduksi::where('no_mutasi', $no_mutasi)
            ->join('produk', 'produksi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();

        return view('produksi.fsthp.show', compact('fsthp', 'detail'));
    }

    public function create()
    {
        $produk = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('produksi.fsthp.create', compact('produk'));
    }
}

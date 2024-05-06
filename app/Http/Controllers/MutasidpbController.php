<?php

namespace App\Http\Controllers;

use App\Models\Jenismutasigudangcabang;
use App\Models\Produk;
use Illuminate\Http\Request;

class MutasidpbController extends Controller
{
    public function create()
    {

        $data['jenis_mutasi'] = Jenismutasigudangcabang::orderBy('kode_jenis_mutasi')->get();
        $data['produk'] = Produk::orderBy('kode_produk')->where('status_aktif_produk', 1)->get();
        return view('gudangcabang.mutasidpb.create', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Detailretur;
use App\Models\Retur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PelunasanreturController extends Controller
{
    public function create($no_retur)
    {
        $no_retur = Crypt::decrypt($no_retur);
        $detail = Detailretur::select(
            'marketing_retur_detail.*',
            'produk_harga.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pcs_pack',
            'subtotal',
            'worksheetom_retur_pelunasan.jumlah as jumlah_pelunasan'
        )
            ->join('produk_harga', 'marketing_retur_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->leftjoin('worksheetom_retur_pelunasan', function ($join) use ($no_retur) {
                $join->on('marketing_retur_detail.no_retur', '=', 'worksheetom_retur_pelunasan.no_retur')
                    ->on('marketing_retur_detail.kode_harga', '=', 'worksheetom_retur_pelunasan.kode_harga');
            })
            ->where('marketing_retur_detail.no_retur', $no_retur)
            ->get();
        $data['detail'] = $detail;
        $data['no_retur'] = $no_retur;
        return view('worksheetom.pelunasanretur.create', $data);
    }
}

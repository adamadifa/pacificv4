<?php

namespace App\Http\Controllers;

use App\Models\Detailretur;
use App\Models\Pelunasanretur;
use App\Models\Retur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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
            ->orderBy('nama_produk')
            ->get();
        $data['detail'] = $detail;

        $data['pelunasan'] = Pelunasanretur::join('produk_harga', 'worksheetom_retur_pelunasan.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->select('worksheetom_retur_pelunasan.kode_harga', 'nama_produk', 'jumlah', 'no_dpb', 'isi_pcs_dus', 'isi_pcs_pack')
            ->where('no_retur', $no_retur)->orderBy('nama_produk')->get();
        $data['no_retur'] = $no_retur;
        return view('worksheetom.pelunasanretur.create', $data);
    }

    public function store(Request $request, $no_retur)
    {
        $no_retur = Crypt::decrypt($no_retur);
        $request->validate([
            'kode_harga_item' => 'required',
        ]);

        $kode_item_harga = $request->kode_harga_item;
        $jml = $request->jml_item;
        $no_dpb = $request->no_dpb_item;
        DB::beginTransaction();
        try {
            Pelunasanretur::where('no_retur', $no_retur)->delete();
            for ($i = 0; $i < count($request->kode_harga_item); $i++) {
                Pelunasanretur::create([
                    'no_retur' => $no_retur,
                    'kode_harga' => $kode_item_harga[$i],
                    'jumlah' => $jml[$i],
                    'no_dpb' => $no_dpb[$i],
                ]);
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Jenisproduk;
use App\Models\Kategoridiskon;
use App\Models\Kategoriproduk;
use App\Models\Pelanggan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();
        $query->join('produk_kategori', 'produk.kode_kategori_produk', '=', 'produk_kategori.kode_kategori_produk');
        $query->join('produk_jenis', 'produk.kode_jenis_produk', '=', 'produk_jenis.kode_jenis_produk');
        $query->leftjoin('produk_diskon_kategori', 'produk.kode_kategori_diskon', '=', 'produk_diskon_kategori.kode_kategori_diskon');
        if (!empty($request->nama_produk)) {
            $query->where('nama_produk', 'like', '%' . $request->nama_produk . '%');
        }
        $query->orderBy('kode_produk');
        $produk = $query->get();
        return view('datamaster.produk.index', compact('produk'));
    }

    public function create()
    {
        $jenisproduk = Jenisproduk::orderBy('kode_jenis_produk')->get();
        $kategoriproduk = Kategoriproduk::orderBy('kode_kategori_produk')->get();
        return view('datamaster.produk.create', compact('jenisproduk', 'kategoriproduk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required',
            'nama_produk' => 'required',
            'satuan' => 'required',
            'isi_pcs_dus' => 'required|numeric',
            'isi_pack_dus' => 'required|numeric',
            'isi_pcs_pack' => 'required|numeric',
            'kode_kategori_produk' => 'required',
            'kode_jenis_produk' => 'required',
            'status_aktif_produk' => 'required',
            'kode_sku' => 'required',
        ]);

        try {
            Produk::create([
                'kode_produk' => $request->kode_produk,
                'nama_produk' => $request->nama_produk,
                'satuan' => $request->satuan,
                'isi_pcs_dus' => $request->isi_pcs_dus,
                'isi_pack_dus' => $request->isi_pack_dus,
                'isi_pcs_pack' => $request->isi_pcs_pack,
                'kode_kategori_produk' => $request->kode_kategori_produk,
                'kode_jenis_produk' => $request->kode_jenis_produk,
                'status_aktif_produk' => $request->status_aktif_produk,
                'kode_sku' => $request->kode_sku,
                'kode_kategori_diskon' => 'D007',
                'urutan' => $request->urutan
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit(Request $request, $kode_produk)
    {
        $kode_produk = Crypt::decrypt($kode_produk);
        $produk = Produk::where('kode_produk', $kode_produk)->first();
        $jenisproduk = Jenisproduk::orderBy('kode_jenis_produk')->get();
        $kategoriproduk = Kategoriproduk::orderBy('kode_kategori_produk')->get();
        $kategoridiskon = Kategoridiskon::orderBy('kode_kategori_diskon')->get();
        return view('datamaster.produk.edit', compact('produk', 'jenisproduk', 'kategoriproduk', 'kategoridiskon'));
    }

    public function update(Request $request, $kode_produk)
    {
        $kode_produk = Crypt::decrypt($kode_produk);
        $request->validate([
            'nama_produk' => 'required',
            'satuan' => 'required',
            'isi_pcs_dus' => 'required|numeric',
            'isi_pack_dus' => 'required|numeric',
            'isi_pcs_pack' => 'required|numeric',
            'kode_kategori_produk' => 'required',
            'kode_jenis_produk' => 'required',
            'status_aktif_produk' => 'required',
            'kode_sku' => 'required',
            'kode_kategori_diskon' => 'required'
        ]);

        try {
            Produk::where('kode_produk', $kode_produk)->update([
                'nama_produk' => $request->nama_produk,
                'satuan' => $request->satuan,
                'isi_pcs_dus' => $request->isi_pcs_dus,
                'isi_pack_dus' => $request->isi_pack_dus,
                'isi_pcs_pack' => $request->isi_pcs_pack,
                'kode_kategori_produk' => $request->kode_kategori_produk,
                'kode_jenis_produk' => $request->kode_jenis_produk,
                'status_aktif_produk' => $request->status_aktif_produk,
                'kode_sku' => $request->kode_sku,
                'kode_kategori_diskon' => $request->kode_kategori_diskon,
                'urutan' => $request->urutan
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_produk)
    {
        $kode_produk = Crypt::decrypt($kode_produk);
        try {
            Produk::where('kode_produk', $kode_produk)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

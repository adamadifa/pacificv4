<?php

namespace App\Http\Controllers;

use App\Models\Kategoriproduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class KategoriprodukController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategoriproduk::query();
        if (!empty($request->nama_kategori_produk)) {
            $query->where('nama_kategori_produk', 'like', '%' . $request->nama_kategori_produk . '%');
        }
        $kategoriproduk = $query->get();
        return view('datamaster.kategoriproduk.index', compact('kategoriproduk'));
    }

    public function create()
    {
        return view('datamaster.kategoriproduk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kategori_produk' => 'required',
            'nama_kategori_produk' => 'required'
        ]);

        try {
            Kategoriproduk::create([
                'kode_kategori_produk' => $request->kode_kategori_produk,
                'nama_kategori_produk' => $request->nama_kategori_produk
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_kategori_produk)
    {
        $kode_kategori_produk = Crypt::decrypt($kode_kategori_produk);
        $kategoriproduk = Kategoriproduk::where('kode_kategori_produk', $kode_kategori_produk)->first();
        return view('datamaster.kategoriproduk.edit', compact('kategoriproduk'));
    }

    public function update(Request $request, $kode_kategori_produk)
    {
        $kode_kategori_produk = Crypt::decrypt($kode_kategori_produk);
        $request->validate([
            'nama_kategori_produk' => 'required'
        ]);

        try {
            Kategoriproduk::where('kode_kategori_produk', $kode_kategori_produk)->update([
                'nama_kategori_produk' => $request->nama_kategori_produk
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_kategori_produk)
    {
        $kode_kategori_produk = Crypt::decrypt($kode_kategori_produk);
        try {
            Kategoriproduk::where('kode_kategori_produk', $kode_kategori_produk)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

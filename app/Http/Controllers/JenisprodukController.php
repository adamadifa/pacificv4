<?php

namespace App\Http\Controllers;

use App\Models\Jenisproduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class JenisprodukController extends Controller
{
    public function index(Request $request)
    {
        $query = Jenisproduk::query();
        if (!empty($request->nama_jenis_produk)) {
            $query->where('nama_jenis_produk', 'like', '%' . $request->nama_jenis_produk . '%');
        }
        $jenisproduk = $query->get();
        return view('datamaster.jenisproduk.index', compact('jenisproduk'));
    }

    public function create()
    {
        return view('datamaster.jenisproduk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jenis_produk' => 'required',
            'nama_jenis_produk' => 'required'
        ]);

        try {
            Jenisproduk::create([
                'kode_jenis_produk' => $request->kode_jenis_produk,
                'nama_jenis_produk' => $request->nama_jenis_produk
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_jenis_produk)
    {
        $kode_jenis_produk = Crypt::decrypt($kode_jenis_produk);
        $jenisproduk = Jenisproduk::where('kode_jenis_produk', $kode_jenis_produk)->first();
        return view('datamaster.jenisproduk.edit', compact('jenisproduk'));
    }

    public function update(Request $request, $kode_jenis_produk)
    {
        $kode_jenis_produk = Crypt::decrypt($kode_jenis_produk);
        $request->validate([
            'nama_jenis_produk' => 'required'
        ]);

        try {
            Jenisproduk::where('kode_jenis_produk', $kode_jenis_produk)->update([
                'nama_jenis_produk' => $request->nama_jenis_produk
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_jenis_produk)
    {
        $kode_jenis_produk = Crypt::decrypt($kode_jenis_produk);
        try {
            Jenisproduk::where('kode_jenis_produk', $kode_jenis_produk)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

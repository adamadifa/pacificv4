<?php

namespace App\Http\Controllers;

use App\Models\Barangproduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class BarangproduksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Barangproduksi::query();
        if (!empty($request->nama_barang)) {
            $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        }
        $query->orderBy('kode_barang_produksi');
        $barangproduksi = $query->paginate(10);
        $barangproduksi->appends(request()->all());

        $asal_barang_produksi = config('produksi.asal_barang_produksi');
        $kategori_barang_produksi = config('produksi.kategori_barang_produksi');

        return view('datamaster.barangproduksi.index', compact(
            'barangproduksi',
            'asal_barang_produksi',
            'kategori_barang_produksi',
        ));
    }

    public function create()
    {

        $lastbarang = Barangproduksi::orderBy('kode_barang_produksi', 'desc')->first();
        $last_kode_barang = $lastbarang->kode_barang_produksi;
        $kode_barang_produksi =  buatkode($last_kode_barang, "BP-", 3);
        $list_kategori_barang_produksi = config('produksi.list_kategori_barang_produksi');
        return view('datamaster.barangproduksi.create', compact('list_kategori_barang_produksi', 'kode_barang_produksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang_produksi' => 'required',
            'nama_barang' => 'required',
            'satuan' => 'required',
            'kode_asal_barang' => 'required',
            'kode_kategori' => 'required',
            'status_aktif_barang' => 'required'
        ]);

        try {

            Barangproduksi::create([
                'kode_barang_produksi' => $request->kode_barang_produksi,
                'nama_barang' => $request->nama_barang,
                'satuan' => $request->satuan,
                'kode_asal_barang' => $request->kode_asal_barang,
                'kode_kategori' => $request->kode_kategori,
                'status_aktif_barang' => $request->status_aktif_barang
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_barang_produksi)
    {
        $kode_barang_produksi = Crypt::decrypt($kode_barang_produksi);
        $barangproduksi = Barangproduksi::where('kode_barang_produksi', $kode_barang_produksi)->first();
        $list_kategori_barang_produksi = config('produksi.list_kategori_barang_produksi');
        return view('datamaster.barangproduksi.edit', compact('barangproduksi', 'list_kategori_barang_produksi'));
    }


    public function update(Request $request, $kode_barang_produksi)
    {
        $kode_barang_produksi = Crypt::decrypt($kode_barang_produksi);
        $request->validate([
            'nama_barang' => 'required',
            'satuan' => 'required',
            'kode_asal_barang' => 'required',
            'kode_kategori' => 'required',
            'status_aktif_barang' => 'required'
        ]);

        try {

            Barangproduksi::where('kode_barang_produksi', $kode_barang_produksi)->update([
                'nama_barang' => $request->nama_barang,
                'satuan' => $request->satuan,
                'kode_asal_barang' => $request->kode_asal_barang,
                'kode_kategori' => $request->kode_kategori,
                'status_aktif_barang' => $request->status_aktif_barang
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_barang_produksi)
    {
        $kode_barang_produksi = Crypt::decrypt($kode_barang_produksi);
        try {
            Barangproduksi::where('kode_barang_produksi', $kode_barang_produksi)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {

            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Barangpembelian;
use App\Models\Kategoribarangpembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class BarangpembelianController extends Controller
{

    public function index(Request $request)
    {
        $query = Barangpembelian::query();
        if (!empty($request->nama_barang)) {
            $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        }
        $query->select('pembelian_barang.*', 'nama_kategori');
        $query->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori');
        $query->orderBy('created_at', 'desc');
        $barang = $query->paginate(10);
        $barang->appends(request()->all());
        $data['barang'] = $barang;
        $data['jenis_barang'] = config('pembelian.jenis_barang');
        $data['group'] = config('pembelian.group');

        return view('datamaster.barangpembelian.index', $data);
    }

    public function create()
    {
        $data['list_jenis_barang'] = config('pembelian.list_jenis_barang');
        $data['kategori'] = Kategoribarangpembelian::orderBy('kode_kategori')->get();
        $data['list_group'] = config('pembelian.list_group');
        return view('datamaster.barangpembelian.create', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'satuan' => 'required',
            'kode_jenis_barang' => 'required',
            'kode_kategori' => 'required',
            'kode_group' => 'required',
            'status' => 'required'
        ]);

        try {
            Barangpembelian::create([
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'satuan' => $request->satuan,
                'kode_jenis_barang' => $request->kode_jenis_barang,
                'kode_kategori' => $request->kode_kategori,
                'kode_group' => $request->kode_group,
                'status' => $request->status,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_barang)
    {
        $kode_barang = Crypt::decrypt($kode_barang);
        $data['barangpembelian'] = Barangpembelian::where('kode_barang', $kode_barang)->first();
        $data['list_jenis_barang'] = config('pembelian.list_jenis_barang');
        $data['kategori'] = Kategoribarangpembelian::orderBy('kode_kategori')->get();
        $data['list_group'] = config('pembelian.list_group');
        return view('datamaster.barangpembelian.edit', $data);
    }


    public function update($kode_barang, Request $request)
    {
        $kode_barang = Crypt::decrypt($kode_barang);
        $request->validate([
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'satuan' => 'required',
            'kode_jenis_barang' => 'required',
            'kode_kategori' => 'required',
            'kode_group' => 'required',
            'status' => 'required'
        ]);

        try {
            Barangpembelian::where('kode_barang', $kode_barang)->update([
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'satuan' => $request->satuan,
                'kode_jenis_barang' => $request->kode_jenis_barang,
                'kode_kategori' => $request->kode_kategori,
                'kode_group' => $request->kode_group,
                'status' => $request->status,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    public function destroy($kode_barang)
    {
        $kode_barang = Crypt::decrypt($kode_barang);
        try {
            Barangpembelian::where('kode_barang', $kode_barang)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {

            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

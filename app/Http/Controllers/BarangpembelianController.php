<?php

namespace App\Http\Controllers;

use App\Models\Barangpembelian;
use App\Models\Kategoribarangpembelian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class BarangpembelianController extends Controller
{

    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $query = Barangpembelian::query();
        if (!empty($request->nama_barang)) {
            $query->where('nama_barang', 'like', '%' . $request->nama_barang . '%');
        }
        $query->select('pembelian_barang.*', 'nama_kategori');
        $query->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori');
        if ($user->hasRole('admin gudang bahan')) {
            $query->where('pembelian_barang.kode_group', 'GDB');
        } else if ($user->hasRole('admin ga')) {
            $query->where('pembelian_barang.kode_group', 'GAF');
        } else if ($user->hasRole('admin gudang logistik')) {
            $query->where('pembelian_barang.kode_group', 'GDL');
        } else if ($user->hasRole('admin pembelian')) {
            $query->where('pembelian_barang.kode_group', '!=', 'GDL');
        }
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


    //GET DATA FROM AJAX
    public function getbarangbykategori(Request $request)
    {

        $query = Barangpembelian::query();
        $query->where('status', 1);
        $query->where('kode_kategori', $request->kode_kategori);
        $query->orderBy('nama_barang');
        $barang = $query->get();




        echo "<option value=''>Semua Barang</option>";
        foreach ($barang as $d) {
            echo "<option  value='$d->kode_barang'>" . textUpperCase($d->nama_barang) . "</option>";
        }
    }


    public function getbarangjson(Request $request, $kode_group)
    {


        if ($request->ajax()) {
            $query = Barangpembelian::query();
            $query->select(
                'pembelian_barang.*',
                'nama_kategori'
            );
            $query->where('pembelian_barang.kode_group', $kode_group);
            $query->join('pembelian_barang_kategori', 'pembelian_barang.kode_kategori', '=', 'pembelian_barang_kategori.kode_kategori');
            $barang = $query;
            return DataTables::of($barang)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="#" kode_barang="' . $row->kode_barang . '" nama_barang="' . $row->nama_barang . '" class="pilihBarang"><i class="ti ti-external-link"></i></a>';
                    return $btn;
                })

                ->addColumn('namabarang', function ($row) {
                    $namabarang = textUpperCase($row->nama_barang);
                    return $namabarang;
                })
                ->addColumn('jenisbarang', function ($row) {
                    $jenis_barang = config('pembelian.jenis_barang');
                    $jenisbarang = $jenis_barang[$row->kode_jenis_barang];
                    return $jenisbarang;
                })
                ->rawColumns(['action', 'jenisbarang', 'namabarang'])
                ->make(true);
        }
    }
}

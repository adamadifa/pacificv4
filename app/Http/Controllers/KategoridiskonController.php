<?php

namespace App\Http\Controllers;

use App\Models\Kategoridiskon;
use App\Models\Diskon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class KategoridiskonController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategoridiskon::query();
        if (!empty($request->nama_kategori)) {
            $query->where('nama_kategori', 'like', '%' . $request->nama_kategori . '%');
        }
        $kategoridiskon = $query->get();
        return view('datamaster.kategoridiskon.index', compact('kategoridiskon'));
    }

    public function create()
    {
        return view('datamaster.kategoridiskon.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kategori_diskon' => 'required|max:4',
            'nama_kategori' => 'required'
        ]);

        try {
            Kategoridiskon::create([
                'kode_kategori_diskon' => strtoupper($request->kode_kategori_diskon),
                'nama_kategori' => $request->nama_kategori
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_kategori_diskon)
    {
        $kode_kategori_diskon = Crypt::decrypt($kode_kategori_diskon);
        $kategoridiskon = Kategoridiskon::where('kode_kategori_diskon', $kode_kategori_diskon)->first();
        return view('datamaster.kategoridiskon.edit', compact('kategoridiskon'));
    }

    public function update(Request $request, $kode_kategori_diskon)
    {
        $kode_kategori_diskon = Crypt::decrypt($kode_kategori_diskon);
        $request->validate([
            'nama_kategori' => 'required'
        ]);

        try {
            Kategoridiskon::where('kode_kategori_diskon', $kode_kategori_diskon)->update([
                'nama_kategori' => $request->nama_kategori
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_kategori_diskon)
    {
        $kode_kategori_diskon = Crypt::decrypt($kode_kategori_diskon);
        try {
            Kategoridiskon::where('kode_kategori_diskon', $kode_kategori_diskon)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    // Detail Configuration (produk_diskon)
    public function show($kode_kategori_diskon)
    {
        $kode_kategori_diskon = Crypt::decrypt($kode_kategori_diskon);
        $kategoridiskon = Kategoridiskon::where('kode_kategori_diskon', $kode_kategori_diskon)->first();
        $diskons = Diskon::where('kode_kategori_diskon', $kode_kategori_diskon)
            ->orderBy('min_qty')
            ->get();

        return view('datamaster.kategoridiskon.show', compact('kategoridiskon', 'diskons'));
    }

    public function createdetail($kode_kategori_diskon)
    {
        $kode_kategori_diskon = Crypt::decrypt($kode_kategori_diskon);
        return view('datamaster.kategoridiskon.createdetail', compact('kode_kategori_diskon'));
    }

    public function storedetail(Request $request, $kode_kategori_diskon)
    {
        $kode_kategori_diskon = Crypt::decrypt($kode_kategori_diskon);
        $request->validate([
            'min_qty' => 'required|numeric',
            'max_qty' => 'required|numeric',
            'diskon' => 'required|numeric',
            'diskon_tunai' => 'required|numeric',
        ]);

        try {
            Diskon::create([
                'kode_kategori_diskon' => $kode_kategori_diskon,
                'min_qty' => $request->min_qty,
                'max_qty' => $request->max_qty,
                'diskon' => $request->diskon,
                'diskon_tunai' => $request->diskon_tunai,
            ]);
            return Redirect::back()->with(messageSuccess('Detail Diskon Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function editdetail($id)
    {
        $id = Crypt::decrypt($id);
        $diskon = Diskon::find($id);
        return view('datamaster.kategoridiskon.editdetail', compact('diskon'));
    }

    public function updatedetail(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'min_qty' => 'required|numeric',
            'max_qty' => 'required|numeric',
            'diskon' => 'required|numeric',
            'diskon_tunai' => 'required|numeric',
        ]);

        try {
            Diskon::where('id', $id)->update([
                'min_qty' => $request->min_qty,
                'max_qty' => $request->max_qty,
                'diskon' => $request->diskon,
                'diskon_tunai' => $request->diskon_tunai,
            ]);
            return Redirect::back()->with(messageSuccess('Detail Diskon Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroydetail($id)
    {
        $id = Crypt::decrypt($id);
        try {
            Diskon::where('id', $id)->delete();
            return Redirect::back()->with(messageSuccess('Detail Diskon Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

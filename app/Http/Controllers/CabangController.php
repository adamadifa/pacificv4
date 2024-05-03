<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Regional;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class CabangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Cabang::query();
        $query->join('regional', 'cabang.kode_regional', '=', 'regional.kode_regional');
        if (!empty($request->nama_cabang)) {
            $query->where('nama_cabang', 'like', '%' . $request->nama_cabang . '%');
        }
        $query->orderBy('kode_cabang');
        $cabang = $query->paginate(10);
        $cabang->appends(request()->all());
        return view('datamaster.cabang.index', compact('cabang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $regional = Regional::orderBy('kode_regional')->get();
        return view('datamaster.cabang.create', compact('regional'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_cabang' => 'required|max:3|unique:cabang,kode_cabang',
            'nama_cabang' => 'required',
            'alamat_cabang' => 'required',
            'telepon_cabang' => 'required|numeric',
            'lokasi_cabang' => 'required',
            'radius_cabang' => 'required',
            'kode_regional' => 'required',
            'kode_pt' => 'required',
            'nama_pt' => 'required',
            'urutan' => 'required',
        ]);


        try {
            Cabang::create([
                'kode_cabang' => $request->kode_cabang,
                'nama_cabang' => $request->nama_cabang,
                'alamat_cabang' => $request->alamat_cabang,
                'telepon_cabang' => $request->telepon_cabang,
                'lokasi_cabang' => $request->lokasi_cabang,
                'radius_cabang' => $request->radius_cabang,
                'kode_pt' => $request->kode_pt,
                'nama_pt' => $request->nama_pt,
                'kode_regional' => $request->kode_regional,
                'urutan' => $request->urutan,
                'color_marker' => $request->color_marker
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kode_cabang)
    {
        $kode_cabang = Crypt::decrypt($kode_cabang);
        $cabang = Cabang::where('kode_cabang', $kode_cabang)->first();
        $regional = Regional::orderBy('kode_regional')->get();
        return view('datamaster.cabang.edit', compact('regional', 'cabang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_cabang)
    {
        $kode_cabang = Crypt::decrypt($kode_cabang);
        $request->validate([
            'nama_cabang' => 'required',
            'alamat_cabang' => 'required',
            'telepon_cabang' => 'required|numeric',
            'lokasi_cabang' => 'required',
            'radius_cabang' => 'required',
            'kode_regional' => 'required',
            'kode_pt' => 'required',
            'nama_pt' => 'required',
            'urutan' => 'required',
        ]);


        try {
            Cabang::where('kode_cabang', $kode_cabang)->update([
                'nama_cabang' => $request->nama_cabang,
                'alamat_cabang' => $request->alamat_cabang,
                'telepon_cabang' => $request->telepon_cabang,
                'lokasi_cabang' => $request->lokasi_cabang,
                'radius_cabang' => $request->radius_cabang,
                'kode_regional' => $request->kode_regional,
                'kode_pt' => $request->kode_pt,
                'nama_pt' => $request->nama_pt,
                'urutan' => $request->urutan,
                'color_marker' => $request->color_marker
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_cabang)
    {
        $kode_cabang = Crypt::decrypt($kode_cabang);
        try {
            Cabang::where('kode_cabang', $kode_cabang)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

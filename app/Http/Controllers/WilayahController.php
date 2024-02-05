<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class WilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Wilayah::query();
        $wilayah = $query->get();
        return view('wilayah.index', compact('wilayah'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('wilayah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_wilayah' => 'required|max:3|unique:wilayah,kode_wilayah',
            'nama_wilayah' => 'required|max:30'
        ]);

        try {
            Wilayah::create([
                'kode_wilayah' => $request->kode_wilayah,
                'nama_wilayah' => $request->nama_wilayah
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
    public function edit($kode_wilayah, Request $request)
    {
        $kode_wilayah = Crypt::decrypt($kode_wilayah);
        $wilayah = Wilayah::where('kode_wilayah', $kode_wilayah)->first();
        return view('wilayah.edit', compact('wilayah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_wilayah)
    {
        $kode_wilayah = Crypt::decrypt($kode_wilayah);
        $request->validate([
            'kode_wilayah' => 'required|max:3',
            'nama_wilayah' => 'required|max:30'
        ]);

        try {
            Wilayah::where('kode_wilayah', $kode_wilayah)
                ->update([
                    'nama_wilayah' => $request->nama_wilayah
                ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_wilayah)
    {
        $kode_wilayah = Crypt::decrypt($kode_wilayah);
        try {
            Wilayah::where('kode_wilayah', $kode_wilayah)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

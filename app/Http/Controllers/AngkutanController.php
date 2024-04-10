<?php

namespace App\Http\Controllers;

use App\Models\Angkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class AngkutanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = Angkutan::query();
        $query->orderBy('kode_angkutan', 'desc');
        if (!empty($request->nama_angkutan_search)) {
            $query->where('nama_angkutan', 'like', '%' . $request->nama_angkutan_search . '%');
        }
        $angkutan = $query->paginate(10);
        $angkutan->appends(request()->all());
        $data['angkutan'] = $angkutan;
        return view('datamaster.angkutan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('datamaster.angkutan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_angkutan' => 'required'
        ]);
        try {

            $lastangkutan = Angkutan::orderBy('kode_angkutan', 'desc')->first();
            $lastkode_angkutan = $lastangkutan != null ? $lastangkutan->kode_angkutan : '';
            $kode_angkutan = buatkode($lastkode_angkutan, "A", 3);



            Angkutan::create([
                'kode_angkutan' => $kode_angkutan,
                'nama_angkutan' => textUpperCase($request->nama_angkutan),
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($kode_angkutan, Request $request)
    {
        $kode_angkutan = Crypt::decrypt($kode_angkutan);
        $data['angkutan'] = Angkutan::where('kode_angkutan', $kode_angkutan)->first();
        return view('datamaster.angkutan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_angkutan)
    {
        $kode_angkutan = Crypt::decrypt($kode_angkutan);
        $request->validate([
            'nama_angkutan' => 'required'
        ]);
        try {
            Angkutan::where('kode_angkutan', $kode_angkutan)->update([
                'nama_angkutan' => textUpperCase($request->nama_angkutan),
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_angkutan)
    {
        $kode_angkutan = Crypt::decrypt($kode_angkutan);
        try {
            Angkutan::where('kode_angkutan', $kode_angkutan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

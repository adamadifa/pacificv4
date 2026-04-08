<?php

namespace App\Http\Controllers;

use App\Models\MesinFingerprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class MesinfingerprintController extends Controller
{
    public function index()
    {
        $mesin = MesinFingerprint::all();
        return view('hrd.mesinfingerprint.index', compact('mesin'));
    }

    public function create()
    {
        return view('hrd.mesinfingerprint.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mesin' => 'required',
            'sn' => 'required|unique:hrd_mesin_fingerprint,sn',
            'status' => 'required'
        ]);

        try {
            MesinFingerprint::create([
                'nama_mesin' => $request->nama_mesin,
                'sn' => $request->sn,
                'status' => $request->status,
                'titik_koordinat' => $request->titik_koordinat
            ]);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $mesin = MesinFingerprint::find($id);
        return view('hrd.mesinfingerprint.edit', compact('mesin'));
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'nama_mesin' => 'required',
            'sn' => 'required|unique:hrd_mesin_fingerprint,sn,' . $id,
            'status' => 'required'
        ]);

        try {
            MesinFingerprint::where('id', $id)->update([
                'nama_mesin' => $request->nama_mesin,
                'sn' => $request->sn,
                'status' => $request->status,
                'titik_koordinat' => $request->titik_koordinat
            ]);
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        try {
            MesinFingerprint::where('id', $id)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
}

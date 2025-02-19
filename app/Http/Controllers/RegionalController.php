<?php

namespace App\Http\Controllers;

use App\Models\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class RegionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Regional::query();
        $regional = $query->get();
        return view('datamaster.regional.index', compact('regional'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('datamaster.regional.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_regional' => 'required|max:3|unique:regional,kode_regional',
            'nama_regional' => 'required|max:30'
        ]);

        try {
            Regional::create([
                'kode_regional' => $request->kode_regional,
                'nama_regional' => $request->nama_regional
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
    public function edit($kode_regional)
    {
        $kode_regional = Crypt::decrypt($kode_regional);
        $regional = Regional::where('kode_regional', $kode_regional)->first();
        return view('datamaster.regional.edit', compact('regional'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_regional)
    {
        //Log::info('Spatie Log Activity dipanggil untuk Regional');
        $kode_regional = Crypt::decrypt($kode_regional);
        $request->validate([
            'kode_regional' => 'required|max:3',
            'nama_regional' => 'required|max:30'
        ]);

        try {
            $regional = Regional::find($kode_regional);
            $regional->update([
                'nama_regional' => $request->nama_regional
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_regional)
    {
        $kode_regional = Crypt::decrypt($kode_regional);
        try {
            Regional::where('kode_regional', $kode_regional)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

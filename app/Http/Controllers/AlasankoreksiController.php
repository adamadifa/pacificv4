<?php

namespace App\Http\Controllers;

use App\Models\Alasankoreksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AlasankoreksiController extends Controller
{
    public function index()
    {
        $alasankoreksi = Alasankoreksi::orderBy('id')->get();
        return view('datamaster.alasankoreksi.index', compact('alasankoreksi'));
    }

    public function create()
    {
        return view('datamaster.alasankoreksi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'alasan' => 'required',
            'status_denda' => 'required',
        ]);

        try {
            Alasankoreksi::create([
                'alasan' => $request->alasan,
                'status_denda' => $request->status_denda,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($id)
    {
        $alasankoreksi = Alasankoreksi::find($id);
        return view('datamaster.alasankoreksi.edit', compact('alasankoreksi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required',
            'status_denda' => 'required',
        ]);

        try {
            Alasankoreksi::where('id', $id)->update([
                'alasan' => $request->alasan,
                'status_denda' => $request->status_denda,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($id)
    {
        try {
            Alasankoreksi::where('id', $id)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

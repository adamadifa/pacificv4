<?php

namespace App\Http\Controllers;

use App\Models\Tujuanangkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class TujuanangkutanController extends Controller
{
    public function index(Request $request)
    {
        $query = Tujuanangkutan::query();
        $query->orderBy('kode_tujuan');
        if (!empty($request->tujuan_search)) {
            $query->where('tujuan', 'like', '%' . $request->tujuan_search . '%');
        }
        $query->where('status', 1);
        $tujuanangkutan = $query->paginate(10);
        $tujuanangkutan->appends(request()->all());
        $data['tujuanangkutan'] = $tujuanangkutan;
        return view('datamaster.tujuanangkutan.index', $data);
    }

    public function create()
    {
        return view('datamaster.tujuanangkutan.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'kode_tujuan' => 'required',
            'tujuan' => 'required'
        ]);
        try {
            Tujuanangkutan::create([
                'kode_tujuan' => $request->kode_tujuan,
                'tujuan' => textUpperCase($request->tujuan),
                'tarif' => toNumber($request->tarif)
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_tujuan)
    {
        $kode_tujuan = Crypt::decrypt($kode_tujuan);
        $data['tujuanangkutan'] = Tujuanangkutan::where('kode_tujuan', $kode_tujuan)->first();
        return view('datamaster.tujuanangkutan.edit', $data);
    }

    public function update($kode_tujuan, Request $request)
    {
        $kode_tujuan = Crypt::decrypt($kode_tujuan);

        $request->validate([
            'tujuan' => 'required'
        ]);
        try {
            Tujuanangkutan::where('kode_tujuan', $kode_tujuan)->update([
                'tujuan' => textUpperCase($request->tujuan),
                'tarif' => toNumber($request->tarif)
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_tujuan)
    {
        $kode_tujuan = Crypt::decrypt($kode_tujuan);
        try {
            Tujuanangkutan::where('kode_tujuan', $kode_tujuan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

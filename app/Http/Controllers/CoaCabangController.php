<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Coa;
use App\Models\Coacabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class CoaCabangController extends Controller
{
    public function index(Request $request)
    {
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['kode_cabang'] = $request->kode_cabang;
        return view('accounting.coacabang.index', $data);
    }

    public function getData(Request $request)
    {
        $kode_cabang = $request->kode_cabang;

        if (!$kode_cabang) {
            return view('accounting.coacabang.load_data', ['coaCabang' => collect([])]);
        }

        $coaCabang = Coacabang::join('cabang', 'coa_cabang.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('coa', 'coa_cabang.kode_akun', '=', 'coa.kode_akun')
            ->select('coa_cabang.*', 'cabang.nama_cabang', 'coa.nama_akun')
            ->where('coa_cabang.kode_cabang', $kode_cabang)
            ->orderBy('coa_cabang.kode_akun')
            ->get();

        return view('accounting.coacabang.load_data', compact('coaCabang'));
    }

    public function create(Request $request)
    {
        $kode_cabang = $request->kode_cabang;
        $cabang_selected = null;
        if ($kode_cabang) {
            $cabang_selected = Cabang::where('kode_cabang', $kode_cabang)->first();
        }
        
        $data['cabang_selected'] = $cabang_selected;
        $data['coa'] = Coa::orderBy('kode_akun')->whereNotIn('kode_akun', ['1', '2', '0-0000'])->get();
        return view('accounting.coacabang.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_cabang' => 'required',
            'kode_akun' => 'required',
        ]);

        try {
            // Cek apakah data sudah ada
            $cek = Coacabang::where('kode_cabang', $request->kode_cabang)
                ->where('kode_akun', $request->kode_akun)
                ->count();

            if ($cek > 0) {
                return Redirect::back()->with(messageError('Data COA Cabang sudah ada'));
            }

            Coacabang::create([
                'kode_cabang' => $request->kode_cabang,
                'kode_akun' => $request->kode_akun,
            ]);

            return redirect()->route('coacabang.index', ['kode_cabang' => $request->kode_cabang])->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return redirect()->route('coacabang.index', ['kode_cabang' => $request->kode_cabang])->with(messageError($e->getMessage()));
        }
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $coaCabang = Coacabang::findOrFail($id);
        
        $data['coaCabang'] = $coaCabang;
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['coa'] = Coa::orderBy('kode_akun')->whereNotIn('kode_akun', ['1', '2', '0-0000'])->get();
        
        return view('accounting.coacabang.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $coaCabang = Coacabang::findOrFail($id);

        $request->validate([
            'kode_cabang' => 'required',
            'kode_akun' => 'required',
        ]);

        try {
            // Cek apakah data sudah ada (selain data yang sedang diupdate)
            $cek = Coacabang::where('kode_cabang', $request->kode_cabang)
                ->where('kode_akun', $request->kode_akun)
                ->where('id', '!=', $id)
                ->count();

            if ($cek > 0) {
                return Redirect::back()->with(messageError('Data COA Cabang sudah ada'));
            }

            $coaCabang->update([
                'kode_cabang' => $request->kode_cabang,
                'kode_akun' => $request->kode_akun,
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $coaCabang = Coacabang::findOrFail($id);
        $kode_cabang = $coaCabang->kode_cabang;

        try {
            $coaCabang->delete();
            return redirect()->route('coacabang.index', ['kode_cabang' => $kode_cabang])->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return redirect()->route('coacabang.index', ['kode_cabang' => $kode_cabang])->with(messageError($e->getMessage()));
        }
    }
}

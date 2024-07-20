<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Lembur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class LemburController extends Controller
{
    public function index(Request $request)
    {
        $lb = new Lembur();
        $lembur = $lb->getLembur(request: $request)->paginate(15);
        $lembur->appends(request()->all());
        $data['lembur'] = $lembur;
        return view('hrd.lembur.index', $data);
    }


    public function create()
    {
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        return view('hrd.lembur.create', $data);
    }


    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $request->validate([
            'tanggal' => 'required|date',
            'dari' => 'required|date',
            'sampai' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'istirahat' => 'required',
            'keterangan' => 'required',
            'kategori' => 'required',
        ]);
        if (in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
            $validationRules['kode_dept'] = 'required';
        }
        $request->validate($validationRules);

        $kode_dept = in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi']) ? $request->kode_dept : $user->kode_dept;
        $kode_cabang = $user->kode_cabang;

        try {
            $lastlembur = Lembur::whereRaw('MID(kode_lembur,3,2)="' . date('y', strtotime($request->tanggal)) . '"')
                ->orderBy('kode_lembur', 'desc')->first();
            $last_kode_lembur = $lastlembur != null ? $lastlembur->kode_lembur : '';
            $kode_lembur = buatkode($last_kode_lembur, "LM" . date('y', strtotime($request->tanggal)), 3);
            $mulai = $request->dari . " " . $request->jam_mulai;
            $selesai = $request->sampai . " " . $request->jam_selesai;
            $mulai = date('Y-m-d H:i:s', strtotime($mulai));
            $selesai = date('Y-m-d H:i:s', strtotime($selesai));
            Lembur::create([
                'kode_lembur' => $kode_lembur,
                'kode_cabang' => $kode_cabang,
                'kode_dept' => $kode_dept,
                'tanggal' => $request->tanggal,
                'tanggal_dari' => $mulai,
                'tanggal_sampai' => $selesai,
                'kategori' => $request->kategori,
                'istirahat' => $request->istirahat,
                'status' => 0,
                'keterangan' => $request->keterangan,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_lembur)
    {
        $kode_lembur = Crypt::decrypt($kode_lembur);
        $data['lembur'] = Lembur::where('kode_lembur', $kode_lembur)->first();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        return view('hrd.lembur.edit', $data);
    }


    public function update($kode_lembur, Request $request)
    {
        $kode_lembur = Crypt::decrypt($kode_lembur);
        $user = User::findorFail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $request->validate([
            'tanggal' => 'required|date',
            'dari' => 'required|date',
            'sampai' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'istirahat' => 'required',
            'keterangan' => 'required',
            'kategori' => 'required',
        ]);
        if (in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
            $validationRules['kode_dept'] = 'required';
        }
        $request->validate($validationRules);

        $kode_dept = in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi']) ? $request->kode_dept : $user->kode_dept;
        $kode_cabang = $user->kode_cabang;

        try {

            $mulai = $request->dari . " " . $request->jam_mulai;
            $selesai = $request->sampai . " " . $request->jam_selesai;
            $mulai = date('Y-m-d H:i:s', strtotime($mulai));
            $selesai = date('Y-m-d H:i:s', strtotime($selesai));
            Lembur::where('kode_lembur', $kode_lembur)->update([
                'kode_cabang' => $kode_cabang,
                'kode_dept' => $kode_dept,
                'tanggal' => $request->tanggal,
                'tanggal_dari' => $mulai,
                'tanggal_sampai' => $selesai,
                'kategori' => $request->kategori,
                'istirahat' => $request->istirahat,
                'status' => 0,
                'keterangan' => $request->keterangan,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_lembur)
    {
        $kode_lembur = Crypt::decrypt($kode_lembur);
        try {
            $lembur = Lembur::where('kode_lembur', $kode_lembur);
            if (!$lembur) {
                return Redirect::back()->with(messageError('Data tidak ditemukan'));
            }
            $lembur->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

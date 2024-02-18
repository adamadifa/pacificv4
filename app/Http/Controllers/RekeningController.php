<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Group;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class RekeningController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_karyawan = config('global.roles_access_all_karyawan');

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();

        $departemen = Departemen::orderBy('kode_dept')->get();
        $group = Group::orderBy('kode_group')->get();


        $query = Karyawan::query();
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_klasifikasi', 'hrd_karyawan.kode_klasifikasi', '=', 'hrd_klasifikasi.kode_klasifikasi');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');

        if (!empty($request->kode_cabang)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('hrd_karyawan.kode_dept', $request->kode_dept);
        }
        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group);
        }

        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }
        $query->orderBy('tanggal_masuk', 'desc');
        $karyawan = $query->paginate(15);
        return view('datamaster.rekening.index', compact('cabang', 'karyawan', 'departemen', 'group'));
    }


    public function edit($nik)
    {
        $nik = Crypt::decrypt($nik);
        $karyawan = Karyawan::where('nik', $nik)
            ->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->first();
        return view('datamaster.rekening.edit', compact('karyawan'));
    }

    public function update(Request $request, $nik)
    {
        $nik = Crypt::decrypt($nik);
        try {
            Karyawan::where('nik', $nik)->update([
                'no_rekening' => $request->no_rekening,
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

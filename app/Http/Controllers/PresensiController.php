<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Tambahkan import untuk User
use App\Models\Cabang; // Tambahkan import untuk Cabang
use App\Models\Karyawan; // Tambahkan import untuk Karyawan
use App\Models\Departemen; // Tambahkan import untuk Departemen
use App\Models\Group; // Tambahkan import untuk Group

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) ?? [];
        $roles_access_all_karyawan = config('global.roles_access_all_karyawan');

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();

        // Tampilkan Departemen dan Group
        if (!$user->hasRole($roles_access_all_karyawan)) {
            if (auth()->user()->kode_cabang != 'PST') {
                $departemen = Karyawan::select('hrd_karyawan.kode_dept', 'nama_dept')
                    ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
                    ->where('kode_cabang', auth()->user()->kode_cabang)
                    ->groupBy('hrd_karyawan.kode_dept')
                    ->orderBy('hrd_karyawan.kode_dept')->get();
                $group = Karyawan::select('hrd_karyawan.kode_group', 'nama_group')
                    ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
                    ->where('kode_cabang', auth()->user()->kode_cabang)
                    ->groupBy('hrd_karyawan.kode_group')
                    ->orderBy('hrd_karyawan.kode_group')->get();
            } else {
                $departemen = Karyawan::select('hrd_karyawan.kode_dept', 'nama_dept')
                    ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
                    ->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept)
                    ->groupBy('hrd_karyawan.kode_dept')
                    ->orderBy('hrd_karyawan.kode_dept')->get();
                $group = Karyawan::select('hrd_karyawan.kode_group', 'nama_group')
                    ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
                    ->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept)
                    ->groupBy('hrd_karyawan.kode_group')
                    ->orderBy('hrd_karyawan.kode_group')->get();
            }
        } else {
            $departemen = Departemen::orderBy('kode_dept')->get();
            $group = Group::orderBy('kode_group')->get();
        }

        $query = Karyawan::query();
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_klasifikasi', 'hrd_karyawan.kode_klasifikasi', '=', 'hrd_klasifikasi.kode_klasifikasi');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        if (!$user->hasRole($roles_access_all_karyawan)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                if (auth()->user()->kode_cabang != 'PST') {
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
                }
            }
        }




        if (!empty($request->kode_cabang_search)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_search);
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
        $query->orderBy('nama_karyawan', 'asc');
        $karyawan = $query->paginate(15);
        $karyawan->appends($request->all());
        return view('presensi.index', compact('cabang', 'departemen', 'group', 'karyawan'));
    }
}

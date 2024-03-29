<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Group;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Klasifikasikaryawan;
use App\Models\Statusperkawinan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_karyawan = config('global.roles_access_all_karyawan');

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();

        //Tampilkan Departemen dan Group
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
                if (auth()->user()->kode_cabang == 'PST') {
                    $query->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
                } else {
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                }
            }
        }

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
        return view('datamaster.karyawan.index', compact('cabang', 'karyawan', 'departemen', 'group'));
    }

    public function create()
    {
        $status_perkawinan = Statusperkawinan::orderBy('kode_status_kawin')->get();
        $cabang = Cabang::orderBy('kode_cabang')->get();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $group = Group::orderBy('kode_group')->get();
        $jabatan = Jabatan::orderBy('kode_jabatan')->get();
        $klasifikasi = Klasifikasikaryawan::orderBy('kode_klasifikasi')->get();
        return view('datamaster.karyawan.create', compact(
            'status_perkawinan',
            'cabang',
            'departemen',
            'group',
            'jabatan',
            'klasifikasi'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'no_ktp' => 'required',
            'nama_karyawan' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required',
            'kode_status_kawin' => 'required',
            'pendidikan_terakhir' => 'required',
            'kode_perusahaan' => 'required',
            'kode_cabang' => 'required',
            'kode_dept' => 'required',
            'kode_group' => 'required',
            'kode_jabatan' => 'required',
            'kode_klasifikasi' => 'required',
            'tanggal_masuk' => 'required',
            'status_karyawan' => 'required'
        ]);

        try {
            Karyawan::create([
                'nik' => $request->nik,
                'no_ktp' => $request->no_ktp,
                'nama_karyawan' => $request->nama_karyawan,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'kode_status_kawin' => $request->kode_status_kawin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'kode_perusahaan' => $request->kode_perusahaan,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_group' => $request->kode_group,
                'kode_jabatan' => $request->kode_jabatan,
                'kode_klasifikasi' => $request->kode_klasifikasi,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_karyawan' => $request->status_karyawan,
                'lock_location' => 1,
                'status_aktif_karyawan' => 1,
                'password' => Hash::make('12345')
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($nik)
    {

        $nik = Crypt::decrypt($nik);
        $karyawan = Karyawan::where('nik', $nik)->first();
        $status_perkawinan = Statusperkawinan::orderBy('kode_status_kawin')->get();
        $cabang = Cabang::orderBy('kode_cabang')->get();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $group = Group::orderBy('kode_group')->get();
        $jabatan = Jabatan::orderBy('kode_jabatan')->get();
        $klasifikasi = Klasifikasikaryawan::orderBy('kode_klasifikasi')->get();
        return view('datamaster.karyawan.edit', compact(
            'status_perkawinan',
            'cabang',
            'departemen',
            'group',
            'jabatan',
            'klasifikasi',
            'karyawan'
        ));
    }


    public function update(Request $request, $nik)
    {
        $nik = Crypt::decrypt($nik);
        $request->validate([
            'nik' => 'required',
            'no_ktp' => 'required',
            'nama_karyawan' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required',
            'kode_status_kawin' => 'required',
            'pendidikan_terakhir' => 'required',
            'kode_perusahaan' => 'required',
            'kode_cabang' => 'required',
            'kode_dept' => 'required',
            'kode_group' => 'required',
            'kode_jabatan' => 'required',
            'kode_klasifikasi' => 'required',
            'tanggal_masuk' => 'required',
            'status_karyawan' => 'required',
            'status_aktif_karyawan' => 'required'
        ]);

        try {
            Karyawan::where('nik', $nik)->update([
                'nik' => $request->nik,
                'no_ktp' => $request->no_ktp,
                'nama_karyawan' => $request->nama_karyawan,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_hp' => $request->no_hp,
                'kode_status_kawin' => $request->kode_status_kawin,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'kode_perusahaan' => $request->kode_perusahaan,
                'kode_cabang' => $request->kode_cabang,
                'kode_dept' => $request->kode_dept,
                'kode_group' => $request->kode_group,
                'kode_jabatan' => $request->kode_jabatan,
                'kode_klasifikasi' => $request->kode_klasifikasi,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_karyawan' => $request->status_karyawan,
                'status_aktif_karyawan' => $request->status_aktif_karyawan,
                'tanggal_nonaktif' => $request->status_aktif_karyawan === "0" ? $request->tanggal_nonaktif : NULL,
                'tanggal_off_gaji' => $request->status_aktif_karyawan === "0" ? $request->tanggal_off_gaji : NULL,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show($nik)
    {
        $nik = Crypt::decrypt($nik);
        $karyawan = Karyawan::where('nik', $nik)
            ->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->join('hrd_klasifikasi', 'hrd_karyawan.kode_klasifikasi', '=', 'hrd_klasifikasi.kode_klasifikasi')
            ->join('hrd_status_kawin', 'hrd_karyawan.kode_status_kawin', '=', 'hrd_karyawan.kode_status_kawin')
            ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')

            ->first();
        return view('datamaster.karyawan.show', compact('karyawan'));
    }


    public function unlocklocation($nik)
    {
        $nik = Crypt::decrypt($nik);
        $karyawan = Karyawan::where('nik', $nik)->first();
        try {
            if ($karyawan->lock_location === '0') {
                Karyawan::where('nik', $nik)->update([
                    'lock_location' => 1,
                ]);
                return Redirect::back()->with(messageSuccess('Lokasi Berhasil Di Unlock'));
            } else {
                Karyawan::where('nik', $nik)->update([
                    'lock_location' => 0,
                ]);
                return Redirect::back()->with(messageSuccess('Lokasi Berhasil Di Lock'));
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

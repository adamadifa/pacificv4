<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Group;
use App\Models\Insentif;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class InsentifController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_pjp = config('global.roles_access_all_pjp');
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $group = Group::orderBy('kode_group')->get();

        $query = Insentif::query();
        $query->select('hrd_insentif.*', 'hrd_karyawan.*', 'lastinsentif.kode_insentif as kode_lastinsentif');
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
        $query->join('hrd_karyawan', 'hrd_insentif.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');

        $query->leftJoin(
            DB::raw("(
                SELECT
                max(kode_insentif) as kode_insentif
                FROM hrd_insentif
                GROUP BY nik
            ) lastinsentif"),
            function ($join) {
                $join->on('hrd_insentif.kode_insentif', '=', 'lastinsentif.kode_insentif');
            }
        );

        if (!$user->hasRole($roles_access_all_pjp)) {
            $query->where('hrd_jabatan.kategori', 'NM');
        }
        $query->orderBy('kode_insentif', 'desc');
        $insentif = $query->paginate('15');
        return view('datamaster.insentif.index', compact('cabang', 'departemen', 'group', 'insentif'));
    }

    public function create()
    {
        $karyawan = Karyawan::orderBy('nama_karyawan')->get();
        return view('datamaster.insentif.create', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_berlaku' => 'required',
            'nik' => 'required',
            'iu_masakerja' => 'required',
            'iu_lembur' => 'required',
            'iu_penempatan' => 'required',
            'iu_kpi' => 'required',
            'im_ruanglingkup' => 'required',
            'im_penempatan' => 'required',
            'im_kinerja' => 'required',
            'im_kendaraan' => 'required'
        ]);

        try {
            $tgl = explode("-", $request->tanggal_berlaku);
            $tahun = substr($tgl[0], 2, 2);
            $insentif = DB::table("hrd_insentif")
                ->whereRaw('YEAR(tanggal_berlaku)="' . $tgl[0] . '"')
                ->orderBy("kode_insentif", "desc")
                ->first();

            $last_kodeinsentif = $insentif != null ? $insentif->kode_insentif : '';
            $kode_insentif  = buatkode($last_kodeinsentif, "IS" . $tahun, 3);

            Insentif::create([
                'kode_insentif' => $kode_insentif,
                'tanggal_berlaku' => $request->tanggal_berlaku,
                'nik' => $request->nik,
                'iu_masakerja' => toNumber($request->iu_masakerja),
                'iu_lembur' => toNumber($request->iu_lembur),
                'iu_penempatan' => toNumber($request->iu_penempatan),
                'iu_kpi' => toNumber($request->iu_kpi),
                'im_ruanglingkup' => toNumber($request->im_ruanglingkup),
                'im_penempatan' => toNumber($request->im_penempatan),
                'im_kinerja' => toNumber($request->im_kinerja),
                'im_kendaraan' => toNumber($request->im_kendaraan)
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_insentif)
    {
        $kode_insentif = Crypt::decrypt($kode_insentif);
        $karyawan = Karyawan::orderBy('nama_karyawan')->get();
        $insentif = Insentif::where('kode_insentif', $kode_insentif)->first();
        return view('datamaster.insentif.edit', compact('karyawan', 'insentif'));
    }


    public function update(Request $request, $kode_insentif)
    {

        $kode_insentif = Crypt::decrypt($kode_insentif);
        $request->validate([
            'tanggal_berlaku' => 'required',
            'nik' => 'required',
            'iu_masakerja' => 'required',
            'iu_lembur' => 'required',
            'iu_penempatan' => 'required',
            'iu_kpi' => 'required',
            'im_ruanglingkup' => 'required',
            'im_penempatan' => 'required',
            'im_kinerja' => 'required',
            'im_kendaraan' => 'required'
        ]);

        try {


            Insentif::where('kode_insentif', $kode_insentif)->update([

                'tanggal_berlaku' => $request->tanggal_berlaku,
                'nik' => $request->nik,
                'iu_masakerja' => toNumber($request->iu_masakerja),
                'iu_lembur' => toNumber($request->iu_lembur),
                'iu_penempatan' => toNumber($request->iu_penempatan),
                'iu_kpi' => toNumber($request->iu_kpi),
                'im_ruanglingkup' => toNumber($request->im_ruanglingkup),
                'im_penempatan' => toNumber($request->im_penempatan),
                'im_kinerja' => toNumber($request->im_kinerja),
                'im_kendaraan' => toNumber($request->im_kendaraan)
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_insentif)
    {
        $kode_insentif = Crypt::decrypt($kode_insentif);
        try {
            Insentif::where('kode_insentif', $kode_insentif)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Gaji;
use App\Models\Group;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $group = Group::orderBy('kode_group')->get();

        $query = Gaji::query();
        $query->select('hrd_gaji.*', 'hrd_karyawan.*', 'lastgaji.kode_gaji as kode_lastgaji');
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
        $query->join('hrd_karyawan', 'hrd_gaji.nik', '=', 'hrd_karyawan.nik');
        $query->leftJoin(
            DB::raw("(
                SELECT
                max(kode_gaji) as kode_gaji
                FROM hrd_gaji
                GROUP BY nik
            ) lastgaji"),
            function ($join) {
                $join->on('hrd_gaji.kode_gaji', '=', 'lastgaji.kode_gaji');
            }
        );
        $query->orderBy('kode_gaji', 'desc');
        $gaji = $query->paginate('15');
        return view('datamaster.gaji.index', compact(
            'cabang',
            'departemen',
            'group',
            'gaji'
        ));
    }


    public function create()
    {

        $karyawan = Karyawan::orderBy('nama_karyawan')->get();
        return view('datamaster.gaji.create', compact('karyawan'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'tanggal_berlaku' => 'required',
            'nik' => 'required',
            'gaji_pokok' => 'required',
            't_jabatan' => 'required',
            't_masakerja' => 'required',
            't_tanggungjawab' => 'required',
            't_makan' => 'required',
            't_istri' => 'required',
            't_skill' => 'required'
        ]);

        try {
            $tgl = explode("-", $request->tanggal_berlaku);
            $tahun = substr($tgl[0], 2, 2);
            $gaji = Gaji::whereRaw('YEAR(tanggal_berlaku)="' . $tgl[0] . '"')
                ->orderBy("kode_gaji", "desc")
                ->first();

            $last_kodegaji = $gaji != null ? $gaji->kode_gaji : '';
            $kode_gaji  = buatkode($last_kodegaji, "GJ" . $tahun, 3);

            Gaji::create([
                'kode_gaji' => $kode_gaji,
                'nik' => $request->nik,
                'tanggal_berlaku' => $request->tanggal_berlaku,
                'gaji_pokok' => toNumber($request->gaji_pokok),
                't_jabatan' => toNumber($request->t_jabatan),
                't_masakerja' => toNumber($request->t_masakerja),
                't_tanggungjawab' => toNumber($request->t_tanggungjawab),
                't_makan' => toNumber($request->t_makan),
                't_istri' => toNumber($request->t_istri),
                't_skill' => toNumber($request->t_skill)
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $gaji = Gaji::where('kode_gaji', $kode_gaji)->first();
        $karyawan = Karyawan::orderBy('nama_karyawan')->get();
        return view('datamaster.gaji.edit', compact('karyawan', 'gaji'));
    }


    public function update(Request $request, $kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        //dd($kode_gaji);
        $request->validate([
            'tanggal_berlaku' => 'required',
            'nik' => 'required',
            'gaji_pokok' => 'required',
            't_jabatan' => 'required',
            't_masakerja' => 'required',
            't_tanggungjawab' => 'required',
            't_makan' => 'required',
            't_istri' => 'required',
            't_skill' => 'required'
        ]);

        try {


            Gaji::where('kode_gaji', $kode_gaji)->update([
                'nik' => $request->nik,
                'tanggal_berlaku' => $request->tanggal_berlaku,
                'gaji_pokok' => toNumber($request->gaji_pokok),
                't_jabatan' => toNumber($request->t_jabatan),
                't_masakerja' => toNumber($request->t_masakerja),
                't_tanggungjawab' => toNumber($request->t_tanggungjawab),
                't_makan' => toNumber($request->t_makan),
                't_istri' => toNumber($request->t_istri),
                't_skill' => toNumber($request->t_skill)
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        try {
            Gaji::where('kode_gaji', $kode_gaji)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

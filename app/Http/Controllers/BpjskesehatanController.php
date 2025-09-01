<?php

namespace App\Http\Controllers;

use App\Models\Bpjskesehatan;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Group;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BpjskesehatanController extends Controller
{
    public function index(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $group = Group::orderBy('kode_group')->get();

        $query = Bpjskesehatan::query();
        $query->select('hrd_bpjs_kesehatan.*', 'hrd_karyawan.*', 'lastbpjskesehatan.kode_bpjs_kesehatan as kode_lastbpjskesehatan');
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
        $query->join('hrd_karyawan', 'hrd_bpjs_kesehatan.nik', '=', 'hrd_karyawan.nik');
        $query->leftJoin(
            DB::raw("(
                SELECT
                max(kode_bpjs_kesehatan) as kode_bpjs_kesehatan
                FROM hrd_bpjs_kesehatan
                GROUP BY nik
            ) lastbpjskesehatan"),
            function ($join) {
                $join->on('hrd_bpjs_kesehatan.kode_bpjs_kesehatan', '=', 'lastbpjskesehatan.kode_bpjs_kesehatan');
            }
        );
        $query->orderBy('kode_bpjs_kesehatan', 'desc');
        $bpjskesehatan = $query->paginate('15');
        return view('datamaster.bpjskesehatan.index', compact('cabang', 'departemen', 'group', 'bpjskesehatan'));
    }

    public function create()
    {

        $karyawan = Karyawan::orderBy('nama_karyawan')->get();
        return view('datamaster.bpjskesehatan.create', compact('karyawan'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'tanggal_berlaku' => 'required',
            'nik' => 'required',
            'iuran' => 'required'
        ]);


        try {
            $tgl = explode("-", $request->tanggal_berlaku);
            $tahun = substr($tgl[0], 2, 2);
            $bpjskes = DB::table("hrd_bpjs_kesehatan")
                ->whereRaw('YEAR(tanggal_berlaku)="' . $tgl[0] . '"')
                ->whereRaw('LENGTH(kode_bpjs_kesehatan) = 9')
                ->orderBy("kode_bpjs_kesehatan", "desc")
                ->first();

            $last_kodebpjskes = $bpjskes != null ? $bpjskes->kode_bpjs_kesehatan : '';
            $kode_bpjs_kesehatan  = buatkode($last_kodebpjskes, "BS" . $tahun, 5);


            // dd($kode_bpjs_kesehatan);

            Bpjskesehatan::create([
                'kode_bpjs_kesehatan' => $kode_bpjs_kesehatan,
                'nik' => $request->nik,
                'iuran' => toNumber($request->iuran),
                'tanggal_berlaku' => $request->tanggal_berlaku
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_bpjs_kesehatan)
    {
        $kode_bpjs_kesehatan = Crypt::decrypt($kode_bpjs_kesehatan);
        $bpjskesehatan = Bpjskesehatan::where('kode_bpjs_kesehatan', $kode_bpjs_kesehatan)->first();
        $karyawan = Karyawan::orderBy('nama_karyawan')->get();
        return view('datamaster.bpjskesehatan.edit', compact('karyawan', 'bpjskesehatan'));
    }

    public function update(Request $request, $kode_bpjs_kesehatan)
    {
        $kode_bpjs_kesehatan = Crypt::decrypt($kode_bpjs_kesehatan);
        $request->validate([
            'tanggal_berlaku' => 'required',
            'nik' => 'required',
            'iuran' => 'required'
        ]);


        try {

            Bpjskesehatan::where('kode_bpjs_kesehatan', $kode_bpjs_kesehatan)->update([
                'nik' => $request->nik,
                'iuran' => toNumber($request->iuran),
                'tanggal_berlaku' => $request->tanggal_berlaku
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_bpjs_kesehatan)
    {
        $kode_bpjs_kesehatan = Crypt::decrypt($kode_bpjs_kesehatan);
        try {
            Bpjskesehatan::where('kode_bpjs_kesehatan', $kode_bpjs_kesehatan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

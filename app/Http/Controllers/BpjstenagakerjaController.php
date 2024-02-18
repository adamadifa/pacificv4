<?php

namespace App\Http\Controllers;


use App\Models\Bpjstenagakerja;
use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Group;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BpjstenagakerjaController extends Controller
{
    public function index(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $departemen = Departemen::orderBy('kode_dept')->get();
        $group = Group::orderBy('kode_group')->get();

        $query = Bpjstenagakerja::query();
        $query->select('hrd_bpjs_tenagakerja.*', 'hrd_karyawan.*', 'lastbpjstenagakerja.kode_bpjs_tenagakerja as kode_lastbpjstenagakerja');
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
        $query->join('hrd_karyawan', 'hrd_bpjs_tenagakerja.nik', '=', 'hrd_karyawan.nik');
        $query->leftJoin(
            DB::raw("(
                SELECT
                max(kode_bpjs_tenagakerja) as kode_bpjs_tenagakerja
                FROM hrd_bpjs_tenagakerja
                GROUP BY nik
            ) lastbpjstenagakerja"),
            function ($join) {
                $join->on('hrd_bpjs_tenagakerja.kode_bpjs_tenagakerja', '=', 'lastbpjstenagakerja.kode_bpjs_tenagakerja');
            }
        );
        $query->orderBy('kode_bpjs_tenagakerja', 'desc');
        $bpjstenagakerja = $query->paginate('15');
        return view('datamaster.bpjstenagakerja.index', compact('cabang', 'departemen', 'group', 'bpjstenagakerja'));
    }

    public function create()
    {
        $karyawan = Karyawan::orderBy('nama_karyawan')->get();
        return view('datamaster.bpjstenagakerja.create', compact('karyawan'));
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
            $bpjskes = DB::table("hrd_bpjs_tenagakerja")
                ->whereRaw('YEAR(tanggal_berlaku)="' . $tgl[0] . '"')
                ->orderBy("kode_bpjs_tenagakerja", "desc")
                ->first();

            $last_kodebpjskes = $bpjskes != null ? $bpjskes->kode_bpjs_tenagakerja : '';
            $kode_bpjs_tenagakerja  = buatkode($last_kodebpjskes, "BT" . $tahun, 3);

            Bpjstenagakerja::create([
                'kode_bpjs_tenagakerja' => $kode_bpjs_tenagakerja,
                'nik' => $request->nik,
                'iuran' => toNumber($request->iuran),
                'tanggal_berlaku' => $request->tanggal_berlaku
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_bpjs_tenagakerja)
    {
        $kode_bpjs_tenagakerja = Crypt::decrypt($kode_bpjs_tenagakerja);
        $bpjstenagakerja = Bpjstenagakerja::where('kode_bpjs_tenagakerja', $kode_bpjs_tenagakerja)->first();
        $karyawan = Karyawan::orderBy('nama_karyawan')->get();
        return view('datamaster.bpjstenagakerja.edit', compact('karyawan', 'bpjstenagakerja'));
    }

    public function update(Request $request, $kode_bpjs_tenagakerja)
    {
        $kode_bpjs_tenagakerja = Crypt::decrypt($kode_bpjs_tenagakerja);
        $request->validate([
            'tanggal_berlaku' => 'required',
            'nik' => 'required',
            'iuran' => 'required'
        ]);


        try {

            Bpjstenagakerja::where('kode_bpjs_tenagakerja', $kode_bpjs_tenagakerja)->update([
                'nik' => $request->nik,
                'iuran' => toNumber($request->iuran),
                'tanggal_berlaku' => $request->tanggal_berlaku
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_bpjs_tenagakerja)
    {
        $kode_bpjs_tenagakerja = Crypt::decrypt($kode_bpjs_tenagakerja);


        try {
            Bpjstenagakerja::where('kode_bpjs_tenagakerja', $kode_bpjs_tenagakerja)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

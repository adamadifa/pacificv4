<?php

namespace App\Http\Controllers;

use App\Models\Jasamasakerja;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class JasamasakerjaController extends Controller
{
    public function index(Request $request)
    {

        $query = Jasamasakerja::query();
        $query->select('hrd_jasamasakerja.*', 'nama_karyawan', 'nama_jabatan', 'kode_dept', 'kode_cabang');
        $query->join('hrd_karyawan', 'hrd_jasamasakerja.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('hrd_jasamasakerja.tanggal', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nik)) {
            $query->where('nik', $request->nik);
        }
        if (!empty($request->nama_karyawan_search)) {
            $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan_search . '%');
        }
        $query->orderBy('hrd_jasamasakerja.tanggal', 'desc');
        $jasamasakerja = $query->paginate(15);
        $jasamasakerja->appends($request->all());
        $data['jasamasakerja'] = $jasamasakerja;
        return view('hrd.jasamasakerja.index', $data);
    }

    public function create()
    {
        $data['karyawan'] = Karyawan::orderBy('nama_karyawan')
            ->where('status_aktif_karyawan', 1)
            ->get();
        return view('hrd.jasamasakerja.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required|date',
            'jumlah' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $lastjasamasakerja = Jasamasakerja::whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->orderBy("kode_jmk", "desc")
                ->first();
            $lastnojasamasakerja = $lastjasamasakerja != null ? $lastjasamasakerja->kode_jmk : '';
            $kode_jmk = buatkode($lastnojasamasakerja, "JMK" . date('y', strtotime($request->tanggal)), 3);
            Jasamasakerja::create([
                'kode_jmk' => $kode_jmk,
                'nik' => $request->nik,
                'tanggal' => $request->tanggal,
                'jumlah' => toNumber($request->jumlah),
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_jmk)
    {
        $kode_jmk = Crypt::decrypt($kode_jmk);
        $data['jasamasakerja'] = Jasamasakerja::where('kode_jmk', $kode_jmk)->first();
        $data['karyawan'] = Karyawan::orderBy('nama_karyawan')
            ->where('status_aktif_karyawan', 1)
            ->get();
        return view('hrd.jasamasakerja.edit', $data);
    }

    public function update(Request $request, $kode_jmk)
    {

        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required|date',
            'jumlah' => 'required',
        ]);

        $kode_jmk = Crypt::decrypt($kode_jmk);
        try {
            Jasamasakerja::where('kode_jmk', $kode_jmk)->update([
                'nik' => $request->nik,
                'tanggal' => $request->tanggal,
                'jumlah' => toNumber($request->jumlah),
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_jmk)
    {
        $kode_jmk = Crypt::decrypt($kode_jmk);
        try {
            Jasamasakerja::where('kode_jmk', $kode_jmk)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

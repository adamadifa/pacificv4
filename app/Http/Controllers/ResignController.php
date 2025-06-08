<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Resign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ResignController extends Controller
{
    public function index(Request $request)
    {

        $query = Resign::query();
        $query->select('hrd_resign.*', 'nama_karyawan', 'nama_jabatan', 'kode_dept', 'kode_cabang');
        $query->join('hrd_karyawan', 'hrd_resign.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('hrd_resign.tanggal', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nik)) {
            $query->where('nik', $request->nik);
        }
        if (!empty($request->nama_karyawan_search)) {
            $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan_search . '%');
        }
        $query->orderBy('hrd_resign.tanggal', 'desc');
        $resign = $query->paginate(15);
        $resign->appends($request->all());
        $data['resign'] = $resign;
        return view('hrd.resign.index', $data);
    }

    public function create()
    {
        $data['karyawan'] = Karyawan::orderBy('nama_karyawan')
            ->where('status_aktif_karyawan', 1)
            ->get();
        return view('hrd.resign.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $lastresign = Resign::whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
                ->orderBy("kode_resign", "desc")
                ->first();
            $lastnoresign = $lastresign != null ? $lastresign->kode_resign : '';
            $kode_resign = buatkode($lastnoresign, "RES" . date('y', strtotime($request->tanggal)), 3);
            Resign::create([
                'kode_resign' => $kode_resign,
                'nik' => $request->nik,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_resign)
    {
        $kode_resign = Crypt::decrypt($kode_resign);
        $data['resign'] = Resign::where('kode_resign', $kode_resign)->first();
        $data['karyawan'] = Karyawan::orderBy('nama_karyawan')
            ->where('status_aktif_karyawan', 1)
            ->get();
        return view('hrd.resign.edit', $data);
    }

    public function update(Request $request, $kode_resign)
    {

        $request->validate([
            'nik' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required',
        ]);

        $kode_resign = Crypt::decrypt($kode_resign);
        try {
            Resign::where('kode_resign', $kode_resign)->update([
                'nik' => $request->nik,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_resign)
    {
        $kode_resign = Crypt::decrypt($kode_resign);
        try {
            Resign::where('kode_resign', $kode_resign)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

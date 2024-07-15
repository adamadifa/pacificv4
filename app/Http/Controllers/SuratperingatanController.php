<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Suratperingatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SuratperingatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Suratperingatan::query();
        $query->select('hrd_suratperingatan.*', 'nama_karyawan', 'nama_jabatan', 'kode_dept', 'kode_cabang');
        $query->join('hrd_karyawan', 'hrd_suratperingatan.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('hrd_suratperingatan.dari', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nama_karyawan_search)) {
            $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan_search . '%');
        }
        $query->orderBy('hrd_suratperingatan.tanggal', 'desc');
        $suratperingatan = $query->paginate(15);
        $suratperingatan->appends($request->all());
        $data['suratperingatan'] = $suratperingatan;

        return view('hrd.suratperingatan.index', $data);
    }

    public function create()
    {
        $data['karyawan'] = Karyawan::orderBy('nama_karyawan')
            ->where('status_aktif_karyawan', 1)
            ->get();
        return view('hrd.suratperingatan.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'dari' => 'required',
            'sampai' => 'required',
            'jenis_sp' => 'required',
            'keterangan' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $lastsuratperingatan = Suratperingatan::select('no_sp')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->dari)) . '"')
                ->orderBy("no_sp", "desc")
                ->first();


            $last_no_surat = $lastsuratperingatan != null ? $lastsuratperingatan->no_sp : '';
            $no_sp  = buatkode($last_no_surat, "SP" . date('y', strtotime($request->dari)), 3);

            Suratperingatan::create([
                'no_sp' => $no_sp,
                'nik' => $request->nik,
                'tanggal' => $request->dari,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'jenis_sp' => $request->jenis_sp,
                'keterangan' => $request->keterangan,
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($no_sp)
    {
        $no_sp = Crypt::decrypt($no_sp);
        $data['sp'] = Suratperingatan::where('no_sp', $no_sp)->first();
        $data['karyawan'] = Karyawan::orderBy('nama_karyawan')->get();
        return view('hrd.suratperingatan.edit', $data);
    }

    public function update(Request $request, $no_sp)
    {
        $no_sp = Crypt::decrypt($no_sp);
        $request->validate([
            'nik' => 'required',
            'dari' => 'required',
            'sampai' => 'required',
            'jenis_sp' => 'required',
            'keterangan' => 'required',
        ]);
        try {
            Suratperingatan::where('no_sp', $no_sp)->update([
                'nik' => $request->nik,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'jenis_sp' => $request->jenis_sp,
                'keterangan' => $request->keterangan,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_sp)
    {
        $no_sp = Crypt::decrypt($no_sp);
        try {
            Suratperingatan::where('no_sp', $no_sp)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

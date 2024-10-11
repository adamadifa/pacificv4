<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanhrdController extends Controller
{
    public function index()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('hrd.laporan.index', $data);
    }


    public function getdepartemen(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $kode_cabang = $request->kode_cabang;
        $departemen = Karyawan::where('kode_cabang', $kode_cabang)
            ->join('hrd_departemen', 'hrd_departemen.kode_dept', '=', 'hrd_karyawan.kode_dept')
            ->select('hrd_karyawan.kode_dept', 'nama_dept')->distinct()->get();

        $html = '<option value="">Semua Departemen</option>';
        foreach ($departemen as $d) {
            $html .= '<option value="' . $d->kode_dept . '">' . $d->nama_dept . '</option>';
        }

        return $html;
    }

    public function getgroup(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $kode_cabang = $request->kode_cabang;
        $group = Karyawan::where('kode_cabang', $kode_cabang)
            ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
            ->select('hrd_karyawan.kode_group', 'nama_group')->distinct()->get();

        $html = '<option value="">Semua Group</option>';
        foreach ($group as $d) {
            $html .= '<option value="' . $d->kode_group . '">' . $d->nama_group . '</option>';
        }

        return $html;
    }

    public function cetakpresensi(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $lastbulan = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
        $lasttahun = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");

        $lastbulan = $lastbulan < 10 ? '0' . $lastbulan : $lastbulan;
        $bulan = $request->bulan < 10 ? '0' . $request->bulan : $request->bulan;
        if ($request->periode_laporan == 2) {
            $dari = $request->tahun . "-" . $request->bulan . "-01";
            $sampai = date("Y-m-t", strtotime($dari));
        } else {
            $dari = $lasttahun . "-" . $lastbulan . "-21";
            $sampai = $request->tahun . "-" . $bulan . "-20";
        }

        $start_date = $dari;
        $end_date = $sampai;


        $presensi = Presensi::query()
            ->select(
                'tanggal',
                'hrd_presensi.nik',
                'nama_karyawan',
                'jam_in',
                'jam_out',
                'status'
            )
            ->join('hrd_karyawan', 'hrd_karyawan.nik', '=', 'hrd_presensi.nik')
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->orderBy('nik', 'asc')
            ->orderBy('tanggal', 'asc')
            ->get();



        $data['presensi'] = $presensi->groupBy('nik', 'nama_karyawan')->map(function ($rows) {
            $data = [
                'nik' => $rows->first()->nik,
                'nama_karyawan' => $rows->first()->nama_karyawan,
            ];
            foreach ($rows as $row) {
                $data[$row->tanggal] = [
                    'status' => $row->status,
                    'jam_in' => $row->jam_in,
                    'jam_out' => $row->jam_out,
                ];
            }
            return $data;
        });

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['jmlhari'] = hitungJumlahHari($start_date, $end_date) + 1;
        return view('hrd.laporan.presensi_cetak', $data);
    }
}

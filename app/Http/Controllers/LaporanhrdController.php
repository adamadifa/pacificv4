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
                'hrd_presensi.tanggal',
                'hrd_presensi.nik',
                'nama_karyawan',
                'hrd_karyawan.kode_jabatan',
                'hrd_karyawan.kode_dept',
                'jam_in',
                'jam_out',
                'hrd_presensi.status',
                'hrd_presensi.kode_jadwal',
                'nama_jadwal',
                'hrd_presensi.kode_jam_kerja',
                'jam_masuk as jam_mulai',
                'hrd_jamkerja.jam_pulang as jam_selesai',
                'lintashari',
                'total_jam',
                'istirahat',
                'jam_awal_istirahat',
                'jam_akhir_istirahat',
                //Izin Keluar
                'hrd_presensi_izinkeluar.kode_izin_keluar',
                'hrd_izinkeluar.jam_keluar',
                'hrd_izinkeluar.jam_kembali',
                'hrd_izinkeluar.direktur as izin_keluar_direktur',

                //Izin Terlambat
                'hrd_presensi_izinterlambat.kode_izin_terlambat',
                'hrd_izinterlambat.direktur as izin_terlambat_direktur',

                //Izin Sakit
                'hrd_presensi_izinsakit.kode_izin_sakit',
                'hrd_izinsakit.doc_sid',
                'hrd_izinsakit.direktur as izin_sakit_direktur',

                //Izin Pulang
                'hrd_presensi_izinpulang.kode_izin_pulang',
                'hrd_izinpulang.direktur as izin_pulang_direktur',

                //Izin Cuti
                'hrd_presensi_izincuti.kode_izin_cuti',
                'hrd_izincuti.direktur as izin_cuti_direktur',
                'hrd_jeniscuti.nama_cuti',

                //Izin Absen
                'hrd_presensi_izinabsen.kode_izin',
                'hrd_izinabsen.direktur as izin_absen_direktur',
            )
            ->join('hrd_karyawan', 'hrd_karyawan.nik', '=', 'hrd_presensi.nik')
            ->leftJoin('hrd_jadwalkerja', 'hrd_presensi.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal')
            ->leftJoin('hrd_jamkerja', 'hrd_presensi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja')

            ->leftJoin('hrd_presensi_izinterlambat', 'hrd_presensi.id', '=', 'hrd_presensi_izinterlambat.id_presensi')
            ->leftJoin('hrd_izinterlambat', 'hrd_presensi_izinterlambat.kode_izin_terlambat', '=', 'hrd_izinterlambat.kode_izin_terlambat')

            ->leftJoin('hrd_presensi_izinkeluar', 'hrd_presensi.id', '=', 'hrd_presensi_izinkeluar.id_presensi')
            ->leftJoin('hrd_izinkeluar', 'hrd_presensi_izinkeluar.kode_izin_keluar', '=', 'hrd_izinkeluar.kode_izin_keluar')

            ->leftJoin('hrd_presensi_izinsakit', 'hrd_presensi.id', '=', 'hrd_presensi_izinsakit.id_presensi')
            ->leftJoin('hrd_izinsakit', 'hrd_presensi_izinsakit.kode_izin_sakit', '=', 'hrd_izinsakit.kode_izin_sakit')

            ->leftJoin('hrd_presensi_izinpulang', 'hrd_presensi.id', '=', 'hrd_presensi_izinpulang.id_presensi')
            ->leftJoin('hrd_izinpulang', 'hrd_presensi_izinpulang.kode_izin_pulang', '=', 'hrd_izinpulang.kode_izin_pulang')


            ->leftJoin('hrd_presensi_izincuti', 'hrd_presensi.id', '=', 'hrd_presensi_izincuti.id_presensi')
            ->leftJoin('hrd_izincuti', 'hrd_presensi_izincuti.kode_izin_cuti', '=', 'hrd_izincuti.kode_izin_cuti')
            ->leftJoin('hrd_jeniscuti', 'hrd_izincuti.kode_cuti', '=', 'hrd_jeniscuti.kode_cuti')

            ->leftJoin('hrd_presensi_izinabsen', 'hrd_presensi.id', '=', 'hrd_presensi_izinabsen.id_presensi')
            ->leftJoin('hrd_izinabsen', 'hrd_presensi_izinabsen.kode_izin', '=', 'hrd_izinabsen.kode_izin')

            ->whereBetween('hrd_presensi.tanggal', [$start_date, $end_date])
            ->orderBy('nik', 'asc')
            ->orderBy('tanggal', 'asc')
            ->get();



        $data['presensi'] = $presensi->groupBy('nik', 'nama_karyawan', 'kode_jabatan', 'kode_dept')->map(function ($rows) {
            $data = [
                'nik' => $rows->first()->nik,
                'nama_karyawan' => $rows->first()->nama_karyawan,
                'kode_jabatan' => $rows->first()->kode_jabatan,
                'kode_dept' => $rows->first()->kode_dept,
            ];
            foreach ($rows as $row) {
                $data[$row->tanggal] = [
                    'status' => $row->status,
                    'jam_in' => $row->jam_in,
                    'jam_out' => $row->jam_out,
                    'kode_jadwal' => $row->kode_jadwal,
                    'nama_jadwal' => $row->nama_jadwal,
                    'kode_jam_kerja' => $row->kode_jam_kerja,
                    'jam_mulai' => $row->jam_mulai,
                    'jam_selesai' => $row->jam_selesai,
                    'lintashari' => $row->lintashari,
                    'istirahat' => $row->istirahat,
                    'jam_awal_istirahat' => $row->jam_awal_istirahat,
                    'jam_akhir_istirahat' => $row->jam_akhir_istirahat,
                    'total_jam' => $row->total_jam,
                    'kode_izin_keluar' => $row->kode_izin_keluar,
                    'jam_keluar' => $row->jam_keluar,
                    'jam_kembali' => $row->jam_kembali,
                    'izin_keluar_direktur' => $row->izin_keluar_direktur,

                    'kode_izin_terlambat' => $row->kode_izin_terlambat,
                    'izin_terlambat_direktur' => $row->izin_terlambat_direktur,

                    'kode_izin_sakit' => $row->kode_izin_sakit,
                    'doc_sid' => $row->doc_sid,
                    'izin_sakit_direktur' => $row->izin_sakit_direktur,

                    'kode_izin_pulang' => $row->kode_izin_pulang,
                    'izin_pulang_direktur' => $row->izin_pulang_direktur,

                    'kode_izin_cuti' => $row->kode_izin_cuti,
                    'izin_cuti_direktur' => $row->izin_cuti_direktur,
                    'nama_cuti' => $row->nama_cuti,

                    'kode_izin' => $row->kode_izin_absen,
                    'izin_absen_direktur' => $row->izin_absen_direktur,
                ];
            }
            return $data;
        });

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $data['dataliburnasional'] = getdataliburnasional($start_date, $end_date);
        $data['datadirumahkan'] = getdirumahkan($start_date, $end_date);
        $data['dataliburpengganti'] = getliburpengganti($start_date, $end_date);
        $data['dataminggumasuk'] = getminggumasuk($start_date, $end_date);
        $data['datatanggallimajam'] = gettanggallimajam($start_date, $end_date);
        $data['jmlhari'] = hitungJumlahHari($start_date, $end_date) + 1;
        return view('hrd.laporan.presensi_cetak', $data);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Tambahkan import untuk User
use App\Models\Cabang; // Tambahkan import untuk Cabang
use App\Models\Karyawan; // Tambahkan import untuk Karyawan
use App\Models\Departemen; // Tambahkan import untuk Departemen
use App\Models\Group; // Tambahkan import untuk Group
use App\Models\Presensi;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) ?? [];
        $roles_access_all_karyawan = config('global.roles_access_all_karyawan');

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $tanggal = !empty($request->tanggal) ? $request->tanggal : date('Y-m-d');

        //Subquery Presensi
        $subqueryPresensi = Presensi::select(
            'hrd_presensi.nik',
            'hrd_presensi.tanggal',
            'hrd_presensi.jam_in',
            'hrd_presensi.jam_out',
            'hrd_presensi.status as status_kehadiran',
            'hrd_presensi.kode_jadwal',
            'hrd_presensi.kode_jam_kerja',
            'hrd_jamkerja.jam_masuk as jam_mulai',
            'hrd_jamkerja.jam_pulang as jam_selesai',
            'hrd_jamkerja.lintashari',
            'hrd_karyawan.kode_jabatan',
            'hrd_karyawan.kode_dept',

            'hrd_presensi_izinterlambat.kode_izin_terlambat',
            'hrd_izinterlambat.direktur as izin_terlambat_direktur',

            'hrd_presensi_izinkeluar.kode_izin_keluar',
            'hrd_izinkeluar.direktur as izin_keluar_direktur',
            'hrd_izinkeluar.keperluan',

            'hrd_izinkeluar.jam_keluar',
            'hrd_izinkeluar.jam_kembali',

            'hrd_presensi_izinsakit.kode_izin_sakit',
            'hrd_izinsakit.direktur as izin_sakit_direktur',

            'hrd_jamkerja.total_jam',
            'hrd_jamkerja.istirahat',
            'hrd_jamkerja.jam_awal_istirahat',
            'hrd_jamkerja.jam_akhir_istirahat',
            'hrd_presensi_izinpulang.kode_izin_pulang',
            'hrd_jadwalkerja.nama_jadwal',
            'hrd_karyawan.kode_cabang',
            // 'hrd_presensi.status',
            'nama_cuti',
            'nama_cuti_khusus',
            'doc_sid',

            'hrd_izinpulang.direktur as izin_pulang_direktur',

            'hrd_presensi_izinabsen.kode_izin as kode_izin_absen',
            'hrd_izinabsen.direktur as izin_absen_direktur'
        )


            ->join('hrd_karyawan', 'hrd_presensi.nik', '=', 'hrd_karyawan.nik')
            ->leftJoin('hrd_jamkerja', 'hrd_presensi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja')
            ->leftJoin('hrd_jadwalkerja', 'hrd_presensi.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal')

            ->leftJoin('hrd_presensi_izinterlambat', 'hrd_presensi.id', '=', 'hrd_presensi_izinterlambat.id_presensi')
            ->leftJoin('hrd_izinterlambat', 'hrd_presensi_izinterlambat.kode_izin_terlambat', '=', 'hrd_izinterlambat.kode_izin_terlambat')

            ->leftJoin('hrd_presensi_izinkeluar', 'hrd_presensi.id', '=', 'hrd_presensi_izinkeluar.id_presensi')
            ->leftJoin('hrd_izinkeluar', 'hrd_presensi_izinkeluar.kode_izin_keluar', '=', 'hrd_izinkeluar.kode_izin_keluar')

            ->leftJoin('hrd_presensi_izinpulang', 'hrd_presensi.id', '=', 'hrd_presensi_izinpulang.id_presensi')
            ->leftJoin('hrd_izinpulang', 'hrd_presensi_izinpulang.kode_izin_pulang', '=', 'hrd_izinpulang.kode_izin_pulang')

            ->leftJoin('hrd_presensi_izincuti', 'hrd_presensi.id', '=', 'hrd_presensi_izincuti.id_presensi')
            ->leftJoin('hrd_izincuti', 'hrd_presensi_izincuti.kode_izin_cuti', '=', 'hrd_izincuti.kode_izin_cuti')
            ->leftJoin('hrd_jeniscuti', 'hrd_izincuti.kode_cuti', '=', 'hrd_jeniscuti.kode_cuti')
            ->leftJoin('hrd_jeniscuti_khusus', 'hrd_izincuti.kode_cuti_khusus', '=', 'hrd_jeniscuti_khusus.kode_cuti_khusus')

            ->leftJoin('hrd_presensi_izinsakit', 'hrd_presensi.id', '=', 'hrd_presensi_izinsakit.id_presensi')
            ->leftJoin('hrd_izinsakit', 'hrd_presensi_izinsakit.kode_izin_sakit', '=', 'hrd_izinsakit.kode_izin_sakit')

            ->leftJoin('hrd_presensi_izinabsen', 'hrd_presensi.id', '=', 'hrd_presensi_izinabsen.id_presensi')
            ->leftJoin('hrd_izinabsen', 'hrd_presensi_izinabsen.kode_izin', '=', 'hrd_izinabsen.kode_izin')

            ->where('hrd_presensi.tanggal', $tanggal);

        // dd($subqueryPresensi->get());
        // Tampilkan Departemen dan Group
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
        $query->select(
            'hrd_karyawan.nik',
            'hrd_karyawan.nama_karyawan',
            'hrd_karyawan.pin',
            'hrd_karyawan.kode_dept',
            'hrd_karyawan.kode_cabang',
            'hrd_karyawan.kode_jabatan',
            'presensi.kode_jadwal',
            'presensi.nama_jadwal',
            'presensi.jam_mulai',
            'presensi.jam_selesai',
            'presensi.jam_in',
            'presensi.jam_out',
            'presensi.status_kehadiran',
            'presensi.tanggal',
            'presensi.kode_izin_keluar',
            'presensi.jam_keluar',
            'presensi.jam_kembali',
            'presensi.istirahat',
            'presensi.jam_awal_istirahat',
            'presensi.jam_akhir_istirahat',
            'presensi.lintashari',

            'presensi.kode_izin_keluar',
            'presensi.izin_keluar_direktur',
            'presensi.keperluan',

            'presensi.kode_izin_terlambat',
            'presensi.izin_terlambat_direktur',

            'presensi.kode_izin_sakit',
            'presensi.izin_sakit_direktur',
            'presensi.doc_sid',

            'presensi.kode_izin_pulang',
            'presensi.izin_pulang_direktur',

            'presensi.kode_izin_absen',
            'presensi.izin_absen_direktur',

            'presensi.total_jam'
        );
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_klasifikasi', 'hrd_karyawan.kode_klasifikasi', '=', 'hrd_klasifikasi.kode_klasifikasi');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftjoinSub($subqueryPresensi, 'presensi', function ($join) {
            $join->on('hrd_karyawan.nik', '=', 'presensi.nik');
        });
        if (!$user->hasRole($roles_access_all_karyawan)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                if (auth()->user()->kode_cabang != 'PST') {
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
                }
            }
        }




        if (!empty($request->kode_cabang_search)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_search);
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
        $query->orderBy('nama_karyawan', 'asc');
        $karyawan = $query->paginate(15);
        $karyawan->appends($request->all());
        return view('presensi.index', compact('cabang', 'departemen', 'group', 'karyawan'));
    }


    public function getdatamesin(Request $request)
    {
        $tanggal = $request->tanggal;
        $pin = $request->pin;
        $kode_jadwal = $request->kode_jadwal;
        if ($kode_jadwal == "JD004") {
            $nextday = date('Y-m-d', strtotime('+1 day', strtotime($tanggal)));
        } else {
            $nextday =  $tanggal;
        }
        $specific_value = $pin;


        //Mesin 1
        $url = 'https://developer.fingerspot.io/api/get_attlog';
        $data = '{"trans_id":"1", "cloud_id":"C2609075E3170B2C", "start_date":"' . $tanggal . '", "end_date":"' . $nextday . '"}';
        $authorization = "Authorization: Bearer QNBCLO9OA0AWILQD";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result);
        $datamesin1 = $res->data;

        $filtered_array = array_filter($datamesin1, function ($obj) use ($specific_value) {
            return $obj->pin == $specific_value;
        });


        //Mesin 2
        $url = 'https://developer.fingerspot.io/api/get_attlog';
        $data = '{"trans_id":"1", "cloud_id":"C268909557211236", "start_date":"' . $tanggal . '", "end_date":"' . $nextday . '"}';
        $authorization = "Authorization: Bearer QNBCLO9OA0AWILQD";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result2 = curl_exec($ch);
        curl_close($ch);
        $res2 = json_decode($result2);
        $datamesin2 = $res2->data;

        $filtered_array_2 = array_filter($datamesin2, function ($obj) use ($specific_value) {
            return $obj->pin == $specific_value;
        });


        return view('presensi.getdatamesin', compact('filtered_array', 'filtered_array_2'));
    }
}

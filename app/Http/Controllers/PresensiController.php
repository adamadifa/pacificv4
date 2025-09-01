<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Tambahkan import untuk User
use App\Models\Cabang; // Tambahkan import untuk Cabang
use App\Models\Karyawan; // Tambahkan import untuk Karyawan
use App\Models\Departemen; // Tambahkan import untuk Departemen
use App\Models\Detailjadwalkerja;
use App\Models\Detailjadwalshift;
use App\Models\Gantishift;
use App\Models\Group; // Tambahkan import untuk Group
use App\Models\Harilibur;
use App\Models\Izindinas;
use App\Models\Jadwalkerja;
use App\Models\Jamkerja;
use App\Models\Presensi;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

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

        $data['cabang'] = $cabang;
        //Subquery Presensi
        $subqueryPresensi = Presensi::select(
            'hrd_presensi.id',
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
                    ->whereIn('hrd_karyawan.kode_dept', $dept_access)
                    ->groupBy('hrd_karyawan.kode_dept')
                    ->orderBy('hrd_karyawan.kode_dept')->get();
                $group = Karyawan::select('hrd_karyawan.kode_group', 'nama_group')
                    ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
                    ->whereIn('hrd_karyawan.kode_dept', $dept_access)
                    ->groupBy('hrd_karyawan.kode_group')
                    ->orderBy('hrd_karyawan.kode_group')->get();
            }
        } else {
            $departemen = Departemen::orderBy('kode_dept')->get();
            $group = Group::orderBy('kode_group')->get();
        }

        $data['departemen'] = $departemen;
        $data['group'] = $group;

        $query = Karyawan::query();
        $query->select(
            'presensi.id',
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



        if (!$user->hasRole($roles_access_all_karyawan) || $user->hasRole(['staff keuangan', 'manager keuangan', 'gm administrasi'])) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                if (auth()->user()->kode_cabang != 'PST') {
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    if ($user->hasRole(['staff keuangan'])) {
                        $query->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
                    } else if ($user->hasRole(['manager keuangan', 'gm administrasi'])) {
                        $query->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
                    } else {
                        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
                    }
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

        // if (auth()->user()->id == '86') {
        //     $query->whereIn('hrd_karyawan.kode_group', ['G19', 'G22', 'G23']);
        // } else if (auth()->user()->id == '87') {
        //     $query->whereNotIn('hrd_karyawan.kode_group', ['G19', 'G22', 'G23']);
        // }
        $query->where('status_aktif_karyawan', 1);
        $query->orderBy('nama_karyawan', 'asc');
        $karyawan = $query->paginate(50);
        $karyawan->appends($request->all());
        $data['karyawan'] = $karyawan;
        $data['tanggal'] = $tanggal;
        $data['roles_access_all_karyawan'] = $roles_access_all_karyawan;
        // if (!empty($request->tanggal)) {
        //
        //     dd($data['harilibur']);
        // }

        $data['dataliburnasional'] = getdataliburnasional($tanggal, $tanggal);
        $data['datadirumahkan'] = getdirumahkan($tanggal, $tanggal);
        $data['dataliburpengganti'] = getliburpengganti($tanggal, $tanggal);
        $data['dataminggumasuk'] = getminggumasuk($tanggal, $tanggal);
        $data['datatanggallimajam'] = gettanggallimajam($tanggal, $tanggal);
        // var_dump($data['dataliburnasional']);
        return view('presensi.index', $data);
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
        // $url = 'https://developer.fingerspot.io/api/get_attlog';
        // $data = '{"trans_id":"1", "cloud_id":"C2609075E3170B2C", "start_date":"' . $tanggal . '", "end_date":"' . $nextday . '"}';
        // $authorization = "Authorization: Bearer QNBCLO9OA0AWILQD";

        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        // $result = curl_exec($ch);
        // curl_close($ch);
        // $res = json_decode($result);
        // $datamesin1 = $res->data;
        $url = 'https://developer.fingerspot.io/api/get_attlog';

        $data = [
            "trans_id"   => "1",
            "cloud_id"   => "C2609075E3170B2C",
            "start_date" => $tanggal,
            "end_date"   => $nextday
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer QNBCLO9OA0AWILQD',
            'Accept: */*',
            'User-Agent: PostmanRuntime/7.36.3'
        ]);

        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);

        $res = json_decode($result);
        $datamesin1 = $res->data;

        $filtered_array = array_filter($datamesin1, function ($obj) use ($specific_value) {
            return $obj->pin == $specific_value;
        });


        //Mesin 2

        // $url = 'https://developer.fingerspot.io/api/get_attlog';
        // $data = '{"trans_id":"1", "cloud_id":"C268909557211236", "start_date":"' . $tanggal . '", "end_date":"' . $nextday . '"}';
        // $authorization = "Authorization: Bearer QNBCLO9OA0AWILQD";

        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        // $result2 = curl_exec($ch);
        // curl_close($ch);
        // $res2 = json_decode($result2);
        // $datamesin2 = $res2->data;

        $url = 'https://developer.fingerspot.io/api/get_attlog';

        $data = [
            "trans_id"   => "1",
            "cloud_id"   => "C268909557211236",
            "start_date" => $tanggal,
            "end_date"   => $nextday
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer QNBCLO9OA0AWILQD',
            'Accept: */*',
            'User-Agent: PostmanRuntime/7.36.3'
        ]);

        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);

        $res = json_decode($result);
        $datamesin2 = $res->data;

        $filtered_array_2 = array_filter($datamesin2, function ($obj) use ($specific_value) {
            return $obj->pin == $specific_value;
        });


        return view('presensi.getdatamesin', compact('filtered_array', 'filtered_array_2'));
    }




    public function updatefrommachine(Request $request, $pin, $status_scan)
    {

        function hari($hari)
        {
            $hari = date("D", strtotime($hari));

            switch ($hari) {
                case 'Sun':
                    $hari_ini = "Minggu";
                    break;

                case 'Mon':
                    $hari_ini = "Senin";
                    break;

                case 'Tue':
                    $hari_ini = "Selasa";
                    break;

                case 'Wed':
                    $hari_ini = "Rabu";
                    break;

                case 'Thu':
                    $hari_ini = "Kamis";
                    break;

                case 'Fri':
                    $hari_ini = "Jumat";
                    break;

                case 'Sat':
                    $hari_ini = "Sabtu";
                    break;

                default:
                    $hari_ini = "Tidak di ketahui";
                    break;
            }

            return $hari_ini;
        }
        $pin = Crypt::decrypt($pin);
        // echo $status_scan;
        // die;
        $status_scan = $status_scan % 2 == 0 ? 0 : 1;
        //echo $status_scan;
        //echo $pin . " " . $status_scan . " " . $scan_date;
        $scan = $request->scan_date;

        $tgl_presensi   = date("Y-m-d", strtotime($scan));
        $karyawan       = Karyawan::where('pin', $pin)->first();
        if ($karyawan == null) {
            return Redirect::back()->with(messageError('PIN Tidak Ditemukan'));
            $nik = "";
        } else {
            $nik = $karyawan->nik;
        }



        //Cek Perjalanan Dinas
        $cekperjalanandinas = Izindinas::whereRaw('"' . $tgl_presensi . '" >= dari')
            ->whereRaw('"' . $tgl_presensi . '" <= sampai')
            ->where('nik', $nik)
            ->first();
        if ($cekperjalanandinas != null) {
            $kode_cabang = $cekperjalanandinas->kode_cabang;
        } else {
            $kode_cabang = $karyawan->kode_cabang;
        }


        //Tanggal Sebelumnya
        $lastday = date('Y-m-d', strtotime('-1 day', strtotime($tgl_presensi)));

        $jam = $scan;

        //Cek Jadwal SHIFT

        $cekjadwalshift = Detailjadwalshift::join('hrd_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift', '=', 'hrd_jadwalshift.kode_jadwalshift')
            ->whereRaw('"' . $tgl_presensi . '" >= dari')
            ->whereRaw('"' . $tgl_presensi . '" <= sampai')
            ->where('nik', $nik)
            ->first();

        $cekgantishift = Gantishift::where('tanggal', $tgl_presensi)->where('nik', $nik)->first();

        if ($cekgantishift != null) {
            $kode_jadwal = $cekgantishift->kode_jadwal;
        } else if ($cekjadwalshift != null) {
            $kode_jadwal = $cekjadwalshift->kode_jadwal;
        } else if ($cekperjalanandinas != null) {
            $cekjadwaldinas = Jadwalkerja::where('nama_jadwal', 'NON SHIFT')
                ->where('kode_cabang', $cekperjalanandinas->kode_cabang)->first();
            $kode_jadwal = $cekjadwaldinas->kode_jadwal;
        } else {
            $kode_jadwal = $karyawan->kode_jadwal;
        }


        $ceklibur = Harilibur::where('kode_cabang', $kode_cabang)
            ->where('tanggal_limajam', $tgl_presensi)->count();
        if ($ceklibur > 0) {
            $hariini = "Sabtu";
        } else {
            $hariini = hari($tgl_presensi);
        }




        $jadwal = Detailjadwalkerja::join('hrd_jadwalkerja', 'hrd_jadwalkerja_detail.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal')
            ->where('hari', $hariini)->where('hrd_jadwalkerja_detail.kode_jadwal', $kode_jadwal)
            ->first();


        $jam_kerja = Jamkerja::where('kode_jam_kerja', $jadwal->kode_jam_kerja)->first();


        $lintashari  = $jam_kerja->lintashari;

        $cek = Presensi::where('tanggal', $tgl_presensi)->where('nik', $nik)->first();

        //dd($status_scan);
        //dd($cek);
        if ($status_scan == 0) {
            //dd($cek);
            if ($cek == null) {
                $data = [
                    'nik' => $nik,
                    'tanggal' => $tgl_presensi,
                    'jam_in' => $jam,
                    'kode_jadwal' => $kode_jadwal,
                    'kode_jam_kerja' => $jadwal->kode_jam_kerja,
                    'status' => 'h',
                ];

                $simpan = Presensi::create($data);
                if ($simpan) {
                    return Redirect::back()->with(messageSuccess('Presensi Berhasil Disimpan'));
                } else {
                    return Redirect::back()->with(messageError('Presensi Gagal Disimpan'));
                }
            } else {
                try {
                    $data_masuk = [
                        'jam_in' => $jam
                    ];
                    $update = Presensi::where('tanggal', $tgl_presensi)->where('nik', $nik)->update($data_masuk);
                    // echo "success|Terimkasih, Selamat Bekerja|in";
                    return Redirect::back()->with(messageSuccess('Presensi Berhasil Disimpan'));
                } catch (\Exception $e) {
                    dd($e);
                    return Redirect::back()->with(messageError('Presensi Gagal Disimpan'));
                }
            }
        } else {
            //Cek Absensi Kemarin
            $ceklastpresensi = Presensi::join('hrd_jamkerja', 'hrd_presensi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja')
                ->where('nik', $nik)->where('tanggal', $lastday)->first();
            //Cek Lintas Hari
            $last_lintashari = $ceklastpresensi != null  ? $ceklastpresensi->lintashari : "";
            $tgl_pulang_shift_3 = date("H:i", strtotime(($jam)));


            if (!empty($last_lintashari) && $tgl_pulang_shift_3 <= "08:00" || empty($last_lintashari) && $tgl_pulang_shift_3 <= "08:00") {
                $tgl_presensi = $lastday;
            }

            $cek = Presensi::where('tanggal', $tgl_presensi)->where('nik', $nik)->first();
            if ($cek == null) {
                $data = [
                    'nik' => $nik,
                    'tanggal' => $tgl_presensi,
                    'jam_out' => $jam,
                    'kode_jadwal' => $kode_jadwal,
                    'kode_jam_kerja' => $jadwal->kode_jam_kerja,
                    'status' => 'h',
                ];

                $simpan = Presensi::create($data);
                if ($simpan) {
                    // echo "success|Terimkasih, Hati Hati Di Jalan|out";
                    return Redirect::back()->with(messageSuccess('Presensi Berhasil Disimpan'));
                } else {
                    // echo "error|Maaf Gagal absen, Hubungi Tim It|out";
                    return Redirect::back()->with(messageError('Presensi Gagal Disimpan'));
                }
            } else {
                $data_masuk = [
                    'jam_out' => $jam
                ];
                $update = Presensi::where('tanggal', $tgl_presensi)->where('nik', $nik)->update($data_masuk);
                if ($update) {
                    // echo "success|Terimkasih, Hati Hati Di Jalan|out";
                    return Redirect::back()->with(messageSuccess('Presensi Berhasil Disimpan'));
                } else {
                    // echo "error|Maaf Gagal absen, Hubungi Tim It|out";
                    return Redirect::back()->with(messageError('Presensi Gagal Disimpan'));
                }
            }
        }
    }


    public function koreksipresensi(Request $request)
    {
        $data['karyawan'] = Karyawan::where('nik', $request->nik)
            ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->first();
        $data['presensi'] = Presensi::where('nik', $request->nik)->where('tanggal', $request->tanggal)->first();
        $data['jadwal'] = Jadwalkerja::where('kode_cabang', $data['karyawan']->kode_cabang)->get();


        return view('presensi.koreksipresensi', $data);
    }

    public function updatepresensi(Request $request, $id_presensi)
    {
        $id_presensi = Crypt::decrypt($id_presensi);
        $presensi = Presensi::where('id', $id_presensi)->first();
        $tanggal = $presensi->tanggal;
        $tanggal_pulang  = in_array($request->kode_jam_kerja, ['JK08', 'JK25']) ? date('Y-m-d', strtotime($tanggal . ' + 1 days')) : $tanggal;
        $jam_in = !empty($request->jam_in) ? $tanggal . ' ' . $request->jam_in : null;

        $jam_out = !empty($request->jam_out) ? $tanggal_pulang . ' ' . $request->jam_out : null;

        $user = User::findOrFail(auth()->user()->id);

        //dd($user->hasRole(['asst. manager hrd', 'super admin', 'spv presensi']));
        try {
            if ($user->hasRole(['asst. manager hrd', 'super admin', 'spv presensi'])) {
                Presensi::where('id', $id_presensi)->update([
                    'kode_jadwal' => $request->kode_jadwal,
                    'kode_jam_kerja' => $request->kode_jam_kerja,
                    'jam_in' => $jam_in,
                    'jam_out' => $jam_out,
                ]);
            } else {
                Presensi::where('id', $id_presensi)->update([
                    'kode_jadwal' => $request->kode_jadwal,
                    'kode_jam_kerja' => $request->kode_jam_kerja,
                ]);
            }

            return Redirect::back()->with(messageSuccess('Presensi Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    function getjamkerja(Request $request)
    {
        $jamkerja = Detailjadwalkerja::select('hrd_jadwalkerja_detail.kode_jam_kerja', 'jam_masuk', 'jam_pulang', 'total_jam')
            ->join('hrd_jamkerja', 'hrd_jadwalkerja_detail.kode_jam_kerja', 'hrd_jamkerja.kode_jam_kerja')
            ->join('hrd_jadwalkerja', 'hrd_jadwalkerja_detail.kode_jadwal', 'hrd_jadwalkerja.kode_jadwal')
            ->where('hrd_jadwalkerja.kode_jadwal', $request->kode_jadwal)
            ->groupBy('kode_jam_kerja', 'jam_masuk', 'jam_pulang', 'total_jam')
            ->get();

        echo "<option value=''>Pilih Jam Kerja</option>";
        foreach ($jamkerja as $j) {
            echo "<option value='" . $j->kode_jam_kerja . "' " . ($j->kode_jam_kerja == $request->kode_jam_kerja ? 'selected' : '') . ">" . $j->jam_masuk . " - " . $j->jam_pulang . " ( " . $j->total_jam . " ) " .   "</option>";
        }
    }


    public function presensikaryawan(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) ?? [];
        $roles_access_all_karyawan = config('global.roles_access_all_karyawan');

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
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

            ->whereBetween('hrd_presensi.tanggal', [$request->dari, $request->sampai]);

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

        $data['departemen'] = $departemen;
        $data['group'] = $group;

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
        // if (!$user->hasRole($roles_access_all_karyawan)) {
        //     if ($user->hasRole('regional sales manager')) {
        //         $query->where('cabang.kode_regional', auth()->user()->kode_regional);
        //     } else {
        //         if (auth()->user()->kode_cabang != 'PST') {
        //             $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
        //         } else {
        //             $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
        //         }
        //     }
        // }




        if (!empty($request->kode_cabang_search)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->kode_dept)) {
            $query->where('hrd_karyawan.kode_dept', $request->kode_dept);
        }
        if (!empty($request->kode_group)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group);
        }

        $query->where('hrd_karyawan.nik', $request->nik);
        $query->orderBy('nama_karyawan', 'asc');
        $data['karyawan'] = $query->get();


        $qkaryawan = Karyawan::query();
        if (!$user->hasRole($roles_access_all_karyawan) || $user->hasRole(['staff keuangan', 'manager keuangan', 'gm administrasi'])) {
            if ($user->hasRole('regional sales manager')) {
                $qkaryawan->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                if (auth()->user()->kode_cabang != 'PST') {
                    $qkaryawan->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                } else {

                    if ($user->hasRole(['staff keuangan'])) {
                        $qkaryawan->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
                    } else if ($user->hasRole(['manager keuangan', 'gm administrasi'])) {
                        $qkaryawan->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
                    } else {
                        $qkaryawan->whereIn('hrd_karyawan.kode_dept', $dept_access);
                    }
                }
            }
        }

        if (auth()->user()->id == '86') {
            $qkaryawan->whereIn('hrd_karyawan.kode_group', ['G19', 'G20', 'G21', 'G22', 'G23']);
        } else if (auth()->user()->id == '87') {
            $qkaryawan->whereNotIn('hrd_karyawan.kode_group', ['G19', 'G22', 'G23']);
        }
        $qkaryawan->orderBy('nama_karyawan');
        $data['listkaryawan'] = $qkaryawan->get();

        return view('presensi.presensikaryawan',  $data);
    }

    public function show($id, $status)
    {
        $presensi = Presensi::where('id', $id)
            ->join('hrd_karyawan', 'hrd_presensi.nik', '=', 'hrd_karyawan.nik')
            ->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $lokasi = explode(',', $presensi->lokasi_cabang);
        $data['latitude'] = $lokasi[0];
        $data['longitude'] = $lokasi[1];
        $data['presensi'] = $presensi;
        $data['status'] = $status;

        return view('presensi.show', $data);
    }
}

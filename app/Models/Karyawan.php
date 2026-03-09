<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
    use HasFactory;
    protected $table = "hrd_karyawan";
    protected $primaryKey = "nik";
    protected $guarded = [];
    public $incrementing = false;


    function getKaryawanpenilaian()
    {
        $user = User::findOrFail(auth()->user()->id);
        $query = Karyawan::query();

        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');

        if (!$user->hasRole(['super admin', 'asst. manager hrd', 'spv presensi'])) {

            $query->where(function ($access) use ($user) {
                $access->where(function ($q) use ($user) {
                    $dept_access = json_decode($user->dept_access, true) ?? [];
                    $cabang_access = json_decode($user->cabang_access, true) ?? [];
                    // $jabatan_access = json_decode($user->jabatan_access, true) ?? [];

                    // 1. Branch Access (Mandatory)
                    if (!in_array('all', $cabang_access)) {
                        if (!empty($cabang_access)) {
                            $q->whereIn('hrd_karyawan.kode_cabang', $cabang_access);
                        } else {
                            // Default logic if cabang_access is empty and not regional
                            if (empty($user->kode_regional) || $user->kode_regional == 'R00') {
                                if ($user->kode_cabang != 'PST') {
                                    $q->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
                                }
                            }
                        }
                    }

                    // 2. Department Access (Mandatory)
                    if (!in_array('all', $dept_access)) {
                        $q->whereIn('hrd_karyawan.kode_dept', $dept_access);
                    }

                    // 3. Job Position Access (AND - Mandatory)
                    //                if (!in_array('all', $jabatan_access)) {
                    //                    $access->whereIn('hrd_karyawan.kode_jabatan', $jabatan_access);
                    //                }

                    // 4. Employee Access (NIK)
                    $karyawan_access = json_decode($user->karyawan_access, true) ?? [];
                    if (!in_array('all', $karyawan_access)) {
                        $q->whereIn('hrd_karyawan.nik', $karyawan_access);
                    }

                    // 5. Group Access (OR - Optional)
                    $group_access = json_decode($user->group_access, true) ?? [];
                    if (!empty($group_access)) {
                        $q->whereIn('hrd_karyawan.kode_group', $group_access);
                    }

                    // 6. Regional (AND)
                    if (!empty($user->kode_regional) && $user->kode_regional != 'R00') {
                        $q->where('cabang.kode_regional', $user->kode_regional);
                    }
                });

                if ($user->id == 82) {
                    $access->orWhere('hrd_karyawan.kode_jabatan', 'J31');
                }
            });

            $query->where('status_aktif_karyawan', 1);
            $query->where('status_karyawan', 'K');
        }

        $query->orderBy('nama_karyawan');
        return $query;
    }




    function getKaryawan($nik)
    {
        $query = Karyawan::where('nik', $nik)
            ->select('hrd_karyawan.*', 'nama_jabatan', 'hrd_jabatan.kategori', 'nama_dept', 'nama_cabang', 'kode_regional')
            ->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->join('hrd_klasifikasi', 'hrd_karyawan.kode_klasifikasi', '=', 'hrd_klasifikasi.kode_klasifikasi')
            ->join('hrd_status_kawin', 'hrd_karyawan.kode_status_kawin', '=', 'hrd_karyawan.kode_status_kawin')
            ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
            ->first();

        return $query;
    }

    public function getkaryawanpresensi()
    {
        $user = User::findOrFail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $role_access_full = ['super admin', 'direktur'];
        $query = Karyawan::query();

        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');

        if (!in_array($role, $role_access_full)) {

            $query->where(function ($access) use ($user) {
                $dept_access = json_decode($user->dept_access, true) ?? [];
                $cabang_access = json_decode($user->cabang_access, true) ?? [];
                // $jabatan_access = json_decode($user->jabatan_access, true) ?? [];
                $group_access = json_decode($user->group_access, true) ?? [];
                $karyawan_access = json_decode($user->karyawan_access, true) ?? [];

                // 1. Employee Access (NIK)
                if (!in_array('all', $karyawan_access)) {
                    $access->whereIn('hrd_karyawan.nik', $karyawan_access);
                }

                // 2. Group Access
                if (!empty($group_access)) {
                    $access->whereIn('hrd_karyawan.kode_group', $group_access);
                }

                // 2. Branch Access (Mandatory)
                if (!in_array('all', $cabang_access)) {
                    if (!empty($cabang_access)) {
                        $access->whereIn('hrd_karyawan.kode_cabang', $cabang_access);
                    } else {
                        // Default logic if cabang_access is empty and not regional
                        if (empty($user->kode_regional) || $user->kode_regional == 'R00') {
                            if ($user->kode_cabang != 'PST') {
                                $access->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
                            }
                        }
                    }
                }

                // 3. Department Access (Mandatory)
                if (!in_array('all', $dept_access)) {
                    $access->whereIn('hrd_karyawan.kode_dept', $dept_access);
                }

                // 4. Job Position Access (AND - Mandatory)
                // if (!in_array('all', $jabatan_access)) {
                //     $access->whereIn('hrd_karyawan.kode_jabatan', $jabatan_access);
                // }

                // 5. Regional (AND)
                if (!empty($user->kode_regional) && $user->kode_regional != 'R00') {
                    $access->where('cabang.kode_regional', $user->kode_regional);
                }
            });
        }

        $query->where('status_aktif_karyawan', 1);
        $query->orderBy('nama_karyawan');
        return $query;
    }

    function getRekapstatuskaryawan()
    {
        $query = Karyawan::query();
        $query->select(
            DB::raw("SUM(IF(status_karyawan = 'K', 1, 0)) as jml_kontrak"),
            DB::raw("SUM(IF(status_karyawan = 'T', 1, 0)) as jml_tetap"),
            DB::raw("SUM(IF(status_karyawan = 'O', 1, 0)) as jml_outsourcing"),
            DB::raw("SUM(IF(status_aktif_karyawan = '1', 1, 0)) as jml_aktif"),
        );
        return $query->first();
    }

    public function getRekapkontrak($kategori)
    {
        $bulanini = date("m");
        $tahunini = date("Y");
        $start_date_bulanini = $tahunini . "-" . $bulanini . "-01";
        $end_date_bulanini = date("Y-m-t", strtotime($start_date_bulanini));
        //Jika Bulan + 1 Lebih dari 12 Maka Bulan + 1 - 12 dan Tahun + 1 Jika Tidak Maka Bulan Depan = Bulan + 1
        $bulandepan = date("m") + 1 > 12 ? (date("m") + 1) - 12 : date("m") + 1;
        $tahunbulandepan = date("m") + 1 > 12 ? $tahunini + 1 : $tahunini;
        $start_date_bulandepan = $tahunbulandepan . "-" . $bulandepan . "-01";
        $end_date_bulandepan = date("Y-m-t", strtotime($start_date_bulandepan));

        //Jika Bulan + 2 Lebih dari 12 Maka Bulan + 2 - 12 dan Tahun + 1 Jika Tidak Maka Bulan Depan = Bulan + 2
        //Sampel Jika Bulan = Desember (12) Maka Dua bulan adalah Februari (2) (12+2-12);
        $duabulan = date("m") + 2 > 12 ? (date("m") + 2) - 12 : date("m") + 2;
        $tahunduabulan = date("m") + 2 > 12 ? $tahunini + 1 : $tahunini;
        $start_date_duabulan = $tahunduabulan . "-" . $duabulan . "-01";
        $end_date_duabulan = date("Y-m-t", strtotime($start_date_duabulan));
        $query = Kontrakkaryawan::query();
        $query->select('hrd_kontrak.no_kontrak', 'hrd_kontrak.nik', 'hrd_kontrak.sampai', 'hrd_karyawan.nama_karyawan', 'nama_jabatan', 'hrd_karyawan.kode_dept', 'hrd_karyawan.kode_cabang', 'nama_cabang');
        $query->join('hrd_karyawan', 'hrd_kontrak.nik', '=', 'hrd_karyawan.nik');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        if ($kategori == 0) { // Lewat Jatuh Tempo
            $query->where('sampai', '<', $start_date_bulanini);
        } else if ($kategori == 1) { // Jatuh Tempo Bulan Ini
            $query->whereBetween('sampai', [$start_date_bulanini, $end_date_bulanini]);
        } else if ($kategori == 2) { // Jatuh Tempo Bulan Depan
            $query->whereBetween('sampai', [$start_date_bulandepan, $end_date_bulandepan]);
        } else if ($kategori == 3) { // Jatuh Tempo Dua Bulan
            $query->whereBetween('sampai', [$start_date_duabulan, $end_date_duabulan]);
        }
        $query->where('status_aktif_karyawan', 1);
        $query->where('status_karyawan', 'K');
        $query->where('status_kontrak', 1);
        $query->orderBy('hrd_kontrak.sampai');
        $query->orderBy('hrd_karyawan.nama_karyawan');
        return $query->get();
    }


    function getRekapkaryawancabang()
    {

        $query = Karyawan::query();
        $query->select('hrd_karyawan.kode_cabang', 'nama_cabang', DB::raw("COUNT(nik) as jml_karyawancabang"));
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->where('status_aktif_karyawan', 1);
        $query->groupBy('hrd_karyawan.kode_cabang', 'cabang.nama_cabang');
        $query->orderBy('hrd_karyawan.kode_cabang');
        return $query->get();
    }

    function getKaryawanUlangTahun()
    {
        $query = Karyawan::query();
        $query->select('hrd_karyawan.nik', 'hrd_karyawan.nama_karyawan', 'hrd_karyawan.tanggal_lahir', 'hrd_karyawan.foto', 'hrd_karyawan.jenis_kelamin', 'nama_jabatan', 'nama_cabang', 'hrd_karyawan.kode_dept', 'nama_dept');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->where('status_aktif_karyawan', 1);
        $query->whereNotNull('tanggal_lahir');
        $query->whereRaw('DAY(tanggal_lahir) = DAY(CURDATE())');
        $query->whereRaw('MONTH(tanggal_lahir) = MONTH(CURDATE())');
        $query->orderBy('hrd_karyawan.nama_karyawan');
        return $query->get();
    }
}

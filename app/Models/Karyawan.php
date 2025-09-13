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
        $query = Karyawan::query();
        $user = User::findorfail(auth()->user()->id);
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];

        $query = Karyawan::query();
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        //Direktur --> Tampilkan General Manger
        if (!$user->hasRole(['super admin', 'asst. manager hrd'])) {


            if ($user->hasRole('direktur')) {
                $query->where('hrd_karyawan.kode_jabatan', 'J02');
            } elseif ($user->hasRole('gm operasional')) {
                $query->whereIn('hrd_karyawan.kode_dept', ['PDQ', 'PMB', 'GDG', 'MTC', 'PRD', 'GAF', 'HRD']);
                $query->whereIn('hrd_karyawan.kode_jabatan', ['J04', 'J05', 'J06']);
            } else if ($user->hasRole('gm administrasi')) { //GM ADMINISTRASI
                $query->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
                $query->whereIn('hrd_karyawan.kode_jabatan', ['J04', 'J05', 'J06', 'J08']);
            } elseif ($user->hasRole('gm marketing')) { //GM MARKETING
                $query->whereIn('hrd_karyawan.kode_dept', ['MKT']);
                $query->whereIn('hrd_karyawan.kode_jabatan', ['J03', 'J07']);
            } else if ($user->hasRole('regional sales manager')) { //REG. SALES MANAGER
                $query->where('hrd_karyawan.kode_dept', 'MKT');
                $query->where('hrd_karyawan.kode_jabatan', 'J07');
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else if ($user->hasRole('regional operation manager')) { //REG. OPERATION MANAGER
                // $query->where('hrd_karyawan.kode_dept', 'AKT');
                $query->where('hrd_karyawan.kode_jabatan', 'J08');
            } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
                $query->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
                $query->where('hrd_karyawan.kode_jabatan', 'J08');
            } else {
                if ($user->hasRole('sales marketing manager')) {
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                    $query->whereNot('hrd_karyawan.kode_jabatan', 'J07');
                } else {
                    $query->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
                    $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                    $query->where('hrd_jabatan.kategori', 'NM');
                }
            }


            $query->where('status_aktif_karyawan', 1);
            $query->where('status_karyawan', 'K');

            if ($user->hasRole('gm operasional')) {
                $query->orWhere('hrd_karyawan.kode_dept', 'PDQ');
                $query->where('status_aktif_karyawan', 1);
                $query->where('status_karyawan', 'K');
            } else if ($user->hasRole('gm administrasi')) {
                $query->orwhereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
                $query->where('hrd_jabatan.kategori', 'NM');
                $query->where('hrd_karyawan.kode_cabang', 'PST');
                $query->where('status_aktif_karyawan', 1);
                $query->where('status_karyawan', 'K');
            } else if ($user->hasRole('regional sales manager')) {
                $query->orWhere('hrd_jabatan.kategori', 'NM');
                $query->where('hrd_karyawan.kode_dept', 'MKT');
                $query->where('status_aktif_karyawan', 1);
                $query->where('status_karyawan', 'K');
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else if ($user->hasRole('regional operation manager')) {
                $query->orWhere('hrd_karyawan.kode_dept', 'AKT');
                $query->where('hrd_jabatan.kategori', 'NM');
                $query->where('hrd_karyawan.kode_cabang', 'PST');
                $query->where('status_aktif_karyawan', 1);
                $query->where('status_karyawan', 'K');
            } else if ($user->hasRole('manager keuangan')) {
                $query->orwhereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
                $query->where('hrd_jabatan.kategori', 'NM');
                $query->where('hrd_karyawan.kode_cabang', 'PST');
                $query->where('status_aktif_karyawan', 1);
                $query->where('status_karyawan', 'K');
            }
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
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();


        $query = Karyawan::query();
        if (!in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
            $query->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
            if ($user->kode_cabang == 'PST') {
                $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            }
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
}

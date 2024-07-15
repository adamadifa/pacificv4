<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            $query->where('hrd_karyawan.kode_dept', 'AKT');
            $query->where('hrd_karyawan.kode_jabatan', 'J08');
        } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
            $query->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
            $query->where('hrd_karyawan.kode_jabatan', 'J08');
        } else {
            $query->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
            $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
            $query->where('hrd_jabatan.kategori', 'NM');
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

        $query->orderBy('nama_karyawan');
        return $query;
    }


    function getKaryawan($nik)
    {
        $query = Karyawan::where('nik', $nik)
            ->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->join('hrd_klasifikasi', 'hrd_karyawan.kode_klasifikasi', '=', 'hrd_klasifikasi.kode_klasifikasi')
            ->join('hrd_status_kawin', 'hrd_karyawan.kode_status_kawin', '=', 'hrd_karyawan.kode_status_kawin')
            ->join('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group')
            ->first();

        return $query;
    }
}

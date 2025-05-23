<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Kontrakkaryawan extends Model
{
    use HasFactory;
    protected $table = "hrd_kontrak";
    protected $primaryKey = "no_kontrak";
    protected $guarded = [];
    public $incrementing = false;

    function getKontrak($no_kontrak = null, Request $request = null)
    {
        $query = Kontrakkaryawan::query();
        $query->select(
            'hrd_kontrak.*',
            'hrd_karyawan.nik',
            'hrd_karyawan.no_ktp',
            'hrd_karyawan.nama_karyawan',
            'hrd_karyawan.tempat_lahir',
            'hrd_karyawan.tanggal_lahir',
            'hrd_karyawan.alamat as alamat_karyawan',
            'hrd_jabatan.nama_jabatan',
            'hrd_jabatan.kategori as kategori_jabatan',
            'hrd_jabatan.alias as alias_jabatan',
            'hrd_departemen.nama_dept',
            'cabang.nama_cabang',
            'cabang.nama_pt',
            'cabang.alamat_cabang',
            'hrd_penilaian.masa_kontrak',
            'cabang.email',
        );
        $query->join('hrd_karyawan', 'hrd_kontrak.nik', '=', 'hrd_karyawan.nik');
        $query->leftJoin('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->leftJoin('hrd_departemen', 'hrd_kontrak.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->leftJoin('cabang', 'hrd_kontrak.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('hrd_kontrak_penilaian', 'hrd_kontrak.no_kontrak', '=', 'hrd_kontrak_penilaian.no_kontrak');
        $query->leftJoin('hrd_penilaian', 'hrd_kontrak_penilaian.kode_penilaian', '=', 'hrd_penilaian.kode_penilaian');
        if ($no_kontrak) {
            $query->where('hrd_kontrak.no_kontrak', $no_kontrak);
        }

        if (!empty($request)) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('hrd_kontrak.tanggal', [$request->dari, $request->sampai]);
            }

            if (!empty($request->nama_karyawan_search)) {
                $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan_search . '%');
            }

            if (!empty($request->kode_cabang_search)) {
                $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_search);
            }

            if (!empty($request->kode_dept_search)) {
                $query->where('hrd_karyawan.kode_dept', $request->kode_dept_search);
            }
        }
        $query->orderBy('hrd_kontrak.tanggal', 'desc');
        return $query;
    }
}

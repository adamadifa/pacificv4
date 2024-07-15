<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Penilaiankaryawan extends Model
{
    use HasFactory;

    protected $table = "hrd_penilaian";
    protected $primaryKey = "kode_penilaian";
    protected $guarded = [];
    public $incrementing = false;


    function getPenilaiankaryawan($kode_penilaian = null, Request $request = null)
    {

        $query = Penilaiankaryawan::query();
        $query->select('hrd_penilaian.*', 'nama_karyawan', 'nama_jabatan', 'status', 'hrd_jabatan.alias as alias_jabatan');
        $query->join('hrd_karyawan', 'hrd_penilaian.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_penilaian.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('hrd_penilaian.tanggal', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nama_karyawan_search)) {
            $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan_search . '%');
        }


        if (!empty($kode_penilaian)) {
            $query->where('hrd_penilaian.kode_penilaian', $kode_penilaian);
        }
        $query->orderBy('hrd_penilaian.tanggal', 'desc');
        $query->orderBy('hrd_penilaian.status');
        return $query;
    }
}

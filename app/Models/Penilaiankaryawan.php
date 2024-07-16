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
        $user = User::findorfail(auth()->user()->id);
        $query = Penilaiankaryawan::query();
        $query->select('hrd_penilaian.*', 'nama_karyawan', 'nama_jabatan', 'status', 'hrd_jabatan.alias as alias_jabatan');
        $query->join('hrd_karyawan', 'hrd_penilaian.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_penilaian.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('cabang', 'hrd_penilaian.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('hrd_departemen', 'hrd_penilaian.kode_dept', '=', 'hrd_departemen.kode_dept');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('hrd_penilaian.tanggal', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nama_karyawan_search)) {
            $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan_search . '%');
        }


        if (!empty($kode_penilaian)) {
            $query->where('hrd_penilaian.kode_penilaian', $kode_penilaian);
        }

        if (!$user->hasRole('super admin')) {
            if ($user->hasRole('gm operasional')) {
                $query->whereIn('hrd_karyawan.kode_dept', ['PDQ', 'PMB', 'GDG', 'MTC', 'PRD', 'GAF', 'HRD']);
            } else if ($user->hasRole('gm administrasi')) { //GM ADMINISTRASI
                $query->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
            } elseif ($user->hasRole('gm marketing')) { //GM MARKETING
                $query->whereIn('hrd_karyawan.kode_dept', ['MKT']);
            } else if ($user->hasRole('regional sales manager')) { //REG. SALES MANAGER
                $query->where('hrd_karyawan.kode_dept', 'MKT');
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else if ($user->hasRole('regional operation manager')) { //REG. OPERATION MANAGER
                $query->where('hrd_karyawan.kode_dept', 'AKT');
            } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
                $query->whereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
            } else {
                $query->where('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
                $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                $query->where('hrd_jabatan.kategori', 'NM');
            }
            $query->where('hrd_penilaian.status', '1');
        }
        $query->orderBy('hrd_penilaian.tanggal', 'desc');
        $query->orderBy('hrd_penilaian.status');
        return $query;
    }
}

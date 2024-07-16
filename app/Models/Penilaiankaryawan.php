<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        //Cek Role User
        $role = $user->getRoleNames()->first();


        $query = Penilaiankaryawan::query();
        $query->select('hrd_penilaian.*', 'nama_karyawan', 'nama_jabatan', 'hrd_jabatan.alias as alias_jabatan', 'nama_dept', 'roles.name as posisi_ajuan');



        $query->join('hrd_karyawan', 'hrd_penilaian.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_penilaian.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('cabang', 'hrd_penilaian.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('hrd_departemen', 'hrd_penilaian.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->leftJoin('hrd_penilaian_disposisi as disposisi', function ($join) {
            $join->on('hrd_penilaian.kode_penilaian', '=', 'disposisi.kode_penilaian')
                ->whereRaw('disposisi.kode_disposisi IN (SELECT MAX(kode_disposisi) FROM hrd_penilaian_disposisi GROUP BY kode_penilaian)');
        });

        $query->leftJoin('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
        $query->leftJoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
        $query->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id');



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

            if ($user->hasRole('gm operasional')) {
                $query->orwhereIn('hrd_karyawan.kode_dept', ['PDQ', 'PMB', 'GDG', 'MTC', 'PRD', 'GAF', 'HRD']);
            } else if ($user->hasRole('gm administrasi')) { //GM ADMINISTRASI
                $query->orwhereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
            } elseif ($user->hasRole('gm marketing')) { //GM MARKETING
                $query->orwhereIn('hrd_karyawan.kode_dept', ['MKT']);
            } else if ($user->hasRole('regional sales manager')) { //REG. SALES MANAGER
                $query->orwhere('hrd_karyawan.kode_dept', 'MKT');
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else if ($user->hasRole('regional operation manager')) { //REG. OPERATION MANAGER
                $query->orwhere('hrd_karyawan.kode_dept', 'AKT');
            } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
                $query->orwhereIn('hrd_karyawan.kode_dept', ['AKT', 'KEU']);
            } else {
                $query->orwhere('hrd_karyawan.kode_dept', auth()->user()->kode_dept);
                $query->where('hrd_karyawan.kode_cabang', auth()->user()->kode_cabang);
                $query->where('hrd_jabatan.kategori', 'NM');
            }
            $query->WhereIn('hrd_penilaian.kode_penilaian', function ($query) use ($user) {
                $query->select('disposisi.kode_penilaian');
                $query->from('hrd_penilaian_disposisi as disposisi');
                $query->join('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
                $query->join('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
                $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');

                $query->join('users as pengirim', 'disposisi.id_pengirim', '=', 'pengirim.id');
                $query->join('model_has_roles as model_has_roles_pengirim', 'pengirim.id', '=', 'model_has_roles_pengirim.model_id');
                $query->join('roles as roles_pengirim', 'model_has_roles_pengirim.role_id', '=', 'roles_pengirim.id');

                $query->where('roles.name', $user->getRoleNames()->first());
                $query->orWhere('roles_pengirim.name', $user->getRoleNames()->first());
            });
        }
        $query->orderBy('hrd_penilaian.tanggal', 'desc');
        $query->orderBy('hrd_penilaian.status');
        return $query;
    }
}

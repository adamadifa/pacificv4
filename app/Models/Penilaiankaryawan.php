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
        $user = User::findOrFail(auth()->user()->id);
        $query = Penilaiankaryawan::query();

        // 1. SELECT & JOINS
        $query->select(
            'hrd_penilaian.*',
            'hrd_karyawan.nama_karyawan',
            'hrd_karyawan.foto',
            'hrd_karyawan.jenis_kelamin',
            'hrd_jabatan.nama_jabatan',
            'hrd_jabatan.alias as alias_jabatan',
            'hrd_jabatan.kategori as kategori_jabatan',
            'hrd_departemen.nama_dept',
            'cabang.nama_cabang',
            'cabang.kode_regional',
            'hrd_group.nama_group',
            'hrd_kontrak_penilaian.no_kontrak as no_kontrak_baru',
            'hrd_kesepakatanbersama.no_kb',
            'roles.name as posisi_ajuan_name'
        );

        $query->join('hrd_karyawan', 'hrd_penilaian.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_penilaian.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('cabang', 'hrd_penilaian.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('hrd_departemen', 'hrd_penilaian.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->leftJoin('hrd_group', 'hrd_karyawan.kode_group', '=', 'hrd_group.kode_group');
        $query->leftJoin('hrd_kontrak_penilaian', 'hrd_penilaian.kode_penilaian', '=', 'hrd_kontrak_penilaian.kode_penilaian');
        $query->leftJoin('hrd_kesepakatanbersama', 'hrd_penilaian.kode_penilaian', '=', 'hrd_kesepakatanbersama.kode_penilaian');
        $query->leftJoin('roles', 'hrd_penilaian.posisi_ajuan', '=', 'roles.id');

        // 2. DATA ACCESS RESTRICTIONS
        if (!$user->hasRole(['super admin', 'asst. manager hrd', 'spv presensi'])) {

            $query->where(function ($access) use ($user) {
                $dept_access = json_decode($user->dept_access, true) ?? [];
                $cabang_access = json_decode($user->cabang_access, true) ?? [];
                $jabatan_access = json_decode($user->jabatan_access, true) ?? [];

                // a. Branch Access (Mandatory)
                if (!in_array('all', $cabang_access)) {
                    if (!empty($cabang_access)) {
                        $access->whereIn('hrd_penilaian.kode_cabang', $cabang_access);
                    } else {
                        // Default logic if cabang_access is empty and not regional
                        if (empty($user->kode_regional) || $user->kode_regional == 'R00') {
                            $access->where('hrd_penilaian.kode_cabang', $user->kode_cabang);
                        }
                    }
                }

                // b. Department Access (Mandatory)
                if (!in_array('all', $dept_access)) {
                    $access->whereIn('hrd_penilaian.kode_dept', $dept_access);
                }

                // c. Explicit Jabatan Access (AND - Mandatory)
                if (!in_array('all', $jabatan_access)) {
                    $access->whereIn('hrd_penilaian.kode_jabatan', $jabatan_access);
                }

                // d. Employee Access (NIK)
                $karyawan_access = json_decode($user->karyawan_access, true) ?? [];
                if (!in_array('all', $karyawan_access)) {
                    $access->whereIn('hrd_penilaian.nik', $karyawan_access);
                }

                // e. Group Access (OR - Optional)
                $group_access = json_decode($user->group_access, true) ?? [];
                if (!empty($group_access)) {
                    $access->whereIn('hrd_karyawan.kode_group', $group_access);
                }

                // f. Regional Access (AND)
                if (!empty($user->kode_regional) && $user->kode_regional != 'R00') {
                    $access->where('cabang.kode_regional', $user->kode_regional);
                }
            });
        }

        // 3. REQUEST FILTERS
        if ($kode_penilaian) {
            $query->where('hrd_penilaian.kode_penilaian', $kode_penilaian);
        }

        if ($request) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('hrd_penilaian.tanggal', [$request->dari, $request->sampai]);
            }
            if (!empty($request->nama_karyawan_search)) {
                $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan_search . '%');
            }
            if (!empty($request->status)) {
                $status_map = ['pending' => '0', 'disetujui' => '1'];
                if (isset($status_map[$request->status])) {
                    $query->where('hrd_penilaian.status', $status_map[$request->status]);
                    if ($request->status == 'pending') {
                        if (!$user->hasRole(['super admin', 'asst. manager hrd', 'spv presensi'])) {
                            $user_role_ids = $user->roles->pluck('id')->toArray();
                            $query->whereIn('hrd_penilaian.posisi_ajuan', $user_role_ids);
                        }
                    }
                }
            }
            if (!empty($request->posisi_ajuan)) {
                $query->where('roles.name', $request->posisi_ajuan);
            }
            if (!empty($request->kode_cabang_search)) {
                $query->where('hrd_penilaian.kode_cabang', $request->kode_cabang_search);
            }
            if (!empty($request->kode_dept_search)) {
                $query->where('hrd_penilaian.kode_dept', $request->kode_dept_search);
            }
        }

        $query->orderBy('hrd_penilaian.status', 'asc');
        $query->orderBy('hrd_penilaian.tanggal', 'desc');

        return $query;
    }
}

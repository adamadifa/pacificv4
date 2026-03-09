<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Izindinas extends Model
{
    use HasFactory;
    protected $table = "hrd_izindinas";
    protected $primaryKey = "kode_izin_dinas";
    protected $guarded = [];
    public $incrementing  = false;

    function getIzindinas($kode_izin_dinas = null, Request $request = null, $cekPending = false)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $role_access_full = ['super admin', 'direktur'];
        $level_hrd = config('presensi.approval.level_hrd');

        $query = Izindinas::query();
        $query->select(
            'hrd_izindinas.*',
            'hrd_karyawan.nama_karyawan',
            'hrd_karyawan.kode_jabatan',
            'hrd_karyawan.kode_dept',
            'hrd_jabatan.nama_jabatan',
            'hrd_jabatan.kategori as kategori_jabatan',
            'cabang.nama_cabang',
            'hrd_departemen.nama_dept',
            'cabang.kode_regional'
        );
        $query->join('hrd_karyawan', 'hrd_izindinas.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_izindinas.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_izindinas.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_izindinas.kode_cabang', '=', 'cabang.kode_cabang');


        // Data Access Restrictions
        if (!in_array($role, $role_access_full)) {
            $query->where(function ($access) use ($user) {
                $dept_access = json_decode($user->dept_access, true) ?? [];
                $cabang_access = json_decode($user->cabang_access, true) ?? [];
                $jabatan_access = json_decode($user->jabatan_access, true) ?? [];
                $group_access = json_decode($user->group_access, true) ?? [];

                // Group Access
                if (!empty($group_access)) {
                    $access->whereIn('hrd_karyawan.kode_group', $group_access);
                }

                // Branch Access
                if (!in_array('all', $cabang_access)) {
                    if (!empty($cabang_access)) {
                        $access->whereIn('hrd_izindinas.kode_cabang', $cabang_access);
                    } else {
                        if (empty($user->kode_regional) || $user->kode_regional == 'R00') {
                            $access->where('hrd_izindinas.kode_cabang', $user->kode_cabang);
                        }
                    }
                }

                // Department Access
                if (!in_array('all', $dept_access)) {
                    $access->whereIn('hrd_izindinas.kode_dept', $dept_access);
                }

                // Jabatan Access
                if (!in_array('all', $jabatan_access)) {
                    $access->whereIn('hrd_izindinas.kode_jabatan', $jabatan_access);
                }

                // Regional Access
                if (!empty($user->kode_regional) && $user->kode_regional != 'R00') {
                    $access->where('cabang.kode_regional', $user->kode_regional);
                }
            });
        }

        // Feature Specific Logic
        if (!empty($kode_izin_dinas)) {
            $query->where('hrd_izindinas.kode_izin_dinas', $kode_izin_dinas);
        }

        if ($cekPending) {
            if (in_array($role, $level_hrd)) {
                $query->where('hrd_izindinas.head', '1');
                $query->where('hrd_izindinas.hrd', 0);
            } else if ($role == 'direktur') {
                $query->where('forward_to_direktur', '1');
                $query->where('direktur', 0);
            } else {
                $query->where('hrd_izindinas.head', '0');
            }
        } else {
            if ($role == 'direktur') {
                $query->where('hrd_izindinas.forward_to_direktur', '1');
            }
        }

        // Request Filters
        if ($request) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('hrd_izindinas.tanggal', [$request->dari, $request->sampai]);
            }
            if (!empty($request->kode_cabang)) {
                $query->where('hrd_izindinas.kode_cabang', $request->kode_cabang);
            }
            if (!empty($request->kode_dept)) {
                $query->where('hrd_izindinas.kode_dept', $request->kode_dept);
            }
            if (!empty($request->nama_karyawan)) {
                $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
            }
            if (!empty($request->status)) {
                if ($role == 'direktur') {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izindinas.direktur', '0');
                    } else if ($request->status == 'disetujui') {
                        $query->where('hrd_izindinas.direktur', '1');
                    }
                } else {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izindinas.status', '0');
                    } else if ($request->status == 'disetujui') {
                        $query->where('hrd_izindinas.status', '1');
                    }
                }
            }
        }

        $query->orderBy('hrd_izindinas.status');
        $query->orderBy('hrd_izindinas.status');
        $query->orderBy('hrd_izindinas.tanggal', 'desc');
        $query->orderBy('hrd_izindinas.created_at', 'desc');
        return $query;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Izinkoreksi extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = "hrd_izinkoreksi";
    protected $primaryKey = "kode_izin_koreksi";
    protected $guarded = [];
    public $incrementing  = false;

    function getIzinkoreksi($kode_izin_koreksi = null, Request $request = null, $cekPending = false)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $role_access_full = ['super admin', 'direktur'];
        $level_hrd = config('presensi.approval.level_hrd');

        $query = Izinkoreksi::query();
        $query->select(
            'hrd_izinkoreksi.*',
            'hrd_karyawan.nama_karyawan',
            'hrd_karyawan.kode_jabatan',
            'hrd_karyawan.kode_dept',
            'hrd_jabatan.nama_jabatan',
            'hrd_jabatan.kategori as kategori_jabatan',
            'cabang.nama_cabang',
            'hrd_departemen.nama_dept',
            'cabang.kode_regional',
            'hrd_jadwalkerja.nama_jadwal',
            'hrd_jamkerja.jam_masuk as jam_mulai',
            'hrd_jamkerja.jam_pulang as jam_selesai',
        );
        $query->join('hrd_karyawan', 'hrd_izinkoreksi.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_izinkoreksi.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_izinkoreksi.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_izinkoreksi.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('hrd_jadwalkerja', 'hrd_izinkoreksi.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal');
        $query->join('hrd_jamkerja', 'hrd_izinkoreksi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja');


        // Data Access Restrictions
        if (!in_array($role, $role_access_full)) {
            $query->where(function ($access) use ($user) {
                $dept_access = json_decode($user->dept_access, true) ?? [];
                $cabang_access = json_decode($user->cabang_access, true) ?? [];
                // $jabatan_access = json_decode($user->jabatan_access, true) ?? [];
                $group_access = json_decode($user->group_access, true) ?? [];
                $karyawan_access = json_decode($user->karyawan_access, true) ?? [];

                // 1. Employee Access (NIK)
                if (!in_array('all', $karyawan_access)) {
                    $access->whereIn('hrd_izinkoreksi.nik', $karyawan_access);
                }

                // 2. Group Access
                if (!empty($group_access)) {
                    $access->whereIn('hrd_karyawan.kode_group', $group_access);
                }

                // Branch Access
                if (!in_array('all', $cabang_access)) {
                    if (!empty($cabang_access)) {
                        $access->whereIn('hrd_izinkoreksi.kode_cabang', $cabang_access);
                    } else {
                        if (empty($user->kode_regional) || $user->kode_regional == 'R00') {
                            $access->where('hrd_izinkoreksi.kode_cabang', $user->kode_cabang);
                        }
                    }
                }

                // Department Access
                if (!in_array('all', $dept_access)) {
                    $access->whereIn('hrd_izinkoreksi.kode_dept', $dept_access);
                }

                // Jabatan Access
                // if (!in_array('all', $jabatan_access)) {
                //     $access->whereIn('hrd_izinkoreksi.kode_jabatan', $jabatan_access);
                // }

                // Regional Access
                if (!empty($user->kode_regional) && $user->kode_regional != 'R00') {
                    $access->where('cabang.kode_regional', $user->kode_regional);
                }
            });
        }

        // Feature Specific Logic
        if (!empty($kode_izin_koreksi)) {
            $query->where('hrd_izinkoreksi.kode_izin_koreksi', $kode_izin_koreksi);
        }

        if ($cekPending) {
            if (in_array($role, $level_hrd)) {
                $query->where('hrd_izinkoreksi.head', '1');
                $query->where('hrd_izinkoreksi.hrd', 0);
            } else if ($role == 'direktur') {
                $query->where('forward_to_direktur', '1');
                $query->where('direktur', 0);
            } else {
                $query->where('hrd_izinkoreksi.head', '0');
            }
        } else {
            if ($role == 'direktur') {
                $query->where('hrd_izinkoreksi.forward_to_direktur', '1');
            }
        }

        // Request Filters
        if ($request) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('hrd_izinkoreksi.tanggal', [$request->dari, $request->sampai]);
            }
            if (!empty($request->kode_cabang)) {
                $query->where('hrd_izinkoreksi.kode_cabang', $request->kode_cabang);
            }
            if (!empty($request->kode_dept)) {
                $query->where('hrd_izinkoreksi.kode_dept', $request->kode_dept);
            }
            if (!empty($request->nama_karyawan)) {
                $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
            }
            if (!empty($request->status)) {
                if ($role == 'direktur') {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinkoreksi.direktur', '0');
                    } else if ($request->status == 'disetujui') {
                        $query->where('hrd_izinkoreksi.direktur', '1');
                    }
                } else {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinkoreksi.status', '0');
                    } else if ($request->status == 'disetujui') {
                        $query->where('hrd_izinkoreksi.status', '1');
                    }
                }
            }
        }

        $query->orderBy('hrd_izinkoreksi.status');
        $query->orderBy('hrd_izinkoreksi.tanggal', 'desc');
        $query->orderBy('hrd_izinkoreksi.created_at', 'desc');
        return $query;
    }
}

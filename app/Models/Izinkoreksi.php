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
        //Catatan Update Permission
        //Role RSM,GM,Manager,Direktur hanya lihat dan approve
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        //dd($user->can('izinabsen.create'));
        $role_access_full = ['super admin', 'asst. manager hrd', 'spv presensi', 'direktur'];
        $level_hrd = config('presensi.approval.level_hrd',);
        $role_approve_presensi = config('presensi.approval');
        $dept_access = $role_approve_presensi[$role]['dept'] ?? [];
        $jabatan_access = $role_approve_presensi[$role]['jabatan'] ?? [];
        $jabatan_filter = $role_approve_presensi[$role]['jabatan_filter'] ?? false;
        // dd($level_hrd);
        // dd(in_array($role, $level_hrd));
        $cabang_access = $role_approve_presensi[$role]['cabang'] ?? 1;
        $dept_access_2 = $role_approve_presensi[$role]['dept2'] ?? [];
        $jabatan_access_2 = $role_approve_presensi[$role]['jabatan2'] ?? [];



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

        //dd(!in_array($role, $role_access_full));
        //Jika Admin Presensi
        //dd($cekPending);
        if (!empty($kode_izin_koreksi)) {
            $query->where('hrd_izinkoreksi.kode_izin_koreksi', $kode_izin_koreksi);
        }
        if (!$cekPending) {
            if (!in_array($role, $role_access_full)) {
                if ($user->can('izinabsen.create')) {
                    if ($user->kode_cabang == 'PST') {
                        $query->where('hrd_izinkoreksi.kode_dept', $user->kode_dept);
                    } else {
                        $query->where('hrd_izinkoreksi.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {

                    if (!empty($request)) {
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
                            if (!empty($request->status)) {
                                if ($request->status == 'pending') {
                                    $query->where('hrd_izinkoreksi.status', '0');
                                } else if ($request->status == 'disetujui') {
                                    $query->where('hrd_izinkoreksi.status', '1');
                                }
                            }
                        }
                    }

                    $query->whereIn('hrd_izinkoreksi.kode_dept', $dept_access);
                    if ($jabatan_filter && $jabatan_access != null) {
                        $query->whereIn('hrd_izinkoreksi.kode_jabatan', $jabatan_access);
                    }
                    if ($cabang_access == 1) {
                        $query->where('hrd_izinkoreksi.kode_cabang', auth()->user()->kode_cabang);
                    } else if ($cabang_access == 2) {
                        $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                    }



                    $query->orWhereIn('hrd_izinkoreksi.kode_dept', $dept_access_2);
                    if ($jabatan_filter && $jabatan_access_2 != null) {
                        $query->whereIn('hrd_izinkoreksi.kode_jabatan', $jabatan_access_2);
                    }
                    if ($cabang_access == 1) {
                        $query->where('hrd_izinkoreksi.kode_cabang', auth()->user()->kode_cabang);
                    } else if ($cabang_access == 2) {
                        $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                    }
                }
            }

            if ($role == 'direktur') {
                $query->where('hrd_izinkoreksi.forward_to_direktur', '1');
            }


            if (!empty($request)) {
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

                if ($role == 'direktur') {
                    if (!empty($request->status)) {
                        if ($request->status == 'pending') {
                            $query->where('hrd_izinkoreksi.forward_to_direktur', '1');
                            $query->where('hrd_izinkoreksi.direktur', '0');
                        } else if ($request->status == 'disetujui') {
                            $query->where('hrd_izinkoreksi.forward_to_direktur', '1');
                            $query->where('hrd_izinkoreksi.direktur', '1');
                        }
                    }
                } else {
                    if (!empty($request->status)) {
                        if ($request->status == 'pending') {
                            $query->where('hrd_izinkoreksi.status', '0');
                        } else if ($request->status == 'disetujui') {
                            $query->where('hrd_izinkoreksi.status', '1');
                        }
                    }
                }
            }

            
        } else {
            if (!in_array($role, $level_hrd) && $role !== 'direktur') {
                $query->where('hrd_izinkoreksi.head', '0');
                $query->whereIn('hrd_izinkoreksi.kode_dept', $dept_access);
                if ($jabatan_access != null) {
                    $query->whereIn('hrd_izinkoreksi.kode_jabatan', $jabatan_access);
                }
                if ($cabang_access == 1) {
                    $query->where('hrd_izinkoreksi.kode_cabang', auth()->user()->kode_cabang);
                } else if ($cabang_access == 2) {
                    $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                }
                $query->orWhere('hrd_izinkoreksi.head', '0');
                $query->whereIn('hrd_izinkoreksi.kode_dept', $dept_access_2);
                if ($jabatan_access_2 != null) {
                    $query->whereIn('hrd_izinkoreksi.kode_jabatan', $jabatan_access_2);
                }
                if ($cabang_access == 1) {
                    $query->where('hrd_izinkoreksi.kode_cabang', auth()->user()->kode_cabang);
                } else if ($cabang_access == 2) {
                    $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                }
            }


            if (in_array($role, $level_hrd)) {
                $query->where('hrd_izinkoreksi.head', '1');
                $query->where('hrd_izinkoreksi.hrd', 0);
            }

            if ($role == 'direktur') {
                $query->where('forward_to_direktur', '1');
                $query->where('direktur', 0);
            }
        }


        // dd($query->get());
        $query->orderBy('hrd_izinkoreksi.status');
        $query->orderBy('hrd_izinkoreksi.tanggal', 'desc');
        $query->orderBy('hrd_izinkoreksi.created_at', 'desc');
        return $query;
    }
}

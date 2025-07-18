<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Izinpulang extends Model
{

    use HasFactory;
    protected $table = "hrd_izinpulang";
    protected $primaryKey = "kode_izin_pulang";
    protected $guarded = [];
    public $incrementing  = false;

    function getIzinpulang($kode_izin_pulang = null, Request $request = null, $cekPending = false)
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



        $query = Izinpulang::query();
        $query->select(
            'hrd_izinpulang.*',
            'hrd_karyawan.nama_karyawan',
            'hrd_karyawan.kode_jabatan',
            'hrd_karyawan.kode_dept',
            'hrd_jabatan.nama_jabatan',
            'hrd_jabatan.kategori as kategori_jabatan',
            'cabang.nama_cabang',
            'hrd_departemen.nama_dept',
            'cabang.kode_regional'
        );
        $query->join('hrd_karyawan', 'hrd_izinpulang.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_izinpulang.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_izinpulang.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_izinpulang.kode_cabang', '=', 'cabang.kode_cabang');


        //dd(!in_array($role, $role_access_full));
        //Jika Admin Presensi
        //dd($cekPending);
        if (!empty($kode_izin_pulang)) {
            $query->where('hrd_izinpulang.kode_izin_pulang', $kode_izin_pulang);
        }
        if (!$cekPending) {
            if (!in_array($role, $role_access_full)) {
                if ($user->can('izinpulang.create')) {
                    if ($user->kode_cabang == 'PST') {
                        $query->where('hrd_izinpulang.kode_dept', $user->kode_dept);
                    } else {
                        $query->where('hrd_izinpulang.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {

                    if (!empty($request)) {
                        if (!empty($request->dari) && !empty($request->sampai)) {
                            $query->whereBetween('hrd_izinpulang.tanggal', [$request->dari, $request->sampai]);
                        }
                        if (!empty($request->kode_cabang)) {
                            $query->where('hrd_izinpulang.kode_cabang', $request->kode_cabang);
                        }
                        if (!empty($request->kode_dept)) {
                            $query->where('hrd_izinpulang.kode_dept', $request->kode_dept);
                        }
                        if (!empty($request->nama_karyawan)) {
                            $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
                        }
                        if (!empty($request->status)) {
                            if (!empty($request->status)) {
                                if ($request->status == 'pending') {
                                    $query->where('hrd_izinpulang.status', '0');
                                } else if ($request->status == 'disetujui') {
                                    $query->where('hrd_izinpulang.status', '1');
                                }
                            }
                        }
                    }

                    $query->whereIn('hrd_izinpulang.kode_dept', $dept_access);
                    if ($jabatan_filter && $jabatan_access != null) {
                        $query->whereIn('hrd_izinpulang.kode_jabatan', $jabatan_access);
                    }
                    if ($cabang_access == 1) {
                        $query->where('hrd_izinpulang.kode_cabang', auth()->user()->kode_cabang);
                    } else if ($cabang_access == 2) {
                        $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                    }



                    $query->orWhereIn('hrd_izinpulang.kode_dept', $dept_access_2);
                    if ($jabatan_filter && $jabatan_access_2 != null) {
                        $query->whereIn('hrd_izinpulang.kode_jabatan', $jabatan_access_2);
                    }
                    if ($cabang_access == 1) {
                        $query->where('hrd_izinpulang.kode_cabang', auth()->user()->kode_cabang);
                    } else if ($cabang_access == 2) {
                        $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                    }
                }
            }

            if ($role == 'direktur') {
                $query->where('hrd_izinpulang.forward_to_direktur', '1');
            }


            if (!empty($request)) {
                if (!empty($request->dari) && !empty($request->sampai)) {
                    $query->whereBetween('hrd_izinpulang.tanggal', [$request->dari, $request->sampai]);
                }
                if (!empty($request->kode_cabang)) {
                    $query->where('hrd_izinpulang.kode_cabang', $request->kode_cabang);
                }
                if (!empty($request->kode_dept)) {
                    $query->where('hrd_izinpulang.kode_dept', $request->kode_dept);
                }
                if (!empty($request->nama_karyawan)) {
                    $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
                }

                if ($role == 'direktur') {
                    if (!empty($request->status)) {
                        if ($request->status == 'pending') {
                            $query->where('hrd_izinpulang.forward_to_direktur', '1');
                            $query->where('hrd_izinpulang.direktur', '0');
                        } else if ($request->status == 'disetujui') {
                            $query->where('hrd_izinpulang.forward_to_direktur', '1');
                            $query->where('hrd_izinpulang.direktur', '1');
                        }
                    }
                } else {
                    if (!empty($request->status)) {
                        if ($request->status == 'pending') {
                            $query->where('hrd_izinpulang.status', '0');
                        } else if ($request->status == 'disetujui') {
                            $query->where('hrd_izinpulang.status', '1');
                        }
                    }
                }
            }

            
        } else {
            if (!in_array($role, $level_hrd) && $role !== 'direktur') {
                $query->where('hrd_izinpulang.head', '0');
                $query->whereIn('hrd_izinpulang.kode_dept', $dept_access);
                if ($jabatan_access != null) {
                    $query->whereIn('hrd_izinpulang.kode_jabatan', $jabatan_access);
                }
                if ($cabang_access == 1) {
                    $query->where('hrd_izinpulang.kode_cabang', auth()->user()->kode_cabang);
                } else if ($cabang_access == 2) {
                    $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                }
                $query->orWhere('hrd_izinpulang.head', '0');
                $query->whereIn('hrd_izinpulang.kode_dept', $dept_access_2);
                if ($jabatan_access_2 != null) {
                    $query->whereIn('hrd_izinpulang.kode_jabatan', $jabatan_access_2);
                }
                if ($cabang_access == 1) {
                    $query->where('hrd_izinpulang.kode_cabang', auth()->user()->kode_cabang);
                } else if ($cabang_access == 2) {
                    $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                }
            }


            if (in_array($role, $level_hrd)) {
                $query->where('hrd_izinpulang.head', '1');
                $query->where('hrd_izinpulang.hrd', 0);
            }

            if ($role == 'direktur') {
                $query->where('hrd_izinpulang.forward_to_direktur', '1');
                $query->where('hrd_izinpulang.direktur', 0);
            }
        }


        // dd($query->get());
        $query->orderBy('hrd_izinpulang.status');
        $query->orderBy('hrd_izinpulang.tanggal', 'desc');
        $query->orderBy('hrd_izinpulang.created_at', 'desc');
        return $query;



        // $user = User::findorfail(auth()->user()->id);
        // $role = $user->getRoleNames()->first();
        // $query = Izinpulang::query();
        // $query->select(
        //     'hrd_izinpulang.*',
        //     'nama_karyawan',
        //     'nama_jabatan',
        //     'hrd_jabatan.kategori as kategori_jabatan',
        //     'disposisi.id_pengirim',
        //     'disposisi.id_penerima',
        //     'roles.name as posisi_ajuan',
        //     'cabang.nama_cabang',
        //     'nama_dept',
        //     'cabang.kode_regional'
        // );
        // $query->join('hrd_karyawan', 'hrd_izinpulang.nik', '=', 'hrd_karyawan.nik');
        // $query->join('hrd_jabatan', 'hrd_izinpulang.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        // $query->join('hrd_departemen', 'hrd_izinpulang.kode_dept', '=', 'hrd_departemen.kode_dept');
        // $query->join('cabang', 'hrd_izinpulang.kode_cabang', '=', 'cabang.kode_cabang');
        // $query->leftJoin('hrd_izinpulang_disposisi as disposisi', function ($join) {
        //     $join->on('hrd_izinpulang.kode_izin_pulang', '=', 'disposisi.kode_izin_pulang')
        //         ->whereRaw('disposisi.kode_disposisi IN (SELECT MAX(kode_disposisi) FROM hrd_izinpulang_disposisi GROUP BY kode_izin_pulang)');
        // });



        // $query->leftJoin('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
        // $query->leftJoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
        // $query->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
        // if (!in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi', 'direktur'])) {
        //     if ($user->hasRole('gm operasional')) {
        //         $query->whereIn('hrd_izinpulang.kode_dept', ['PDQ', 'PMB', 'GDG', 'MTC', 'PRD', 'GAF', 'HRD']);
        //         $query->whereIn('hrd_izinpulang.kode_jabatan', ['J05', 'J06']);
        //     } else if ($user->hasRole('gm administrasi')) { //GM ADMINISTRASI
        //         $query->whereIn('hrd_izinpulang.kode_dept', ['AKT', 'KEU']);
        //         // $query->where('hrd_karyawan.kode_cabang', 'PST');
        //         // $query->whereIn('hrd_izincuti.kode_jabatan', ['J04', 'J05', 'J06', 'J12', 'J24', 'J25', 'J26']);
        //     } elseif ($user->hasRole('gm marketing')) { //GM MARKETING
        //         $query->whereIn('hrd_izinpulang.kode_dept', ['MKT']);
        //         $query->whereIn('hrd_izinpulang.kode_jabatan', ['J03', 'J05', 'J06']);
        //     } else if ($user->hasRole('regional sales manager')) { //REG. SALES MANAGER
        //         $query->where('hrd_izinpulang.kode_dept', 'MKT');
        //         $query->where('hrd_izinpulang.kode_jabatan', 'J07');
        //         $query->where('cabang.kode_regional', auth()->user()->kode_regional);
        //     } else if ($user->hasRole('regional operation manager')) { //REG. OPERATION MANAGER
        //         $query->where('hrd_izinpulang.kode_dept', 'AKT');
        //         $query->whereIn('hrd_izinpulang.kode_jabatan', ['J08']);
        //     } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
        //         $query->whereIn('hrd_izinpulang.kode_dept', ['AKT', 'KEU']);
        //         // $query->where('hrd_izinpulang.kode_cabang', 'PST');
        //         // $query->whereIn('hrd_izinpulang.kode_jabatan', ['J28', 'J12', 'J13', 'J14']);
        //     } else {
        //         if (auth()->user()->kode_cabang == 'PST') {
        //             $query->where('hrd_izinpulang.kode_dept', auth()->user()->kode_dept);
        //             $query->where('hrd_izinpulang.kode_cabang', auth()->user()->kode_cabang);
        //         } else {
        //             $query->where('hrd_izinpulang.kode_cabang', auth()->user()->kode_cabang);
        //         }
        //     }

        //     if (!empty($request)) {
        //         if (!empty($request->dari) && !empty($request->sampai)) {
        //             $query->whereBetween('hrd_izinpulang.tanggal', [$request->dari, $request->sampai]);
        //         }

        //         if (!empty($request->kode_cabang)) {
        //             $query->where('hrd_izinpulang.kode_cabang', $request->kode_cabang);
        //         }

        //         if (!empty($request->kode_dept)) {
        //             $query->where('hrd_izinpulang.kode_dept', $request->kode_dept);
        //         }

        //         if (!empty($request->nama_karyawan)) {
        //             $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        //         }

        //         if (!empty($request->status)) {
        //             if ($request->status == 'pending') {
        //                 $query->where('hrd_izinpulang.status', '0');
        //             } else if ($request->status == "disetujui") {
        //                 $query->where('hrd_izinpulang.status', '1');
        //             }
        //         }

        //         if (!empty($request->posisi_ajuan)) {
        //             $query->where('roles.name', $request->posisi_ajuan);
        //         }
        //     }

        //     // $query->where('hrd_izinpulang.status', '1');
        //     if (!empty($kode_izin_pulang)) {
        //         $query->where('hrd_izinpulang.kode_izin_pulang', $kode_izin_pulang);
        //     }
        //     if ($user->hasRole('gm operasional')) {
        //         $query->orWhere('hrd_izinpulang.kode_dept', 'PDQ');
        //     } else if ($user->hasRole('gm administrasi')) { //GM ADMINISTRASI
        //         $query->orwhereIn('hrd_izinpulang.kode_dept', ['AKT', 'KEU']);
        //         // $query->where('hrd_karyawan.kode_cabang', 'PST');
        //         // $query->whereIn('hrd_izincuti.kode_jabatan', ['J04', 'J05', 'J06', 'J12', 'J24', 'J25', 'J26']);
        //     } elseif ($user->hasRole('gm marketing')) { //GM MARKETING
        //         $query->orwhereIn('hrd_izinpulang.kode_dept', ['MKT']);
        //         $query->whereIn('hrd_izinpulang.kode_jabatan', ['J03', 'J05', 'J06']);
        //     } else if ($user->hasRole('regional sales manager')) { //REG. SALES MANAGER
        //         $query->orwhere('hrd_izinpulang.kode_dept', 'MKT');
        //         $query->where('hrd_izinpulang.kode_jabatan', 'J07');
        //         $query->where('cabang.kode_regional', auth()->user()->kode_regional);
        //     } else if ($user->hasRole('regional operation manager')) { //REG. OPERATION MANAGER
        //         $query->orwhere('hrd_izinpulang.kode_dept', 'AKT');
        //         $query->whereIn('hrd_izinpulang.kode_jabatan', ['J08']);
        //     } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
        //         $query->orwhereIn('hrd_izinpulang.kode_dept', ['AKT', 'KEU']);
        //         // $query->where('hrd_izinpulang.kode_cabang', 'PST');
        //         // $query->whereIn('hrd_izinpulang.kode_jabatan', ['J28', 'J12', 'J13', 'J14']);
        //     } else {
        //         if (auth()->user()->kode_cabang == 'PST') {
        //             $query->orwhere('hrd_izinpulang.kode_dept', auth()->user()->kode_dept);
        //             $query->where('hrd_izinpulang.kode_cabang', auth()->user()->kode_cabang);
        //         } else {
        //             $query->orwhere('hrd_izinpulang.kode_cabang', auth()->user()->kode_cabang);
        //         }
        //     }
        //     $query->WhereIn('hrd_izinpulang.kode_izin_pulang', function ($query) use ($user) {
        //         $query->select('disposisi.kode_izin_pulang');
        //         $query->from('hrd_izinpulang_disposisi as disposisi');
        //         $query->join('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
        //         $query->join('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
        //         $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');

        //         $query->join('users as pengirim', 'disposisi.id_pengirim', '=', 'pengirim.id');
        //         $query->join('model_has_roles as model_has_roles_pengirim', 'pengirim.id', '=', 'model_has_roles_pengirim.model_id');
        //         $query->join('roles as roles_pengirim', 'model_has_roles_pengirim.role_id', '=', 'roles_pengirim.id');

        //         $query->where('roles.name', $user->getRoleNames()->first());
        //         $query->orWhere('roles_pengirim.name', $user->getRoleNames()->first());
        //     });
        //     if (!empty($request)) {
        //         if (!empty($request->dari) && !empty($request->sampai)) {
        //             $query->whereBetween('hrd_izinpulang.tanggal', [$request->dari, $request->sampai]);
        //         }

        //         if (!empty($request->kode_cabang)) {
        //             $query->where('hrd_izinpulang.kode_cabang', $request->kode_cabang);
        //         }

        //         if (!empty($request->kode_dept)) {
        //             $query->where('hrd_izinpulang.kode_dept', $request->kode_dept);
        //         }

        //         if (!empty($request->nama_karyawan)) {
        //             $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        //         }

        //         if (!empty($request->status)) {
        //             if ($request->status == 'pending') {
        //                 $query->where('hrd_izinpulang.status', '0');
        //             } else if ($request->status == "disetujui") {
        //                 $query->where('hrd_izinpulang.status', '1');
        //             }
        //         }

        //         if (!empty($request->posisi_ajuan)) {
        //             $query->where('roles.name', $request->posisi_ajuan);
        //         }
        //     }
        //     if (!empty($kode_izin_pulang)) {
        //         $query->where('hrd_izinpulang.kode_izin_pulang', $kode_izin_pulang);
        //     }
        //     //Jika User Memiliki Permission create izin pulang
        //     if ($user->can('izinpulang.create') && auth()->user()->kode_cabang != 'PST') {
        //         $query->orWhere('hrd_izinpulang.kode_cabang', auth()->user()->kode_cabang);
        //         if (!empty($request)) {
        //             if (!empty($request->dari) && !empty($request->sampai)) {
        //                 $query->whereBetween('hrd_izinpulang.tanggal', [$request->dari, $request->sampai]);
        //             }

        //             if (!empty($request->kode_cabang)) {
        //                 $query->where('hrd_izinpulang.kode_cabang', $request->kode_cabang);
        //             }

        //             if (!empty($request->kode_dept)) {
        //                 $query->where('hrd_izinpulang.kode_dept', $request->kode_dept);
        //             }

        //             if (!empty($request->nama_karyawan)) {
        //                 $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        //             }

        //             if (!empty($request->status)) {
        //                 if ($request->status == 'pending') {
        //                     $query->where('hrd_izinpulang.status', '0');
        //                 } else if ($request->status == "disetujui") {
        //                     $query->where('hrd_izinpulang.status', '1');
        //                 }
        //             }

        //             if (!empty($request->posisi_ajuan)) {
        //                 $query->where('roles.name', $request->posisi_ajuan);
        //             }
        //         }
        //         if (!empty($kode_izin_pulang)) {
        //             $query->where('hrd_izinpulang.kode_izin_pulang', $kode_izin_pulang);
        //         }
        //     }
        // } else if ($user->hasRole('direktur')) {
        //     $query->WhereIn('hrd_izinpulang.kode_izin_pulang', function ($query) use ($user) {
        //         $query->select('disposisi.kode_izin_pulang');
        //         $query->from('hrd_izinpulang_disposisi as disposisi');
        //         $query->join('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
        //         $query->join('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
        //         $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');

        //         $query->join('users as pengirim', 'disposisi.id_pengirim', '=', 'pengirim.id');
        //         $query->join('model_has_roles as model_has_roles_pengirim', 'pengirim.id', '=', 'model_has_roles_pengirim.model_id');
        //         $query->join('roles as roles_pengirim', 'model_has_roles_pengirim.role_id', '=', 'roles_pengirim.id');

        //         $query->where('roles.name', $user->getRoleNames()->first());
        //         $query->orWhere('roles_pengirim.name', $user->getRoleNames()->first());
        //     });
        //     if (!empty($request)) {
        //         if (!empty($request->dari) && !empty($request->sampai)) {
        //             $query->whereBetween('hrd_izinpulang.tanggal', [$request->dari, $request->sampai]);
        //         }

        //         if (!empty($request->kode_cabang)) {
        //             $query->where('hrd_izinpulang.kode_cabang', $request->kode_cabang);
        //         }

        //         if (!empty($request->kode_dept)) {
        //             $query->where('hrd_izinpulang.kode_dept', $request->kode_dept);
        //         }

        //         if (!empty($request->nama_karyawan)) {
        //             $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        //         }

        //         if (!empty($request->status)) {
        //             if ($request->status == 'pending') {
        //                 $query->where('hrd_izinpulang.direktur', '0');
        //             } else if ($request->status == "disetujui") {
        //                 $query->where('hrd_izinpulang.direktur', '1');
        //             }
        //         }

        //         if (!empty($request->posisi_ajuan)) {
        //             $query->where('roles.name', $request->posisi_ajuan);
        //         }
        //     }
        //     if (!empty($kode_izin_pulang)) {
        //         $query->where('hrd_izinpulang.kode_izin_pulang', $kode_izin_pulang);
        //     }
        // } else {
        //     if (!empty($request)) {
        //         if (!empty($request->dari) && !empty($request->sampai)) {
        //             $query->whereBetween('hrd_izinpulang.tanggal', [$request->dari, $request->sampai]);
        //         }


        //         if (!empty($request->kode_cabang)) {
        //             $query->where('hrd_izinpulang.kode_cabang', $request->kode_cabang);
        //         }

        //         if (!empty($request->kode_dept)) {
        //             $query->where('hrd_izinpulang.kode_dept', $request->kode_dept);
        //         }

        //         if (!empty($request->nama_karyawan)) {
        //             $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        //         }

        //         if (!empty($request->status)) {
        //             if ($request->status == 'pending') {
        //                 $query->where('hrd_izinpulang.status', '0');
        //             } else if ($request->status == "disetujui") {
        //                 $query->where('hrd_izinpulang.status', '1');
        //             } else if ($request->status == "direktur") {
        //                 $query->where('hrd_izinpulang.direktur', '1');
        //             } else if ($request->status == "pendingdirektur") {
        //                 $query->where('roles.name', 'direktur');
        //                 $query->where('hrd_izinpulang.direktur', '0');
        //             }
        //         }

        //         if (!empty($request->posisi_ajuan)) {
        //             $query->where('roles.name', $request->posisi_ajuan);
        //         }
        //     }
        //     if (!empty($kode_izin_pulang)) {
        //         $query->where('hrd_izinpulang.kode_izin_pulang', $kode_izin_pulang);
        //     }
        // }



        // $query->orderBy('hrd_izinpulang.status');
        // $query->orderBy('hrd_izinpulang.tanggal', 'desc');
        // $query->orderBy('hrd_izinpulang.created_at', 'desc');
        // return $query;
    }
}

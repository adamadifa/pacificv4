<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Izinabsen extends Model
{
    use HasFactory;
    protected $table = "hrd_izinabsen";
    protected $primaryKey = "kode_izin";
    protected $guarded = [];
    public $incrementing  = false;

    function getIzinabsen($kode_izin = null, Request $request = null, $cekPending = false)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $role_access_full = ['super admin', 'direktur'];
        $level_hrd = config('presensi.approval.level_hrd');

        $query = Izinabsen::query();
        $query->select(
            'hrd_izinabsen.*',
            'hrd_karyawan.nama_karyawan',
            'hrd_karyawan.kode_jabatan',
            'hrd_karyawan.kode_dept',
            'hrd_jabatan.nama_jabatan',
            'hrd_jabatan.kategori as kategori_jabatan',
            'cabang.nama_cabang',
            'hrd_departemen.nama_dept',
            'cabang.kode_regional'
        );
        $query->join('hrd_karyawan', 'hrd_izinabsen.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_izinabsen.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_izinabsen.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_izinabsen.kode_cabang', '=', 'cabang.kode_cabang');


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
                    $access->whereIn('hrd_izinabsen.nik', $karyawan_access);
                }

                // 2. Group Access
                if (!empty($group_access)) {
                    $access->whereIn('hrd_karyawan.kode_group', $group_access);
                }

                // Branch Access
                if (!in_array('all', $cabang_access)) {
                    if (!empty($cabang_access)) {
                        $access->whereIn('hrd_izinabsen.kode_cabang', $cabang_access);
                    } else {
                        if (empty($user->kode_regional) || $user->kode_regional == 'R00') {
                            $access->where('hrd_izinabsen.kode_cabang', $user->kode_cabang);
                        }
                    }
                }

                // Department Access
                if (!in_array('all', $dept_access)) {
                    $access->whereIn('hrd_izinabsen.kode_dept', $dept_access);
                }

                // Jabatan Access
                // if (!in_array('all', $jabatan_access)) {
                //     $access->whereIn('hrd_izinabsen.kode_jabatan', $jabatan_access);
                // }

                // Regional Access
                if (!empty($user->kode_regional) && $user->kode_regional != 'R00') {
                    $access->where('cabang.kode_regional', $user->kode_regional);
                }
            });
        }

        // Feature Specific Logic
        if (!empty($kode_izin)) {
            $query->where('hrd_izinabsen.kode_izin', $kode_izin);
        }

        if ($cekPending) {
            if (in_array($role, $level_hrd)) {
                $query->where('hrd_izinabsen.head', '1');
                $query->where('hrd_izinabsen.hrd', 0);
            } else if ($role == 'direktur') {
                $query->where('forward_to_direktur', '1');
                $query->where('direktur', 0);
            } else {
                $query->where('hrd_izinabsen.head', '0');
            }
        } else {
            if ($role == 'direktur') {
                $query->where('hrd_izinabsen.forward_to_direktur', '1');
            }
        }

        // Request Filters
        if ($request) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('hrd_izinabsen.tanggal', [$request->dari, $request->sampai]);
            }
            if (!empty($request->kode_cabang)) {
                $query->where('hrd_izinabsen.kode_cabang', $request->kode_cabang);
            }
            if (!empty($request->kode_dept)) {
                $query->where('hrd_izinabsen.kode_dept', $request->kode_dept);
            }
            if (!empty($request->nama_karyawan)) {
                $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
            }
            if (!empty($request->status)) {
                if ($role == 'direktur') {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinabsen.direktur', '0');
                    } else if ($request->status == 'disetujui') {
                        $query->where('hrd_izinabsen.direktur', '1');
                    }
                } else {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinabsen.status', '0');
                    } else if ($request->status == 'disetujui') {
                        $query->where('hrd_izinabsen.status', '1');
                    }
                }
            }
        }

        $query->orderBy('hrd_izinabsen.status');
        $query->orderBy('hrd_izinabsen.tanggal', 'desc');
        $query->orderBy('hrd_izinabsen.created_at', 'desc');
        return $query;
    }
}



// if (!in_array($role, ['super admin', 'direktur'])) {
//     if ($user->hasRole('gm operasional')) {
//         $query->whereIn('hrd_izinabsen.kode_dept', ['PMB', 'GDG', 'MTC', 'PRD', 'GAF', 'HRD']);
//         $query->whereIn('hrd_izinabsen.kode_jabatan', ['J05', 'J06']);
//     } else if ($user->hasRole('gm administrasi')) { //GM ADMINISTRASI
//         $query->whereIn('hrd_izinabsen.kode_dept', ['AKT', 'KEU']);
//         // $query->where('hrd_karyawan.kode_cabang', 'PST');
//         // $query->whereIn('hrd_izinabsen.kode_jabatan', ['J04', 'J05', 'J06']);
//     } elseif ($user->hasRole('gm marketing')) { //GM MARKETING
//         $query->whereIn('hrd_izinabsen.kode_dept', ['MKT']);
//         $query->whereIn('hrd_izinabsen.kode_jabatan', ['J03', 'J05', 'J06']);
//     } else if ($user->hasRole('regional sales manager')) { //REG. SALES MANAGER
//         $query->where('hrd_izinabsen.kode_dept', 'MKT');
//         $query->where('hrd_izinabsen.kode_jabatan', 'J07');
//         $query->where('cabang.kode_regional', auth()->user()->kode_regional);
//     } else if ($user->hasRole('regional operation manager')) { //REG. OPERATION MANAGER
//         $query->where('hrd_izinabsen.kode_dept', 'AKT');
//         $query->whereIn('hrd_izinabsen.kode_jabatan', ['J08']);
//     } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
//         $query->whereIn('hrd_izinabsen.kode_dept', ['AKT', 'KEU']);
//         // $query->where('hrd_izinabsen.kode_cabang', 'PST');
//         // $query->whereIn('hrd_izinabsen.kode_jabatan', ['J28', 'J12', 'J13', 'J14']);
//     } else {
//         if (auth()->user()->kode_cabang == 'PST') {
//             $query->where('hrd_izinabsen.kode_dept', auth()->user()->kode_dept);
//             $query->where('hrd_izinabsen.kode_cabang', auth()->user()->kode_cabang);
//         } else {
//             $query->where('hrd_izinabsen.kode_cabang', auth()->user()->kode_cabang);
//         }
//     }

//     if (!empty($request)) {
//         if (!empty($request->dari) && !empty($request->sampai)) {
//             $query->whereBetween('hrd_izinabsen.tanggal', [$request->dari, $request->sampai]);
//         }

//         if (!empty($request->kode_cabang)) {
//             $query->where('hrd_izinabsen.kode_cabang', $request->kode_cabang);
//         }

//         if (!empty($request->kode_dept)) {
//             $query->where('hrd_izinabsen.kode_dept', $request->kode_dept);
//         }

//         if (!empty($request->nama_karyawan)) {
//             $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
//         }

//         if (!empty($request->status)) {
//             if ($request->status == 'pending') {
//                 $query->where('hrd_izinabsen.status', '0');
//             } else if ($request->status == "disetujui") {
//                 $query->where('hrd_izinabsen.status', '1');
//             }
//         }

//         if (!empty($request->posisi_ajuan)) {
//             $query->where('roles.name', $request->posisi_ajuan);
//         }
//     }

//     // $query->where('hrd_izinabsen.status', '1');
//     if (!empty($kode_izin)) {
//         $query->where('hrd_izinabsen.kode_izin', $kode_izin);
//     }



//     if ($user->hasRole('gm operasional')) {
//         $query->orWhere('hrd_izinabsen.kode_dept', 'PDQ');
//     } else if ($user->hasRole('gm administrasi')) { //GM ADMINISTRASI
//         $query->orwhereIn('hrd_izinabsen.kode_dept', ['AKT', 'KEU']);
//         // $query->where('hrd_karyawan.kode_cabang', 'PST');
//         // $query->whereIn('hrd_izinabsen.kode_jabatan', ['J04', 'J05', 'J06']);
//     } elseif ($user->hasRole('gm marketing')) { //GM MARKETING
//         $query->orwhereIn('hrd_izinabsen.kode_dept', ['MKT']);
//         $query->whereIn('hrd_izinabsen.kode_jabatan', ['J03', 'J05', 'J06']);
//     } else if ($user->hasRole('regional sales manager')) { //REG. SALES MANAGER
//         $query->orwhere('hrd_izinabsen.kode_dept', 'MKT');
//         $query->where('hrd_izinabsen.kode_jabatan', 'J07');
//         $query->where('cabang.kode_regional', auth()->user()->kode_regional);
//     } else if ($user->hasRole('regional operation manager')) { //REG. OPERATION MANAGER
//         $query->orwhere('hrd_izinabsen.kode_dept', 'AKT');
//         $query->whereIn('hrd_izinabsen.kode_jabatan', ['J08']);
//     } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
//         $query->orwhereIn('hrd_izinabsen.kode_dept', ['AKT', 'KEU']);
//         // $query->where('hrd_izinabsen.kode_cabang', 'PST');
//         // $query->whereIn('hrd_izinabsen.kode_jabatan', ['J28', 'J12', 'J13', 'J14']);
//     } else {
//         if (auth()->user()->kode_cabang == 'PST') {
//             $query->orwhere('hrd_izinabsen.kode_dept', auth()->user()->kode_dept);
//             $query->where('hrd_izinabsen.kode_cabang', auth()->user()->kode_cabang);
//         } else {
//             $query->orwhere('hrd_izinabsen.kode_cabang', auth()->user()->kode_cabang);
//         }
//     }
//     $query->WhereIn('hrd_izinabsen.kode_izin', function ($query) use ($user) {
//         $query->select('disposisi.kode_izin');
//         $query->from('hrd_izinabsen_disposisi as disposisi');
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
//             $query->whereBetween('hrd_izinabsen.tanggal', [$request->dari, $request->sampai]);
//         }

//         if (!empty($request->kode_cabang)) {
//             $query->where('hrd_izinabsen.kode_cabang', $request->kode_cabang);
//         }

//         if (!empty($request->kode_dept)) {
//             $query->where('hrd_izinabsen.kode_dept', $request->kode_dept);
//         }

//         if (!empty($request->nama_karyawan)) {
//             $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
//         }

//         if (!empty($request->status)) {
//             if ($request->status == 'pending') {
//                 $query->where('hrd_izinabsen.status', '0');
//             } else if ($request->status == "disetujui") {
//                 $query->where('hrd_izinabsen.status', '1');
//             }
//         }

//         if (!empty($request->posisi_ajuan)) {
//             $query->where('roles.name', $request->posisi_ajuan);
//         }
//     }
//     if (!empty($kode_izin)) {
//         $query->where('hrd_izinabsen.kode_izin', $kode_izin);
//     }
//     //Jika User Memiliki Permission create izin absen
//     if ($user->can('izinabsen.create') && auth()->user()->kode_cabang != 'PST') {

//         $query->orWhere('hrd_izinabsen.kode_cabang', auth()->user()->kode_cabang);
//         if (!empty($request)) {
//             if (!empty($request->dari) && !empty($request->sampai)) {
//                 $query->whereBetween('hrd_izinabsen.tanggal', [$request->dari, $request->sampai]);
//             }

//             if (!empty($request->kode_cabang)) {
//                 $query->where('hrd_izinabsen.kode_cabang', $request->kode_cabang);
//             }

//             if (!empty($request->kode_dept)) {
//                 $query->where('hrd_izinabsen.kode_dept', $request->kode_dept);
//             }

//             if (!empty($request->nama_karyawan)) {
//                 $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
//             }

//             if (!empty($request->status)) {
//                 if ($request->status == 'pending') {
//                     $query->where('hrd_izinabsen.status', '0');
//                 } else if ($request->status == "disetujui") {
//                     $query->where('hrd_izinabsen.status', '1');
//                 }
//             }

//             if (!empty($request->posisi_ajuan)) {
//                 $query->where('roles.name', $request->posisi_ajuan);
//             }
//         }
//         if (!empty($kode_izin)) {
//             $query->where('hrd_izinabsen.kode_izin', $kode_izin);
//         }
//     }
// } else if ($user->hasRole('direktur')) {
//     $query->WhereIn('hrd_izinabsen.kode_izin', function ($query) use ($user) {
//         $query->select('disposisi.kode_izin');
//         $query->from('hrd_izinabsen_disposisi as disposisi');
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
//             $query->whereBetween('hrd_izinabsen.tanggal', [$request->dari, $request->sampai]);
//         }

//         if (!empty($request->kode_cabang)) {
//             $query->where('hrd_izinabsen.kode_cabang', $request->kode_cabang);
//         }

//         if (!empty($request->kode_dept)) {
//             $query->where('hrd_izinabsen.kode_dept', $request->kode_dept);
//         }

//         if (!empty($request->nama_karyawan)) {
//             $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
//         }

//         if (!empty($request->status)) {
//             if ($request->status == 'pending') {
//                 $query->where('hrd_izinabsen.direktur', '0');
//             } else if ($request->status == "disetujui") {
//                 $query->where('hrd_izinabsen.direktur', '1');
//             }
//         }

//         if (!empty($request->posisi_ajuan)) {
//             $query->where('roles.name', $request->posisi_ajuan);
//         }
//     }
//     if (!empty($kode_izin)) {
//         $query->where('hrd_izinabsen.kode_izin', $kode_izin);
//     }
// } else {
//     if (!empty($request)) {
//         if (!empty($request->dari) && !empty($request->sampai)) {
//             $query->whereBetween('hrd_izinabsen.tanggal', [$request->dari, $request->sampai]);
//         }


//         if (!empty($request->kode_cabang)) {
//             $query->where('hrd_izinabsen.kode_cabang', $request->kode_cabang);
//         }

//         if (!empty($request->kode_dept)) {
//             $query->where('hrd_izinabsen.kode_dept', $request->kode_dept);
//         }

//         if (!empty($request->nama_karyawan)) {
//             $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
//         }

//         if (!empty($request->status)) {
//             if ($request->status == 'pending') {
//                 $query->where('hrd_izinabsen.status', '0');
//             } else if ($request->status == "disetujui") {
//                 $query->where('hrd_izinabsen.status', '1');
//             } else if ($request->status == "direktur") {
//                 $query->where('hrd_izinabsen.direktur', '1');
//             } else if ($request->status == "pendingdirektur") {
//                 $query->where('roles.name', 'direktur');
//                 $query->where('hrd_izinabsen.direktur', '0');
//             }
//         }

//         if (!empty($request->posisi_ajuan)) {
//             $query->where('roles.name', $request->posisi_ajuan);
//         }
//     }
//     if (!empty($kode_izin)) {
//         $query->where('hrd_izinabsen.kode_izin', $kode_izin);
//     }
// }

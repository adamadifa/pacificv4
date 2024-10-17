<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Izinterlambat extends Model
{
    use HasFactory;
    protected $table = "hrd_izinterlambat";
    protected $primaryKey = "kode_izin_terlambat";
    protected $guarded = [];
    public $incrementing  = false;

    function getIzinterlambat($kode_izin_terlambat = null, Request $request = null)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $query = Izinterlambat::query();
        $query->select(
            'hrd_izinterlambat.*',
            'nama_karyawan',
            'nama_jabatan',
            'hrd_jabatan.kategori as kategori_jabatan',
            'disposisi.id_pengirim',
            'disposisi.id_penerima',
            'roles.name as posisi_ajuan',
            'cabang.nama_cabang',
            'nama_dept',
            'cabang.kode_regional'
        );
        $query->join('hrd_karyawan', 'hrd_izinterlambat.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_izinterlambat.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_izinterlambat.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_izinterlambat.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('hrd_izinterlambat_disposisi as disposisi', function ($join) {
            $join->on('hrd_izinterlambat.kode_izin_terlambat', '=', 'disposisi.kode_izin_terlambat')
                ->whereRaw('disposisi.kode_disposisi IN (SELECT MAX(kode_disposisi) FROM hrd_izinterlambat_disposisi GROUP BY kode_izin_terlambat)');
        });



        $query->leftJoin('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
        $query->leftJoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
        $query->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
        if (!in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi', 'direktur'])) {
            if ($user->hasRole('gm operasional')) {
                $query->whereIn('hrd_izinterlambat.kode_dept', ['PDQ', 'PMB', 'GDG', 'MTC', 'PRD', 'GAF', 'HRD']);
                $query->whereIn('hrd_izinterlambat.kode_jabatan', ['J05', 'J06']);
            } else if ($user->hasRole('gm administrasi')) { //GM ADMINISTRASI
                $query->whereIn('hrd_izinterlambat.kode_dept', ['AKT', 'KEU']);
                $query->where('hrd_karyawan.kode_cabang', 'PST');
                // $query->whereIn('hrd_izincuti.kode_jabatan', ['J04', 'J05', 'J06', 'J12', 'J24', 'J25', 'J26']);
            } elseif ($user->hasRole('gm marketing')) { //GM MARKETING
                $query->whereIn('hrd_izinterlambat.kode_dept', ['MKT']);
                $query->whereIn('hrd_izinterlambat.kode_jabatan', ['J03', 'J05', 'J06']);
            } else if ($user->hasRole('regional sales manager')) { //REG. SALES MANAGER
                $query->where('hrd_izinterlambat.kode_dept', 'MKT');
                $query->where('hrd_izinterlambat.kode_jabatan', 'J07');
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else if ($user->hasRole('regional operation manager')) { //REG. OPERATION MANAGER
                $query->where('hrd_izinterlambat.kode_dept', 'AKT');
                $query->whereIn('hrd_izinterlambat.kode_jabatan', ['J08']);
            } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
                $query->whereIn('hrd_izinterlambat.kode_dept', ['AKT', 'KEU']);
                $query->where('hrd_izinterlambat.kode_cabang', 'PST');
                $query->whereIn('hrd_izinterlambat.kode_jabatan', ['J28', 'J12', 'J13', 'J14']);
            } else {
                if (auth()->user()->kode_cabang == 'PST') {
                    $query->where('hrd_izinterlambat.kode_dept', auth()->user()->kode_dept);
                    $query->where('hrd_izinterlambat.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    $query->where('hrd_izinterlambat.kode_cabang', auth()->user()->kode_cabang);
                }
            }

            if (!empty($request)) {
                if (!empty($request->dari) && !empty($request->sampai)) {
                    $query->whereBetween('hrd_izinterlambat.tanggal', [$request->dari, $request->sampai]);
                }

                if (!empty($request->kode_cabang)) {
                    $query->where('hrd_izinterlambat.kode_cabang', $request->kode_cabang);
                }

                if (!empty($request->kode_dept)) {
                    $query->where('hrd_izinterlambat.kode_dept', $request->kode_dept);
                }

                if (!empty($request->nama_karyawan)) {
                    $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
                }

                if (!empty($request->status)) {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinterlambat.status', '0');
                    } else if ($request->status == "disetujui") {
                        $query->where('hrd_izinterlambat.status', '1');
                    }
                }

                if (!empty($request->posisi_ajuan)) {
                    $query->where('roles.name', $request->posisi_ajuan);
                }
            }

            // $query->where('hrd_izinterlambat.status', '1');
            if (!empty($kode_izin_terlambat)) {
                $query->where('hrd_izinterlambat.kode_izin_terlambat', $kode_izin_terlambat);
            }
            if ($user->hasRole('gm operasional')) {
                $query->orWhere('hrd_izinterlambat.kode_dept', 'PDQ');
            } else if ($user->hasRole('gm administrasi')) { //GM ADMINISTRASI
                $query->orwhereIn('hrd_izinterlambat.kode_dept', ['AKT', 'KEU']);
                $query->where('hrd_karyawan.kode_cabang', 'PST');
                // $query->whereIn('hrd_izincuti.kode_jabatan', ['J04', 'J05', 'J06', 'J12', 'J24', 'J25', 'J26']);
            } elseif ($user->hasRole('gm marketing')) { //GM MARKETING
                $query->orwhereIn('hrd_izinterlambat.kode_dept', ['MKT']);
                $query->whereIn('hrd_izinterlambat.kode_jabatan', ['J03', 'J05', 'J06']);
            } else if ($user->hasRole('regional sales manager')) { //REG. SALES MANAGER
                $query->orwhere('hrd_izinterlambat.kode_dept', 'MKT');
                $query->where('hrd_izinterlambat.kode_jabatan', 'J07');
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else if ($user->hasRole('regional operation manager')) { //REG. OPERATION MANAGER
                $query->orwhere('hrd_izinterlambat.kode_dept', 'AKT');
                $query->whereIn('hrd_izinterlambat.kode_jabatan', ['J08']);
            } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
                $query->orwhereIn('hrd_izinterlambat.kode_dept', ['AKT', 'KEU']);
                $query->where('hrd_izinterlambat.kode_cabang', 'PST');
                $query->whereIn('hrd_izinterlambat.kode_jabatan', ['J28', 'J12', 'J13', 'J14']);
            } else {
                if (auth()->user()->kode_cabang == 'PST') {
                    $query->orwhere('hrd_izinterlambat.kode_dept', auth()->user()->kode_dept);
                    $query->where('hrd_izinterlambat.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    $query->orwhere('hrd_izinterlambat.kode_cabang', auth()->user()->kode_cabang);
                }
            }
            $query->WhereIn('hrd_izinterlambat.kode_izin_terlambat', function ($query) use ($user) {
                $query->select('disposisi.kode_izin_terlambat');
                $query->from('hrd_izinterlambat_disposisi as disposisi');
                $query->join('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
                $query->join('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
                $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');

                $query->join('users as pengirim', 'disposisi.id_pengirim', '=', 'pengirim.id');
                $query->join('model_has_roles as model_has_roles_pengirim', 'pengirim.id', '=', 'model_has_roles_pengirim.model_id');
                $query->join('roles as roles_pengirim', 'model_has_roles_pengirim.role_id', '=', 'roles_pengirim.id');

                $query->where('roles.name', $user->getRoleNames()->first());
                $query->orWhere('roles_pengirim.name', $user->getRoleNames()->first());
            });
            if (!empty($request)) {
                if (!empty($request->dari) && !empty($request->sampai)) {
                    $query->whereBetween('hrd_izinterlambat.tanggal', [$request->dari, $request->sampai]);
                }

                if (!empty($request->kode_cabang)) {
                    $query->where('hrd_izinterlambat.kode_cabang', $request->kode_cabang);
                }

                if (!empty($request->kode_dept)) {
                    $query->where('hrd_izinterlambat.kode_dept', $request->kode_dept);
                }

                if (!empty($request->nama_karyawan)) {
                    $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
                }

                if (!empty($request->status)) {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinterlambat.status', '0');
                    } else if ($request->status == "disetujui") {
                        $query->where('hrd_izinterlambat.status', '1');
                    }
                }

                if (!empty($request->posisi_ajuan)) {
                    $query->where('roles.name', $request->posisi_ajuan);
                }
            }
            if (!empty($kode_izin_terlambat)) {
                $query->where('hrd_izinterlambat.kode_izin_terlambat', $kode_izin_terlambat);
            }
            //Jika User Memiliki Permission create izin terlambat
            if ($user->can('izinterlambat.create') && auth()->user()->kode_cabang != 'PST') {
                $query->orWhere('hrd_izinterlambat.kode_cabang', auth()->user()->kode_cabang);
                if (!empty($request)) {
                    if (!empty($request->dari) && !empty($request->sampai)) {
                        $query->whereBetween('hrd_izinterlambat.tanggal', [$request->dari, $request->sampai]);
                    }

                    if (!empty($request->kode_cabang)) {
                        $query->where('hrd_izinterlambat.kode_cabang', $request->kode_cabang);
                    }

                    if (!empty($request->kode_dept)) {
                        $query->where('hrd_izinterlambat.kode_dept', $request->kode_dept);
                    }

                    if (!empty($request->nama_karyawan)) {
                        $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
                    }

                    if (!empty($request->status)) {
                        if ($request->status == 'pending') {
                            $query->where('hrd_izinterlambat.status', '0');
                        } else if ($request->status == "disetujui") {
                            $query->where('hrd_izinterlambat.status', '1');
                        }
                    }

                    if (!empty($request->posisi_ajuan)) {
                        $query->where('roles.name', $request->posisi_ajuan);
                    }
                }
                if (!empty($kode_izin_terlambat)) {
                    $query->where('hrd_izinterlambat.kode_izin_terlambat', $kode_izin_terlambat);
                }
            }
        } else if ($user->hasRole('direktur')) {
            $query->WhereIn('hrd_izinterlambat.kode_izin_terlambat', function ($query) use ($user) {
                $query->select('disposisi.kode_izin_terlambat');
                $query->from('hrd_izinterlambat_disposisi as disposisi');
                $query->join('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
                $query->join('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
                $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');

                $query->join('users as pengirim', 'disposisi.id_pengirim', '=', 'pengirim.id');
                $query->join('model_has_roles as model_has_roles_pengirim', 'pengirim.id', '=', 'model_has_roles_pengirim.model_id');
                $query->join('roles as roles_pengirim', 'model_has_roles_pengirim.role_id', '=', 'roles_pengirim.id');

                $query->where('roles.name', $user->getRoleNames()->first());
                $query->orWhere('roles_pengirim.name', $user->getRoleNames()->first());
            });
            if (!empty($request)) {
                if (!empty($request->dari) && !empty($request->sampai)) {
                    $query->whereBetween('hrd_izinterlambat.tanggal', [$request->dari, $request->sampai]);
                }

                if (!empty($request->kode_cabang)) {
                    $query->where('hrd_izinterlambat.kode_cabang', $request->kode_cabang);
                }

                if (!empty($request->kode_dept)) {
                    $query->where('hrd_izinterlambat.kode_dept', $request->kode_dept);
                }

                if (!empty($request->nama_karyawan)) {
                    $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
                }

                if (!empty($request->status)) {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinterlambat.direktur', '0');
                    } else if ($request->status == "disetujui") {
                        $query->where('hrd_izinterlambat.direktur', '1');
                    }
                }

                if (!empty($request->posisi_ajuan)) {
                    $query->where('roles.name', $request->posisi_ajuan);
                }
            }
            if (!empty($kode_izin_terlambat)) {
                $query->where('hrd_izinterlambat.kode_izin_terlambat', $kode_izin_terlambat);
            }
        } else {
            if (!empty($request)) {
                if (!empty($request->dari) && !empty($request->sampai)) {
                    $query->whereBetween('hrd_izinterlambat.tanggal', [$request->dari, $request->sampai]);
                }


                if (!empty($request->kode_cabang)) {
                    $query->where('hrd_izinterlambat.kode_cabang', $request->kode_cabang);
                }

                if (!empty($request->kode_dept)) {
                    $query->where('hrd_izinterlambat.kode_dept', $request->kode_dept);
                }

                if (!empty($request->nama_karyawan)) {
                    $query->where('hrd_karyawan.nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
                }

                if (!empty($request->status)) {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinterlambat.status', '0');
                    } else if ($request->status == "disetujui") {
                        $query->where('hrd_izinterlambat.status', '1');
                    } else if ($request->status == "direktur") {
                        $query->where('hrd_izinterlambat.direktur', '1');
                    } else if ($request->status == "pendingdirektur") {
                        $query->where('roles.name', 'direktur');
                        $query->where('hrd_izinterlambat.direktur', '0');
                    }
                }

                if (!empty($request->posisi_ajuan)) {
                    $query->where('roles.name', $request->posisi_ajuan);
                }
            }
            if (!empty($kode_izin_terlambat)) {
                $query->where('hrd_izinterlambat.kode_izin_terlambat', $kode_izin_terlambat);
            }
        }




        $query->orderBy('hrd_izinterlambat.status');
        $query->orderBy('hrd_izinterlambat.tanggal', 'desc');
        $query->orderBy('hrd_izinterlambat.created_at', 'desc');
        return $query;
    }
}

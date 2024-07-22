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

    function getIzinabsen($kode_izin = null, Request $request = null)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $query = Izinabsen::query();
        $query->select(
            'hrd_izinabsen.*',
            'nama_karyawan',
            'nama_jabatan',
            'disposisi.id_pengirim',
            'disposisi.id_penerima',
            'roles.name as posisi_ajuan',
        );
        $query->join('hrd_karyawan', 'hrd_izinabsen.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_izinabsen.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->leftJoin('hrd_izinabsen_disposisi as disposisi', function ($join) {
            $join->on('hrd_izinabsen.kode_izin', '=', 'disposisi.kode_izin')
                ->whereRaw('disposisi.kode_disposisi IN (SELECT MAX(kode_disposisi) FROM hrd_izinabsen_disposisi GROUP BY kode_izin)');
        });



        $query->leftJoin('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
        $query->leftJoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
        $query->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
        if (!in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
            if ($user->hasRole('gm operasional')) {
                $query->whereIn('hrd_izinabsen.kode_dept', ['PDQ', 'PMB', 'GDG', 'MTC', 'PRD', 'GAF', 'HRD']);
                $query->whereIn('hrd_izinabsen.kode_jabatan', ['J05', 'J06']);
            } else if ($user->hasRole('gm administrasi')) { //GM ADMINISTRASI
                $query->whereIn('hrd_izinabsen.kode_dept', ['AKT', 'KEU']);
                $query->whereIn('hrd_izinabsen.kode_jabatan', ['J04', 'J05', 'J06']);
            } elseif ($user->hasRole('gm marketing')) { //GM MARKETING
                $query->whereIn('hrd_izinabsen.kode_dept', ['MKT']);
                $query->whereIn('hrd_izinabsen.kode_jabatan', ['J03', 'J05', 'J06']);
            } else if ($user->hasRole('regional sales manager')) { //REG. SALES MANAGER
                $query->where('hrd_izinabsen.kode_dept', 'MKT');
                $query->where('hrd_izinabsen.kode_jabatan', 'J07');
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else if ($user->hasRole('regional operation manager')) { //REG. OPERATION MANAGER
                $query->where('hrd_izinabsen.kode_dept', 'AKT');
                $query->whereIn('hrd_izinabsen.kode_jabatan', ['J05', 'J06']);
            } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
                $query->where('hrd_izinabsen.kode_dept', ['AKT', 'KEU']);
                $query->where('hrd_izinabsen.kode_cabang', 'PST');
                $query->whereIn('hrd_izinabsen.kode_jabatan', ['J08', 'J12', 'J13', 'J14']);
            } else {
                if (auth()->user()->kode_cabang == 'PST') {
                    $query->where('hrd_izinabsen.kode_dept', auth()->user()->kode_dept);
                    $query->where('hrd_izinabsen.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    $query->where('hrd_izinabsen.kode_cabang', auth()->user()->kode_cabang);
                }
            }

            if (!empty($request)) {
                if (!empty($request->dari) && !empty($request->sampai)) {
                    $query->whereBetween('hrd_izinabsen.tanggal', [$request->dari, $request->sampai]);
                }

                if (!empty($request->kode_cabang)) {
                    $query->where('hrd_izinabsen.kode_cabang', $request->kode_cabang);
                }

                if (!empty($request->kode_dept)) {
                    $query->where('hrd_izinabsen.kode_dept', $request->kode_dept);
                }

                if (!empty($request->status)) {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinabsen.status', '0');
                    } else if ($request->status == "disetujui") {
                        $query->where('hrd_izinabsen.status', '1');
                    }
                }

                if (!empty($request->posisi_ajuan)) {
                    $query->where('roles.name', $request->posisi_ajuan);
                }
            }

            $query->where('hrd_izinabsen.status', '1');
            if ($user->hasRole('gm operasional')) {
                $query->orwhereIn('hrd_izinabsen.kode_dept', ['PDQ', 'PMB', 'GDG', 'MTC', 'PRD', 'GAF', 'HRD']);
                $query->whereIn('hrd_izinabsen.kode_jabatan', ['J05', 'J06']);
            } else if ($user->hasRole('gm administrasi')) { //GM ADMINISTRASI
                $query->orwhereIn('hrd_izinabsen.kode_dept', ['AKT', 'KEU']);
                $query->whereIn('hrd_izinabsen.kode_jabatan', ['J04', 'J05', 'J06']);
            } elseif ($user->hasRole('gm marketing')) { //GM MARKETING
                $query->orwhereIn('hrd_izinabsen.kode_dept', ['MKT']);
                $query->whereIn('hrd_izinabsen.kode_jabatan', ['J03', 'J05', 'J06']);
            } else if ($user->hasRole('regional sales manager')) { //REG. SALES MANAGER
                $query->orwhere('hrd_izinabsen.kode_dept', 'MKT');
                $query->where('hrd_izinabsen.kode_jabatan', 'J07');
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else if ($user->hasRole('regional operation manager')) { //REG. OPERATION MANAGER
                $query->orwhere('hrd_izinabsen.kode_dept', 'AKT');
                $query->whereIn('hrd_izinabsen.kode_jabatan', ['J05', 'J06']);
            } else if ($user->hasRole('manager keuangan')) { //MANAGER KEUANGAN
                $query->orwhere('hrd_izinabsen.kode_dept', ['AKT', 'KEU']);
                $query->where('hrd_izinabsen.kode_cabang', 'PST');
                $query->whereIn('hrd_izinabsen.kode_jabatan', ['J08', 'J12', 'J13', 'J14']);
            } else {
                if (auth()->user()->kode_cabang == 'PST') {
                    $query->orwhere('hrd_izinabsen.kode_dept', auth()->user()->kode_dept);
                    $query->where('hrd_izinabsen.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    $query->orwhere('hrd_izinabsen.kode_cabang', auth()->user()->kode_cabang);
                }
            }
            $query->WhereIn('hrd_izinabsen.kode_izin', function ($query) use ($user) {
                $query->select('disposisi.kode_izin');
                $query->from('hrd_izinabsen as disposisi');
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
                    $query->whereBetween('hrd_izinabsen.tanggal', [$request->dari, $request->sampai]);
                }

                if (!empty($request->kode_cabang)) {
                    $query->where('hrd_izinabsen.kode_cabang', $request->kode_cabang);
                }

                if (!empty($request->kode_dept)) {
                    $query->where('hrd_izinabsen.kode_dept', $request->kode_dept);
                }

                if (!empty($request->status)) {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinabsen.status', '0');
                    } else if ($request->status == "disetujui") {
                        $query->where('hrd_izinabsen.status', '1');
                    }
                }

                if (!empty($request->posisi_ajuan)) {
                    $query->where('roles.name', $request->posisi_ajuan);
                }
            }

            //Jika User Memiliki Permission create izin absen
            if ($user->can('izinabsen.create') && auth()->user()->kode_cabang != 'PST') {
                $query->orWhere('hrd_izinabsen.kode_cabang', auth()->user()->kode_cabang);
                if (!empty($request)) {
                    if (!empty($request->dari) && !empty($request->sampai)) {
                        $query->whereBetween('hrd_izinabsen.tanggal', [$request->dari, $request->sampai]);
                    }

                    if (!empty($request->kode_cabang)) {
                        $query->where('hrd_izinabsen.kode_cabang', $request->kode_cabang);
                    }

                    if (!empty($request->kode_dept)) {
                        $query->where('hrd_izinabsen.kode_dept', $request->kode_dept);
                    }

                    if (!empty($request->status)) {
                        if ($request->status == 'pending') {
                            $query->where('hrd_izinabsen.status', '0');
                        } else if ($request->status == "disetujui") {
                            $query->where('hrd_izinabsen.status', '1');
                        }
                    }

                    if (!empty($request->posisi_ajuan)) {
                        $query->where('roles.name', $request->posisi_ajuan);
                    }
                }
            }
        } else if ($user->hasRole('direktur')) {
            if (!empty($request)) {
                if (!empty($request->dari) && !empty($request->sampai)) {
                    $query->whereBetween('hrd_izinabsen.tanggal', [$request->dari, $request->sampai]);
                }

                if (!empty($request->kode_cabang)) {
                    $query->where('hrd_izinabsen.kode_cabang', $request->kode_cabang);
                }

                if (!empty($request->kode_dept)) {
                    $query->where('hrd_izinabsen.kode_dept', $request->kode_dept);
                }

                if (!empty($request->status)) {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinabsen.status', '0');
                    } else if ($request->status == "disetujui") {
                        $query->where('hrd_izinabsen.status', '1');
                    }
                }

                if (!empty($request->posisi_ajuan)) {
                    $query->where('roles.name', $request->posisi_ajuan);
                }
            }



            $query->where('hrd_izinabsen.status', '1');
            $query->orWhereIn('hrd_izinabsen.kode_izin', function ($query) use ($user) {
                $query->select('disposisi.kode_izin');
                $query->from('hrd_izinabsen as disposisi');
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
                    $query->whereBetween('hrd_izinabsen.tanggal', [$request->dari, $request->sampai]);
                }

                if (!empty($request->kode_cabang)) {
                    $query->where('hrd_izinabsen.kode_cabang', $request->kode_cabang);
                }

                if (!empty($request->kode_dept)) {
                    $query->where('hrd_izinabsen.kode_dept', $request->kode_dept);
                }

                if (!empty($request->status)) {
                    if ($request->status == 'pending') {
                        $query->where('hrd_izinabsen.status', '0');
                    } else if ($request->status == "disetujui") {
                        $query->where('hrd_izinabsen.status', '1');
                    }
                }

                if (!empty($request->posisi_ajuan)) {
                    $query->where('roles.name', $request->posisi_ajuan);
                }
            }
        } else {
            if (!empty($request)) {
                if (!empty($request->dari) && !empty($request->sampai)) {
                    $query->whereBetween('hrd_lembur.tanggal', [$request->dari, $request->sampai]);
                }

                if (!empty($request->kategori)) {
                    $query->where('hrd_lembur.kategori', $request->kategori);
                }

                if (!empty($request->kode_dept)) {
                    $query->where('hrd_lembur.kode_dept', $request->kode_dept);
                }
                if (!empty($request->status)) {
                    if ($request->status == 'pending') {
                        $query->where('hrd_lembur.status', '0');
                    } else if ($request->status == "disetujui") {
                        $query->where('hrd_lembur.status', '1');
                    }
                }

                if (!empty($request->posisi_ajuan)) {
                    $query->where('roles.name', $request->posisi_ajuan);
                }
            }
        }


        if (!empty($kode_izin)) {
            $query->where('hrd_izinabsen.kode_izin', $kode_izin);
        }

        $query->orderBy('hrd_izinabsen.tanggal', 'desc');
        return $query;
    }
}

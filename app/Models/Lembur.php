<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Lembur extends Model
{
    use HasFactory;
    protected $table = "hrd_lembur";
    protected $primaryKey = "kode_lembur";
    protected $guarded = [];
    public $incrementing = false;

    function getLembur($kode_lembur = null, Request $request = null)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $query = Lembur::query();
        $query->select(
            'hrd_lembur.*',
            'nama_cabang',
            'nama_dept',
            'disposisi.id_pengirim',
            'disposisi.id_penerima',
            'roles.name as posisi_ajuan',
        );
        $query->join('cabang', 'hrd_lembur.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('hrd_departemen', 'hrd_lembur.kode_dept', '=', 'hrd_departemen.kode_dept');

        $query->leftJoin('hrd_lembur_disposisi as disposisi', function ($join) {
            $join->on('hrd_lembur.kode_lembur', '=', 'disposisi.kode_lembur')
                ->whereRaw('disposisi.kode_disposisi IN (SELECT MAX(kode_disposisi) FROM hrd_lembur_disposisi GROUP BY kode_lembur)');
        });



        $query->leftJoin('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
        $query->leftJoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
        $query->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id');




        if (!in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi', 'direktur'])) {
            if ($user->hasRole('gm operasional')) {
                $query->whereIn('hrd_lembur.kode_dept', ['PDQ', 'PMB', 'GDG', 'MTC', 'PRD', 'GAF', 'HRD']);
            } else {
                if ($user->kode_cabang == 'PST') {
                    $query->where('hrd_lembur.kode_dept', auth()->user()->kode_dept);
                    $query->where('hrd_lembur.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    $query->where('hrd_lembur.kode_cabang', auth()->user()->kode_cabang);
                }
            }

            if (!empty($kode_lembur)) {
                $query->where('hrd_lembur.kode_lembur', $kode_lembur);
            }
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

            $query->where('hrd_lembur.status', '1');

            if ($user->hasRole('gm operasional')) {
                $query->orwhereIn('hrd_lembur.kode_dept', ['PDQ', 'PMB', 'GDG', 'MTC', 'PRD', 'GAF', 'HRD']);
            } else {
                if ($user->kode_cabang == 'PST') {
                    $query->orwhere('hrd_lembur.kode_dept', auth()->user()->kode_dept);
                    $query->where('hrd_lembur.kode_cabang', auth()->user()->kode_cabang);
                } else {
                    $query->orwhere('hrd_lembur.kode_cabang', auth()->user()->kode_cabang);
                }
            }

            $query->WhereIn('hrd_lembur.kode_lembur', function ($query) use ($user) {
                $query->select('disposisi.kode_lembur');
                $query->from('hrd_lembur_disposisi as disposisi');
                $query->join('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
                $query->join('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
                $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');

                $query->join('users as pengirim', 'disposisi.id_pengirim', '=', 'pengirim.id');
                $query->join('model_has_roles as model_has_roles_pengirim', 'pengirim.id', '=', 'model_has_roles_pengirim.model_id');
                $query->join('roles as roles_pengirim', 'model_has_roles_pengirim.role_id', '=', 'roles_pengirim.id');

                $query->where('roles.name', $user->getRoleNames()->first());
                $query->orWhere('roles_pengirim.name', $user->getRoleNames()->first());
            });

            if (!empty($kode_lembur)) {
                $query->where('hrd_lembur.kode_lembur', $kode_lembur);
            }
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
        } else if ($user->hasRole('direktur')) {

            if (!empty($kode_lembur)) {
                $query->where('hrd_lembur.kode_lembur', $kode_lembur);
            }
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

            $query->where('hrd_lembur.status', '1');

            $query->orWhereIn('hrd_lembur.kode_lembur', function ($query) use ($user) {
                $query->select('disposisi.kode_lembur');
                $query->from('hrd_lembur_disposisi as disposisi');
                $query->join('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
                $query->join('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
                $query->join('roles', 'model_has_roles.role_id', '=', 'roles.id');

                $query->join('users as pengirim', 'disposisi.id_pengirim', '=', 'pengirim.id');
                $query->join('model_has_roles as model_has_roles_pengirim', 'pengirim.id', '=', 'model_has_roles_pengirim.model_id');
                $query->join('roles as roles_pengirim', 'model_has_roles_pengirim.role_id', '=', 'roles_pengirim.id');

                $query->where('roles.name', $user->getRoleNames()->first());
                $query->orWhere('roles_pengirim.name', $user->getRoleNames()->first());
            });
            if (!empty($kode_lembur)) {
                $query->where('hrd_lembur.kode_lembur', $kode_lembur);
            }
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
        } else {
            if (!empty($kode_lembur)) {
                $query->where('hrd_lembur.kode_lembur', $kode_lembur);
            }
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


        $query->orderBy('hrd_lembur.tanggal', 'desc');
        return $query;
    }
}

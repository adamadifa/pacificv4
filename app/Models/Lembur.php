<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\User;

class Lembur extends Model
{
    use HasFactory;
    protected $table = "hrd_lembur";
    protected $primaryKey = "kode_lembur";
    protected $guarded = [];
    public $incrementing = false;

    function getLembur($kode_lembur = null, Request $request = null)
    {
        $user = User::findOrFail(auth()->user()->id);
        $query = Lembur::query();

        // 1. SELECT & JOINS
        $query->select(
            'hrd_lembur.*',
            'cabang.nama_cabang',
            'hrd_departemen.nama_dept',
        );

        $query->join('cabang', 'hrd_lembur.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('hrd_departemen', 'hrd_lembur.kode_dept', '=', 'hrd_departemen.kode_dept');

        // 2. DATA ACCESS RESTRICTIONS
        if (!$user->hasRole(['super admin', 'asst. manager hrd', 'spv presensi'])) {
            $query->where(function ($access) use ($user) {
                $dept_access = json_decode($user->dept_access, true) ?? [];
                $cabang_access = json_decode($user->cabang_access, true) ?? [];

                // Branch Access
                if (!in_array('all', $cabang_access)) {
                    if (!empty($cabang_access)) {
                        $access->whereIn('hrd_lembur.kode_cabang', $cabang_access);
                    } else {
                        // Default logic if cabang_access is empty
                        if (empty($user->kode_regional) || $user->kode_regional == 'R00') {
                            $access->where('hrd_lembur.kode_cabang', $user->kode_cabang);
                        }
                    }
                }

                // Department Access
                if (!in_array('all', $dept_access)) {
                    $access->whereIn('hrd_lembur.kode_dept', $dept_access);
                }

                // Regional Access
                if (!empty($user->kode_regional) && $user->kode_regional != 'R00') {
                    $access->where('cabang.kode_regional', $user->kode_regional);
                }
            });
        }

        // 3. REQUEST FILTERS
        if ($kode_lembur) {
            $query->where('hrd_lembur.kode_lembur', $kode_lembur);
        }

        if ($request) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('hrd_lembur.tanggal', [$request->dari, $request->sampai]);
            }

            if (!empty($request->kategori)) {
                $query->where('hrd_lembur.kategori', $request->kategori);
            }

            if (!empty($request->kode_dept)) {
                $query->where('hrd_lembur.kode_dept', $request->kode_dept);
            }

            if (!empty($request->kode_cabang)) {
                $query->where('hrd_lembur.kode_cabang', $request->kode_cabang);
            }

            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->where('hrd_lembur.status', '0');
                    // For non-admin, restrict to their approval position
                    if (!$user->hasRole(['super admin', 'asst. manager hrd', 'spv presensi'])) {
                        $roles = $user->getRoleNames()->toArray();
                        $query->whereIn('hrd_lembur.posisi_ajuan', $roles);
                    }
                } else if ($request->status == "disetujui") {
                    $query->where('hrd_lembur.status', '1');
                }
            }

            if (!empty($request->posisi_ajuan)) {
                $query->where('hrd_lembur.posisi_ajuan', $request->posisi_ajuan);
            }
        }

        $query->orderBy('hrd_lembur.tanggal', 'desc');
        return $query;
    }
}

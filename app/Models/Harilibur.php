<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Harilibur extends Model
{
    use HasFactory;
    protected $table = "hrd_harilibur";
    protected $primaryKey = "kode_libur";
    protected $guarded = [];
    public $incrementing  = false;

    function getHarilibur($kode_libur = null, Request $request = null)
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $query = Harilibur::query();
        $query->select('hrd_harilibur.*', 'nama_cabang', 'nama_dept', 'nama_kategori', 'color');
        $query->join('cabang', 'hrd_harilibur.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('hrd_departemen', 'hrd_harilibur.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('hrd_harilibur_kategori', 'hrd_harilibur.kategori', '=', 'hrd_harilibur_kategori.kode_kategori');
        if (!empty($kode_libur)) {
            $query->where('hrd_harilibur.kode_libur', $kode_libur);
        }

        if (!in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
            if ($user->kode_cabang != 'PST') {
                $query->where('hrd_harilibur.kode_cabang', $user->kode_cabang);
            } else {
                $query->where('hrd_harilibur.kode_dept', $user->kode_dept);
            }
        }

        if (!empty($request)) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('hrd_harilibur.tanggal', [$request->dari, $request->sampai]);
            }

            if (!empty($request->kategori)) {
                $query->where('hrd_harilibur.kategori', $request->kategori);
            }

            if (!empty($request->kode_cabang)) {
                $query->where('hrd_harilibur.kode_cabang', $request->kode_cabang);
            }

            if (!empty($request->kode_dept)) {
                $query->where('hrd_harilibur.kode_dept', $request->kode_dept);
            }

            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->where('hrd_harilibur.status', '0');
                } else if ($request->status == "disetujui") {
                    $query->where('hrd_harilibur.status', '1');
                }
            }
        }
        $query->orderBy('hrd_harilibur.tanggal', 'desc');
        return $query;
    }
}

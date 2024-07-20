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
        $query->select('hrd_lembur.*', 'nama_cabang', 'nama_dept');
        $query->join('cabang', 'hrd_lembur.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('hrd_departemen', 'hrd_lembur.kode_dept', '=', 'hrd_departemen.kode_dept');
        if (!empty($kode_libur)) {
            $query->where('hrd_harilibur.kode_libur', $kode_libur);
        }

        if (!in_array($role, ['super admin', 'asst. manager hrd', 'spv presensi'])) {
            $query->where('hrd_lembur.kode_cabang', $user->kode_cabang);
            $query->where('hrd_lembur.kode_dept', $user->kode_dept);
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
        }
        $query->orderBy('hrd_lembur.tanggal', 'desc');
        return $query;
    }
}

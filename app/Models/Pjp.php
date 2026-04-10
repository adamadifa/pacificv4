<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Pjp extends Model
{
    use HasFactory;
    protected $table = 'keuangan_pjp';
    protected $primaryKey = "no_pinjaman";
    protected $guarded = [];
    public $incrementing = false;

    function getPjp($no_pinjaman = "", Request $request = null)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_pjp = config('global.roles_access_all_pjp');
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $dept_access = json_decode($user->dept_access, true) != null ? json_decode($user->dept_access, true) : [];
        $query = Pjp::query();
        $query->select(
            'keuangan_pjp.*',
            'nama_karyawan',
            'nama_jabatan',
            'hrd_jabatan.kategori as kategori_jabatan',
            'hrd_karyawan.kode_dept',
            'hrd_karyawan.kode_cabang',
            'nama_dept',
            'nama_cabang',
            'totalpembayaran',
            'tanggal_masuk',
            'keuangan_ledger.tanggal as tanggal_proses'
        );
        $query->join('hrd_karyawan', 'keuangan_pjp.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('keuangan_ledger_pjp', 'keuangan_pjp.no_pinjaman', '=', 'keuangan_ledger_pjp.no_pinjaman');
        $query->leftJoin('keuangan_ledger', 'keuangan_ledger_pjp.no_bukti', '=', 'keuangan_ledger.no_bukti');
        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpembayaran FROM keuangan_pjp_historibayar GROUP BY no_pinjaman
        ) historibayar"),
            function ($join) {
                $join->on('keuangan_pjp.no_pinjaman', '=', 'historibayar.no_pinjaman');
            }
        );

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('keuangan_pjp.tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_search);
        }

        //Report Keuangan
        if (!empty($request->kode_cabang_pinjaman)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_pinjaman);
        }

        if (!empty($request->kode_dept_pinjaman)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_dept_pinjaman);
        }


        if (!empty($request->nama_karyawan_search)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan_search . '%');
        }

        if (!empty($request->kode_group_pjp)) {
            $query->where('hrd_karyawan.kode_group', $request->kode_group_pjp);
        }

        $query = self::applyPjpAccess($query, $user);

        if (!empty($no_pinjaman)) {
            $query->where('keuangan_pjp.no_pinjaman', $no_pinjaman);
        }

        $query->orderBy('keuangan_pjp.tanggal', 'desc');
        $query->orderBy('keuangan_pjp.no_pinjaman', 'desc');
        return $query;
    }

    public static function applyPjpAccess($query, $user)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        // PJP Access Filters
        $pjp_cabang_access = json_decode($user->pjp_cabang_access, true) ?? [];
        $pjp_dept_access = json_decode($user->pjp_dept_access, true) ?? [];
        $pjp_jabatan_access = json_decode($user->pjp_jabatan_access, true) ?? [];
        $pjp_karyawan_access = json_decode($user->pjp_karyawan_access, true) ?? [];
        $pjp_group_access = json_decode($user->pjp_group_access, true) ?? [];
        $pjp_kategori_jabatan_access = json_decode($user->pjp_kategori_jabatan_access, true) ?? [];

        if (!in_array('all', $pjp_cabang_access)) {
            $query->whereIn('hrd_karyawan.kode_cabang', $pjp_cabang_access);
        }

        if (!in_array('all', $pjp_dept_access)) {
            $query->whereIn('hrd_karyawan.kode_dept', $pjp_dept_access);
        }

        if (!in_array('all', $pjp_jabatan_access)) {
            $query->whereIn('hrd_karyawan.kode_jabatan', $pjp_jabatan_access);
        }

        if (!in_array('all', $pjp_karyawan_access)) {
            $query->whereIn('hrd_karyawan.nik', $pjp_karyawan_access);
        }

        if (!empty($pjp_group_access) && !in_array('all', $pjp_group_access)) {
            $query->whereIn('hrd_karyawan.kode_group', $pjp_group_access);
        }

        $query->whereIn('hrd_jabatan.kategori', $pjp_kategori_jabatan_access);

        if (!$user->hasRole($roles_access_all_cabang) && empty($pjp_cabang_access)) {
            $query->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
        }

        return $query;
    }
}

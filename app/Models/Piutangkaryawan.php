<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Piutangkaryawan extends Model
{
    use HasFactory;
    protected $table = "keuangan_piutangkaryawan";
    protected $primaryKey = "no_pinjaman";
    protected $guarded = [];
    public $incrementing = false;

    function getPiutangkaryawan($no_pinjaman = "", Request $request = null)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_pjp = config('global.roles_access_all_pjp');
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];
        $query = Piutangkaryawan::query();
        $query->select(
            'keuangan_piutangkaryawan.*',
            'nama_karyawan',
            'nama_jabatan',
            'hrd_karyawan.kode_dept',
            'hrd_karyawan.kode_cabang',
            'nama_dept',
            'nama_cabang',
            'totalpembayaran',
            'tanggal_masuk',

        );
        $query->join('hrd_karyawan', 'keuangan_piutangkaryawan.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin(
            DB::raw("(
            SELECT no_pinjaman,SUM(jumlah) as totalpembayaran FROM keuangan_piutangkaryawan_historibayar GROUP BY no_pinjaman
        ) historibayar"),
            function ($join) {
                $join->on('keuangan_piutangkaryawan.no_pinjaman', '=', 'historibayar.no_pinjaman');
            }
        );

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('keuangan_piutangkaryawan.tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_search);
        }

        //Report Piutang Karyawan
        if (!empty($request->kode_cabang_piutangkaryawan)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_piutangkaryawan);
        }

        if (!empty($request->kode_dept_piutangkaryawan)) {
            $query->where('hrd_karyawan.kode_dept', $request->kode_dept_piutangkaryawan);
        }


        if (!empty($request->nama_karyawan_search)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan_search . '%');
        }

        // if ($request->status === "1" || $request->status === 0) {
        //     $query->where('pjp.status', $request->status);
        // }

        if (!$user->hasRole($roles_access_all_pjp)) {
            $query->where('keuangan_piutangkaryawan.status', '0');
        }

        if (!empty($no_pinjaman)) {
            $query->where('keuangan_piutangkaryawan.no_pinjaman', $no_pinjaman);
        }
        //Jika User Tidak Memiliki Akses ke Semua PJP
        // if (!$user->hasRole($roles_access_all_pjp)) {
        //     $query->whereNotIn('hrd_karyawan.kode_jabatan', ['J01', 'J02']);
        // }

        // $query->whereIn('hrd_karyawan.kode_dept', $dept_access);

        // if (!$user->hasRole($roles_access_all_pjp)) {
        //     $query->where('hrd_jabatan.kategori', 'NM');
        // }


        $query->orderBy('keuangan_piutangkaryawan.tanggal', 'desc');
        $query->orderBy('keuangan_piutangkaryawan.no_pinjaman', 'desc');
        return $query;
    }
}

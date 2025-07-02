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
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];
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

        // if ($request->status === "1" || $request->status === 0) {
        //     $query->where('pjp.status', $request->status);
        // }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', $user->kode_regional);
                $query->where('hrd_karyawan.kode_jabatan', '!=', 'J03');
                // $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            } else {
                if ($user->hasRole('sales marketing manager')) {
                    $query->where('hrd_karyawan.kode_jabatan', '!=', 'J07');
                } else {
                    $query->where('hrd_jabatan.kategori', 'NM');
                }
                $query->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
                // $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            }
        } else {
            if (!$user->hasRole($roles_access_all_pjp)) {
                if (!$user->hasRole('regional operation manager')) {
                    $query->where('hrd_jabatan.kategori', 'NM');
                } else {
                    $query->whereNotIn('hrd_karyawan.kode_jabatan', ['J01', 'J02', 'J04']);
                }
            } else {
                if (!$user->hasRole(['super admin', 'manager keuangan', 'gm administrasi', 'staff keuangan'])) {
                    $query->whereNotIn('hrd_karyawan.kode_jabatan', ['J01', 'J02']);
                }
            }
        }

        if (!empty($no_pinjaman)) {
            $query->where('keuangan_pjp.no_pinjaman', $no_pinjaman);
        }
        //Jika User Tidak Memiliki Akses ke Semua PJP
        // if (!$user->hasRole($roles_access_all_pjp)) {
        //     $query->whereNotIn('hrd_karyawan.kode_jabatan', ['J01', 'J02']);
        // }

        $query->whereIn('hrd_karyawan.kode_dept', $dept_access);
        if (auth()->user()->id == '86') {
            $query->whereIn('hrd_karyawan.kode_group', ['G19', 'G22', 'G23']);
        } else if (auth()->user()->id == '87') {
            $query->whereNotIn('hrd_karyawan.kode_group', ['G19', 'G22', 'G23']);
        }
        // if (!$user->hasRole($roles_access_all_pjp)) {
        //     $query->where('hrd_jabatan.kategori', 'NM');
        // }


        $query->orderBy('keuangan_pjp.tanggal', 'desc');
        $query->orderBy('keuangan_pjp.no_pinjaman', 'desc');
        return $query;
    }
}

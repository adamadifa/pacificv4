<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Kasbon extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'keuangan_kasbon';
    protected $primaryKey = "no_kasbon";
    protected $guarded = [];
    public $incrementing = false;

    function getKasbon($no_kasbon = "", Request $request = null)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_pjp = config('global.roles_access_all_pjp');
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];

        // dd($dept_access);
        $query = Kasbon::query();
        $query->select(
            'keuangan_kasbon.*',
            'nama_karyawan',
            'nama_jabatan',
            'hrd_karyawan.kode_dept',
            'hrd_karyawan.kode_cabang',
            'nama_dept',
            'nama_cabang',
            'tanggal_bayar',
            'totalpembayaran',
            'tanggal_masuk',
            'keuangan_ledger.tanggal as tanggal_proses'
        );
        $query->join('hrd_karyawan', 'keuangan_kasbon.nik', '=', 'hrd_karyawan.nik');
        $query->join('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->join('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->join('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('keuangan_ledger_kasbon', 'keuangan_kasbon.no_kasbon', '=', 'keuangan_ledger_kasbon.no_kasbon');
        $query->leftJoin('keuangan_ledger', 'keuangan_ledger_kasbon.no_bukti', '=', 'keuangan_ledger.no_bukti');
        $query->leftJoin(
            DB::raw("(
            SELECT no_kasbon,tanggal as tanggal_bayar,jumlah as totalpembayaran FROM keuangan_kasbon_historibayar
        ) historibayar"),
            function ($join) {
                $join->on('keuangan_kasbon.no_kasbon', '=', 'historibayar.no_kasbon');
            }
        );

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('keuangan_kasbon.tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_search);
        }

        //Report Kasbon
        if (!empty($request->kode_cabang_kasbon)) {
            $query->where('hrd_karyawan.kode_cabang', $request->kode_cabang_kasbon);
        }

        if (!empty($request->kode_dept_kasbon)) {
            $query->where('hrd_karyawan.kode_dept', $request->kode_dept_kasbon);
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
                $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            } else {
                if ($user->hasRole('sales marketing manager')) {
                    $query->where('hrd_karyawan.kode_jabatan', '!=', 'J07');
                } else {
                    $query->where('hrd_jabatan.kategori', 'NM');
                }
                $query->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
                $query->where('hrd_karyawan.kode_dept', $user->kode_dept);
            }
        } else {
            if (!$user->hasRole($roles_access_all_pjp)) {
                if (!$user->hasRole('regional operation manager')) {
                    $query->where('hrd_jabatan.kategori', 'NM');
                } else {
                    $query->whereNotIn('hrd_karyawan.kode_jabatan', ['J01', 'J02']);
                }
            } else {
                if (!$user->hasRole(['super admin', 'manager keuangan', 'gm administrasi', 'staff keuangan'])) {
                    $query->whereNotIn('hrd_karyawan.kode_jabatan', ['J01', 'J02']);
                }
            }
        }

        if (!empty($no_kasbon)) {
            $query->where('keuangan_kasbon.no_kasbon', $no_kasbon);
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


        $query->orderBy('keuangan_kasbon.tanggal', 'desc');
        $query->orderBy('keuangan_kasbon.no_kasbon', 'desc');
        return $query;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Ajuantransferdana extends Model
{
    use HasFactory;

    protected $table = "keuangan_ajuantransferdana";
    protected $primaryKey = "no_pengajuan";
    protected $guarded = [];
    public $incrementing = false;

    function getAjuantransferdana($no_pengajuan = "", Request $request = null)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Ajuantransferdana::query();
        $query->select(
            'keuangan_ajuantransferdana.*',
            'keuangan_setoranpusat_ajuantransfer.kode_setoran',
            'keuangan_setoranpusat.tanggal as tanggal_proses',
            'keuangan_setoranpusat.status as status_setoran',
            'nama_cabang'
        );
        $query->join('cabang', 'keuangan_ajuantransferdana.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('keuangan_setoranpusat_ajuantransfer', 'keuangan_ajuantransferdana.no_pengajuan', '=', 'keuangan_setoranpusat_ajuantransfer.no_pengajuan');
        $query->leftJoin('keuangan_setoranpusat', 'keuangan_setoranpusat_ajuantransfer.kode_setoran', '=', 'keuangan_setoranpusat.kode_setoran');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('keuangan_ajuantransferdana.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('keuangan_ajuantransferdana.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('keuangan_ajuantransferdana.tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($no_pengajuan)) {
            $query->where('keuangan_ajuantransferdana.no_pengajuan', $no_pengajuan);
        }

        $query->orderBy('keuangan_ajuantransferdana.status');
        $query->orderBy('keuangan_ajuantransferdana.tanggal', 'desc');
        return $query;
    }
}

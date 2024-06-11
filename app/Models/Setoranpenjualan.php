<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Setoranpenjualan extends Model
{
    use HasFactory;
    protected $table = "keuangan_setoranpenjualan";
    protected $primaryKey = "kode_status_kawin";
    protected $guarded = [];
    public $incrementing  = false;

    function getSetoranpenjualan($kode_setoran = "", Request $request = null)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Setoranpenjualan::query();
        $query->select('keuangan_setoranpenjualan.*', 'nama_salesman');
        $query->join('salesman', 'keuangan_setoranpenjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        $query->whereBetween('keuangan_setoranpenjualan.tanggal', [$request->dari, $request->sampai]);
        $query->where('salesman.kode_cabang', $request->kode_cabang_search);
        if (!empty($request->kode_salesman_search)) {
            $query->where('keuangan_setoranpenjualan.kode_salesman', $request->kode_salesman_search);
        }

        $query->orderBy('keuangan_setoranpenjualan.tanggal');
        $query->orderBy('nama_salesman');

        return $query;
    }
}

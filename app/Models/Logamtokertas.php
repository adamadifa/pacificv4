<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Logamtokertas extends Model
{
    use HasFactory;

    protected $table = "keuangan_logamtokertas";
    protected $primaryKey = "kode_logamtokertas";
    protected $guarded = [];
    public $incrementing = false;


    function getLogamtokertas($kode_logamtokertas = "", Request $request = null)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Logamtokertas::query();
        $query->join('cabang', 'keuangan_logamtokertas.kode_cabang', '=', 'cabang.kode_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('keuangan_logamtokertas.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('keuangan_logamtokertas.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('keuangan_logamtokertas.tanggal', [$request->dari, $request->sampai]);
        } else {
            if (empty($kode_logamtokertas)) {
                $query->whereNull('keuangan_logamtokertas.tanggal');
            }
        }

        if (!empty($kode_logamtokertas)) {
            $query->where('keuangan_logamtokertas.kode_logamtokertas', $kode_logamtokertas);
        }

        $query->orderBy('keuangan_logamtokertas.tanggal');

        return $query;
    }
}

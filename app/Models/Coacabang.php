<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coacabang extends Model
{
    use HasFactory;
    protected $table = "coa_cabang";
    protected $guarded = [];

    function getCoacabang()
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Coacabang::query();
        $query->join('cabang', 'coa_cabang.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('coa', 'coa_cabang.kode_akun', '=', 'coa.kode_akun');
        $query->select('coa_cabang.kode_akun', 'nama_akun');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('coa_cabang.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        $query->groupBy('coa_cabang.kode_akun', 'nama_akun');
        $query->orderBy('coa_cabang.kode_akun');
        return $query;
    }
}

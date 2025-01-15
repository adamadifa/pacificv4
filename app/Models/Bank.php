<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $table = "bank";
    protected $primaryKey = "kode_bank";
    protected $guarded = [];
    public $incrementing = false;

    function getbankCabang()
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Bank::query();
        $query->join('cabang', 'bank.kode_cabang', '=', 'cabang.kode_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('bank.kode_cabang', auth()->user()->kode_cabang);
                $query->orWhereIn('bank.kode_cabang', ['TSM', 'BDG', 'BGR', 'PWT']);
            }
        } else {
            $query->where('show_on_cabang', 1);
        }

        return $query;
    }


    function getBank()
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Bank::query();
        $query->join('cabang', 'bank.kode_cabang', '=', 'cabang.kode_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else if ($user->hasRole('admin pusat')) {
                $query->where('bank.kode_cabang', '!=', 'PST');
            } else {
                $query->where('bank.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        $query->orderBy('kode_bank');
        return $query;
    }


    function getMutasibank()
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Bank::query();
        $query->join('cabang', 'bank.kode_cabang', '=', 'cabang.kode_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else if ($user->hasRole('admin pusat')) {
                $query->where('bank.kode_cabang', '!=', 'PST');
            } else {
                $query->where('bank.kode_cabang', auth()->user()->kode_cabang);
                $query->where('nama_bank', 'not like', '%giro%');
            }
        }

        $query->orderBy('kode_bank');
        return $query;
    }
}

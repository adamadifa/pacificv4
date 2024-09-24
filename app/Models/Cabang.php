<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Cabang extends Model
{
    use HasFactory;
    protected $table = "cabang";
    protected $primaryKey = "kode_cabang";
    protected $guarded = [];
    public $incrementing = false;


    function getCabang()
    {
        $id_user = auth()->user()->id;
        $user = User::findorFail($id_user);
        $kode_regional = auth()->user()->kode_regional;
        $kode_cabang = auth()->user()->kode_cabang;
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if ($user->hasRole($roles_access_all_cabang) || $user->hasRole('admin pusat')) {
            $cabang = Cabang::orderBy('kode_cabang')->get();
        } else {
            if ($kode_regional != "R00") {
                $cabang = Cabang::where('kode_regional', $kode_regional)->get();
            } else {
                $cabang = Cabang::where('kode_cabang', $kode_cabang)->get();
            }
        }

        return $cabang;
    }

    public function omancabang()
    {
        return $this->hasMany(Omancabang::class, 'kode_cabang');
    }
}

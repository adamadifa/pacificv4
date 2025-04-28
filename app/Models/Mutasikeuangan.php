<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Mutasikeuangan extends Model
{
    use HasFactory;
    protected $table = 'keuangan_mutasi';
    protected $guarded = ['id'];

    function getMutasi($no_bukti = '', Request $request = null)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Mutasikeuangan::query();
        $query->select('keuangan_mutasi.*', 'keuangan_mutasi_kategori.nama_kategori');
        $query->join('bank', 'keuangan_mutasi.kode_bank', '=', 'bank.kode_bank');
        $query->join('cabang', 'bank.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftjoin('keuangan_mutasi_kategori', 'keuangan_mutasi.kode_kategori', '=', 'keuangan_mutasi_kategori.kode_kategori');


        // if (!$user->hasRole($roles_access_all_cabang)) {
        //     if ($user->hasRole('regional sales manager')) {
        //         $query->where('cabang.kode_regional', auth()->user()->kode_regional);
        //     } else {
        //         $query->where('bank.kode_cabang', auth()->user()->kode_cabang);
        //     }
        // }

        if ($user->hasRole('staff keuangan 2')) {
            $query->where('keuangan_mutasi.kode_bank', 'BK070');
        } else {
            $query->where('keuangan_mutasi.kode_bank', $request->kode_bank_search);
        }
        $query->whereBetween('keuangan_mutasi.tanggal', [$request->dari, $request->sampai]);
        $query->orderBy('keuangan_mutasi.tanggal');
        $query->orderBy('keuangan_mutasi.created_at');
        return $query;
    }
}

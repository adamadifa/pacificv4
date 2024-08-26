<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Kaskecil;
use App\Models\User;
use Illuminate\Http\Request;

class KaskecilController extends Controller
{
    //
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Kaskecil::query();
        $query->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('kode_cabang', $request->kode_cabang_search);
            } else {
                $query->where('kode_cabang', auth()->user()->kode_cabang);
            }
        } else {
            $query->where('kode_cabang', $request->kode_cabang_search);
        }

        $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        $query->orderBy('tanggal');
        $query->orderBy('debet_kredit', 'desc');
        $query->orderBy('no_bukti');
        $kaskecil = $query->get();
        $data['kaskecil'] = $kaskecil;
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();

        return view('keuangan.kaskecil.index', $data);
    }
}

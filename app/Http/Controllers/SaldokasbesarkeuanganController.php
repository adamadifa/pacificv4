<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Saldokasbesarkeuangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class SaldokasbesarkeuanganController extends Controller
{
    public function index(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang_search;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang_search;
        }


        $qsaldokasbesar = Saldokasbesarkeuangan::query();

        if ($request->has('kode_cabang')) {
            $qsaldokasbesar->where('kode_cabang', $request->kode_cabang);
        }

        if ($request->has('dari') && $request->has('sampai')) {
            $qsaldokasbesar->where('tanggal', '>=', $request->dari);
            $qsaldokasbesar->where('tanggal', '<=', $request->sampai);
        }
        $qsaldokasbesar->join('cabang', 'keuangan_saldokasbesar.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($kode_cabang)) {
            $qsaldokasbesar->where('keuangan_saldokasbesar.kode_cabang', $kode_cabang);
        }
        $qsaldokasbesar->orderBy('tanggal');
        $saldokasbesar = $qsaldokasbesar->get();

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;

        $data['saldokasbesar'] = $saldokasbesar;
        return view('keuangan.mutasikeuangan.sakasbesarkeuangan.index', $data);
    }


    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('keuangan.mutasikeuangan.sakasbesarkeuangan.create', $data);
    }


    public function store(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);


        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
                $request->validate([
                    'keterangan' => 'required',
                    'jumlah' => 'required',
                    'tanggal' => 'required',
                    'kode_cabang' => 'required',
                ]);
            } else {
                $kode_cabang = $user->kode_cabang;
                $request->validate([
                    'keterangan' => 'required',
                    'jumlah' => 'required',
                    'tanggal' => 'required',
                ]);
            }
        } else {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'keterangan' => 'required',
                'jumlah' => 'required',
                'tanggal' => 'required',
                'kode_cabang' => 'required',
            ]);
        }

        Saldokasbesarkeuangan::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'jumlah' => toNumber($request->jumlah),
            'kode_cabang' => $kode_cabang
        ]);

        return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);

        $saldokasbesarkeuangan = Saldokasbesarkeuangan::findorfail($id);
        $saldokasbesarkeuangan->delete();
        return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
    }
}

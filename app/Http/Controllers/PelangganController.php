<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_access_all_cabang');

        $query = Pelanggan::query();
        $query->leftjoin('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'pelanggan.kode_cabang', '=', 'cabang.kode_cabang');
        if (!$user->hasRole($roles_show_cabang)) {
            if ($user->hasRole('rsm')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('pelanggan.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('pelanggan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $query->where('pelanggan.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $query->where('kode_pelanggan', $request->kode_pelanggan);
        }

        if (!empty($request->nama_pelanggan)) {
            $query->where('nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }

        $query->orderBy('tanggal_register', 'desc');

        $pelanggan = $query->paginate('30');
        $pelanggan->appends(request()->all());

        $plg = new Pelanggan();
        $jmlpelanggan = $plg->getJmlpelanggan($request);
        $jmlpelangganaktif = $plg->getJmlpelanggan($request, 1);
        $jmlpelanggannonaktif = $plg->getJmlpelanggan($request, 0);


        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.pelanggan.index', compact('pelanggan', 'cabang', 'jmlpelanggan', 'jmlpelangganaktif', 'jmlpelanggannonaktif'));
    }

    public function create()
    {
        return view('datamaster.pelanggan.create');
    }
}

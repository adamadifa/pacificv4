<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Dpb;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DpbController extends Controller
{
    public function index(Request $request)
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $query = Dpb::query();
        $query->select('no_dpb', 'tanggal_ambil', 'nama_salesman', 'nama_cabang', 'tujuan', 'no_polisi');
        $query->join('salesman', 'dpb.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('kendaraan', 'dpb.kode_kendaraan', '=', 'kendaraan.kode_kendaraan');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('dpb.tanggal_ambil', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('dpb.tanggal_ambil', [$start_date, $end_date]);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('cabang.kode_cabang', $request->kode_cabang_search);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->no_dpb_search)) {
            $query->where('dpb.no_dpb', $request->no_dpb_search);
        }

        if (!empty($request->kode_salesman_search)) {
            $query->where('dpb.kode_salesman', $request->kode_salesman_search);
        }
        $query->orderBy('tanggal_ambil', 'desc');
        $query->orderBy('dpb.created_at', 'desc');
        $dpb = $query->paginate('15');
        $dpb->appends(request()->all());
        $data['dpb'] = $dpb;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('gudangcabang.dpb.index', $data);
    }


    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('gudangcabang.dpb.create', $data);
    }
}

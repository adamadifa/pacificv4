<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Produk;
use App\Models\Salesman;
use App\Models\Targetkomisi;
use App\Models\User;
use Illuminate\Http\Request;

class TargetkomisiController extends Controller
{
    public function index(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        $query = Targetkomisi::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_komisi_target.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('marketing_komisi_target.kode_cabang', $request->kode_cabang_search);
        }
        $query->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang');
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $targetkomisi = $query->paginate(15);
        $targetkomisi->appends(request()->all());
        $data['targetkomisi'] = $targetkomisi;
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('marketing.targetkomisi.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('marketing.targetkomisi.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'kode_cabang' => 'required',
                'bulan' => 'required',
                'tahun' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'bulan' => 'required',
                'tahun' => 'required'
            ]);
        }

        $produk = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        $kode_salesman = $request->kode_salesman;
        for ($i = 0; $i < count($kode_salesman); $i++) {
            foreach ($produk as $p) {
                $kode_produk = $p->kode_produk;
                ${$kode_produk} = $request->$kode_produk;
                $data[] = [
                    'kode_salesman' => $kode_salesman[$i],
                    'kode_produk' => $kode_produk,
                    'jumlah' => ${$kode_produk}[$i]
                ];
            }
        }


        dd($data);
    }



    public function gettargetsalesman(Request $request)
    {

        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }
        $query = Salesman::query();
        $query->where('kode_cabang', $kode_cabang);
        $query->where('status_aktif_salesman', 1);
        $query->where('nama_salesman', '!=', '-');
        $query->orderBy('nama_salesman');
        $data['salesman'] = $query->get();
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();

        return view('marketing.targetkomisi.gettargetsalesman', $data);
    }
}

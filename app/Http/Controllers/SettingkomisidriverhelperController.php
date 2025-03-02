<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Detailpenjualan;
use App\Models\Settingkomisidriverhelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SettingkomisidriverhelperController extends Controller
{
    public function index(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');

        $dari = $request->tahun . '-' . $request->bulan . '-01';
        $sampai = date('Y-m-t', strtotime($dari));

        $detailpenjualan = Detailpenjualan::select(
            'salesman.kode_cabang',
            DB::raw('SUM(FLOOR(jumlah/isi_pcs_dus)) as jml_dus'),
        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan.no_faktur', '=', 'marketing_penjualan_detail.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('status_batal', 0)
            ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
            ->groupBy('salesman.kode_cabang');
        $query = Settingkomisidriverhelper::query();
        $query->select('marketing_komisi_driverhelper_setting.*', 'nama_cabang', 'jml_dus as qty_penjualan');
        $query->where('bulan', $request->bulan);
        $query->where('tahun', $request->tahun);
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_komisi_driverhelper_setting.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('marketing_komisi_driverhelper_setting.kode_cabang', $request->kode_cabang_search);
        }
        $query->join('cabang', 'marketing_komisi_driverhelper_setting.kode_cabang', '=', 'cabang.kode_cabang');
        $query->joinSub($detailpenjualan, 'detailpenjualan', function ($join) {
            $join->on('marketing_komisi_driverhelper_setting.kode_cabang', '=', 'detailpenjualan.kode_cabang');
        });
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $settingkomisidriverhelper = $query->paginate(15);
        $settingkomisidriverhelper->appends(request()->all());
        $data['settingkomisidriverhelper'] = $settingkomisidriverhelper;
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('marketing.settingkomisidriverhelper.index', $data);
    }


    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('marketing.settingkomisidriverhelper.create', $data);
    }

    public function store(Request $request)
    {
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'kode_cabang' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'komisi_salesman' => 'required',
                'qty_flat' => 'required',
                'umk' => 'required',
                'persentase' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'bulan' => 'required',
                'tahun' => 'required',
                'komisi_salesman' => 'required',
                'qty_flat' => 'required',
                'umk' => 'required',
                'persentase' => 'required'
            ]);
        }
        $kode_komisi =  "K" . $kode_cabang . $bln . $tahun;
        try {
            //code...
            Settingkomisidriverhelper::create([
                'kode_komisi' => $kode_komisi,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'kode_cabang' => $kode_cabang,
                'komisi_salesman' => toNumber($request->komisi_salesman),
                'qty_flat' => toNumber($request->qty_flat),
                'umk' => toNumber($request->umk),
                'persentase' => $request->persentase
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_komisi)
    {

        $kode_komisi = Crypt::decrypt($kode_komisi);
        $settingkomisidriverhelper = Settingkomisidriverhelper::findorFail($kode_komisi);
        $data['settingkomisidriverhelper'] = $settingkomisidriverhelper;
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('marketing.settingkomisidriverhelper.edit', $data);
    }

    public function update(Request $request, $kode_komisi)
    {
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'kode_cabang' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'komisi_salesman' => 'required',
                'qty_flat' => 'required',
                'umk' => 'required',
                'persentase' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'bulan' => 'required',
                'tahun' => 'required',
                'komisi_salesman' => 'required',
                'qty_flat' => 'required',
                'umk' => 'required',
                'persentase' => 'required'
            ]);
        }
        $kode_komisi = Crypt::decrypt($kode_komisi);
        try {
            Settingkomisidriverhelper::where('kode_komisi', $kode_komisi)->update([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'kode_cabang' => $kode_cabang,
                'komisi_salesman' => toNumber($request->komisi_salesman),
                'qty_flat' => toNumber($request->qty_flat),
                'umk' => toNumber($request->umk),
                'persentase' => $request->persentase
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

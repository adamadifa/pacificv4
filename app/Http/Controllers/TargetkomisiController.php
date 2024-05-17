<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailtargetkomisi;
use App\Models\Produk;
use App\Models\Salesman;
use App\Models\Targetkomisi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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
        $query->select('marketing_komisi_target.*', 'nama_cabang');
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
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
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
        $kode_target =  $kode_cabang . $bln . $tahun;
        $produk = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        $kode_salesman = $request->kode_salesman;
        for ($i = 0; $i < count($kode_salesman); $i++) {
            foreach ($produk as $p) {
                $kode_produk = $p->kode_produk;
                ${$kode_produk} = $request->$kode_produk;
                $data[] = [
                    'kode_target' => $kode_target,
                    'kode_salesman' => $kode_salesman[$i],
                    'kode_produk' => $kode_produk,
                    'jumlah' => toNumber(${$kode_produk}[$i])
                ];
            }
        }

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektarget = Targetkomisi::where('kode_target', $kode_target)->count();
            if ($cektarget > 0) {
                return Redirect::back()->with(messageError('Data Target Sudah Ada'));
            }
            $timestamp = Carbon::now();
            foreach ($data as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }

            Targetkomisi::create([
                'kode_target' => $kode_target,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'kode_cabang' => $kode_cabang,
                'status' => 0
            ]);

            Detailtargetkomisi::insert($data);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_target)
    {
        $kode_target = Crypt::decrypt($kode_target);
        $data['targetkomisi'] = Targetkomisi::select('marketing_komisi_target.*', 'nama_cabang')
            ->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_target', $kode_target)
            ->first();
        $produk = Detailtargetkomisi::select('kode_produk')
            ->orderBy('kode_produk')
            ->groupBy('kode_produk')
            ->where('kode_target', $kode_target)
            ->get();

        foreach ($produk as $d) {
            $select_produk[] = "SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `target_" . $d->kode_produk . "`";
        }

        $s_produk = implode(",", $select_produk);
        $data['detail'] = Detailtargetkomisi::select('marketing_komisi_target_detail.kode_salesman', 'nama_salesman', DB::raw("$s_produk"))
            ->join('salesman', 'marketing_komisi_target_detail.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('kode_target', $kode_target)
            ->groupBy('marketing_komisi_target_detail.kode_salesman', 'nama_salesman')
            ->get();

        $data['produk'] = $produk;
        return view('marketing.targetkomisi.show', $data);
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

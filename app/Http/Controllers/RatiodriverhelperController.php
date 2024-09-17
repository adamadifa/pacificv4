<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailratiodriverhelper;
use App\Models\Driverhelper;
use App\Models\Ratiokomisidriverhelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RatiodriverhelperController extends Controller
{
    public function index(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        $query = Ratiokomisidriverhelper::query();
        $query->select('marketing_komisi_ratiodriverhelper.*', 'nama_cabang');
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
                $query->where('marketing_komisi_ratiodriverhelper.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('marketing_komisi_ratiodriverhelper.kode_cabang', $request->kode_cabang_search);
        }
        $query->join('cabang', 'marketing_komisi_ratiodriverhelper.kode_cabang', '=', 'cabang.kode_cabang');
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $ratiodriverhelper = $query->paginate(15);
        $ratiodriverhelper->appends(request()->all());
        $data['ratiodriverhelper'] = $ratiodriverhelper;
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('marketing.ratiodriverhelper.index', $data);
    }


    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('marketing.ratiodriverhelper.create', $data);
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
        $kode_ratio =  "R" . $kode_cabang . $bln . $tahun;
        $kode_driver_helper = $request->kode_driver_helper;
        $ratio_default = $request->ratio_default;
        $ratio_helper = $request->ratio_helper;



        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektarget = Ratiokomisidriverhelper::where('kode_ratio', $kode_ratio)->count();
            if ($cektarget > 0) {
                return Redirect::back()->with(messageError('Data Target Sudah Ada'));
            }


            for ($i = 0; $i < count($kode_driver_helper); $i++) {
                $detail[] = [
                    'kode_ratio' => $kode_ratio,
                    'kode_driver_helper' => $kode_driver_helper[$i],
                    'ratio_default' => toNumber($ratio_default[$i]),
                    'ratio_helper' => toNumber($ratio_helper[$i]),
                ];
            }
            $timestamp = Carbon::now();
            foreach ($detail as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }
            Ratiokomisidriverhelper::create([
                'kode_ratio' => $kode_ratio,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'kode_cabang' => $kode_cabang,
                'tanggal_berlaku' => $tanggal,
            ]);

            Detailratiodriverhelper::insert($detail);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_ratio)
    {
        $kode_ratio = Crypt::decrypt($kode_ratio);
        $data['ratiodriverhelper'] = Ratiokomisidriverhelper::select('marketing_komisi_ratiodriverhelper.*', 'nama_cabang')
            ->join('cabang', 'marketing_komisi_ratiodriverhelper.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_ratio', $kode_ratio)
            ->first();

        $data['detail'] = Detailratiodriverhelper::select('marketing_komisi_ratiodriverhelper_detail.*', 'nama_driver_helper')
            ->join('driver_helper', 'marketing_komisi_ratiodriverhelper_detail.kode_driver_helper', '=', 'driver_helper.kode_driver_helper')
            ->where('kode_ratio', $kode_ratio)
            ->get();
        return view('marketing.ratiodriverhelper.show', $data);
    }

    public function edit($kode_ratio)
    {
        $kode_ratio = Crypt::decrypt($kode_ratio);
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();

        $data['ratiodriverhelper'] = Ratiokomisidriverhelper::select('marketing_komisi_ratiodriverhelper.*', 'nama_cabang')
            ->join('cabang', 'marketing_komisi_ratiodriverhelper.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_ratio', $kode_ratio)
            ->first();

        $data['detail'] = Detailratiodriverhelper::select('marketing_komisi_ratiodriverhelper_detail.*', 'nama_driver_helper')
            ->join('driver_helper', 'marketing_komisi_ratiodriverhelper_detail.kode_driver_helper', '=', 'driver_helper.kode_driver_helper')
            ->where('kode_ratio', $kode_ratio)
            ->get();
        return view('marketing.ratiodriverhelper.edit', $data);
    }


    public function update($kode_ratio, Request $request)
    {
        $kode_ratio = Crypt::decrypt($kode_ratio);
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
        $kode_ratio_new =  "R" . $kode_cabang . $bln . $tahun;
        $kode_driver_helper = $request->kode_driver_helper;
        $ratio_default = $request->ratio_default;
        $ratio_helper = $request->ratio_helper;



        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektarget = Ratiokomisidriverhelper::where('kode_ratio', $kode_ratio_new)->where('kode_ratio', '!=', $kode_ratio)->count();
            if ($cektarget > 0) {
                return Redirect::back()->with(messageError('Data Target Sudah Ada'));
            }


            for ($i = 0; $i < count($kode_driver_helper); $i++) {
                $detail[] = [
                    'kode_ratio' => $kode_ratio_new,
                    'kode_driver_helper' => $kode_driver_helper[$i],
                    'ratio_default' => toNumber($ratio_default[$i]),
                    'ratio_helper' => toNumber($ratio_helper[$i]),
                ];
            }
            $timestamp = Carbon::now();
            foreach ($detail as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }
            Ratiokomisidriverhelper::where('kode_ratio', $kode_ratio)->update([
                'kode_ratio' => $kode_ratio_new,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'kode_cabang' => $kode_cabang,
                'tanggal_berlaku' => $tanggal,
            ]);
            Detailratiodriverhelper::where('kode_ratio', $kode_ratio)->delete();
            Detailratiodriverhelper::insert($detail);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_ratio)
    {
        $kode_ratio = Crypt::decrypt($kode_ratio);
        $ratiodriverhelper = Ratiokomisidriverhelper::where('kode_ratio', $kode_ratio)->first();
        $tanggal = $ratiodriverhelper->tahun . "-" . $ratiodriverhelper->bulan . "-01";
        try {
            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Ratiokomisidriverhelper::where('kode_ratio', $kode_ratio)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function getratiodriverhelper(Request $request)
    {

        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }
        $query = Driverhelper::query();
        $query->where('kode_cabang', $kode_cabang);
        $query->orderBy('nama_driver_helper');
        $data['driverhelper'] = $query->get();

        return view('marketing.ratiodriverhelper.getratiodriverhelper', $data);
    }


    public function getratiodriverhelperedit(Request $request)
    {

        $kode_ratio = $request->kode_ratio;
        $data['detail'] = Detailratiodriverhelper::select('marketing_komisi_ratiodriverhelper_detail.*', 'nama_driver_helper', 'posisi')
            ->join('driver_helper', 'marketing_komisi_ratiodriverhelper_detail.kode_driver_helper', '=', 'driver_helper.kode_driver_helper')
            ->where('kode_ratio', $kode_ratio)
            ->get();

        return view('marketing.ratiodriverhelper.getratiodriverhelperedit', $data);
    }
}

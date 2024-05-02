<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Driverhelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use SebastianBergmann\CodeCoverage\Driver\Driver;

class DriverhelperController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_access_all_cabang');
        $query = Driverhelper::query();
        $query->join('cabang', 'driver_helper.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($request->nama_driver_helper)) {
            $query->where('nama_driver_helper', 'like', '%' . $request->nama_driver_helper . '%');
        }
        if (!empty($request->kode_cabang_search)) {
            $query->where('driver_helper.kode_cabang', $request->kode_cabang_search);
        }
        if (!$user->hasRole($roles_show_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('driver_helper.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        $query->orderBy('driver_helper.created_at', 'desc');
        $driverhelper = $query->paginate(30);
        $driverhelper->appends(request()->all());
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.driverhelper.index', compact('driverhelper', 'cabang'));
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.driverhelper.create', compact('cabang'));
    }


    public function store(Request $request)
    {


        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'nama_driver_helper' => 'required',
                'kode_cabang' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'nama_driver_helper' => 'required',
            ]);
        }




        $lastdriverhelper = Driverhelper::orderBy('kode_driver_helper', 'desc')->first();
        $last_kode_driver_helper = $lastdriverhelper->kode_driver_helper;
        $kode_driver_helper =  buatkode($last_kode_driver_helper, 'DR', 4);

        try {
            Driverhelper::create([
                'kode_driver_helper' => $kode_driver_helper,
                'nama_driver_helper' => $request->nama_driver_helper,
                'kode_cabang' => $kode_cabang
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_driver_helper)
    {
        $kode_driver_helper = Crypt::decrypt($kode_driver_helper);
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $driverhelper = Driverhelper::where('kode_driver_helper', $kode_driver_helper)->first();
        return view('datamaster.driverhelper.edit', compact('cabang', 'driverhelper'));
    }


    public function update(Request $request, $kode_driver_helper)
    {

        $kode_driver_helper = Crypt::decrypt($kode_driver_helper);
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'nama_driver_helper' => 'required',
                'kode_cabang' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'nama_driver_helper' => 'required',
            ]);
        }


        try {
            Driverhelper::where('kode_driver_helper', $kode_driver_helper)->update([
                'nama_driver_helper' => $request->nama_driver_helper,
                'kode_cabang' => $kode_cabang
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_driver_helper)
    {
        $kode_driver_helper = Crypt::decrypt($kode_driver_helper);
        try {
            Driverhelper::where('kode_driver_helper', $kode_driver_helper)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getdriverhelperbycabang(Request $request)
    {
        $kode_cabang_user = auth()->user()->kode_cabang;
        $query = Driverhelper::query();
        if ($kode_cabang_user != "PST") {
            $query->where('kode_cabang', $kode_cabang_user);
        } else {
            $query->where('kode_cabang', $request->kode_cabang);
        }
        $query->orderBy('nama_driver_helper');
        // $query->where('status_aktif_kendaraan', 1);
        $driverhelper = $query->get();


        echo "<option value=''>Pilih Driver / Helper</option>";
        foreach ($driverhelper as $d) {
            $selected = $d->kode_driver_helper == $request->kode_driver_helper ? 'selected' : '';
            echo "<option $selected value='$d->kode_driver_helper'>" . $d->nama_driver_helper . "</option>";
        }
    }
}

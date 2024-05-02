<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class WilayahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_access_all_cabang');
        $query = Wilayah::query();
        $query->join('cabang', 'wilayah.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($request->nama_wilayah)) {
            $query->where('nama_wilayah', 'like', '%' . $request->nama_wilayah . '%');
        }
        if (!empty($request->kode_cabang)) {
            $query->where('wilayah.kode_cabang', $request->kode_cabang);
        }
        if (!$user->hasRole($roles_show_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('wilayah.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        $query->orderBy('kode_wilayah', 'desc');
        $wilayah = $query->paginate(30);
        $wilayah->appends(request()->all());
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.wilayah.index', compact('wilayah', 'cabang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.wilayah.create', compact('cabang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'nama_wilayah' => 'required|max:30',
                'kode_cabang' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'nama_wilayah' => 'required|max:30',
            ]);
        }




        $lastwilayah = Wilayah::where('kode_cabang', $kode_cabang)
            ->orderBy('kode_wilayah', 'desc')
            ->first();
        $last_kode_wilayah = $lastwilayah->kode_wilayah;
        $kode_wilayah =  buatkode($last_kode_wilayah, 'W' . $kode_cabang, 6);

        try {
            Wilayah::create([
                'kode_wilayah' => $kode_wilayah,
                'nama_wilayah' => $request->nama_wilayah,
                'kode_cabang' => $kode_cabang
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */


    public function edit($kode_wilayah, Request $request)
    {
        $kode_wilayah = Crypt::decrypt($kode_wilayah);
        $wilayah = Wilayah::where('kode_wilayah', $kode_wilayah)->first();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.wilayah.edit', compact('wilayah', 'cabang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_wilayah)
    {
        $kode_wilayah = Crypt::decrypt($kode_wilayah);
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'nama_wilayah' => 'required|max:30',
                'kode_cabang' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'nama_wilayah' => 'required|max:30',
            ]);
        }

        try {
            Wilayah::where('kode_wilayah', $kode_wilayah)
                ->update([
                    'nama_wilayah' => $request->nama_wilayah,
                    'kode_cabang' => $kode_cabang
                ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_wilayah)
    {
        $kode_wilayah = Crypt::decrypt($kode_wilayah);
        try {
            Wilayah::where('kode_wilayah', $kode_wilayah)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    //GET DATA FROM AJAX
    public function getwilayahbycabang(Request $request)
    {

        $kode_cabang_user = auth()->user()->kode_cabang;
        $query = Wilayah::query();
        if ($kode_cabang_user != "PST") {
            $query->where('kode_cabang', $kode_cabang_user);
        } else {
            $query->where('kode_cabang', $request->kode_cabang);
        }

        $wilayah = $query->get();




        echo "<option value=''>Wilayah</option>";
        foreach ($wilayah as $d) {
            $selected = $d->kode_wilayah == $request->kode_wilayah ? 'selected' : '';
            echo "<option $selected value='$d->kode_wilayah'>$d->nama_wilayah</option>";
        }
    }
}

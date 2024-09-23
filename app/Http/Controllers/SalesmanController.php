<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Kategorisalesman;
use App\Models\Salesman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class SalesmanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $query = Salesman::query();
        $query->join('salesman_kategori', 'salesman.kode_kategori_salesman', '=', 'salesman_kategori.kode_kategori_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($request->nama_salesman)) {
            $query->where('nama_salesman', 'like', '%' . $request->nama_salesman . '%');
        }

        if (!empty($request->kode_cabang)) {
            $query->where('salesman.kode_cabang', $request->kode_cabang);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        $salesman = $query->paginate(10);
        $salesman->appends(request()->all());
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.salesman.index', compact('salesman', 'cabang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori_salesman = Kategorisalesman::orderBy('kode_kategori_salesman')->get();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.salesman.create', compact('kategori_salesman', 'cabang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_salesman' => 'required|unique:salesman,kode_salesman',
            'nama_salesman' => 'required',
            'alamat_salesman' => 'required',
            'no_hp_salesman' => 'required|numeric',
            'kode_kategori_salesman' => 'required',
            'status_komisi_salesman' => 'required',
            'status_aktif_salesman' => 'required',
        ]);

        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }

        $data_marker = [];
        if ($request->hasfile('marker')) {
            $marker_name =  $request->kode_salesman . "." . $request->file('marker')->getClientOriginalExtension();
            $destination_marker_path = "/public/marker";
            $marker = $marker_name;
            $data_marker = [
                'marker' => $marker
            ];
        }

        $data_salesman = [
            'kode_salesman' => textUpperCase($request->kode_salesman),
            'nama_salesman' => $request->nama_salesman,
            'alamat_salesman' => $request->alamat_salesman,
            'no_hp_salesman' => $request->no_hp_salesman,
            'kode_kategori_salesman' => $request->kode_kategori_salesman,
            'status_komisi_salesman' => $request->status_komisi_salesman,
            'status_aktif_salesman' => $request->status_aktif_salesman,
            'kode_cabang' => $kode_cabang
        ];

        $data = array_merge($data_salesman, $data_marker);
        DB::beginTransaction();
        try {
            $simpan = Salesman::create($data);
            if ($simpan) {
                if ($request->hasfile('marker')) {
                    $request->file('marker')->storeAs($destination_marker_path, $marker_name);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
            //throw $th;
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
    public function edit($kode_salesman)
    {
        $kode_salesman = Crypt::decrypt($kode_salesman);
        $salesman = Salesman::where('kode_salesman', $kode_salesman)->first();
        $kategori_salesman = Kategorisalesman::orderBy('kode_kategori_salesman')->get();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $roles_show_cabang = config('global.roles_show_cabang');
        return view('datamaster.salesman.edit', compact('kategori_salesman', 'cabang', 'roles_show_cabang', 'salesman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $kode_salesman)
    {
        $kode_salesman = Crypt::decrypt($kode_salesman);
        $salesman = Salesman::where('kode_salesman', $kode_salesman)->first();
        $request->validate([
            'nama_salesman' => 'required',
            'alamat_salesman' => 'required',
            'no_hp_salesman' => 'required|numeric',
            'kode_kategori_salesman' => 'required',
            'status_komisi_salesman' => 'required',
            'status_aktif_salesman' => 'required',
        ]);

        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }

        $data_marker = [];
        if ($request->hasfile('marker')) {
            $marker_name =  $kode_salesman . "." . $request->file('marker')->getClientOriginalExtension();
            $destination_marker_path = "/public/marker";
            $marker = $marker_name;
            $data_marker = [
                'marker' => $marker
            ];
        }

        $data_salesman = [
            'nama_salesman' => $request->nama_salesman,
            'alamat_salesman' => $request->alamat_salesman,
            'no_hp_salesman' => $request->no_hp_salesman,
            'kode_kategori_salesman' => $request->kode_kategori_salesman,
            'status_komisi_salesman' => $request->status_komisi_salesman,
            'status_aktif_salesman' => $request->status_aktif_salesman,
            'kode_cabang' => $kode_cabang
        ];

        $data = array_merge($data_salesman, $data_marker);
        DB::beginTransaction();
        try {
            $simpan = Salesman::where('kode_salesman', $kode_salesman)->update($data);
            if ($simpan) {
                if ($request->hasfile('marker')) {
                    Storage::delete($destination_marker_path . "/" . $salesman->marker);
                    $request->file('marker')->storeAs($destination_marker_path, $marker_name);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($kode_salesman)
    {
        $kode_salesman = Crypt::decrypt($kode_salesman);
        $salesman = Salesman::where('kode_salesman', $kode_salesman)->first();
        DB::beginTransaction();
        try {
            $hapus = Salesman::where('kode_salesman', $kode_salesman)->delete();
            if ($hapus) {
                $destination_marker_path = "/public/marker";
                Storage::delete($destination_marker_path . "/" . $salesman->marker);
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    //GET DATA FROM AJAX
    public function getsalesmanbycabang(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);



        $kode_cabang_user = auth()->user()->kode_cabang;
        $query = Salesman::query();
        if ($kode_cabang_user != "PST" || $user->hasRole('admin pusat')) {
            $query->where('kode_cabang', $kode_cabang_user);
        } else {
            $query->where('kode_cabang', $request->kode_cabang);
        }
        if ($user->hasRole('salesman')) {
            $query->where('kode_salesman', $user->kode_salesman);
        }
        $query->where('status_aktif_salesman', 1);
        $salesman = $query->get();




        echo "<option value=''>Salesman</option>";
        foreach ($salesman as $d) {
            $selected = $d->kode_salesman == $request->kode_salesman ? 'selected' : '';
            echo "<option $selected value='$d->kode_salesman'>$d->nama_salesman</option>";
        }
    }
}

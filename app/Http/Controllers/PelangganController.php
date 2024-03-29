<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Pelanggan;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PelangganController extends Controller
{
    public function index(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');


        $query = Pelanggan::query();
        $query->leftjoin('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'pelanggan.kode_cabang', '=', 'cabang.kode_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
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
        return view('datamaster.pelanggan.index', compact(
            'pelanggan',
            'cabang',
            'jmlpelanggan',
            'jmlpelangganaktif',
            'jmlpelanggannonaktif',
        ));
    }

    public function create()
    {

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.pelanggan.create', compact('cabang'));
    }


    public function store(Request $request)
    {


        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'nama_pelanggan' => 'required',
                'alamat_pelanggan' => 'required',
                'alamat_toko' => 'required',
                'kode_cabang' => 'required',
                'kode_salesman' => 'required',
                'kode_wilayah' => 'required',
                'hari' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'nama_pelanggan' => 'required',
                'alamat_pelanggan' => 'required',
                'alamat_toko' => 'required',
                'kode_salesman' => 'required',
                'kode_wilayah' => 'required',
                'hari' => 'required'
            ]);
        }




        $lastpelanggan = Pelanggan::where('kode_cabang', $kode_cabang)
            ->orderBy('kode_pelanggan', 'desc')
            ->first();
        $last_kode_pelanggan = $lastpelanggan->kode_pelanggan;
        $kode_pelanggan =  buatkode($last_kode_pelanggan, $kode_cabang . '-', 5);


        $data_foto = [];
        if ($request->hasfile('foto')) {
            $foto_name =  $kode_pelanggan . "." . $request->file('foto')->getClientOriginalExtension();
            $destination_foto_path = "/public/pelanggan";
            $foto = $foto_name;
            $data_foto = [
                'foto' => $foto
            ];
        }

        if (isset($request->lokasi)) {
            $lokasi = explode(",", $request->lokasi);
            $latitude = $lokasi[0];
            $longitude = $lokasi[1];
        } else {
            $latitude = NULL;
            $longitude = NULL;
        }

        $data_pelanggan = [
            'kode_pelanggan' => $kode_pelanggan,
            'tanggal_register' => date('Y-m-d'),
            'nik' => $request->nik,
            'no_kk' => $request->no_kk,
            'nama_pelanggan' => $request->nama_pelanggan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_pelanggan' => $request->alamat_pelanggan,
            'alamat_toko' => $request->alamat_toko,
            'no_hp_pelanggan' => $request->no_hp_pelanggan,
            'kode_cabang' => $kode_cabang,
            'kode_salesman' => $request->kode_salesman,
            'kode_wilayah' => $request->kode_wilayah,
            'hari' => $request->hari,
            'limit_pelanggan' => isset($request->limit_pelanggan) ?  toNumber($request->limit_pelanggan) : NULL,
            'ljt' => $request->ljt,
            'kepemilikan' => $request->kepemilikan,
            'lama_berjualan' => $request->lama_berjualan,
            'status_outlet' => $request->status_outlet,
            'type_outlet' => $request->type_outlet,
            'cara_pembayaran' => $request->cara_pembayaran,
            'lama_langganan' => $request->lama_langganan,
            'jaminan' => $request->jaminan,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'omset_toko' => isset($request->omset_toko) ?  toNumber($request->omset_toko) : NULL,
            'status_aktif_pelanggan' => $request->status_aktif_pelanggan,
        ];
        $data = array_merge($data_pelanggan, $data_foto);
        DB::beginTransaction();
        try {
            $simpan = Pelanggan::create($data);
            if ($simpan) {
                if ($request->hasfile('foto')) {
                    $request->file('foto')->storeAs($destination_foto_path, $foto_name);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.pelanggan.edit', compact('cabang', 'pelanggan'));
    }

    public function update(Request $request, $kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);

        //dd($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();



        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'nama_pelanggan' => 'required',
                'alamat_pelanggan' => 'required',
                'alamat_toko' => 'required',
                'kode_cabang' => 'required',
                'kode_salesman' => 'required',
                'kode_wilayah' => 'required',
                'hari' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'nama_pelanggan' => 'required',
                'alamat_pelanggan' => 'required',
                'alamat_toko' => 'required',
                'kode_salesman' => 'required',
                'kode_wilayah' => 'required',
                'hari' => 'required'
            ]);
        }







        $data_foto = [];
        if ($request->hasfile('foto')) {
            $foto_name =  $kode_pelanggan . "." . $request->file('foto')->getClientOriginalExtension();
            $destination_foto_path = "/public/pelanggan";
            $foto = $foto_name;
            $data_foto = [
                'foto' => $foto
            ];
        }

        if (isset($request->lokasi)) {
            $lokasi = explode(",", $request->lokasi);
            $latitude = $lokasi[0];
            $longitude = $lokasi[1];
        } else {
            $latitude = NULL;
            $longitude = NULL;
        }

        $data_pelanggan = [
            'nik' => $request->nik,
            'no_kk' => $request->no_kk,
            'nama_pelanggan' => $request->nama_pelanggan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat_pelanggan' => $request->alamat_pelanggan,
            'alamat_toko' => $request->alamat_toko,
            'no_hp_pelanggan' => $request->no_hp_pelanggan,
            'kode_cabang' => $kode_cabang,
            'kode_salesman' => $request->kode_salesman,
            'kode_wilayah' => $request->kode_wilayah,
            'hari' => $request->hari,
            'limit_pelanggan' => isset($request->limit_pelanggan) ?  toNumber($request->limit_pelanggan) : NULL,
            'ljt' => $request->ljt,
            'kepemilikan' => $request->kepemilikan,
            'lama_berjualan' => $request->lama_berjualan,
            'status_outlet' => $request->status_outlet,
            'type_outlet' => $request->type_outlet,
            'cara_pembayaran' => $request->cara_pembayaran,
            'lama_langganan' => $request->lama_langganan,
            'jaminan' => $request->jaminan,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'omset_toko' => isset($request->omset_toko) ?  toNumber($request->omset_toko) : NULL,
            'status_aktif_pelanggan' => $request->status_aktif_pelanggan,
        ];
        $data = array_merge($data_pelanggan, $data_foto);
        DB::beginTransaction();
        try {
            $simpan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->update($data);
            if ($simpan) {
                if ($request->hasfile('foto')) {
                    Storage::delete($destination_foto_path . "/" . $pelanggan->foto);
                    $request->file('foto')->storeAs($destination_foto_path, $foto_name);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        try {
            Pelanggan::where('kode_pelanggan', $kode_pelanggan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)
            ->leftjoin('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah')
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'pelanggan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $kepemilikan = config('pelanggan.kepemilikan');
        $lama_berjualan = config('pelanggan.lama_berjualan');
        $status_outlet = config('pelanggan.status_outlet');
        $type_outlet = config('pelanggan.type_outlet');
        $cara_pembayaran = config('pelanggan.cara_pembayaran');
        $lama_langganan = config('pelanggan.lama_langganan');
        return view('datamaster.pelanggan.show', compact(
            'pelanggan',
            'kepemilikan',
            'lama_berjualan',
            'status_outlet',
            'type_outlet',
            'cara_pembayaran',
            'lama_langganan'
        ));
    }
}

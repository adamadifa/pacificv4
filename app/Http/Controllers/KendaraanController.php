<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Dpb;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_access_all_cabang');
        $query  = Kendaraan::query();
        $query->orderBy('kode_kendaraan', 'desc');
        $query->where('no_polisi', '!=', 'ZL');
        if (!empty($request->no_polisi)) {
            $query->where('no_polisi', 'like', '%' . $request->no_polisi . '%');
        }

        if (!empty($request->kode_cabang)) {
            $query->where('kendaraan.kode_cabang', $request->kode_cabang);
        }

        if (!$user->hasRole($roles_show_cabang)) {
            if ($user->hasRole('rsm')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('kendaraan.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        $kendaraan = $query->paginate(10);
        $kendaraan->appends(request()->all());

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.kendaraan.index', compact('kendaraan', 'cabang'));
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.kendaraan.create', compact('cabang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_polisi' => 'required',
            // 'jatuhtempo_kir' => 'required',
            'jatuhtempo_pajak_satutahun' => 'required',
            'jatuhtempo_pajak_limatahun' => 'required',
            'kapasitas' => 'required',
            'kode_cabang' => 'required',
            'status_aktif_kendaraan' => 'required',
        ]);

        try {

            $lastkendaraan = Kendaraan::orderBy('kode_kendaraan', 'desc')->first();
            $last_kode_kendraan = $lastkendaraan != NULL ? $lastkendaraan->kode_kendaraan : '';
            $kode_kendaraan =  buatkode($last_kode_kendraan, "KD", 4);

            Kendaraan::create([
                'kode_kendaraan' => $kode_kendaraan,
                'no_polisi' => $request->no_polisi,
                'no_stnk' => $request->no_stnk,
                'no_uji' => $request->no_uji,
                'sipa' => $request->sipa,
                'merek' => $request->merk,
                'tipe_kendaraan' => $request->tipe_kendaraan,
                'tipe' => $request->tipe,
                'no_rangka' => $request->no_rangka,
                'no_mesin' => $request->no_mesin,
                'tahun_pembuatan' => $request->tahun_pembuatan,
                'atas_nama' => $request->atas_nama,
                'alamat' => $request->alamat,
                'jatuhtempo_kir' => $request->jatuhtempo_kir,
                'jatuhtempo_pajak_satutahun' => $request->jatuhtempo_pajak_satutahun,
                'jatuhtempo_pajak_limatahun' => $request->jatuhtempo_pajak_limatahun,
                'kapasitas' => $request->kapasitas,
                'kode_cabang' => $request->kode_cabang,
                'status_aktif_kendaraan' => $request->status_aktif_kendaraan
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_kendaraan)
    {
        $kode_kendaraan = Crypt::decrypt($kode_kendaraan);
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $kendaraan = Kendaraan::where('kode_kendaraan', $kode_kendaraan)->first();
        return view('datamaster.kendaraan.edit', compact('cabang', 'kendaraan'));
    }


    public function update(Request $request, $kode_kendaraan)
    {
        $kode_kendaraan = Crypt::decrypt($kode_kendaraan);
        $request->validate([
            'no_polisi' => 'required',
            // 'jatuhtempo_kir' => 'required',
            'jatuhtempo_pajak_satutahun' => 'required',
            'jatuhtempo_pajak_limatahun' => 'required',
            'kapasitas' => 'required',
            'kode_cabang' => 'required',
            'status_aktif_kendaraan' => 'required',
        ]);

        try {
            Kendaraan::where('kode_kendaraan', $kode_kendaraan)->update([
                'no_polisi' => $request->no_polisi,
                'no_stnk' => $request->no_stnk,
                'no_uji' => $request->no_uji,
                'sipa' => $request->sipa,
                'merek' => $request->merk,
                'tipe_kendaraan' => $request->tipe_kendaraan,
                'tipe' => $request->tipe,
                'no_rangka' => $request->no_rangka,
                'no_mesin' => $request->no_mesin,
                'tahun_pembuatan' => $request->tahun_pembuatan,
                'atas_nama' => $request->atas_nama,
                'alamat' => $request->alamat,
                'jatuhtempo_kir' => $request->jatuhtempo_kir,
                'jatuhtempo_pajak_satutahun' => $request->jatuhtempo_pajak_satutahun,
                'jatuhtempo_pajak_limatahun' => $request->jatuhtempo_pajak_limatahun,
                'kapasitas' => $request->kapasitas,
                'kode_cabang' => $request->kode_cabang,
                'status_aktif_kendaraan' => $request->status_aktif_kendaraan
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_kendaraan)
    {
        $kode_kendaraan = Crypt::decrypt($kode_kendaraan);
        try {
            Kendaraan::where('kode_kendaraan', $kode_kendaraan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_kendaraan)
    {
        $kode_kendaraan = Crypt::decrypt($kode_kendaraan);
        $kendaraan = Kendaraan::where('kode_kendaraan', $kode_kendaraan)
            ->join('cabang', 'kendaraan.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        return view('datamaster.kendaraan.show', compact('kendaraan'));
    }


    //AJAX REQUEST

    public function getkendaraanbycabang(Request $request)
    {
        $kode_cabang_user = auth()->user()->kode_cabang;
        $query = Kendaraan::query();
        if ($kode_cabang_user != "PST") {
            $query->where('kode_cabang', $kode_cabang_user);
            $query->orWhere('kode_kendaraan', 'KD0092');
        } else {
            $query->where('kode_cabang', $request->kode_cabang);
            $query->orWhere('kode_kendaraan', 'KD0092');
        }
        $query->where('status_aktif_kendaraan', 1);
        $kendaraan = $query->get();




        echo "<option value=''>Kendaraan</option>";
        foreach ($kendaraan as $d) {
            $selected = $d->kode_kendaraan == $request->kode_kendaraan ? 'selected' : '';
            echo "<option $selected value='$d->kode_kendaraan'>" . $d->no_polisi . "-" . textUpperCase($d->merek) . "-" . textUpperCase($d->tipe) .  "</option>";
        }
    }

    public function getkendaraandpbbycabang(Request $request)
    {

        $kode_cabang_user = auth()->user()->kode_cabang;
        $query = Dpb::query();
        $query->select('gudang_cabang_dpb.kode_kendaraan', 'kendaraan.merek', 'kendaraan.tipe', 'kendaraan.no_polisi', 'kendaraan.tipe_kendaraan');
        if ($kode_cabang_user != "PST") {
            $query->where('salesman.kode_cabang', $kode_cabang_user);
        } else {
            $query->where('salesman.kode_cabang', $request->kode_cabang);
        }
        $query->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('kendaraan', 'gudang_cabang_dpb.kode_kendaraan', '=', 'kendaraan.kode_kendaraan');
        $query->where('status_aktif_kendaraan', 1);
        $query->groupBy('kendaraan.kode_kendaraan', 'kendaraan.merek', 'kendaraan.tipe', 'kendaraan.no_polisi', 'kendaraan.tipe_kendaraan');
        $kendaraan = $query->get();




        echo "<option value=''>Kendaraan</option>";
        foreach ($kendaraan as $d) {
            $selected = $d->kode_kendaraan == $request->kode_kendaraan ? 'selected' : '';
            echo "<option $selected value='$d->kode_kendaraan'>" . $d->no_polisi . "-" . textUpperCase($d->merek) . "-" . textUpperCase($d->tipe) .  "</option>";
        }
    }
}

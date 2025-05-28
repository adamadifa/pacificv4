<?php

namespace App\Http\Controllers;

use App\Models\Slipgaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class SlipgajiController extends Controller
{
    public function index()
    {
        $data['slipgaji'] = Slipgaji::orderBy('tahun')->orderBy('bulan')->get();
        return view('hrd.slipgaji.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('hrd.slipgaji.create', $data);
    }

    public function store(Request $request)
    {

        try {
            Slipgaji::create([
                'kode_gaji' => 'GJ' . $request->bulan . $request->tahun,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'status' => $request->status
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $data['slipgaji'] = Slipgaji::where('kode_gaji', $kode_gaji)->first();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('hrd.slipgaji.edit', $data);
    }

    public function update(Request $request, $kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        try {
            Slipgaji::where('kode_gaji', $kode_gaji)->update([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'status' => $request->status
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        try {
            Slipgaji::where('kode_gaji', $kode_gaji)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cetakslipgaji($nik, $bulan, $tahun)
    {
        $nik = Crypt::decrypt($nik);
        $response = Http::get('https://app.portalmp.com/api/slipgaji/' . $bulan * 1 . '/' . $tahun . '/' . $nik);
        $data = $response->json(); // Mengubah response ke array
        $data['start_date'] = $data['start_date'];
        $data['end_date'] = $data['end_date'];

        $data['dataliburnasional'] = $data['dataliburnasional'];
        $data['datadirumahkan'] = $data['datadirumahkan'];
        $data['dataliburpengganti'] = $data['dataliburpengganti'];
        $data['dataminggumasuk'] = $data['dataminggumasuk'];
        $data['datatanggallimajam'] = $data['datatanggallimajam'];
        $data['datalembur'] = $data['datalembur'];
        $data['datalemburharilibur'] = $data['datalemburharilibur'];
        $data['jmlhari'] = $data['jmlhari'] + 1;
        $privillage_karyawan = [
            '16.11.266',
            '22.08.339',
            '19.10.142',
            '17.03.025',
            '00.12.062',
            '08.07.092',
            '16.05.259',
            '17.08.023',
            '15.10.043',
            '17.07.302',
            '15.10.143',
            '03.03.065',
            '23.12.337',
        ];
        $data['privillage_karyawan'] = $privillage_karyawan;

        return view('hrd.slipgaji.cetakslip', $data);
    }
}

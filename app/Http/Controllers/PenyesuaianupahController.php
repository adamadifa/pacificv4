<?php

namespace App\Http\Controllers;

use App\Models\Detailpenyesuaianupah;
use App\Models\Karyawan;
use App\Models\Penyesuaianupah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class PenyesuaianupahController extends Controller
{
    public function index()
    {
        $data['penyupah'] = Penyesuaianupah::orderBy('tahun')->orderBy('bulan')->get();
        return view('hrd.penyesuaianupah.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('hrd.penyesuaianupah.create', $data);
    }

    public function store(Request $request)
    {

        try {
            Penyesuaianupah::create([
                'kode_gaji' => 'GJ' . $request->bulan . $request->tahun,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $data['penyupah'] = Penyesuaianupah::where('kode_gaji', $kode_gaji)->first();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('hrd.penyesuaianupah.edit', $data);
    }

    public function update(Request $request, $kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        try {
            Penyesuaianupah::where('kode_gaji', $kode_gaji)->update([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
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
            Penyesuaianupah::where('kode_gaji', $kode_gaji)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $data['penyupah'] = Penyesuaianupah::where('kode_gaji', $kode_gaji)->first();
        $data['detailpenyupah'] = Detailpenyesuaianupah::where('kode_gaji', $kode_gaji)
            ->join('hrd_karyawan', 'hrd_penyesuaian_upah_detail.nik', '=', 'hrd_karyawan.nik')
            ->get();
        return view('hrd.penyesuaianupah.show', $data);
    }


    public function tambahkaryawan($kode_gaji)
    {
        $data['kode_gaji'] = Crypt::decrypt($kode_gaji);
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['karyawan'] = Karyawan::where('status_aktif_karyawan', 1)->get();
        return view('hrd.penyesuaianupah.tambahkaryawan', $data);
    }

    public function storekaryawan(Request $request, $kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $request->validate([
            'nik' => 'required',
        ]);

        try {
            //code...
            $cek = Detailpenyesuaianupah::where('nik', $request->nik)->where('kode_gaji', $kode_gaji)->first();
            if ($cek) {
                return Redirect::back()->with(messageError('Data Sudah Ada'));
            }
            Detailpenyesuaianupah::create([
                'kode_gaji' => $kode_gaji,
                'nik' => $request->nik,
                'pengurang' => toNumber($request->pengurang),
                'penambah' => toNumber($request->penambah)
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function deletekaryawan($kode_gaji, $nik)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $nik = Crypt::decrypt($nik);
        try {
            Detailpenyesuaianupah::where('nik', $nik)->where('kode_gaji', $kode_gaji)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

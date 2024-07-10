<?php

namespace App\Http\Controllers;

use App\Models\Detailhpp;
use App\Models\Hpp;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HppController extends Controller
{
    public function index(Request $request)
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        $query = Hpp::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $data['hpp'] = $query->get();

        return view('accounting.hpp.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('accounting.hpp.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $kode_produk = $request->kode_produk;
        $harga_hpp = $request->harga_hpp;
        DB::beginTransaction();
        try {
            $bln = $request->bulan < 10 ? "0" . $request->bulan : $request->bulan;
            $kode_hpp = "HPP" . $bln . $request->tahun;
            $cekhpp = Hpp::where('kode_hpp', $kode_hpp)->first();
            if (!empty($cekhpp)) {
                return Redirect::back()->with(messageError('Kode HPP Sudah Ada'));
            }

            Hpp::create([
                'kode_hpp' => $kode_hpp,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            for ($i = 0; $i < count($kode_produk); $i++) {
                Detailhpp::create([
                    'kode_hpp' => $kode_hpp,
                    'kode_produk' => $kode_produk[$i],
                    'harga_hpp' => toNumber($harga_hpp[$i]),
                ]);
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_hpp)
    {
        $kode_hpp = Crypt::decrypt($kode_hpp);
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['hpp'] = Hpp::where('kode_hpp', $kode_hpp)->first();
        $data['detail'] = Detailhpp::where('kode_hpp', $kode_hpp)
            ->join('produk', 'accounting_hpp_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();
        return view('accounting.hpp.edit', $data);
    }

    public function update(Request $request, $kode_hpp)
    {
        $kode_hpp  = Crypt::decrypt($kode_hpp);
        $kode_produk = $request->kode_produk;
        $harga_hpp = $request->harga_hpp;
        DB::beginTransaction();
        try {

            Detailhpp::where('kode_hpp', $kode_hpp)->delete();

            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'kode_hpp' => $kode_hpp,
                    'kode_produk' => $kode_produk[$i],
                    'harga_hpp' => toNumber($harga_hpp[$i]),
                ];
            }
            Detailhpp::insert($detail);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show($kode_hpp)
    {

        $kode_hpp = Crypt::decrypt($kode_hpp);
        $data['hpp'] = Hpp::where('kode_hpp', $kode_hpp)->first();
        $data['detail'] = Detailhpp::where('kode_hpp', $kode_hpp)
            ->join('produk', 'accounting_hpp_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();
        return view('accounting.hpp.show', $data);
    }

    public function destroy($kode_hpp)
    {
        $kode_hpp = Crypt::decrypt($kode_hpp);
        try {
            Hpp::where('kode_hpp', $kode_hpp)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

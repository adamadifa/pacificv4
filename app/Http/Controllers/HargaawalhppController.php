<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailhargaawalhpp;
use App\Models\Hargaawalhpp;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HargaawalhppController extends Controller
{
    public function index()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('accounting.hargaawalhpp.index', $data);
    }

    public function gethargaawal(Request $request)
    {
        $detail = Detailhargaawalhpp::where('lokasi', $request->lokasi)->where('bulan', $request->bulan)->where('tahun', $request->tahun)
            ->join('accounting_hpp_hargaawal', 'accounting_hpp_hargaawal_detail.kode_hargaawal', '=', 'accounting_hpp_hargaawal.kode_hargaawal')
            ->join('produk', 'accounting_hpp_hargaawal_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();

        if ($detail->isEmpty()) {
            $detail = Produk::select('kode_produk', 'nama_produk', DB::raw("'0' as harga"))->where('status_aktif_produk', 1)->get();
        }

        $data['detail'] = $detail;
        return view('accounting.hargaawalhpp.gethargaawalhpp', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        $kode_produk = $request->kode_produk;
        $harga_awal = $request->harga_awal;
        DB::beginTransaction();
        try {
            $bln = $request->bulan < 10 ? "0" . $request->bulan : $request->bulan;
            $kode_hargaawal = "HA" . $request->lokasi . $bln . $request->tahun;
            $cekhpp = Hargaawalhpp::where('kode_hargaawal', $kode_hargaawal)->first();
            if (!empty($cekhpp)) {
                Hargaawalhpp::where('kode_hargaawal', $kode_hargaawal)->delete();
            }

            Hargaawalhpp::create([
                'kode_hargaawal' => $kode_hargaawal,
                'lokasi' => $request->lokasi,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'kode_hargaawal' => $kode_hargaawal,
                    'kode_produk' => $kode_produk[$i],
                    'harga_awal' => toNumber($harga_awal[$i]),
                ];
            }

            Detailhargaawalhpp::insert($detail);
            DB::commit();
            return redirect('/hargaawalhpp?lokasi=' . $request->lokasi . '&bulan=' . $request->bulan . '&tahun=' . $request->tahun)->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

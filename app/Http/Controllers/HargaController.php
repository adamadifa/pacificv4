<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Harga;
use App\Models\Kategorisalesman;
use App\Models\Pelanggan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Expr\FuncCall;

class HargaController extends Controller
{
    public function index(Request $request)
    {

        $query = Harga::query();
        $query->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        if (!empty($request->nama_produk)) {
            $query->where('nama_produk', 'like', '%' . $request->nama_produk . '%');
        }

        if (!empty($request->kode_cabang)) {
            $query->where('kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_kategori_salesman)) {
            $query->where('kode_kategori_salesman', $request->kode_kategori_salesman);
        }
        $harga = $query->paginate(10);
        $harga->appends(request()->all());

        $cabang = Cabang::orderBy('kode_cabang')->get();
        $kategorisalesman = Kategorisalesman::orderBy('kode_kategori_salesman')->get();
        return view('datamaster.harga.index', compact('harga', 'cabang', 'kategorisalesman'));
    }

    public function create()
    {
        $produk = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        $kategori_salesman = Kategorisalesman::orderBy('kode_kategori_salesman')->get();
        $cabang = Cabang::orderBy('kode_cabang')->get();
        return view('datamaster.harga.create', compact('produk', 'kategori_salesman', 'cabang'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'kode_harga' => 'required',
            'kode_produk' => 'required',
            'harga_dus' => 'required',
            'harga_pack' => 'required',
            'harga_pcs' => 'required',
            'harga_retur_dus' => 'required',
            'harga_retur_pack' => 'required',
            'harga_retur_pcs' => 'required',
            'status_aktif_harga' => 'required',
            'status_promo' => 'required',
            'kode_kategori_salesman' => 'required',
            'kode_cabang' => 'required'
        ]);

        try {
            Harga::create([
                'kode_harga' => $request->kode_harga,
                'kode_produk' => $request->kode_produk,
                'harga_dus' =>  toNumber($request->harga_dus),
                'harga_pack' => toNumber($request->harga_pack),
                'harga_pcs' => toNumber($request->harga_pcs),
                'harga_retur_dus' => toNumber($request->harga_retur_dus),
                'harga_retur_pack' => toNumber($request->harga_retur_pack),
                'harga_retur_pcs' => toNumber($request->harga_retur_pcs),
                'status_aktif_harga' => $request->status_aktif_harga,
                'status_promo' => $request->status_promo,
                'status_ppn' => $request->status_ppn,
                'kode_kategori_salesman' => $request->kode_kategori_salesman,
                'kode_cabang' => $request->kode_cabang,
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_harga)
    {
        $kode_harga = Crypt::decrypt($kode_harga);
        $produk = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        $kategori_salesman = Kategorisalesman::orderBy('kode_kategori_salesman')->get();
        $cabang = Cabang::orderBy('kode_cabang')->get();
        $harga = Harga::where('kode_harga', $kode_harga)->first();
        return view('datamaster.harga.edit', compact('produk', 'kategori_salesman', 'cabang', 'harga'));
    }


    public function update(Request $request, $kode_harga)
    {
        $kode_harga = Crypt::decrypt($kode_harga);
        $request->validate([

            'kode_produk' => 'required',
            'harga_dus' => 'required',
            'harga_pack' => 'required',
            'harga_pcs' => 'required',
            'harga_retur_dus' => 'required',
            'harga_retur_pack' => 'required',
            'harga_retur_pcs' => 'required',
            'status_aktif_harga' => 'required',
            'status_promo' => 'required',
            'kode_kategori_salesman' => 'required',
            'kode_cabang' => 'required'
        ]);

        try {
            Harga::where('kode_harga', $kode_harga)->update([
                'kode_produk' => $request->kode_produk,
                'harga_dus' =>  toNumber($request->harga_dus),
                'harga_pack' => toNumber($request->harga_pack),
                'harga_pcs' => toNumber($request->harga_pcs),
                'harga_retur_dus' => toNumber($request->harga_retur_dus),
                'harga_retur_pack' => toNumber($request->harga_retur_pack),
                'harga_retur_pcs' => toNumber($request->harga_retur_pcs),
                'status_aktif_harga' => $request->status_aktif_harga,
                'status_promo' => $request->status_promo,
                'status_ppn' => $request->status_ppn,
                'kode_kategori_salesman' => $request->kode_kategori_salesman,
                'kode_cabang' => $request->kode_cabang,
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_harga)
    {
        $kode_harga = Crypt::decrypt($kode_harga);
        try {
            Harga::where('kode_harga', $kode_harga)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function gethargabypelanggan($kode_pelanggan)
    {
        $hrg = new Harga();
        $harga = $hrg->getHargabypelanggan($kode_pelanggan);

        return view('datamaster.harga.gethargabypelanggan', compact('harga'));
    }
}

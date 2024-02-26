<?php

namespace App\Http\Controllers;

use App\Models\Detailmaxstok;
use App\Models\Detailmutasiproduksi;
use App\Models\Mutasiproduksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class FsthpController extends Controller
{
    public function index(Request $request)
    {

        $query = Mutasiproduksi::query();
        $query->orderBy('tanggal_mutasi', 'desc');
        $query->orderBy('created_at', 'desc');
        if (!empty($request->tanggal_mutasi_search)) {
            $query->where('tanggal_mutasi', $request->tanggal_mutasi_search);
        }
        $query->where('jenis_mutasi', 'FSTHP');
        $fsthp = $query->simplePaginate(20);
        $fsthp->appends(request()->all());
        return view('produksi.fsthp.index', compact('fsthp'));
    }


    public function show($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $fsthp = Mutasiproduksi::where('no_mutasi', $no_mutasi)->first();
        $detail = Detailmutasiproduksi::where('no_mutasi', $no_mutasi)
            ->join('produk', 'produksi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();

        return view('produksi.fsthp.show', compact('fsthp', 'detail'));
    }

    public function create()
    {
        $produk = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('produksi.fsthp.create', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_mutasi' => 'required',
            'tanggal_mutasi' => 'required',
            'unit' => 'required',
            'kode_produk' => 'required',
            'shift' => 'required',
            'jumlah' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal_mutasi, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            $cekfsthp = Mutasiproduksi::where('no_mutasi', $request->no_mutasi)->count();


            if ($cekfsthp > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }

            Mutasiproduksi::create([
                'no_mutasi' => $request->no_mutasi,
                'tanggal_mutasi' => $request->tanggal_mutasi,
                'in_out' => 'OUT',
                'jenis_mutasi' => 'FSTHP',
                'unit' => $request->unit
            ]);

            Detailmutasiproduksi::create([
                'no_mutasi' => $request->no_mutasi,
                'kode_produk' => $request->kode_produk,
                'shift' => $request->shift,
                'jumlah' => toNumber($request->jumlah)
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $fsthp = Mutasiproduksi::where('no_mutasi', $no_mutasi)->first();
        try {
            $cektutuplaporan = cektutupLaporan($fsthp->tanggal_mutasi, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Mutasiproduksi::where('no_mutasi', $no_mutasi)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
    //AJAX REQUEST
    public function generatenofsthp(Request $request)
    {

        $namabulan = config('global.nama_bulan_singkat');
        $tanggal = explode("-", $request->tanggal_mutasi);
        $hari = $tanggal[2];
        $bulan = $tanggal[1] + 0;
        //echo $bl;
        $tahun = $tanggal[0];
        $tgl = "/" . $hari . "/" . $namabulan[$bulan] . "/" . $tahun;


        $no_fsthp = "F" . $request->kode_produk . "/0" . $request->shift . $tgl;
        return $no_fsthp;
    }
}

<?php

namespace App\Http\Controllers;

use App\Charts\HasilproduksiChart;
use App\Models\Detailmutasiproduksi;
use App\Models\Detailmutasiproduksitemp;
use App\Models\Mutasiproduksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BpbjController extends Controller
{
    public function index(Request $request)
    {
        $start_year = config('global.start_year');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Mutasiproduksi::query();
        $query->orderBy('tanggal_mutasi', 'desc');
        $query->orderBy('created_at', 'desc');
        if (!empty($request->tanggal_mutasi_search)) {
            $query->where('tanggal_mutasi', $request->tanggal_mutasi_search);
        } else {
            $query->whereBetween('tanggal_mutasi', [$start_date, $end_date]);
        }
        $query->where('jenis_mutasi', 'BPBJ');
        $bpbj = $query->simplePaginate(20);
        $bpbj->appends(request()->all());
        return view('produksi.bpbj.index', compact('bpbj'));
    }


    public function create()
    {
        $produk = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('produksi.bpbj.create', compact('produk'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'no_mutasi' => 'required',
            'tanggal_mutasi' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal_mutasi, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            $temp = Detailmutasiproduksitemp::where('kode_produk', $request->kode_produk)
                ->where('id_user', auth()->user()->id)
                ->where('in_out', 'IN');


            $cekdetailtemp = $temp->count();
            if (empty($cekdetailtemp)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            $cekbpbj = Mutasiproduksi::where('no_mutasi', $request->no_mutasi)->count();
            if ($cekbpbj > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }
            $detailtemp = $temp->get();
            foreach ($detailtemp as $d) {
                $detail[] = [
                    'no_mutasi' => $request->no_mutasi,
                    'kode_produk' => $d->kode_produk,
                    'shift' => $d->shift,
                    'jumlah' => $d->jumlah
                ];
            }
            Mutasiproduksi::create([
                'no_mutasi' => $request->no_mutasi,
                'tanggal_mutasi' => $request->tanggal_mutasi,
                'in_out' => 'IN',
                'jenis_mutasi' => 'BPBJ'
            ]);

            Detailmutasiproduksi::insert($detail);

            Detailmutasiproduksitemp::where('kode_produk', $request->kode_produk)
                ->where('id_user', auth()->user()->id)
                ->where('in_out', 'IN')
                ->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $bpbj = Mutasiproduksi::where('no_mutasi', $no_mutasi)->first();
        $detail = Detailmutasiproduksi::where('no_mutasi', $no_mutasi)
            ->join('produk', 'produksi_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();

        return view('produksi.bpbj.show', compact('bpbj', 'detail'));
    }


    public function destroy($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $bpbj = Mutasiproduksi::where('no_mutasi', $no_mutasi)->first();
        try {
            $cektutuplaporan = cektutupLaporan($bpbj->tanggal_mutasi, "produksi");
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
    public function storedetailtemp(Request $request)
    {
        try {

            $cekbpbj = Detailmutasiproduksitemp::where('kode_produk', $request->kode_produk)
                ->where('shift', $request->shift)
                ->where('in_out', 'IN')
                ->where('id_user', auth()->user()->id)->count();

            if ($cekbpbj > 0) {
                return response()->json(['status' => 'error', 'message' => 'Data Sudah Ada'], 400);
            }
            Detailmutasiproduksitemp::create([
                'kode_produk' => $request->kode_produk,
                'shift' => $request->shift,
                'unit' => $request->unit,
                'in_out' => 'IN',
                'jumlah' => toNumber($request->jumlah),
                'id_user' => auth()->user()->id
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }


    public function getdetailtemp($kode_produk)
    {
        $detailtemp = Detailmutasiproduksitemp::where('produksi_mutasi_detail_temp.kode_produk', $kode_produk)
            ->join('produk', 'produksi_mutasi_detail_temp.kode_produk', '=', 'produk.kode_produk')
            ->where('in_out', 'IN')
            ->where('id_user', auth()->user()->id)
            ->orderBy('shift')
            ->get();
        return view('produksi.bpbj.getdetailtemp', compact('detailtemp', 'kode_produk'));
    }


    public function generatenobpbj(Request $request)
    {

        $kode = strlen($request->kode_produk);
        $no_bpbj = $kode + 2;

        $bpbj = Mutasiproduksi::selectRaw("LEFT(no_mutasi,$no_bpbj) as no_bpbj")
            ->whereRaw("LEFT(no_mutasi," . $kode . ")='" . $request->kode_produk . "'")
            ->where('tanggal_mutasi', $request->tanggal_mutasi)
            ->where('jenis_mutasi', 'BPBJ')
            ->orderByRaw("LEFT(no_mutasi," . $no_bpbj . ") DESC")
            ->first();



        $namabulan = config('global.nama_bulan_singkat');
        $tanggal = explode("-", $request->tanggal_mutasi);
        $hari = $tanggal[2];
        $bulan = $tanggal[1] + 0;
        //echo $bl;
        $tahun = $tanggal[0];
        $tgl = "/" . $hari . "/" . $namabulan[$bulan] . "/" . $tahun;
        if ($bpbj != null) {
            $last_nobpbj = $bpbj->no_bpbj;
        } else {
            $last_nobpbj = "";
        }
        $no_bpbj = buatkode($last_nobpbj, $request->kode_produk, 2) . $tgl;
        return $no_bpbj;
    }

    public function deletetemp(Request $request)
    {
        try {
            Detailmutasiproduksitemp::where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function cekdetailtemp(Request $request)
    {
        $cek = Detailmutasiproduksitemp::where('kode_produk', $request->kode_produk)
            ->where('id_user', auth()->user()->id)
            ->where('in_out', 'IN')
            ->count();
        return $cek;
    }

    public function getrekaphasilproduksi(Request $request)
    {
        $nama_bulan_singkat = config('global.nama_bulan_singkat');
        $select_bulan = "";
        for ($i = 1; $i <= 12; $i++) {
            $select_bulan .= "SUM(IF(MONTH(tanggal_mutasi)='$i' AND jenis_mutasi='BPBJ',jumlah,0)) as " . $nama_bulan_singkat[$i] . ",";
        }
        $rekap = Detailmutasiproduksi::selectRaw("
            $select_bulan
            kode_produk
        ")
            ->whereRaw("YEAR(tanggal_mutasi)='$request->tahun'")
            ->join("produksi_mutasi", "produksi_mutasi_detail.no_mutasi", "=", "produksi_mutasi.no_mutasi")
            ->groupBy("kode_produk")
            ->orderBy("kode_produk")
            ->get();

        return view('produksi.bpbj.getrekaphasilproduksi', compact('rekap', 'nama_bulan_singkat'));
    }


    public function getgrafikhasilproduksi(Request $request, HasilproduksiChart $chart)
    {
        $data['chart'] = $chart->build($request->tahun);
        return view('produksi.bpbj.getgrafikhasilproduksi', $data);
    }
}

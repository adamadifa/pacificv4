<?php

namespace App\Http\Controllers;

use App\Models\Detailoman;
use App\Models\Oman;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class OmanController extends Controller
{
    public function index(Request $request)
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        $query = Oman::query();
        if (!empty($request->bulan_search)) {
            $query->where('bulan', $request->bulan_search);
        }

        if (!empty($request->tahun_search)) {
            $query->where('tahun', $request->tahun_search);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->orderBy('bulan');
        $oman = $query->paginate(15);
        $oman->appends(request()->all());
        return view('marketing.oman.index', compact('list_bulan', 'start_year', 'oman'));
    }

    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        $produk = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('marketing.oman.create', compact('list_bulan', 'start_year', 'produk'));
    }

    public function store(Request $request)
    {


        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
        $kode_produk = $request->kode_produk;
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required'
        ]);
        $kode_oman = "OM"  . $bln . substr($tahun, 2, 2);
        $jmlm1 = $request->jmlm1;
        $jmlm2 = $request->jmlm2;
        $jmlm3 = $request->jmlm3;
        $jmlm4 = $request->jmlm4;

        $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        }

        DB::beginTransaction();
        try {
            for ($i = 0; $i < count($kode_produk); $i++) {

                for ($m = 1; $m <= 4; $m++) {
                    ${"detail_m$m"}[] = [
                        'kode_oman' => $kode_oman,
                        'kode_produk' => $kode_produk[$i],
                        'minggu_ke' => $m,
                        'jumlah' => toNumber(${"jmlm$m"}[$i] != null ? ${"jmlm$m"}[$i] : 0),
                    ];
                }
            }

            $detail = array_merge($detail_m1, $detail_m2, $detail_m3, $detail_m4);
            $timestamp = Carbon::now();
            foreach ($detail as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }

            Oman::create([
                'kode_oman' => $kode_oman,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal' => $tanggal,
                'status_oman' => 0
            ]);

            $chunks_buffer = array_chunk($detail, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailoman::insert($chunk_buffer);
            }

            DB::commit();
            return redirect(route('oman.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('oman.index'))->with(messageError($e->getMessage()));
        }
    }


    public function show($kode_oman)
    {
        $kode_oman = Crypt::decrypt($kode_oman);
        $oman = Oman::where('kode_oman', $kode_oman)->first();
        $detail = Detailoman::join('produk', 'marketing_oman_detail.kode_produk', '=', 'produk.kode_produk')
            ->select(
                'marketing_oman_detail.kode_produk',
                'nama_produk',
                DB::raw("SUM(IF(minggu_ke='1',jumlah,0)) as minggu_1"),
                DB::raw("SUM(IF(minggu_ke='2',jumlah,0)) as minggu_2"),
                DB::raw("SUM(IF(minggu_ke='3',jumlah,0)) as minggu_3"),
                DB::raw("SUM(IF(minggu_ke='4',jumlah,0)) as minggu_4"),
                DB::raw("SUM(jumlah) as total")
            )
            ->where('kode_oman', $kode_oman)
            ->orderBy('marketing_oman_detail.kode_produk')
            ->groupBy('marketing_oman_detail.kode_produk')
            ->groupBy('nama_produk')
            ->get();

        return view('marketing.oman.show', compact('oman', 'detail'));
    }

    public function destroy($kode_oman)
    {
        $kode_oman = Crypt::decrypt($kode_oman);
        $omancabang = Oman::where('kode_oman', $kode_oman)->first();
        try {
            $cektutuplaporan = cektutupLaporan($omancabang->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Oman::where('kode_oman', $kode_oman)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailomancabang;
use App\Models\Oman;
use App\Models\Omancabang;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class OmancabangController extends Controller
{
    public function index(Request $request)
    {
        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $user = User::findorfail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_access_all_cabang');
        $query = Omancabang::query();
        $query->join('cabang', 'marketing_oman_cabang.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($request->bulan_search)) {
            $query->where('bulan', $request->bulan_search);
        }

        if (!empty($request->tahun_search)) {
            $query->where('tahun', $request->tahun_search);
        } else {
            $query->where('tahun', date('Y'));
        }


        if (!$user->hasRole($roles_show_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_oman_cabang.kode_cabang', auth()->user()->kode_cabang);
            }
        }
        $query->orderBy('bulan');
        $oman_cabang = $query->paginate(15);
        $oman_cabang->appends(request()->all());
        return view('marketing.omancabang.index', compact(
            'list_bulan',
            'nama_bulan',
            'start_year',
            'oman_cabang'
        ));
    }

    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        $cabang = Cabang::orderBy('kode_cabang')->get();
        $produk = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('marketing.omancabang.create', compact('cabang', 'list_bulan', 'start_year', 'produk'));
    }


    public function edit($kode_oman)
    {
        $kode_oman = Crypt::decrypt($kode_oman);
        $oman_cabang = Omancabang::with('cabang')->where('kode_oman', $kode_oman)->first();
        $detail = Detailomancabang::join('produk', 'marketing_oman_cabang_detail.kode_produk', '=', 'produk.kode_produk')
            ->select(
                'marketing_oman_cabang_detail.kode_produk',
                'nama_produk',
                DB::raw("SUM(IF(minggu_ke='1',jumlah,0)) as minggu_1"),
                DB::raw("SUM(IF(minggu_ke='2',jumlah,0)) as minggu_2"),
                DB::raw("SUM(IF(minggu_ke='3',jumlah,0)) as minggu_3"),
                DB::raw("SUM(IF(minggu_ke='4',jumlah,0)) as minggu_4"),
                DB::raw("SUM(jumlah) as total")
            )
            ->where('kode_oman', $kode_oman)
            ->orderBy('marketing_oman_cabang_detail.kode_produk')
            ->groupBy('marketing_oman_cabang_detail.kode_produk')
            ->groupBy('nama_produk')
            ->get();
        return view('marketing.omancabang.edit', compact('oman_cabang', 'detail'));
    }

    public function store(Request $request)
    {

        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');



        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
        $kode_produk = $request->kode_produk;
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
            $request->validate([
                'kode_cabang' => 'required',
                'bulan' => 'required',
                'tahun' => 'required'
            ]);
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
            $request->validate([
                'bulan' => 'required',
                'tahun' => 'required'
            ]);
        }
        $kode_oman = "OM" . $kode_cabang . $bln . substr($tahun, 2, 2);
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

            if (!empty($detail)) {

                Omancabang::create([
                    'kode_oman' => $kode_oman,
                    'kode_cabang' => $kode_cabang,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'tanggal' => $tanggal,
                    'status_oman_cabang' => 0
                ]);

                $chunks_buffer = array_chunk($detail, 5);
                foreach ($chunks_buffer as $chunk_buffer) {
                    Detailomancabang::insert($chunk_buffer);
                }
            } else {
                DB::rollBack();
                return Redirect::back()->with(messageError('Detail Saldo Kosong'));
            }

            DB::commit();
            if ($user->hasRole($roles_show_cabang)) {
                return redirect('/omancabang?bulan_search=' . $bulan . '&tahun=' . $tahun)->with(messageSuccess('Data Berhasil Disimpan'));
            } else {
                return redirect(route('omancabang.index'))->with(messageSuccess('Data Berhasil Disimpan'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('omancabang.index'))->with(messageError($e->getMessage()));
        }
    }


    public function update(Request $request, $kode_oman)
    {
        $kode_oman = Crypt::decrypt($kode_oman);
        $oman_cabang = Omancabang::where('kode_oman', $kode_oman)->first();
        $bulan = $oman_cabang->bulan;
        $tahun = $oman_cabang->tahun;
        $kode_produk = $request->kode_produk;
        $jmlm1 = $request->jmlm1;
        $jmlm2 = $request->jmlm2;
        $jmlm3 = $request->jmlm3;
        $jmlm4 = $request->jmlm4;

        $cektutuplaporan = cektutupLaporan($oman_cabang->tanggal, "penjualan");
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

            if (!empty($detail)) {

                Detailomancabang::where('kode_oman', $kode_oman)->delete();

                $chunks_buffer = array_chunk($detail, 5);
                foreach ($chunks_buffer as $chunk_buffer) {
                    Detailomancabang::insert($chunk_buffer);
                }
            } else {
                DB::rollBack();
                return Redirect::back()->with(messageError('Detail Saldo Kosong'));
            }

            DB::commit();
            return redirect('/omancabang?bulan_search=' . $bulan . '&tahun=' . $tahun)->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('omancabang.index'))->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_oman)
    {
        $kode_oman = Crypt::decrypt($kode_oman);
        $oman_cabang = Omancabang::with('cabang')->where('kode_oman', $kode_oman)->first();
        $detail = Detailomancabang::join('produk', 'marketing_oman_cabang_detail.kode_produk', '=', 'produk.kode_produk')
            ->select(
                'marketing_oman_cabang_detail.kode_produk',
                'nama_produk',
                DB::raw("SUM(IF(minggu_ke='1',jumlah,0)) as minggu_1"),
                DB::raw("SUM(IF(minggu_ke='2',jumlah,0)) as minggu_2"),
                DB::raw("SUM(IF(minggu_ke='3',jumlah,0)) as minggu_3"),
                DB::raw("SUM(IF(minggu_ke='4',jumlah,0)) as minggu_4"),
                DB::raw("SUM(jumlah) as total")
            )
            ->where('kode_oman', $kode_oman)
            ->orderBy('marketing_oman_cabang_detail.kode_produk')
            ->groupBy('marketing_oman_cabang_detail.kode_produk')
            ->groupBy('nama_produk')
            ->get();

        return view('marketing.omancabang.show', compact('oman_cabang', 'detail'));
    }

    public function destroy($kode_oman)
    {
        $kode_oman = Crypt::decrypt($kode_oman);
        $omancabang = Omancabang::where('kode_oman', $kode_oman)->first();
        try {
            $cektutuplaporan = cektutupLaporan($omancabang->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Omancabang::where('kode_oman', $kode_oman)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    //AJAX REQUEST
    public function getomancabang(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $detail = Detailomancabang::join('produk', 'marketing_oman_cabang_detail.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_oman_cabang', 'marketing_oman_cabang_detail.kode_oman', '=', 'marketing_oman_cabang.kode_oman')
            ->select(
                'marketing_oman_cabang_detail.kode_produk',
                'nama_produk',
                DB::raw("SUM(IF(minggu_ke='1',jumlah,0)) as minggu_1"),
                DB::raw("SUM(IF(minggu_ke='2',jumlah,0)) as minggu_2"),
                DB::raw("SUM(IF(minggu_ke='3',jumlah,0)) as minggu_3"),
                DB::raw("SUM(IF(minggu_ke='4',jumlah,0)) as minggu_4"),
                DB::raw("SUM(jumlah) as total")
            )
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('marketing_oman_cabang_detail.kode_produk')
            ->groupBy('marketing_oman_cabang_detail.kode_produk')
            ->groupBy('nama_produk')
            ->get();
        return view('marketing.omancabang.getomancabang', compact('detail', 'bulan', 'tahun'));
    }

    public function editprodukomancabang(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bulan . "-01";
        $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
        $cek = Oman::where('bulan', $bulan)->where('tahun', $tahun)->count();
        $minggu_ke = $request->minggu_ke;
        if ($cektutuplaporan > 0) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Periode Laporan Sudah Ditutup !'
            ]);
        } else if ($cek > 0) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Data Untuk Periode Ini Sudah Ada, Untuk Melakukan Update Silahkan Hapus Data Dulu !'
            ]);
        } else {
            $produk = Produk::where('kode_produk', $request->kode_produk)->first();
            $data = Detailomancabang::join('marketing_oman_cabang', 'marketing_oman_cabang_detail.kode_oman', '=', 'marketing_oman_cabang.kode_oman')
                ->join('cabang', 'marketing_oman_cabang.kode_cabang', '=', 'cabang.kode_cabang')
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->where('minggu_ke', $request->minggu_ke)
                ->where('marketing_oman_cabang_detail.kode_produk', $request->kode_produk)
                ->get();

            return view('marketing.omancabang.editproduk', compact('data', 'minggu_ke', 'produk', 'bulan', 'tahun'));
        }
    }

    public function updateprodukomancabang(Request $request)
    {
        $kode_oman = $request->kode_oman;
        $kode_produk = $request->kode_produk;
        $minggu_ke = $request->minggu_ke;
        $jumlah = $request->jumlah;
        DB::beginTransaction();
        try {
            //Hapus Data Sebelumnya
            Detailomancabang::whereIn('kode_oman', $kode_oman)->where('minggu_ke', $minggu_ke)
                ->whereIn('kode_produk', $kode_produk)
                ->delete();

            //Insert Data Baru
            for ($i = 0; $i < count($kode_oman); $i++) {

                $detail[] = [
                    'kode_oman' => $kode_oman[$i],
                    'kode_produk' => $kode_produk[$i],
                    'minggu_ke' => $minggu_ke,
                    'jumlah' => toNumber($jumlah[$i] != null ? $jumlah[$i] : 0),
                ];
            }


            $timestamp = Carbon::now();
            foreach ($detail as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }

            $chunks_buffer = array_chunk($detail, 5);
            foreach ($chunks_buffer as $chunk_buffer) {
                Detailomancabang::insert($chunk_buffer);
            }
            DB::commit();
            return "success|Berhasil|Data Berhasil Disimpan";
        } catch (\Exception $e) {
            DB::rollBack();
            return "error|Error|Data Gagal Disimpan";
        }
    }
}

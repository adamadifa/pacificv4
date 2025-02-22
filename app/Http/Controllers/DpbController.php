<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detaildpb;
use App\Models\Detailmutasigudangcabang;
use App\Models\Dpb;
use App\Models\Dpbdriverhelper;
use App\Models\Jenismutasigudangcabang;
use App\Models\Mutasigudangcabang;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DpbController extends Controller
{
    public function index(Request $request)
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!empty($request->dari) && !empty($request->sampai) && !$user->hasRole(['manager audit', 'super admin'])) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $query = Dpb::query();
        $query->select('no_dpb', 'tanggal_ambil', 'nama_salesman', 'nama_cabang', 'tujuan', 'no_polisi', 'tanggal_kembali');
        $query->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('kendaraan', 'gudang_cabang_dpb.kode_kendaraan', '=', 'kendaraan.kode_kendaraan');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('gudang_cabang_dpb.tanggal_ambil', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('gudang_cabang_dpb.tanggal_ambil', [$start_date, $end_date]);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('cabang.kode_cabang', $request->kode_cabang_search);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->no_dpb_search)) {
            $query->where('gudang_cabang_dpb.no_dpb', $request->no_dpb_search);
        }

        if (!empty($request->kode_salesman_search)) {
            $query->where('gudang_cabang_dpb.kode_salesman', $request->kode_salesman_search);
        }
        $query->orderBy('tanggal_ambil', 'desc');
        $query->orderBy('gudang_cabang_dpb.created_at', 'desc');
        $dpb = $query->paginate('15');
        $dpb->appends(request()->all());
        $data['dpb'] = $dpb;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;


        return view('gudangcabang.dpb.index', $data);
    }


    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('gudangcabang.dpb.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_dpb_format' => 'required',
            'no_dpb' => 'required',
            'tanggal_ambil' => 'required',
            'kode_salesman' => 'required',
            'kode_kendaraan' => 'required',
            'tujuan' => 'required',
            // 'kode_driver' => 'required'
        ]);
        $no_dpb = $request->no_dpb_format . $request->no_dpb;
        $kode_produk = $request->kode_produk;
        $jml_dus = $request->jml_dus;
        $jml_pack = $request->jml_pack;
        $jml_pcs = $request->jml_pcs;
        $isi_pcs_dus = $request->isi_pcs_dus;
        $isi_pcs_pack = $request->isi_pcs_pack;


        DB::beginTransaction();
        try {
            //Checking
            $cektutuplaporan = cektutupLaporan($request->tanggal_ambil, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            $cekdpb = Dpb::where('no_dpb', $no_dpb)->count();
            if ($cekdpb > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }

            for ($i = 0; $i < count($kode_produk); $i++) {

                $dus = toNumber(!empty($jml_dus[$i]) ? $jml_dus[$i] : 0);
                $pack = toNumber(!empty($jml_pack[$i]) ? $jml_pack[$i] : 0);
                $pcs = toNumber(!empty($jml_pcs[$i]) ? $jml_pcs[$i] : 0);


                $jumlah = ($dus * $isi_pcs_dus[$i]) + ($pack * $isi_pcs_pack[$i]) + $pcs;
                if (!empty($jumlah)) {
                    $detail[] = [
                        'no_dpb' => $request->no_dpb_format . $request->no_dpb,
                        'kode_produk' => $kode_produk[$i],
                        'jml_ambil' => $jumlah,
                        'jml_kembali' => 0,
                        'jml_penjualan' => 0
                    ];
                }
            }


            if (empty($detail)) {
                return Redirect::back()->with(messageError('Data Pengambilan Masih Kosong'));
            } else {
                Dpb::create([
                    'no_dpb' => $request->no_dpb_format . $request->no_dpb,
                    'tanggal_ambil' => $request->tanggal_ambil,
                    'kode_salesman' => $request->kode_salesman,
                    'kode_kendaraan' => $request->kode_kendaraan,
                    'tujuan' => $request->tujuan
                ]);

                $timestamp = Carbon::now();
                foreach ($detail as &$record) {
                    $record['created_at'] = $timestamp;
                    $record['updated_at'] = $timestamp;
                }

                $chunks_buffer = array_chunk($detail, 5);
                foreach ($chunks_buffer as $chunk_buffer) {
                    Detaildpb::insert($chunk_buffer);
                }

                $driverhelper = [];
                $driver = [
                    'no_dpb' => $request->no_dpb_format . $request->no_dpb,
                    'kode_driver_helper' => $request->kode_driver,
                    'kode_posisi' => 'D',
                    'jumlah' => 0,
                    'keterangan' => 0
                ];

                if (!empty($request->kode_driver)) {
                    $driverhelper[] = $driver;
                }

                //dd($driver);

                $helper_1 = !empty($request->kode_helper_1) ?  [
                    'no_dpb' => $request->no_dpb_format . $request->no_dpb,
                    'kode_driver_helper' => $request->kode_helper_1,
                    'kode_posisi' => 'H',
                    'jumlah' => 0,
                    'keterangan' => 1
                ] : [];

                if (!empty($helper_1)) {
                    $driverhelper[] = $helper_1;
                }
                $helper_2 = !empty($request->kode_helper_2) ?  [
                    'no_dpb' => $request->no_dpb_format . $request->no_dpb,
                    'kode_driver_helper' => $request->kode_helper_2,
                    'kode_posisi' => 'H',
                    'jumlah' => 0,
                    'keterangan' => 2
                ] : [];
                if (!empty($helper_2)) {
                    $driverhelper[] = $helper_2;
                }
                $helper_3 = !empty($request->kode_helper_3) ?  [
                    'no_dpb' => $request->no_dpb_format . $request->no_dpb,
                    'kode_driver_helper' => $request->kode_helper_3,
                    'kode_posisi' => 'H',
                    'jumlah' => 0,
                    'keterangan' => 3
                ] : [];

                if (!empty($helper_3)) {
                    $driverhelper[] = $helper_3;
                }

                //$driverhelper   = array($driver, $helper_1, $helper_2, $helper_3);

                // dd($driverhelper);
                //Simpan Driver Helper
                if (!empty($driverhelper)) {
                    Dpbdriverhelper::insert($driverhelper);
                }

                DB::commit();
                return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
            }
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            //return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($no_dpb)
    {
        $no_dpb = Crypt::decrypt($no_dpb);
        $data['dpb'] = Dpb::where('no_dpb', $no_dpb)
            ->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['produk'] = Produk::where('status_aktif_produk', 1)
            ->select('produk.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pack_dus', 'isi_pcs_pack', 'jml_ambil', 'jml_kembali', 'jml_penjualan')
            ->leftJoin(
                DB::raw("(
                    SELECT
                    kode_produk,jml_ambil,jml_kembali,jml_penjualan
                    FROM
                    gudang_cabang_dpb_detail
                    WHERE no_dpb = '$no_dpb'
                ) dpb"),
                function ($join) {
                    $join->on('produk.kode_produk', '=', 'dpb.kode_produk');
                }
            )
            ->orderBy('produk.kode_produk')
            ->get();

        $data['driver'] = Dpbdriverhelper::where('no_dpb', $no_dpb)
            ->where('kode_posisi', 'D')
            ->first();

        $data['driverhelper'] = Dpbdriverhelper::select('gudang_cabang_dpb_driverhelper.*', 'nama_driver_helper')
            ->join('driver_helper', 'gudang_cabang_dpb_driverhelper.kode_driver_helper', '=', 'driver_helper.kode_driver_helper')
            ->where('no_dpb', $no_dpb)
            ->where('kode_posisi', 'H')
            ->get();
        return view('gudangcabang.dpb.edit', $data);
    }


    public function update(Request $request, $no_dpb)
    {
        $no_dpb = Crypt::decrypt($no_dpb);
        $request->validate([
            'no_dpb' => 'required',
            'tanggal_ambil' => 'required',
            'tanggal_kembali' => 'required',
            'kode_salesman' => 'required',
            'kode_kendaraan' => 'required',
            'tujuan' => 'required',
            // 'kode_driver' => 'required'
        ]);

        $kode_produk = $request->kode_produk;
        $jml_ambil_dus = $request->jml_ambil_dus;
        $jml_ambil_pack = $request->jml_ambil_pack;
        $jml_ambil_pcs = $request->jml_ambil_pcs;

        $jml_kembali_dus = $request->jml_kembali_dus;
        $jml_kembali_pack = $request->jml_kembali_pack;
        $jml_kembali_pcs = $request->jml_kembali_pcs;

        $jml_keluar_dus = $request->jml_keluar_dus;
        $jml_keluar_pack = $request->jml_keluar_pack;
        $jml_keluar_pcs = $request->jml_keluar_pcs;


        $isi_pcs_dus = $request->isi_pcs_dus;
        $isi_pcs_pack = $request->isi_pcs_pack;


        $kode_helper = $request->kodehelper;
        $qty_helper = $request->qtyhelper;

        DB::beginTransaction();
        try {
            //Checking
            $dpb = Dpb::where('no_dpb', $no_dpb)->first();

            //Checking
            $cektutuplaporan_dpb = cektutupLaporan($dpb->tanggal_ambil, "gudangcabang");
            if ($cektutuplaporan_dpb > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }


            $cektutuplaporan = cektutupLaporan($request->tanggal_ambil, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            $totalbarangkeluar = 0;
            for ($i = 0; $i < count($kode_produk); $i++) {

                $dus_ambil = toNumber(!empty($jml_ambil_dus[$i]) ?  $jml_ambil_dus[$i] : 0);
                $pack_ambil = toNumber(!empty($jml_ambil_pack[$i]) ?  $jml_ambil_pack[$i] : 0);
                $pcs_ambil = toNumber(!empty($jml_ambil_pcs[$i]) ?  $jml_ambil_pcs[$i] : 0);

                $dus_kembali = toNumber(!empty($jml_kembali_dus[$i]) ?  $jml_kembali_dus[$i] : 0);
                $pack_kembali = toNumber(!empty($jml_kembali_pack[$i]) ?  $jml_kembali_pack[$i] : 0);
                $pcs_kembali = toNumber(!empty($jml_kembali_pcs[$i]) ?  $jml_kembali_pcs[$i] : 0);

                $dus_keluar = toNumber(!empty($jml_keluar_dus[$i]) ?  $jml_keluar_dus[$i] : 0);
                $pack_keluar = toNumber(!empty($jml_keluar_pack[$i]) ?  $jml_keluar_pack[$i] : 0);
                $pcs_keluar = toNumber(!empty($jml_keluar_pcs[$i]) ?  $jml_keluar_pcs[$i] : 0);

                $jumlah_ambil = ($dus_ambil * $isi_pcs_dus[$i]) + ($pack_ambil * $isi_pcs_pack[$i]) + $pcs_ambil;
                $jumlah_kembali = ($dus_kembali * $isi_pcs_dus[$i]) + ($pack_kembali * $isi_pcs_pack[$i]) + $pcs_kembali;
                $jumlah_keluar = ($dus_keluar * $isi_pcs_dus[$i]) + ($pack_keluar * $isi_pcs_pack[$i]) + $pcs_keluar;
                $barangkeluar_dus = ROUND($jumlah_keluar / $isi_pcs_dus[$i], 3);
                $totalbarangkeluar += $barangkeluar_dus;

                $jumlah = $jumlah_ambil + $jumlah_kembali + $jumlah_keluar;
                if (!empty($jumlah)) {
                    $detail[] = [
                        'no_dpb' => $request->no_dpb_format . $request->no_dpb,
                        'kode_produk' => $kode_produk[$i],
                        'jml_ambil' => $jumlah_ambil,
                        'jml_kembali' => $jumlah_kembali,
                        'jml_penjualan' => $jumlah_keluar
                    ];
                }
            }


            if (empty($detail)) {
                return Redirect::back()->with(messageError('Data Pengambilan Masih Kosong'));
            } else {

                Dpb::where('no_dpb', $no_dpb)->delete();

                Dpb::create([
                    'no_dpb' => $request->no_dpb,
                    'tanggal_ambil' => $request->tanggal_ambil,
                    'tanggal_kembali' => $request->tanggal_kembali,
                    'kode_salesman' => $request->kode_salesman,
                    'kode_kendaraan' => $request->kode_kendaraan,
                    'tujuan' => $request->tujuan,
                    'jenis_perhitungan' => $request->jenis_perhitungan
                ]);

                $timestamp = Carbon::now();
                foreach ($detail as &$record) {
                    $record['created_at'] = $timestamp;
                    $record['updated_at'] = $timestamp;
                }

                $chunks_buffer = array_chunk($detail, 5);
                foreach ($chunks_buffer as $chunk_buffer) {
                    Detaildpb::insert($chunk_buffer);
                }


                if (isset($request->kode_driver)) {
                    Dpbdriverhelper::create([
                        'no_dpb' => $request->no_dpb,
                        'kode_driver_helper' => $request->kode_driver,
                        'kode_posisi' => 'D',
                        'jumlah' => 0,
                        'keterangan' => 0
                    ]);
                }


                $no = 1;
                if (!empty($kode_helper)) {
                    for ($i = 0; $i < count($kode_helper); $i++) {
                        if ($request->jenis_perhitungan == "P") {
                            $jumlah_qty_helper = ROUND(toNumber($qty_helper[$i]) / 100 * $totalbarangkeluar, 3);
                        } else if ($request->jenis_perhitungan == "Q") {
                            $jumlah_qty_helper = toNumber($qty_helper[$i]);
                        } else if ($request->jenis_perhitungan == "R") {
                            $jumlah_qty_helper =  ROUND($totalbarangkeluar / count($kode_helper), 3);
                        }
                        $helper[] = [
                            'no_dpb' => $request->no_dpb,
                            'kode_driver_helper' => $kode_helper[$i],
                            'kode_posisi' => 'H',
                            'jumlah' => $jumlah_qty_helper,
                            'keterangan' => $no
                        ];

                        $no++;
                    }

                    Dpbdriverhelper::insert($helper);
                }
                // dd($totalbarangkeluar);

                Mutasigudangcabang::where('no_dpb', $no_dpb)->update([
                    'no_dpb' => $request->no_dpb
                ]);
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            //return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($no_dpb)
    {
        $no_dpb = Crypt::decrypt($no_dpb);
        $data['dpb'] = Dpb::select('no_dpb', 'tanggal_ambil', 'tanggal_kembali', 'nama_salesman', 'nama_cabang', 'tujuan', 'no_polisi')
            ->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('kendaraan', 'gudang_cabang_dpb.kode_kendaraan', '=', 'kendaraan.kode_kendaraan')
            ->where('no_dpb', $no_dpb)->first();
        $data['detail'] = Detaildpb::select(
            'gudang_cabang_dpb_detail.*',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pack_dus',
            'isi_pcs_pack'
        )
            ->join('produk', 'gudang_cabang_dpb_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('no_dpb', $no_dpb)
            ->get();
        $data['jenis_mutasi'] = Jenismutasigudangcabang::orderBy('kode_jenis_mutasi')->where('kategori', 'DPB')
            ->orderBY('order')
            ->get();

        $data['driverhelper'] = Dpbdriverhelper::select('gudang_cabang_dpb_driverhelper.*', 'nama_driver_helper')
            ->join('driver_helper', 'gudang_cabang_dpb_driverhelper.kode_driver_helper', '=', 'driver_helper.kode_driver_helper')
            ->where('no_dpb', $no_dpb)
            ->where('kode_posisi', 'H')
            ->get();

        $data['driver'] = Dpbdriverhelper::where('no_dpb', $no_dpb)
            ->join('driver_helper', 'gudang_cabang_dpb_driverhelper.kode_driver_helper', '=', 'driver_helper.kode_driver_helper')
            ->where('kode_posisi', 'D')
            ->first();

        // dd($data['mutasi_dpb']);
        return view('gudangcabang.dpb.show', $data);
    }

    public function getdetailmutasidpb($no_dpb)
    {
        $no_dpb = Crypt::decrypt($no_dpb);
        $data['mutasi_dpb'] = Detailmutasigudangcabang::select(
            'gudang_cabang_mutasi_detail.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pack_dus',
            'isi_pcs_pack',
            DB::raw("SUM(IF(jenis_mutasi='RT',jumlah,0)) as jml_retur"),
            DB::raw("SUM(IF(jenis_mutasi='HK',jumlah,0)) as jml_hutangkirim"),
            DB::raw("SUM(IF(jenis_mutasi='PT',jumlah,0)) as jml_pelunasanttr"),

            DB::raw("SUM(IF(jenis_mutasi='PJ',jumlah,0)) as jml_penjualan"),
            DB::raw("SUM(IF(jenis_mutasi='GB',jumlah,0)) as jml_gantibarang"),
            DB::raw("SUM(IF(jenis_mutasi='PH',jumlah,0)) as jml_pelunasanhutangkirim"),
            DB::raw("SUM(IF(jenis_mutasi='TR',jumlah,0)) as jml_ttr"),
            DB::raw("SUM(IF(jenis_mutasi='RP',jumlah,0)) as jml_rejectpasar"),
            DB::raw("SUM(IF(jenis_mutasi='PR',jumlah,0)) as jml_promosi")
        )
            ->join('produk', 'gudang_cabang_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi')
            ->where('no_dpb', $no_dpb)
            ->orderBy('gudang_cabang_mutasi_detail.kode_produk')
            ->groupBy('gudang_cabang_mutasi_detail.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pack_dus', 'isi_pcs_pack')
            ->get();

        return view('gudangcabang.dpb.getdetailmutasidpb', $data);
    }

    public function destroy($no_dpb)
    {
        $no_dpb = Crypt::decrypt($no_dpb);
        $dpb = Dpb::where('no_dpb', $no_dpb)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($dpb->tanggal_ambil, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Dpb::where('no_dpb', $no_dpb)->delete();
            Mutasigudangcabang::where('no_dpb', $no_dpb)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    //AJAX REQUEST
    public function generatenodpb(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            $kode_cabang = auth()->user()->kode_cabang;
        } else {
            $kode_cabang = $request->kode_cabang;
        }


        $cabang = Cabang::where('kode_cabang', $kode_cabang)->first();
        $kode_pt = $cabang->kode_pt;

        if (!empty($request->tanggal)) {
            $tahun = date('Y', strtotime($request->tanggal));
        } else {
            $tahun = date('Y');
        }
        return $kode_pt . substr($tahun, 2, 2);
    }

    public function getautocompletedpb(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            $kode_cabang = auth()->user()->kode_cabang;
        }
        $search = $request->search;
        if ($search == '') {
            $query = Dpb::query();
            $query->select('gudang_cabang_dpb.*', 'nama_salesman', 'salesman.kode_cabang', 'no_polisi');
            $query->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman');
            $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
            $query->join('kendaraan', 'gudang_cabang_dpb.kode_kendaraan', '=', 'kendaraan.kode_kendaraan');
            if (!$user->hasRole($roles_access_all_cabang)) {
                $query->where('salesman.kode_cabang', $kode_cabang);
            }
            $query->orderBy('tanggal_ambil', 'desc');
            $query->orderby('no_dpb', 'desc');
            $query->limit(10);
            $autocomplate = $query->get();
        } else {
            $query = Dpb::query();
            $query->select('gudang_cabang_dpb.*', 'nama_salesman', 'salesman.kode_cabang', 'no_polisi');
            $query->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman');
            $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
            $query->join('kendaraan', 'gudang_cabang_dpb.kode_kendaraan', '=', 'kendaraan.kode_kendaraan');

            if (!$user->hasRole($roles_access_all_cabang)) {
                $query->where('no_dpb', 'like', '%' . $search . '%');
                $query->where('salesman.kode_cabang', $kode_cabang);
                $query->orWhere('nama_salesman', 'like', '%' . $search . '%');
                $query->where('salesman.kode_cabang', $kode_cabang);
            } else {
                $query->where('no_dpb', 'like', '%' . $search . '%');
                $query->orWhere('nama_salesman', 'like', '%' . $search . '%');
            }
            $query->orderBy('tanggal_ambil', 'desc');
            $query->orderby('no_dpb', 'desc');
            $query->limit(10);
            $autocomplate = $query->get();
        }


        //dd($autocomplate);
        $response = array();
        foreach ($autocomplate as $autocomplate) {
            $label = $autocomplate->no_dpb . " - " . $autocomplate->nama_salesman . " - " . $autocomplate->kode_cabang . " - " . $autocomplate->tujuan . " - " . $autocomplate->no_polisi;
            $response[] = array("value" => $autocomplate->nama_salesman, "label" => $label, 'val' => $autocomplate->no_dpb);
        }

        echo json_encode($response);
        exit;
    }
}

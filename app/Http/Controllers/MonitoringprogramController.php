<?php

namespace App\Http\Controllers;


use App\Models\Cabang;
use App\Models\Detailpencairan;
use App\Models\Detailpencairanprogramikatan;
use App\Models\Detailpenjualan;
use App\Models\Detailtargetikatan;
use App\Models\Historibayarpenjualan;
use App\Models\Pelanggan;
use App\Models\Pencairansimpanan;
use App\Models\Programikatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MonitoringprogramController extends Controller
{
    public function index(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $programikatan = !empty($request->kode_program) ? Programikatan::where('kode_program', $request->kode_program)->first() : [];

        $listpelangganikatan = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            'marketing_program_ikatan_detail.top'
        )
            ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->where('marketing_program_ikatan.status', 1)
            ->where('marketing_program_ikatan.kode_program', $request->kode_program)
            ->where('marketing_program_ikatan_target.bulan', $request->bulan)
            ->where('marketing_program_ikatan_target.tahun', $request->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $kode_cabang);

        $start_date = $request->tahun . '-' . $request->bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));


        $lastbulan = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
        $lasttahun = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");
        $start_last_date = "2025-01-01";

        $end_last_date = date('Y-m-t', strtotime($lasttahun . '-' . $lastbulan . '-01'));

        $produk = !empty($programikatan) ? json_decode($programikatan->produk, true) ?? [] : [];

        $detailpenjualan = Detailpenjualan::select(
            'marketing_penjualan.kode_pelanggan',
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) as jml_dus'),
            DB::raw('SUM(IF(jenis_transaksi = "T", floor(jumlah/isi_pcs_dus), 0)) as jml_tunai'),
            DB::raw('SUM(IF(jenis_transaksi = "K", floor(jumlah/isi_pcs_dus), 0)) as jml_kredit'),
        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->joinSub($listpelangganikatan, 'listpelangganikatan', function ($join) {
                $join->on('marketing_penjualan.kode_pelanggan', '=', 'listpelangganikatan.kode_pelanggan');
            })
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('marketing_penjualan.status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            ->groupBy('marketing_penjualan.kode_pelanggan');


        // $pelanggansudahdicairkan = Detailpencairanprogramikatan::join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
        //     ->select('kode_pelanggan')
        //     ->where('marketing_pencairan_ikatan.bulan', $request->bulan)
        //     ->where('marketing_pencairan_ikatan.tahun', $request->tahun)
        //     ->where('marketing_pencairan_ikatan.kode_program', $request->kode_program)
        //     ->where('marketing_pencairan_ikatan.kode_cabang', $kode_cabang);


        $detailpenjualan_bulanlalu = Detailpenjualan::select(
            'marketing_penjualan.kode_pelanggan',
            DB::raw('MONTH(marketing_penjualan.tanggal) as bulan'),
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) as jml_dus'),
        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->joinSub($listpelangganikatan, 'listpelangganikatan', function ($join) {
                $join->on('marketing_penjualan.kode_pelanggan', '=', 'listpelangganikatan.kode_pelanggan');
            })
            ->whereBetween('marketing_penjualan.tanggal', [$start_last_date, $end_last_date])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('marketing_penjualan.status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            // ->whereNotIn('marketing_penjualan.kode_pelanggan', function ($query) use ($pencairanprogram) {
            //     $query->select('kode_pelanggan')
            //         ->from('marketing_pencairan_ikatan_detail')
            //         ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            //         ->where('bulan', $pencairanprogram->bulan)
            //         ->where('tahun', $pencairanprogram->tahun);
            // })
            ->groupBy('marketing_penjualan.kode_pelanggan', DB::raw('MONTH(marketing_penjualan.tanggal)'));

        $bulan = $request->bulan != null ? $request->bulan : 0;


        $peserta_gagal = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',


        )
            ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->leftJoinSub($detailpenjualan_bulanlalu, 'detailpenjualan', function ($join) {
                $join->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'detailpenjualan.kode_pelanggan');
                $join->on('marketing_program_ikatan_target.bulan', '=', 'detailpenjualan.bulan');
            })
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')

            // ->whereNotIn('marketing_program_ikatan_target.kode_pelanggan', $pelanggansudahdicairkan)
            ->where('marketing_program_ikatan.status', 1)
            ->where('marketing_program_ikatan.kode_program', $request->kode_program)
            ->when($request->kode_program == 'PRIK002' && $bulan > 6, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '>', 6);
            })
            ->where('marketing_program_ikatan_target.bulan', '<', $bulan)
            ->where('marketing_program_ikatan_target.tahun', $request->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $request->kode_cabang)
            ->whereRaw('IFNULL(jml_dus,0) < target_perbulan');


        $peserta = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            'nama_pelanggan',
            'target_perbulan as qty_target',
            'budget_rsm',
            'budget_smm',
            'budget_gm',
            'reward',
            'jml_dus',
            'jml_tunai',
            'jml_kredit',
            'file_doc',
            'nama_salesman',
            'nama_wilayah',
            'marketing_program_ikatan.kode_program'
        )
            ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
                $join->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'detailpenjualan.kode_pelanggan');
            })

            ->when($request->nama_pelanggan, function ($query, $nama_pelanggan) {
                return $query->where('nama_pelanggan', 'like', '%' . $nama_pelanggan . '%');
            })
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->where('marketing_program_ikatan.status', 1)
            ->whereNotIn('marketing_program_ikatan_target.kode_pelanggan', $peserta_gagal)
            ->where('marketing_program_ikatan.kode_program', $request->kode_program)
            ->where('marketing_program_ikatan_target.bulan', $request->bulan)
            ->where('marketing_program_ikatan_target.tahun', $request->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $kode_cabang)
            ->get();


        // dd($peserta);
        // $data['detail'] = $detail;

        $data['peserta'] = $peserta;





        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('worksheetom.monitoringprogram.index', $data);
    }


    public function detailfaktur($kode_pelanggan, $kode_program, $bulan, $tahun)
    {



        $programikatan = Programikatan::where('kode_program', $kode_program)->first();

        $start_date = $tahun . '-' . $bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));

        $produk = json_decode($programikatan->produk, true) ?? [];

        $detailpenjualan = Detailpenjualan::select(
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.tanggal_pelunasan',
            'marketing_penjualan.jenis_transaksi',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            DB::raw('floor(jumlah/isi_pcs_dus) as jml_dus'),
        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            // ->where('salesman.kode_cabang', $pencairanprogram->kode_cabang)
            ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
            // ->where('status', 1)
            // ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= 14")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            // ->whereIn('produk_harga.kode_produk', $produk)
            ->get();

        // dd($detailpenjualan);
        return view('worksheetom.pencairanprogramikatan.detailfaktur', compact('detailpenjualan'));
    }


    public function saldosimpanan(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $qpencairansimpanan = Pencairansimpanan::query();
        $qpencairansimpanan->select('kode_pelanggan', DB::raw('SUM(jumlah) as total_pencairan'));
        $qpencairansimpanan->where('kode_cabang', $kode_cabang);
        $qpencairansimpanan->groupBy('kode_pelanggan');

        $query = Detailpencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan_detail.kode_pelanggan',
            'nama_pelanggan',
            'nama_salesman',
            'nama_wilayah',
            DB::raw('SUM(total_reward) as total_reward'),
            'total_pencairan'
        );
        $query->join('pelanggan', 'marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan');
        $query->where('status_pencairan', 0);
        $query->where('marketing_pencairan_ikatan.kode_cabang', $kode_cabang);
        $query->where('marketing_pencairan_ikatan.status', 1);
        $query->leftJoinSub($qpencairansimpanan, 'pencairansimpanan', function ($join) {
            $join->on('marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pencairansimpanan.kode_pelanggan');
        });
        $query->groupBy('marketing_pencairan_ikatan_detail.kode_pelanggan', 'nama_pelanggan', 'total_pencairan');
        if (!empty($request->nama_pelanggan)) {
            $query->where('pelanggan.nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }
        $query->orderBy('nama_pelanggan');
        $saldosimpanan = $query->paginate(20);
        $saldosimpanan->appends(request()->query());

        $data['saldosimpanan'] = $saldosimpanan;
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('worksheetom.monitoringprogram.saldosimpanan', $data);
    }

    public function saldovoucher(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $voucherdigunakan = Historibayarpenjualan::query();
        $voucherdigunakan->select('marketing_penjualan.kode_pelanggan', DB::raw('SUM(jumlah) as total_bayar_voucher'));
        $voucherdigunakan->join('marketing_penjualan', 'marketing_penjualan.no_faktur', '=', 'marketing_penjualan_historibayar.no_faktur');
        $voucherdigunakan->where('marketing_penjualan.status_batal', 0);
        $voucherdigunakan->where('voucher', 1);
        $voucherdigunakan->where('jenis_voucher', 2);
        $voucherdigunakan->where('voucher_reward', 1);
        $voucherdigunakan->where('marketing_penjualan_historibayar.tanggal', '>=', '2025-01-01');
        $voucherdigunakan->groupBy('marketing_penjualan.kode_pelanggan');

        // dd($voucherdigunakan->get()->toArray());

        $query = Detailpencairan::query();
        $query->select(
            'marketing_program_pencairan_detail.kode_pelanggan',
            'nama_pelanggan',
            'nama_salesman',
            'nama_wilayah',
            DB::raw('SUM(diskon_kumulatif-diskon_reguler) as total_reward'),
            'total_bayar_voucher'
        );
        $query->join('pelanggan', 'marketing_program_pencairan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');

        $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('marketing_program_pencairan', 'marketing_program_pencairan_detail.kode_pencairan', '=', 'marketing_program_pencairan.kode_pencairan');
        $query->join('cabang', 'marketing_program_pencairan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoinSub($voucherdigunakan, 'voucherdigunakan', function ($join) {
            $join->on('marketing_program_pencairan_detail.kode_pelanggan', '=', 'voucherdigunakan.kode_pelanggan');
        });
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_program_pencairan.kode_cabang', $kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('marketing_program_pencairan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->nama_pelanggan)) {
            $query->where('pelanggan.nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
        }
        // $query->where('marketing_program_pencairan.kode_cabang', $kode_cabang);
        $query->where('marketing_program_pencairan.status', 1);
        $query->where('metode_pembayaran', 'VC');
        $query->groupBy('marketing_program_pencairan_detail.kode_pelanggan', 'nama_pelanggan');
        $query->orderBy('nama_pelanggan');
        $saldovoucher = $query->paginate(20);
        $saldovoucher->appends(request()->query());

        $data['saldovoucher'] = $saldovoucher;
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('worksheetom.monitoringprogram.saldovoucher', $data);
    }


    public function getdetailsimpanan($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $query = Detailpencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan.tanggal',
            'marketing_pencairan_ikatan_detail.kode_pelanggan',
            'nama_pelanggan',
            'no_rekening',
            'bank',
            'pemilik_rekening',
            'nama_salesman',
            'nama_wilayah',
            'total_reward',
            'nama_program',
        );
        $query->join('pelanggan', 'marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan');
        $query->join('program_ikatan', 'marketing_pencairan_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->where('status_pencairan', 0);
        $query->where('marketing_pencairan_ikatan_detail.kode_pelanggan', $kode_pelanggan);
        $query->where('marketing_pencairan_ikatan.status', 1);
        $query->orderBy('nama_pelanggan');
        $data['detailsimpanan'] = $query->get();

        return view('worksheetom.monitoringprogram.getdetailsimpanan', $data);
    }

    public function createpencairansimpanan($kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);

        $qpencairansimpanan = Pencairansimpanan::query();
        $qpencairansimpanan->select('kode_pelanggan', DB::raw('SUM(jumlah) as total_pencairan'));
        $qpencairansimpanan->where('kode_pelanggan', $kode_pelanggan);
        $qpencairansimpanan->groupBy('kode_pelanggan');

        $query = Detailpencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan_detail.kode_pelanggan',
            'nama_pelanggan',
            'nama_salesman',
            'nama_wilayah',
            DB::raw('SUM(total_reward) as total_reward'),
            'total_pencairan'
        );
        $query->join('pelanggan', 'marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan');
        $query->leftJoinSub($qpencairansimpanan, 'pencairansimpanan', function ($join) {
            $join->on('marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pencairansimpanan.kode_pelanggan');
        });
        $query->where('status_pencairan', 0);
        $query->where('marketing_pencairan_ikatan.status', 1);
        $query->where('marketing_pencairan_ikatan_detail.kode_pelanggan', $kode_pelanggan);
        $query->groupBy('marketing_pencairan_ikatan_detail.kode_pelanggan', 'nama_pelanggan', 'total_pencairan');
        $simpanan = $query->first();

        $data['simpanan'] = $simpanan;
        return view('worksheetom.monitoringprogram.createpencairansimpanan', $data);
    }


    public function storepencairansimpanan(Request $request, $kode_pelanggan)
    {
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $pelanggan = Pelanggan::where('kode_pelanggan', $kode_pelanggan)->first();
        $kode_cabang = $pelanggan->kode_cabang;
        $lastpencairan = Pencairansimpanan::select('kode_pencairan')->orderBy('kode_pencairan', 'desc')
            ->whereRaw('YEAR(marketing_pencairan_simpanan.tanggal)="' . date('Y', strtotime(date('Y-m-d'))) . '"')
            ->where('kode_cabang', $kode_cabang)
            ->first();
        $last_kode_pencairan = $lastpencairan != null ? $lastpencairan->kode_pencairan : '';

        // dd($last_kode_pencairan);
        $kode_pencairan = buatkode($last_kode_pencairan, "PS" . $kode_cabang . date('y', strtotime(date('Y-m-d'))), 4);

        DB::beginTransaction();
        try {
            //code...
            Pencairansimpanan::create([
                'kode_pencairan' => $kode_pencairan,
                'tanggal' => date('Y-m-d'),
                'kode_pelanggan' => $kode_pelanggan,
                'jumlah' => toNumber($request->jumlah),
                'status' => 0,
                'kode_cabang' => $kode_cabang,
                'metode_pembayaran' => $request->metode_pembayaran
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function pencairansimpanan(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }
        $cbg = new Cabang();
        $query = Pencairansimpanan::query();
        $query->select(
            'marketing_pencairan_simpanan.*',
            'nama_pelanggan',
            'no_rekening',
            'bank',
            'pemilik_rekening',
            'nama_salesman'
        );

        if (!empty($kode_cabang)) {
            $query->where('marketing_pencairan_simpanan.kode_cabang', $kode_cabang);
        }
        $query->join('pelanggan', 'marketing_pencairan_simpanan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->orderBy('marketing_pencairan_simpanan.kode_pencairan', 'desc');
        $pencairan = $query->paginate(15);
        $pencairan->appends($request->all());
        $data['pencairan'] = $pencairan;
        $data['cabang'] = $cbg->getCabang();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('worksheetom.monitoringprogram.pencairansimpanan', $data);
    }

    public function deletepencairansimpanan($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        try {
            $pencairanprogram = Pencairansimpanan::where('kode_pencairan', $kode_pencairan)->firstorFail();
            $pencairanprogram->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approvepencairansimpanan($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $pencairansimpanan = Pencairansimpanan::where('kode_pencairan', $kode_pencairan)
            ->join('pelanggan', 'marketing_pencairan_simpanan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->firstorFail();
        return view('worksheetom.monitoringprogram.approvepencairansimpanan', compact('pencairansimpanan'));
    }

    public function storeapprovepencairansimpanan(Request $request)
    {
        $kode_pencairan = Crypt::decrypt($request->kode_pencairan);

        if (isset($_POST['cancel'])) {
            Pencairansimpanan::where('kode_pencairan', $kode_pencairan)
                ->update([
                    'status' => 0
                ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        }


        $pencairansimpanan = Pencairansimpanan::where('kode_pencairan', $kode_pencairan)->firstorFail();
        $pencairansimpanan->status = 1;
        $pencairansimpanan->save();
        return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
    }



    public function cetakpencairansimpanan($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $pencairansimpanan = Pencairansimpanan::where('kode_pencairan', $kode_pencairan)
            ->join('pelanggan', 'marketing_pencairan_simpanan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->firstorFail();
        $data['pencairansimpanan'] = $pencairansimpanan;
        return view('worksheetom.monitoringprogram.pencairansimpanan_cetak', $data);
    }



    public function cetak(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $programikatan = !empty($request->kode_program) ? Programikatan::where('kode_program', $request->kode_program)->first() : [];

        $listpelangganikatan = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            'marketing_program_ikatan_detail.top'
        )
            ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->where('marketing_program_ikatan.status', 1)
            ->where('marketing_program_ikatan.kode_program', $request->kode_program)
            ->where('marketing_program_ikatan_target.bulan', $request->bulan)
            ->where('marketing_program_ikatan_target.tahun', $request->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $kode_cabang);

        $start_date = $request->tahun . '-' . $request->bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));

        $lastbulan = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
        $lasttahun = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");
        $start_last_date = "2025-01-01";

        $end_last_date = date('Y-m-t', strtotime($lasttahun . '-' . $lastbulan . '-01'));

        $produk = !empty($programikatan) ? json_decode($programikatan->produk, true) ?? [] : [];

        $detailpenjualan = Detailpenjualan::select(
            'marketing_penjualan.kode_pelanggan',
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) as jml_dus'),
            DB::raw('SUM(IF(jenis_transaksi = "T", floor(jumlah/isi_pcs_dus), 0)) as jml_tunai'),
            DB::raw('SUM(IF(jenis_transaksi = "K", floor(jumlah/isi_pcs_dus), 0)) as jml_kredit'),
        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->joinSub($listpelangganikatan, 'listpelangganikatan', function ($join) {
                $join->on('marketing_penjualan.kode_pelanggan', '=', 'listpelangganikatan.kode_pelanggan');
            })
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('marketing_penjualan.status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            ->groupBy('marketing_penjualan.kode_pelanggan');



        $detailpenjualan_bulanlalu = Detailpenjualan::select(
            'marketing_penjualan.kode_pelanggan',
            DB::raw('MONTH(marketing_penjualan.tanggal) as bulan'),
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) as jml_dus'),
        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->joinSub($listpelangganikatan, 'listpelangganikatan', function ($join) {
                $join->on('marketing_penjualan.kode_pelanggan', '=', 'listpelangganikatan.kode_pelanggan');
            })
            ->whereBetween('marketing_penjualan.tanggal', [$start_last_date, $end_last_date])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('marketing_penjualan.status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            // ->whereNotIn('marketing_penjualan.kode_pelanggan', function ($query) use ($pencairanprogram) {
            //     $query->select('kode_pelanggan')
            //         ->from('marketing_pencairan_ikatan_detail')
            //         ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            //         ->where('bulan', $pencairanprogram->bulan)
            //         ->where('tahun', $pencairanprogram->tahun);
            // })
            ->groupBy('marketing_penjualan.kode_pelanggan', DB::raw('MONTH(marketing_penjualan.tanggal)'));

        $bulan = $request->bulan != null ? $request->bulan : 0;
        $peserta_gagal = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',


        )
            ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->leftJoinSub($detailpenjualan_bulanlalu, 'detailpenjualan', function ($join) {
                $join->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'detailpenjualan.kode_pelanggan');
                $join->on('marketing_program_ikatan_target.bulan', '=', 'detailpenjualan.bulan');
            })
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')

            // ->whereNotIn('marketing_program_ikatan_target.kode_pelanggan', $pelanggansudahdicairkan)
            ->where('marketing_program_ikatan.status', 1)
            ->where('marketing_program_ikatan.kode_program', $request->kode_program)
            ->where('marketing_program_ikatan_target.bulan', '<', $bulan)
            ->where('marketing_program_ikatan_target.tahun', $request->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $request->kode_cabang)
            ->whereRaw('IFNULL(jml_dus,0) < target_perbulan');
        $peserta = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            'nama_pelanggan',
            'target_perbulan as qty_target',
            'budget_rsm',
            'budget_smm',
            'budget_gm',
            'reward',
            'jml_dus',
            'jml_tunai',
            'jml_kredit',
            'file_doc',
            'nama_salesman',
            'nama_wilayah',
            'marketing_program_ikatan.kode_program'
        )
            ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
                $join->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'detailpenjualan.kode_pelanggan');
            })

            ->when($request->nama_pelanggan, function ($query, $nama_pelanggan) {
                return $query->where('nama_pelanggan', 'like', '%' . $nama_pelanggan . '%');
            })
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->where('marketing_program_ikatan.status', 1)
            ->whereNotIn('marketing_program_ikatan_target.kode_pelanggan', $peserta_gagal)
            ->where('marketing_program_ikatan.kode_program', $request->kode_program)
            ->where('marketing_program_ikatan_target.bulan', $request->bulan)
            ->where('marketing_program_ikatan_target.tahun', $request->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $kode_cabang)
            ->get();


        // dd($peserta);
        // $data['detail'] = $detail;

        $data['peserta'] = $peserta;
        $data['programikatan'] = $programikatan;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        if (isset($_GET['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Monitoring Program.xls");
        }
        return view('worksheetom.monitoringprogram.cetak', $data);
    }
}

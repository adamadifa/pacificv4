<?php

namespace App\Http\Controllers;

use App\Models\Ajuanprogramikatan;
use App\Models\Cabang;
use App\Models\Detailajuanprogramikatan;
use App\Models\Detailajuanprogramikatanenambulan;
use App\Models\Detailpencairanprogramikatan;
use App\Models\Detailpenjualan;
use App\Models\Detailtargetikatan;
use App\Models\Pencairanprogram;
use App\Models\Pencairanprogramikatan;
use App\Models\Programikatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PencairanprogramikatanController extends Controller
{
    public function index(Request $request)
    {

        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');


        $query = Pencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan.*',
            'cabang.nama_cabang',
            'nama_program',
        );
        $query->join('program_ikatan', 'marketing_pencairan_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->join('cabang', 'marketing_pencairan_ikatan.kode_cabang', '=', 'cabang.kode_cabang');


        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_pencairan_ikatan.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('marketing_pencairan_ikatan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_program)) {
            $query->where('marketing_pencairan_ikatan.kode_program', $request->kode_program);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_pencairan_ikatan.tanggal', [$request->dari, $request->sampai]);
        }



        if ($user->hasRole('regional sales manager')) {
            $query->whereNotNull('marketing_pencairan_ikatan.om');
            $query->where('marketing_pencairan_ikatan.status', '!=', 2);
        }

        if ($user->hasRole('gm marketing')) {
            $query->whereNotNull('marketing_pencairan_ikatan.rsm');
            $query->where('marketing_pencairan_ikatan.status', '!=', 2);
        }

        if ($user->hasRole('direktur')) {
            $query->whereNotNull('marketing_pencairan_ikatan.gm');
            $query->where('marketing_pencairan_ikatan.status', '!=', 2);
        }

        if ($user->hasRole('direktur')) {
            $query->orderBy('marketing_pencairan_ikatan.status', 'asc');
            $query->orderBy('marketing_pencairan_ikatan.bulan', 'desc');
            $query->orderBy('marketing_pencairan_ikatan.tahun', 'desc');
        } else if ($user->hasRole('staff keuangan')) {
            $query->orderBy('marketing_pencairan_ikatan.keuangan', 'asc');
            $query->orderBy('marketing_pencairan_ikatan.bulan', 'desc');
            $query->orderBy('marketing_pencairan_ikatan.tahun', 'desc');
        } else {
            $query->orderBy('marketing_pencairan_ikatan.status', 'asc');
            $query->orderBy('marketing_pencairan_ikatan.bulan', 'desc');
            $query->orderBy('marketing_pencairan_ikatan.tahun', 'desc');
        }
        $pencairanprogramikatan = $query->paginate(15);
        $pencairanprogramikatan->appends(request()->all());
        $data['pencairanprogramikatan'] = $pencairanprogramikatan;

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['user'] = $user;
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.pencairanprogramikatan.index', $data);
    }


    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.pencairanprogramikatan.create', $data);
    }


    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            $request->validate([
                'tanggal' => 'required',
                'kode_program' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'keterangan' => 'required'
            ]);
        } else {
            $request->validate([
                'tanggal' => 'required',
                'kode_program' => 'required',
                'kode_cabang' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'keterangan' => 'required'
            ]);
        }

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $lastpencairan = Pencairanprogramikatan::select('kode_pencairan')->orderBy('kode_pencairan', 'desc')
            ->whereRaw('YEAR(marketing_pencairan_ikatan.tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
            ->where('kode_cabang', $kode_cabang)
            ->first();
        $last_kode_pencairan = $lastpencairan != null ? $lastpencairan->kode_pencairan : '';

        // dd($last_kode_pencairan);
        $kode_pencairan = buatkode($last_kode_pencairan, "PI" . $kode_cabang . date('y', strtotime($request->tanggal)), 4);

        // $periode_dari = $ajuan->periode_dari;
        // $periode_sampai = $ajuan->periode_sampai;

        // $bulan_dari = (int) date('m', strtotime($periode_dari));
        // $tahun_dari = (int) date('Y', strtotime($periode_dari));
        // $bulan_sampai = (int) date('m', strtotime($periode_sampai));
        // $tahun_sampai = (int) date('Y', strtotime($periode_sampai));

        // $array_bulan = [];
        // for ($tahun = $tahun_dari; $tahun <= $tahun_sampai; $tahun++) {
        //     for ($bulan = $tahun == $tahun_dari ? $bulan_dari : 1; $tahun == $tahun_sampai ? $bulan <= $bulan_sampai : $bulan <= 12; $bulan++) {
        //         $array_bulan[] = $bulan . '-' . $tahun;
        //     }
        // }


        try {
            // $periodepencairan = explode('-', $request->periodepencairan);
            // $bulan = $periodepencairan[0];
            // $tahun = $periodepencairan[1];
            // $cekajuan = Pencairanprogramikatan::where('kode_program', $request->kode_program)
            //     ->where('bulan', $bulan)
            //     ->where('tahun', $tahun)
            //     ->where('kode_cabang', $kode_cabang)
            //     ->first();
            // if (!empty($cekajuan)) {
            //     return Redirect::back()->with(messageError('Periode Pencairan Sudah Ada'));
            // }
            // $cek = Pencairanprogramikatan::where('no_pengajuan', $request->no_pengajuan)
            //     ->first();

            // $bulan_sebelumnya = getbulandantahunlalu($bulan, $tahun, "bulan");
            // $tahun_sebelumnya = getbulandantahunlalu($bulan, $tahun, "tahun");

            //$periode_pertama = $array_bulan[0];
            // if (empty($cek) && $periode_pertama != $request->periodepencairan) {
            //     $bulan_tahun_periode_pertama = explode('-', $periode_pertama);
            //     $bulan_periode_pertama = $bulan_tahun_periode_pertama[0];
            //     $tahun_periode_pertama = $bulan_tahun_periode_pertama[1];
            //     return Redirect::back()->with(messageError('Periode Pencairan Harus Dimulai dari Periode ' . getMonthName($bulan_periode_pertama) . ' ' . $tahun_periode_pertama));
            // } else {
            //     if ($request->periodepencairan != $periode_pertama) {
            //         $cek_bulan_sebelumnya = Pencairanprogramikatan::where('no_pengajuan', $request->no_pengajuan)
            //             ->where('bulan', $bulan_sebelumnya)
            //             ->where('tahun', $tahun_sebelumnya)
            //             ->first();
            //         if (empty($cek_bulan_sebelumnya)) {
            //             return Redirect::back()->with(messageError('Periode Bulan ' . getMonthName($bulan_sebelumnya) . ' Tahun ' . $tahun_sebelumnya . ' Belum Dicairkan'));
            //         }
            //     }
            // }

            //code...
            Pencairanprogramikatan::create([
                'kode_pencairan' => $kode_pencairan,
                'tanggal' => $request->tanggal,
                'kode_program' => $request->kode_program,
                'kode_cabang' => $kode_cabang,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'keterangan' => $request->keterangan
            ]);
            return Redirect::back()->with(messageSuccess("Data Berhasil Disimpan"));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }




    public function setpencairan($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $query = Pencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan.*',
            'cabang.nama_cabang',
            'nama_program',
        );
        $query->join('cabang', 'marketing_pencairan_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_pencairan_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogramikatan = $query->first();


        $pelangganprogram = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            'marketing_program_ikatan_detail.top',
            'marketing_program_ikatan_detail.metode_pembayaran',
            'marketing_program_ikatan_target.target_perbulan as qty_target',
            'reward',
            'tipe_reward',
            'budget_smm',
            'budget_rsm',
            'budget_gm'
        )
            ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->where('marketing_program_ikatan.status', 1)
            ->where('marketing_program_ikatan.kode_program', $pencairanprogramikatan->kode_program)
            ->where('marketing_program_ikatan_target.bulan', $pencairanprogramikatan->bulan)
            ->where('marketing_program_ikatan_target.tahun', $pencairanprogramikatan->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogramikatan->kode_cabang);




        $detail = Detailpencairanprogramikatan::join('pelanggan', 'marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            ->leftJoinSub($pelangganprogram, 'pelangganprogram', function ($join) {
                $join->on('marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelangganprogram.kode_pelanggan');
            })
            ->select(
                'marketing_pencairan_ikatan_detail.*',
                'pelanggan.nama_pelanggan',
                'top',
                'metode_pembayaran',
                'qty_target',
                'reward',
                'tipe_reward',
                'budget_smm',
                'budget_rsm',
                'budget_gm',
                'kode_program'
            )
            ->where('marketing_pencairan_ikatan_detail.kode_pencairan', $kode_pencairan)
            ->orderBy('pelangganprogram.metode_pembayaran')
            ->get();
        $data['pencairanprogram'] = $pencairanprogramikatan;
        $data['detail'] = $detail;
        $data['user'] = User::find(auth()->user()->id);
        return view('worksheetom.pencairanprogramikatan.setpencairan', $data);
    }


    function tambahpelanggan($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $data['kode_pencairan'] = $kode_pencairan;
        return view('worksheetom.pencairanprogramikatan.tambahpelanggan', $data);
    }

    public function getpelanggan(Request $request)
    {

        $kode_pencairan = Crypt::decrypt($request->kode_pencairan);
        $query = Pencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan.*',
            'cabang.nama_cabang',
            'nama_program',
            'produk',

        );

        $query->join('cabang', 'marketing_pencairan_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_pencairan_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogram = $query->first();

        // $listpelangganikatan = Detailajuanprogramikatan::where('no_pengajuan', $pencairanprogram->no_pengajuan);
        $pelanggansudahdicairkan = Detailpencairanprogramikatan::join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            ->select('kode_pelanggan')
            ->where('marketing_pencairan_ikatan.bulan', $pencairanprogram->bulan)
            ->where('marketing_pencairan_ikatan.tahun', $pencairanprogram->tahun)
            ->where('marketing_pencairan_ikatan.kode_program', $pencairanprogram->kode_program)
            ->where('marketing_pencairan_ikatan.kode_cabang', $pencairanprogram->kode_cabang);


        $lastbulan = getbulandantahunlalu($pencairanprogram->bulan, $pencairanprogram->tahun, "bulan");
        $lasttahun = getbulandantahunlalu($pencairanprogram->bulan, $pencairanprogram->tahun, "tahun");




        $start_last_date = "2025-01-01";

        $end_last_date = date('Y-m-t', strtotime($lasttahun . '-' . $lastbulan . '-01'));


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
            ->where('marketing_program_ikatan.kode_program', $pencairanprogram->kode_program)
            ->where('marketing_program_ikatan_target.bulan', $pencairanprogram->bulan)
            ->where('marketing_program_ikatan_target.tahun', $pencairanprogram->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogram->kode_cabang);

        $start_date = $pencairanprogram->tahun . '-' . $pencairanprogram->bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));

        $produk = json_decode($pencairanprogram->produk, true) ?? [];



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
            ->where('salesman.kode_cabang', $pencairanprogram->kode_cabang)
            ->where('marketing_penjualan.status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)

            ->groupBy('marketing_penjualan.kode_pelanggan', DB::raw('MONTH(marketing_penjualan.tanggal)'));

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
            ->where('salesman.kode_cabang', $pencairanprogram->kode_cabang)
            ->where('marketing_penjualan.status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)

            ->groupBy('marketing_penjualan.kode_pelanggan');



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

            ->whereNotIn('marketing_program_ikatan_target.kode_pelanggan', $pelanggansudahdicairkan)
            ->where('marketing_program_ikatan.status', 1)
            ->where('marketing_program_ikatan.kode_program', $pencairanprogram->kode_program)
            ->when($pencairanprogram->kode_program == 'PRIK002' && $pencairanprogram->bulan > 6, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '>', 6);
            })
            ->where('marketing_program_ikatan_target.bulan', '<', $pencairanprogram->bulan)
            ->where('marketing_program_ikatan_target.tahun', $pencairanprogram->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogram->kode_cabang)
            ->whereRaw('IFNULL(jml_dus,0) < target_perbulan');


        // Query untuk mencari pelanggan yang tidak tercapai lebih dari 1 bulan
        $peserta_gagal_lebih_dari_satu_bulan = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            DB::raw('COUNT(*) as jumlah_bulan_gagal')
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
            ->whereNotIn('marketing_program_ikatan_target.kode_pelanggan', $pelanggansudahdicairkan)
            ->where('marketing_program_ikatan.status', 1)
            ->where('marketing_program_ikatan.kode_program', $pencairanprogram->kode_program)
            ->where('marketing_program_ikatan_target.bulan', '<', $pencairanprogram->bulan)
            ->where('marketing_program_ikatan_target.tahun', $pencairanprogram->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogram->kode_cabang)
            ->whereRaw('IFNULL(jml_dus,0) < target_perbulan')
            ->groupBy('marketing_program_ikatan_target.kode_pelanggan')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('marketing_program_ikatan_target.kode_pelanggan');


        $peserta_ikut_program_enambulan = Detailajuanprogramikatanenambulan::join('marketing_program_ikatan_enambulan', 'marketing_program_ikatan_enambulan_detail.no_pengajuan', '=', 'marketing_program_ikatan_enambulan.no_pengajuan')
            ->select('kode_pelanggan')
            ->where('kode_program', $pencairanprogram->kode_program)
            ->where('marketing_program_ikatan_enambulan.kode_cabang', $pencairanprogram->kode_cabang)
            ->where('marketing_program_ikatan_enambulan.periode_pencairan', 2)
            ->where('marketing_program_ikatan_enambulan.status', 1)
            ->get()->pluck('kode_pelanggan');


        //dd($peserta_ikut_program_enambulan);


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
            'marketing_program_ikatan.kode_program'
        )
            ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
                $join->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'detailpenjualan.kode_pelanggan');
            })
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->whereNotIn('marketing_program_ikatan_target.kode_pelanggan', $pelanggansudahdicairkan)
            // Query where ini artinya: ambil data pelanggan yang TIDAK termasuk dalam daftar peserta_gagal
            // ATAU pelanggan yang termasuk dalam daftar peserta_ikut_program_enambulan.
            // Dengan kata lain, pelanggan yang gagal (tidak memenuhi syarat) akan dikecualikan,
            // kecuali jika pelanggan tersebut juga merupakan peserta program enam bulan (peserta_ikut_program_enambulan),
            // maka tetap akan diikutkan.
            ->where(function ($query) use ($peserta_gagal, $peserta_ikut_program_enambulan) {
                $query->whereNotIn('marketing_program_ikatan_target.kode_pelanggan', $peserta_gagal)
                    ->orWhereIn('marketing_program_ikatan_target.kode_pelanggan', $peserta_ikut_program_enambulan);
            })
            ->where('marketing_program_ikatan.status', 1)
            ->where('marketing_program_ikatan.kode_program', $pencairanprogram->kode_program)
            ->where('marketing_program_ikatan_target.bulan', $pencairanprogram->bulan)
            ->where('marketing_program_ikatan_target.tahun', $pencairanprogram->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogram->kode_cabang)
            ->get();


        // dd($peserta);
        // $data['detail'] = $detail;
        $data['kode_pencairan'] = $kode_pencairan;
        $data['peserta'] = $peserta;
        // $data['bulan'] = $request->bulan;
        // $data['tahun'] = $request->tahun;
        // $data['diskon'] = $request->diskon;
        // $data['kategori_diskon'] = $kategori_diskon;
        // $data['kode_program'] = $request->kode_program;
        // $data['kode_cabang'] = $request->kode_cabang;
        return view('worksheetom.pencairanprogramikatan.getpelanggan', $data);
    }

    public function storepelanggan(Request $request, $kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $kode_pelanggan = $request->kode_pelanggan;
        $jumlah = $request->jumlah;
        $status = $request->status;
        $status_pencairan = $request->status_pencairan;

        // dd($status_pencairan);
        // dd($kode_pelanggan);
        DB::beginTransaction();
        try {
            //Detailpencairanprogramikatan::where('kode_pencairan', $kode_pencairan)->delete();
            // for ($i = 0; $i < count($kode_pelanggan); $i++) {

            //     if ($status[$i] == 1) {
            //         Detailpencairanprogramikatan::create([
            //             'kode_pencairan' => $kode_pencairan,
            //             'kode_pelanggan' => $kode_pelanggan[$i],
            //             'jumlah' => toNumber($jumlah[$i]),
            //             'status_pencairan' => $status_pencairan[$i]
            //         ]);
            //         Detailajuanprogramikatan::where('kode_pelanggan', $kode_pelanggan[$i])->update([
            //             'status' => 1
            //         ]);
            //     } else {
            //         Detailajuanprogramikatan::where('kode_pelanggan', $kode_pelanggan[$i])->update([
            //             'status' => 0
            //         ]);
            //     }
            // }

            $checkpelanggan = $request->input('checkpelanggan', []);

            //dd($status);
            foreach ($checkpelanggan as $index => $value) {

                if ($status[$index] == 1) {
                    Detailpencairanprogramikatan::create([
                        'kode_pencairan' => $kode_pencairan,
                        'kode_pelanggan' => $kode_pelanggan[$index],
                        'jumlah' => toNumber($jumlah[$index]),
                        'qty_tunai' => toNumber($request->qty_tunai[$index]),
                        'qty_kredit' => toNumber($request->qty_kredit[$index]),
                        'reward_tunai' => toNumber($request->reward_tunai[$index]),
                        'reward_kredit' => toNumber($request->reward_kredit[$index]),
                        'total_reward' => toNumber($request->total_reward[$index]),
                        'status_pencairan' => $status_pencairan[$index]
                    ]);

                    Detailajuanprogramikatan::where('kode_pelanggan', $kode_pelanggan[$index])->update([
                        'status' => 1
                    ]);
                } else {
                    Detailajuanprogramikatan::where('kode_pelanggan', $kode_pelanggan[$index])->update([
                        'status' => 0
                    ]);
                }
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Pelanggan Berhasil Di Proses'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        try {
            Pencairanprogramikatan::where('kode_pencairan', $kode_pencairan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function approve($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $query = Pencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan.*',
            'cabang.nama_cabang',
            'nama_program',
        );
        $query->join('cabang', 'marketing_pencairan_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_pencairan_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogramikatan = $query->first();


        $pelangganprogram = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            'marketing_program_ikatan_detail.top',
            'marketing_program_ikatan_detail.metode_pembayaran',
            'marketing_program_ikatan_target.target_perbulan as qty_target',
            'reward',
            'tipe_reward',
            'budget_smm',
            'budget_rsm',
            'budget_gm',
            'kode_program'
        )
            ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->where('marketing_program_ikatan.status', 1)
            ->where('marketing_program_ikatan.kode_program', $pencairanprogramikatan->kode_program)
            ->where('marketing_program_ikatan_target.bulan', $pencairanprogramikatan->bulan)
            ->where('marketing_program_ikatan_target.tahun', $pencairanprogramikatan->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogramikatan->kode_cabang);




        $detail = Detailpencairanprogramikatan::join('pelanggan', 'marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            ->leftJoinSub($pelangganprogram, 'pelangganprogram', function ($join) {
                $join->on('marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelangganprogram.kode_pelanggan');
            })
            ->select(
                'marketing_pencairan_ikatan_detail.*',
                'pelanggan.nama_pelanggan',
                'pelanggan.bank',
                'pelanggan.pemilik_rekening',
                'pelanggan.no_rekening',
                'pelangganprogram.metode_pembayaran',
                'pelangganprogram.top',
                'pelangganprogram.qty_target',
                'pelangganprogram.reward',
                'pelangganprogram.tipe_reward',
                'pelangganprogram.budget_smm',
                'pelangganprogram.budget_rsm',
                'pelangganprogram.budget_gm',
                'pelangganprogram.kode_program'
            )
            ->where('marketing_pencairan_ikatan_detail.kode_pencairan', $kode_pencairan)
            ->orderBy('pelangganprogram.metode_pembayaran')
            ->get();
        $data['pencairanprogram'] = $pencairanprogramikatan;
        $data['detail'] = $detail;
        return view('worksheetom.pencairanprogramikatan.approve', $data);
    }

    public function storeapprove(Request $request, $kode_pencairan)
    {
        $user = User::find(auth()->user()->id);
        if ($user->hasRole('operation manager')) {
            $field = 'om';
        } else if ($user->hasRole('regional sales manager')) {
            $field = 'rsm';
        } else if ($user->hasRole('gm marketing')) {
            $field = 'gm';
        } else if ($user->hasRole('direktur')) {
            $field = 'direktur';
        } else if ($user->hasRole(['manager keuangan', 'staff keuangan'])) {
            $field = 'keuangan';
        }


        // dd(isset($_POST['decline']));
        if (isset($_POST['decline'])) {
            $status  = 2;
        } else {
            $status = $user->hasRole(['direktur', 'super admin', 'manager keuangan', 'staff keuangan'])  ? 1 : 0;
        }

        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        try {
            if ($user->hasRole('super admin')) {
                Pencairanprogramikatan::where('kode_pencairan', $kode_pencairan)
                    ->update([
                        'status' => $status
                    ]);
            } else {
                Pencairanprogramikatan::where('kode_pencairan', $kode_pencairan)
                    ->update([
                        $field => auth()->user()->id,
                        'status' => $status
                    ]);

                if (isset($_POST['cancel'])) {
                    Pencairanprogram::where('kode_pencairan', $kode_pencairan)
                        ->update([
                            'keuangan' => 0
                        ]);
                }
            }

            return Redirect::back()->with(messageSuccess('Data Berhasil Di Approve'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function upload($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        // $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        $data['kode_pencairan'] = $kode_pencairan;
        // $data['kode_pelanggan'] = $kode_pelanggan;
        return view('worksheetom.pencairanprogramikatan.upload', $data);
    }

    public function storeupload(Request $request, $kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        // dd($kode_pencairan);
        // $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        try {
            //code...
            // Detailpencairanprogramikatan::where('kode_pencairan', $kode_pencairan)
            //     ->where('kode_pelanggan', $kode_pelanggan)
            //     ->update([fstore
            //         'bukti_transfer' => $request->bukti_transfer
            //     ]);

            Pencairanprogramikatan::where('kode_pencairan', $kode_pencairan)
                ->update([
                    'bukti_transfer' => $request->bukti_transfer
                ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Upload'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function cetak($kode_pencairan, Request $request)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $query = Pencairanprogramikatan::query();
        $query->select(
            'marketing_pencairan_ikatan.*',
            'cabang.nama_cabang',
            'nama_program',
        );
        $query->join('cabang', 'marketing_pencairan_ikatan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_pencairan_ikatan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogramikatan = $query->first();



        $pelangganprogram = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            'marketing_program_ikatan_detail.top',
            'marketing_program_ikatan_detail.metode_pembayaran',
            'marketing_program_ikatan_target.target_perbulan as qty_target',
            'reward',
            'tipe_reward',
            'budget_smm',
            'budget_rsm',
            'budget_gm',
            'kode_program'
        )
            ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->where('marketing_program_ikatan.status', 1)
            ->where('marketing_program_ikatan.kode_program', $pencairanprogramikatan->kode_program)
            ->where('marketing_program_ikatan_target.bulan', $pencairanprogramikatan->bulan)
            ->where('marketing_program_ikatan_target.tahun', $pencairanprogramikatan->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogramikatan->kode_cabang);


        $detail = Detailpencairanprogramikatan::join('pelanggan', 'marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            ->leftJoinSub($pelangganprogram, 'pelangganprogram', function ($join) {
                $join->on('marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelangganprogram.kode_pelanggan');
            })
            ->where('marketing_pencairan_ikatan_detail.kode_pencairan', $kode_pencairan)
            ->where('status_pencairan', 1)
            ->orderBy('pelangganprogram.metode_pembayaran')
            ->get();

        $detail_hold = Detailpencairanprogramikatan::join('pelanggan', 'marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            ->leftJoinSub($pelangganprogram, 'pelangganprogram', function ($join) {
                $join->on('marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelangganprogram.kode_pelanggan');
            })
            ->where('marketing_pencairan_ikatan_detail.kode_pencairan', $kode_pencairan)
            ->where('status_pencairan', 0)
            ->orderBy('pelangganprogram.metode_pembayaran')
            ->get();
        $data['pencairanprogram'] = $pencairanprogramikatan;
        $data['detail'] = $detail;
        $data['detail_hold'] = $detail_hold;

        if ($request->export == 'true') {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=$kode_pencairan.xls");
            return view('worksheetom.pencairanprogramikatan.cetak_export', $data);
        }
        return view('worksheetom.pencairanprogramikatan.cetak', $data);
    }


    public function detailfaktur($kode_pelanggan, $kode_pencairan)
    {

        $kode_pencairan = Crypt::decrypt($kode_pencairan);


        $pencairanprogram = Pencairanprogramikatan::where('kode_pencairan', $kode_pencairan)
            ->join('program_ikatan', 'marketing_pencairan_ikatan.kode_program', '=', 'program_ikatan.kode_program')
            ->first();
        $bulan = $pencairanprogram->bulan;
        $tahun = $pencairanprogram->tahun;


        $start_date = $tahun . '-' . $bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));

        $produk = json_decode($pencairanprogram->produk, true) ?? [];

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


    public function deletepelanggan($kode_pencairan, $kode_pelanggan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        try {
            Detailpencairanprogramikatan::where('kode_pencairan', $kode_pencairan)->where('kode_pelanggan', $kode_pelanggan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

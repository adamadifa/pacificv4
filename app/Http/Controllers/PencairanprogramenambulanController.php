<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailajuanprogramikatanenambulan;
use App\Models\Detailpencairanprogramenambulan;
use App\Models\Detailpencairanprogramikatan;
use App\Models\Detailpenjualan;
use App\Models\Detailtargetikatan;
use App\Models\Pencairanprogramenambulan;
use App\Models\Programikatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PencairanprogramenambulanController extends Controller
{
    public function index(Request $request)
    {

        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');


        $query = Pencairanprogramenambulan::query();
        $query->select(
            'marketing_pencairan_ikatan_enambulan.*',
            'cabang.nama_cabang',
            'nama_program',
        );
        $query->join('program_ikatan', 'marketing_pencairan_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program');
        $query->join('cabang', 'marketing_pencairan_ikatan_enambulan.kode_cabang', '=', 'cabang.kode_cabang');


        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_pencairan_ikatan_enambulan.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('marketing_pencairan_ikatan_enambulan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_program)) {
            $query->where('marketing_pencairan_ikatan_enambulan.kode_program', $request->kode_program);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_pencairan_ikatan_enambulan.tanggal', [$request->dari, $request->sampai]);
        }



        if ($user->hasRole('regional sales manager')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_pencairan_ikatan_enambulan.om');
                    $query->whereNull('marketing_pencairan_ikatan_enambulan.rsm');
                } else if ($request->status == 'approved') {
                    $query->whereNotnull('marketing_pencairan_ikatan_enambulan.rsm');
                    $query->where('status', 0);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_pencairan_ikatan_enambulan.om');
            // $query->where('marketing_pencairan_ikatan_enambulan.status', '!=', 2);
        }

        if ($user->hasRole('gm marketing')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_pencairan_ikatan_enambulan.rsm');
                    $query->whereNull('marketing_pencairan_ikatan_enambulan.gm');
                } else if ($request->status == 'approved') {
                    $query->whereNotnull('marketing_pencairan_ikatan_enambulan.gm');
                    $query->where('status', 0);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_pencairan_ikatan_enambulan.rsm');
            // $query->where('marketing_pencairan_ikatan_enambulan.status', '!=', 2);
        }

        if ($user->hasRole('direktur')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_pencairan_ikatan_enambulan.gm');
                    $query->whereNull('marketing_pencairan_ikatan_enambulan.direktur');
                } else if ($request->status == 'approved') {
                    $query->whereNotnull('marketing_pencairan_ikatan_enambulan.direktur');
                    $query->where('status', 0);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_pencairan_ikatan_enambulan.gm');
            // $query->where('marketing_pencairan_ikatan_enambulan.status', '!=', 2);
        }

        if ($user->hasRole('direktur')) {
            $query->orderBy('marketing_pencairan_ikatan_enambulan.status', 'asc');
            $query->orderBy('marketing_pencairan_ikatan_enambulan.semester', 'desc');
            $query->orderBy('marketing_pencairan_ikatan_enambulan.tahun', 'desc');
        } else if ($user->hasRole('staff keuangan')) {
            $query->orderBy('marketing_pencairan_ikatan_enambulan.keuangan', 'asc');
            $query->orderBy('marketing_pencairan_ikatan_enambulan.semester', 'desc');
            $query->orderBy('marketing_pencairan_ikatan_enambulan.tahun', 'desc');
        } else {
            $query->orderBy('marketing_pencairan_ikatan_enambulan.status', 'asc');
            $query->orderBy('marketing_pencairan_ikatan_enambulan.semester', 'desc');
            $query->orderBy('marketing_pencairan_ikatan_enambulan.tahun', 'desc');
        }
        $pencairanprogramenambulan = $query->paginate(15);
        $pencairanprogramenambulan->appends(request()->all());
        $data['pencairanprogramenambulan'] = $pencairanprogramenambulan;

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['user'] = $user;
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.pencairanprogramenambulan.index', $data);
    }


    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.pencairanprogramenambulan.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            $request->validate([
                'tanggal' => 'required',
                'kode_program' => 'required',
                'semester' => 'required',
                'tahun' => 'required',
                'keterangan' => 'required'
            ]);
        } else {
            $request->validate([
                'tanggal' => 'required',
                'kode_program' => 'required',
                'kode_cabang' => 'required',
                'semester' => 'required',
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

        $lastpencairan = Pencairanprogramenambulan::select('kode_pencairan')->orderBy('kode_pencairan', 'desc')
            ->whereRaw('YEAR(marketing_pencairan_ikatan_enambulan.tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
            ->where('kode_cabang', $kode_cabang)
            ->first();
        $last_kode_pencairan = $lastpencairan != null ? $lastpencairan->kode_pencairan : '';


        $kode_pencairan = buatkode($last_kode_pencairan, "PI" . $kode_cabang . date('y', strtotime($request->tanggal)), 4);



        try {

            Pencairanprogramenambulan::create([
                'kode_pencairan' => $kode_pencairan,
                'tanggal' => $request->tanggal,
                'kode_program' => $request->kode_program,
                'kode_cabang' => $kode_cabang,
                'semester' => $request->semester,
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
        $query = Pencairanprogramenambulan::query();
        $query->select(
            'marketing_pencairan_ikatan_enambulan.*',
            'cabang.nama_cabang',
            'nama_program',
        );
        $query->join('cabang', 'marketing_pencairan_ikatan_enambulan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_pencairan_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan_enambulan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogramenambulan = $query->first();


        $pelangganprogram = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            'marketing_program_ikatan_detail.top',
            'marketing_program_ikatan_detail.metode_pembayaran',
            DB::raw('SUM(marketing_program_ikatan_target.target_perbulan) as qty_target'),
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
            ->where('marketing_program_ikatan.kode_program', $pencairanprogramenambulan->kode_program)
            ->when($pencairanprogramenambulan->semester == 1, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '<=', 6);
            })
            ->when($pencairanprogramenambulan->semester == 2, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '>', 6);
            })
            ->where('marketing_program_ikatan_target.tahun', $pencairanprogramenambulan->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogramenambulan->kode_cabang)
            ->groupBy(
                'marketing_program_ikatan_target.kode_pelanggan',
                'marketing_program_ikatan_detail.top',
                'marketing_program_ikatan_detail.metode_pembayaran',
                'reward',
                'tipe_reward',
                'budget_smm',
                'budget_rsm',
                'budget_gm'
            );




        $detail = Detailpencairanprogramenambulan::join('pelanggan', 'marketing_pencairan_ikatan_enambulan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_pencairan_ikatan_enambulan', 'marketing_pencairan_ikatan_enambulan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan_enambulan.kode_pencairan')
            ->leftJoinSub($pelangganprogram, 'pelangganprogram', function ($join) {
                $join->on('marketing_pencairan_ikatan_enambulan_detail.kode_pelanggan', '=', 'pelangganprogram.kode_pelanggan');
            })
            ->select(
                'marketing_pencairan_ikatan_enambulan_detail.*',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_rekening',
                'pelanggan.pemilik_rekening',
                'pelanggan.bank',
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
            ->where('marketing_pencairan_ikatan_enambulan_detail.kode_pencairan', $kode_pencairan)
            ->orderBy('pelangganprogram.metode_pembayaran')
            ->get();

        $data['pencairanprogram'] = $pencairanprogramenambulan;
        $data['detail'] = $detail;
        $data['user'] = User::find(auth()->user()->id);
        return view('worksheetom.pencairanprogramenambulan.setpencairan', $data);
    }

    function tambahpelanggan($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $pencairanprogram = Pencairanprogramenambulan::where('kode_pencairan', $kode_pencairan)->first();
        if ($pencairanprogram->semester == 1) {
            $start_date = $pencairanprogram->tahun . '-01-01';
            $end_date = date('Y-m-t', strtotime($pencairanprogram->tahun . '-06-01'));
        } else {
            $start_date = $pencairanprogram->tahun . '-07-01';
            $end_date = date('Y-m-t', strtotime($pencairanprogram->tahun . '-12-01'));
        }

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['kode_pencairan'] = $kode_pencairan;

        return view('worksheetom.pencairanprogramenambulan.tambahpelanggan', $data);
    }


    public function getpelanggan(Request $request)
    {

        $kode_pencairan = Crypt::decrypt($request->kode_pencairan);
        $query = Pencairanprogramenambulan::query();
        $query->select(
            'marketing_pencairan_ikatan_enambulan.*',
            'cabang.nama_cabang',
            'nama_program',
            'produk',

        );

        $query->join('cabang', 'marketing_pencairan_ikatan_enambulan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_pencairan_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan_enambulan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogram = $query->first();


        if ($pencairanprogram->semester == 1) {
            $start_date = $pencairanprogram->tahun . '-01-01';
            $end_date = date('Y-m-t', strtotime($pencairanprogram->tahun . '-06-01'));
        } else {
            $start_date = $pencairanprogram->tahun . '-07-01';
            $end_date = date('Y-m-t', strtotime($pencairanprogram->tahun . '-12-01'));
        }


        $pelanggansudahdicairkan = Detailpencairanprogramenambulan::join('marketing_pencairan_ikatan_enambulan', 'marketing_pencairan_ikatan_enambulan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan_enambulan.kode_pencairan')
            ->select('kode_pelanggan')
            ->where('marketing_pencairan_ikatan_enambulan.semester', $pencairanprogram->semester)
            ->where('marketing_pencairan_ikatan_enambulan.tahun', $pencairanprogram->tahun)
            ->where('marketing_pencairan_ikatan_enambulan.kode_program', $pencairanprogram->kode_program)
            ->where('marketing_pencairan_ikatan_enambulan.kode_cabang', $pencairanprogram->kode_cabang);


        $pelangganprogramenambulan = Detailajuanprogramikatanenambulan::select('kode_pelanggan')
            ->join('marketing_program_ikatan_enambulan', 'marketing_program_ikatan_enambulan_detail.no_pengajuan', '=', 'marketing_program_ikatan_enambulan.no_pengajuan')
            ->where('marketing_program_ikatan_enambulan.kode_program', $pencairanprogram->kode_program)
            ->where('marketing_program_ikatan_enambulan.kode_cabang', $pencairanprogram->kode_cabang)
            ->where('marketing_program_ikatan_enambulan.periode_pencairan', '1')
            ->when($pencairanprogram->semester == 1, function ($query) {
                $query->whereRaw('MONTH(marketing_program_ikatan_enambulan.periode_sampai) <= 6');
            })
            ->when($pencairanprogram->semester == 2, function ($query) {
                $query->whereRaw('MONTH(marketing_program_ikatan_enambulan.periode_dari) >= 7');
            })


            ->groupBy('marketing_program_ikatan_enambulan_detail.kode_pelanggan');


        $pencairanreguler = Detailpencairanprogramikatan::select('kode_pelanggan', DB::raw('SUM(total_reward) as total_reward_reguler'))
            ->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan')
            ->where('kode_program', $pencairanprogram->kode_program)
            ->where('kode_cabang', $pencairanprogram->kode_cabang)
            ->when($pencairanprogram->semester == 1, function ($query) {
                $query->where('bulan', '<=', 6);
            })
            ->when($pencairanprogram->semester == 2, function ($query) {
                $query->where('bulan', '>', 6);
            })
            ->where('tahun', $pencairanprogram->tahun)
            ->whereIn('kode_pelanggan', $pelangganprogramenambulan)
            ->groupBy('kode_pelanggan');



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
            ->when($pencairanprogram->semester == 1, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '<=', 6);
            })
            ->when($pencairanprogram->semester == 2, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '>', 6);
            })
            ->where('marketing_program_ikatan_target.tahun', $pencairanprogram->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogram->kode_cabang)
            ->whereIn('marketing_program_ikatan_target.kode_pelanggan', $pelangganprogramenambulan)
            ->groupBy('marketing_program_ikatan_target.kode_pelanggan', 'marketing_program_ikatan_detail.top');




        $produk = json_decode($pencairanprogram->produk, true) ?? [];

        $select_jml_dus = [];
        $select_jml_dus_tunai = [];
        $select_jml_dus_kredit = [];

        $select_field_jml_dus = [];
        $select_field_jml_dus_tunai = [];
        $select_field_jml_dus_kredit = [];

        $select_target = [];


        for ($i = date('m', strtotime($start_date)); $i <= date('m', strtotime($end_date)); $i++) {
            $start_date_i = date('Y-m-01', strtotime($pencairanprogram->tahun . '-' . $i . '-01'));
            $end_date_i = date('Y-m-t', strtotime($start_date_i));

            $select_jml_dus[] = DB::raw('SUM(IF(marketing_penjualan.tanggal BETWEEN "' . $start_date_i . '" AND "' . $end_date_i . '" AND datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3, floor(jumlah/isi_pcs_dus), 0)) as jml_dus_bulan_' . (int)$i);
            $select_jml_dus_tunai[] = DB::raw('SUM(IF(marketing_penjualan.tanggal BETWEEN "' . $start_date_i . '" AND "' . $end_date_i . '" AND jenis_transaksi = "T" AND datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3, floor(jumlah/isi_pcs_dus), 0)) as jml_dus_tunai_bulan_' . (int)$i);
            $select_jml_dus_kredit[] = DB::raw('SUM(IF(marketing_penjualan.tanggal BETWEEN "' . $start_date_i . '" AND "' . $end_date_i . '" AND jenis_transaksi = "K" AND datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3, floor(jumlah/isi_pcs_dus), 0)) as jml_dus_kredit_bulan_' . (int)$i);

            $select_field_jml_dus[] = "jml_dus_bulan_" . (int)$i;
            $select_field_jml_dus_tunai[] = "jml_dus_tunai_bulan_" . (int)$i;
            $select_field_jml_dus_kredit[] = "jml_dus_kredit_bulan_" . (int)$i;


            $select_target[] = DB::raw('SUM(IF(bulan = ' . (int)$i . ', target_perbulan, 0)) as qty_target_bulan_' . (int)$i);
        }


        $detailpenjualan = Detailpenjualan::select(
            'marketing_penjualan.kode_pelanggan',
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) as total_jml_dus'),
            DB::raw('SUM(IF(datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3, floor(jumlah/isi_pcs_dus), 0)) as total_qty'),
            DB::raw('SUM(IF(jenis_transaksi = "T" AND datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3, floor(jumlah/isi_pcs_dus), 0)) as total_jml_dus_tunai'),
            DB::raw('SUM(IF(jenis_transaksi = "K" AND datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3, floor(jumlah/isi_pcs_dus), 0)) as total_jml_dus_kredit'),

            ...$select_jml_dus,
            ...$select_jml_dus_tunai,
            ...$select_jml_dus_kredit

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
            // ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= listpelangganikatan.top + 3")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            ->whereIn('marketing_penjualan.kode_pelanggan', $pelangganprogramenambulan)
            ->groupBy('marketing_penjualan.kode_pelanggan');



        $peserta = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            'nama_pelanggan',
            'budget_rsm',
            'budget_smm',
            'budget_gm',
            'reward',
            'file_doc',
            'marketing_program_ikatan.kode_program',
            DB::raw('SUM(target_perbulan) as total_qty_target'),
            'total_jml_dus',
            'total_jml_dus_tunai',
            'total_jml_dus_kredit',
            'total_reward_reguler',
            ...$select_target,
            ...$select_field_jml_dus,
            ...$select_field_jml_dus_tunai,
            ...$select_field_jml_dus_kredit
        )
            ->join('pelanggan', 'marketing_program_ikatan_target.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_program_ikatan_detail', function ($join) {
                $join->on('marketing_program_ikatan_target.no_pengajuan', '=', 'marketing_program_ikatan_detail.no_pengajuan')
                    ->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'marketing_program_ikatan_detail.kode_pelanggan');
            })
            ->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
                $join->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'detailpenjualan.kode_pelanggan');
            })

            ->leftJoinSub($pencairanreguler, 'pencairanreguler', function ($join) {
                $join->on('marketing_program_ikatan_target.kode_pelanggan', '=', 'pencairanreguler.kode_pelanggan');
            })
            ->join('marketing_program_ikatan', 'marketing_program_ikatan_detail.no_pengajuan', '=', 'marketing_program_ikatan.no_pengajuan')
            ->whereNotIn('marketing_program_ikatan_target.kode_pelanggan', $pelanggansudahdicairkan)
            ->where('marketing_program_ikatan.status', 1)
            ->where('marketing_program_ikatan.kode_program', $pencairanprogram->kode_program)
            ->when($pencairanprogram->semester == 1, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '<=', 6);
            })
            ->when($pencairanprogram->semester == 2, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '>', 6);
            })

            ->where('marketing_program_ikatan_target.tahun', $pencairanprogram->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogram->kode_cabang)
            ->whereIn('marketing_program_ikatan_target.kode_pelanggan', $pelangganprogramenambulan)
            ->groupBy(
                'marketing_program_ikatan_target.kode_pelanggan',
                'nama_pelanggan',
                'budget_rsm',
                'budget_smm',
                'budget_gm',
                'reward',
                'file_doc',
                'marketing_program_ikatan.kode_program',
                'total_jml_dus',
                'total_jml_dus_tunai',
                'total_jml_dus_kredit',
                ...$select_field_jml_dus,
                ...$select_field_jml_dus_tunai,
                ...$select_field_jml_dus_kredit,

            )
            ->get();

        //dd($peserta);


        // dd($peserta);
        // $data['detail'] = $detail;
        $data['kode_pencairan'] = $kode_pencairan;
        $data['peserta'] = $peserta;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        return view('worksheetom.pencairanprogramenambulan.getpelanggan', $data);
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
            //dd($checkpelanggan);
            //dd($status);
            foreach ($checkpelanggan as $index => $value) {
                if ($status[$index] == 1) {
                    Detailpencairanprogramenambulan::create([
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

                    Detailajuanprogramikatanenambulan::where('kode_pelanggan', $kode_pelanggan[$index])->update([
                        'status' => 1
                    ]);
                } else {
                    Detailajuanprogramikatanenambulan::where('kode_pelanggan', $kode_pelanggan[$index])->update([
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


    public function detailfaktur($kode_pelanggan, $kode_pencairan)
    {

        $kode_pencairan = Crypt::decrypt($kode_pencairan);


        $pencairanprogram = Pencairanprogramenambulan::where('kode_pencairan', $kode_pencairan)
            ->join('program_ikatan', 'marketing_pencairan_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program')
            ->first();



        if ($pencairanprogram->semester == 1) {
            $start_date = $pencairanprogram->tahun . '-01-01';
            $end_date = $pencairanprogram->tahun . '-06-30';
        } else {
            $start_date = $pencairanprogram->tahun . '-07-01';
            $end_date = $pencairanprogram->tahun . '-12-31';
        }

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
            Detailpencairanprogramenambulan::where('kode_pencairan', $kode_pencairan)->where('kode_pelanggan', $kode_pelanggan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function approve($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $query = Pencairanprogramenambulan::query();
        $query->select(
            'marketing_pencairan_ikatan_enambulan.*',
            'cabang.nama_cabang',
            'nama_program',
        );
        $query->join('cabang', 'marketing_pencairan_ikatan_enambulan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_pencairan_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan_enambulan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogramenambulan = $query->first();


        $pelangganprogram = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            'marketing_program_ikatan_detail.top',
            'marketing_program_ikatan_detail.metode_pembayaran',
            DB::raw('SUM(marketing_program_ikatan_target.target_perbulan) as qty_target'),
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
            ->where('marketing_program_ikatan.kode_program', $pencairanprogramenambulan->kode_program)
            ->when($pencairanprogramenambulan->semester == 1, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '<=', 6);
            })
            ->when($pencairanprogramenambulan->semester == 2, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '>', 6);
            })
            ->where('marketing_program_ikatan_target.tahun', $pencairanprogramenambulan->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogramenambulan->kode_cabang)
            ->groupBy(
                'marketing_program_ikatan_target.kode_pelanggan',
                'marketing_program_ikatan_detail.top',
                'marketing_program_ikatan_detail.metode_pembayaran',
                'reward',
                'tipe_reward',
                'budget_smm',
                'budget_rsm',
                'budget_gm'
            );




        $detail = Detailpencairanprogramenambulan::join('pelanggan', 'marketing_pencairan_ikatan_enambulan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_pencairan_ikatan_enambulan', 'marketing_pencairan_ikatan_enambulan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan_enambulan.kode_pencairan')
            ->leftJoinSub($pelangganprogram, 'pelangganprogram', function ($join) {
                $join->on('marketing_pencairan_ikatan_enambulan_detail.kode_pelanggan', '=', 'pelangganprogram.kode_pelanggan');
            })
            ->select(
                'marketing_pencairan_ikatan_enambulan_detail.*',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_rekening',
                'pelanggan.pemilik_rekening',
                'pelanggan.bank',
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
            ->where('marketing_pencairan_ikatan_enambulan_detail.kode_pencairan', $kode_pencairan)
            ->orderBy('pelangganprogram.metode_pembayaran')
            ->get();

        $data['pencairanprogram'] = $pencairanprogramenambulan;
        $data['detail'] = $detail;
        return view('worksheetom.pencairanprogramenambulan.approve', $data);
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
                Pencairanprogramenambulan::where('kode_pencairan', $kode_pencairan)
                    ->update([
                        'status' => $status
                    ]);
            } else {
                Pencairanprogramenambulan::where('kode_pencairan', $kode_pencairan)
                    ->update([
                        $field => auth()->user()->id,
                        'status' => $status
                    ]);

                if (isset($_POST['cancel'])) {
                    Pencairanprogramenambulan::where('kode_pencairan', $kode_pencairan)
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


    public function destroy($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        try {
            Pencairanprogramenambulan::where('kode_pencairan', $kode_pencairan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Hapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }



    public function cetak($kode_pencairan, Request $request)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $query = Pencairanprogramenambulan::query();
        $query->select(
            'marketing_pencairan_ikatan_enambulan.*',
            'cabang.nama_cabang',
            'nama_program',
        );
        $query->join('cabang', 'marketing_pencairan_ikatan_enambulan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_pencairan_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_pencairan_ikatan_enambulan.tanggal', 'desc');
        $query->where('kode_pencairan', $kode_pencairan);
        $pencairanprogramenambulan = $query->first();


        $pelangganprogram = Detailtargetikatan::select(
            'marketing_program_ikatan_target.kode_pelanggan',
            'marketing_program_ikatan_detail.top',
            'marketing_program_ikatan_detail.metode_pembayaran',
            DB::raw('SUM(marketing_program_ikatan_target.target_perbulan) as qty_target'),
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
            ->where('marketing_program_ikatan.kode_program', $pencairanprogramenambulan->kode_program)
            ->when($pencairanprogramenambulan->semester == 1, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '<=', 6);
            })
            ->when($pencairanprogramenambulan->semester == 2, function ($query) {
                $query->where('marketing_program_ikatan_target.bulan', '>', 6);
            })
            ->where('marketing_program_ikatan_target.tahun', $pencairanprogramenambulan->tahun)
            ->where('marketing_program_ikatan.kode_cabang', $pencairanprogramenambulan->kode_cabang)
            ->groupBy(
                'marketing_program_ikatan_target.kode_pelanggan',
                'marketing_program_ikatan_detail.top',
                'marketing_program_ikatan_detail.metode_pembayaran',
                'reward',
                'tipe_reward',
                'budget_smm',
                'budget_rsm',
                'budget_gm'
            );




        $detail = Detailpencairanprogramenambulan::join('pelanggan', 'marketing_pencairan_ikatan_enambulan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('marketing_pencairan_ikatan_enambulan', 'marketing_pencairan_ikatan_enambulan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan_enambulan.kode_pencairan')
            ->leftJoinSub($pelangganprogram, 'pelangganprogram', function ($join) {
                $join->on('marketing_pencairan_ikatan_enambulan_detail.kode_pelanggan', '=', 'pelangganprogram.kode_pelanggan');
            })
            ->select(
                'marketing_pencairan_ikatan_enambulan_detail.*',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_rekening',
                'pelanggan.pemilik_rekening',
                'pelanggan.bank',
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
            ->where('marketing_pencairan_ikatan_enambulan_detail.kode_pencairan', $kode_pencairan)
            ->orderBy('pelangganprogram.metode_pembayaran')
            ->get();


        $data['pencairanprogram'] = $pencairanprogramenambulan;
        $data['detail'] = $detail;


        if ($request->export == 'true') {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=$kode_pencairan.xls");
            return view('worksheetom.pencairanprogramenambulan.cetak_export', $data);
        }
        return view('worksheetom.pencairanprogramenambulan.cetak', $data);
    }
}

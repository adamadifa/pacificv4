<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailpenjualan;
use App\Models\Detailtargetkomisi;
use App\Models\Disposisitargetkomisi;
use App\Models\Produk;
use App\Models\Salesman;
use App\Models\Targetkomisi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TargetkomisiController extends Controller
{
    public function index(Request $request)
    {
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $roles_approve_targetkomisi = config('global.roles_aprove_targetkomisi');
        $user = User::findorfail(auth()->user()->id);
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');

        // dd($user->roles->pluck('name'));
        if ($user->hasRole($roles_approve_targetkomisi) && !$user->hasRole('regional sales manager')) {
            $query = Disposisitargetkomisi::select(
                'marketing_komisi_target_disposisi.kode_target',
                'bulan',
                'tahun',
                'nama_cabang',
                'disposisi.id_pengirim',
                'roles.name as role',
                'marketing_komisi_target.status',
                'marketing_komisi_target.created_at',
                'marketing_komisi_target_disposisi.status as status_disposisi',
                'status_ajuan'

            );
            $query->where('marketing_komisi_target_disposisi.id_penerima', auth()->user()->id);
            $query->join('marketing_komisi_target', 'marketing_komisi_target_disposisi.kode_target', '=', 'marketing_komisi_target.kode_target');
            $query->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang');
            $query->leftJoin(
                DB::raw("(
                SELECT marketing_komisi_target_disposisi.kode_target,id_pengirim,id_penerima,catatan,status as status_ajuan
                FROM marketing_komisi_target_disposisi
				WHERE marketing_komisi_target_disposisi.kode_disposisi IN
                    (SELECT MAX(kode_disposisi) as kode_disposisi
                    FROM marketing_komisi_target_disposisi
                    GROUP BY kode_target)
                ) disposisi"),
                function ($join) {
                    $join->on('marketing_komisi_target.kode_target', '=', 'disposisi.kode_target');
                }
            );
            $query->leftjoin('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
            $query->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
            $query->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
            $query->orderBy('tahun', 'desc');
            $query->orderBy('bulan');
        } else {
            $query = Targetkomisi::query();
            $query->select('marketing_komisi_target.*', 'nama_cabang', 'roles.name as role', 'disposisi.id_pengirim');
            $query->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang');
            $query->leftJoin(
                DB::raw("(
                SELECT marketing_komisi_target_disposisi.kode_target,id_pengirim,id_penerima,catatan,status
                FROM marketing_komisi_target_disposisi
				WHERE marketing_komisi_target_disposisi.kode_disposisi IN
                    (SELECT MAX(kode_disposisi) as kode_disposisi
                    FROM marketing_komisi_target_disposisi
                    GROUP BY kode_target)
                ) disposisi"),
                function ($join) {
                    $join->on('marketing_komisi_target.kode_target', '=', 'disposisi.kode_target');
                }
            );
            $query->leftjoin('users as penerima', 'disposisi.id_penerima', '=', 'penerima.id');
            $query->leftjoin('model_has_roles', 'penerima.id', '=', 'model_has_roles.model_id');
            $query->leftjoin('roles', 'model_has_roles.role_id', '=', 'roles.id');
            $query->orderBy('tahun', 'desc');
            $query->orderBy('bulan');

            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            }
        }


        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_komisi_target.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('marketing_komisi_target.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->posisi_ajuan)) {
            $query->where('roles.name', $request->posisi_ajuan);
        }

        if ($request->status === '0') {
            $query->where('marketing_komisi_target.status', $request->status);
        } else {
            if (!empty($request->status)) {
                $query->where('marketing_komisi_target.status', $request->status);
            }
        }
        $targetkomisi = $query->paginate(15);
        $targetkomisi->appends(request()->all());
        $data['targetkomisi'] = $targetkomisi;
        $data['roles_approve_targetkomisi'] = $roles_approve_targetkomisi;
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('marketing.targetkomisi.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        return view('marketing.targetkomisi.create', $data);
    }

    public function store(Request $request)
    {
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
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
        $kode_target =  $kode_cabang . $bln . $tahun;
        $produk = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        $kode_salesman = $request->kode_salesman;
        for ($i = 0; $i < count($kode_salesman); $i++) {
            foreach ($produk as $p) {
                $kode_produk = $p->kode_produk;
                ${$kode_produk} = $request->$kode_produk;
                $data[] = [
                    'kode_target' => $kode_target,
                    'kode_salesman' => $kode_salesman[$i],
                    'kode_produk' => $kode_produk,
                    'jumlah' => toNumber(${$kode_produk}[$i]),
                    'jml_awal' => toNumber(${$kode_produk}[$i]),
                ];
            }
        }

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektarget = Targetkomisi::where('kode_target', $kode_target)->count();
            if ($cektarget > 0) {
                return Redirect::back()->with(messageError('Data Target Sudah Ada'));
            }
            $timestamp = Carbon::now();
            foreach ($data as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }
            Targetkomisi::create([
                'kode_target' => $kode_target,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'kode_cabang' => $kode_cabang,
                'status' => 0,
                'id_user' => auth()->user()->id
            ]);

            Detailtargetkomisi::insert($data);

            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisitargetkomisi::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPTK" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            $regional = Cabang::where('kode_cabang', $kode_cabang)->first();
            $user_penerima = User::role('regional sales manager')->where('kode_regional', $regional->kode_regional)
                ->where('status', 1)
                ->first();
            if ($user_penerima == NULL) {
                $user_penerima = User::role('gm marketing')
                    ->where('status', 1)
                    ->first();
            }


            Disposisitargetkomisi::create([
                'kode_disposisi' => $kode_disposisi,
                'kode_target' => $kode_target,
                'id_pengirim' => auth()->user()->id,
                'id_penerima' => $user_penerima->id,
                'status' => 0
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {

            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }




    public function show($kode_target)
    {
        $kode_target = Crypt::decrypt($kode_target);
        $target = Targetkomisi::where('kode_target', $kode_target)->first();
        $bulan = $target->bulan;
        $tahun = $target->tahun;
        $lastbulan = getbulandantahunlalu($bulan, $tahun, "bulan");
        $lasttahun = getbulandantahunlalu($bulan, $tahun, "tahun");

        $lastduabulan = getbulandantahunlalu($lastbulan, $lasttahun, "bulan");
        $lastduabulantahun = getbulandantahunlalu($lastbulan, $lasttahun, "tahun");

        $lasttigabulan = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "bulan");
        $lasttigabulantahun = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "tahun");

        // if (in_array($bulan, [1, 2, 3])) {
        //     $bulan = $bulan + 12;
        //     $tahun = $tahun - 1;
        // }

        // $last3bulan = $bulan - 3;


        $start_date = $lasttigabulantahun . "-" . $lasttigabulan . "-01";
        $end_date = date('Y-m-t', strtotime($lasttahun . "-" . $lastbulan . "-01"));
        $data['targetkomisi'] = Targetkomisi::select('marketing_komisi_target.*', 'nama_cabang')
            ->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_target', $kode_target)
            ->first();
        $produk = Detailtargetkomisi::select('marketing_komisi_target_detail.kode_produk', 'isi_pcs_dus')
            ->join('produk', 'marketing_komisi_target_detail.kode_produk', '=', 'produk.kode_produk')
            ->orderBy('marketing_komisi_target_detail.kode_produk')
            ->groupBy('marketing_komisi_target_detail.kode_produk')
            ->where('kode_target', $kode_target)
            ->get();

        $select_produk = [];
        $select_produk_penjualan = [];
        $s_penjualan = [];
        $select_penjualan_tiga_bulan = [];
        $select_penjualan_dua_bulan = [];
        $select_penjualan_last_bulan = [];

        $select_target_awal = [];
        $select_target_rsm = [];
        $select_target_gm = [];
        $select_target_dirut = [];

        $s_penjualan_tiga_bulan = [];
        $s_penjualan_dua_bulan = [];
        $s_penjualan_last_bulan = [];
        $s_target_last = [];

        foreach ($produk as $d) {
            // $select_produk[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `target_" . $d->kode_produk . "`");
            $select_produk[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `target_$d->kode_produk`");
            $select_target_awal[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jml_awal,0)) as `target_awal_$d->kode_produk`");
            $select_target_rsm[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',rsm,0)) as `target_rsm_$d->kode_produk`");
            $select_target_gm[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',gm,0)) as `target_gm_$d->kode_produk`");
            $select_target_dirut[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',direktur,0)) as `target_dirut_$d->kode_produk`");

            $select_produk_penjualan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk',jumlah,0)) as `penjualan_$d->kode_produk`");

            $select_penjualan_tiga_bulan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk' AND MONTH(tanggal) = '$lasttigabulan' AND YEAR(tanggal) = '$lasttigabulantahun',jumlah,0)) as `penjualan_tiga_bulan_$d->kode_produk`");

            $select_penjualan_dua_bulan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk' AND MONTH(tanggal) = '$lastduabulan' AND YEAR(tanggal) = '$lastduabulantahun',jumlah,0)) as `penjualan_dua_bulan_$d->kode_produk`");

            $select_penjualan_last_bulan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk' AND MONTH(tanggal) = '$lastbulan' AND YEAR(tanggal) = '$lasttahun',jumlah,0)) as `penjualan_last_bulan_$d->kode_produk`");

            $select_produk_last[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `target_last_$d->kode_produk`");

            $s_penjualan[] = "penjualan_$d->kode_produk";

            $s_penjualan_tiga_bulan[] = "penjualan_tiga_bulan_$d->kode_produk";

            $s_penjualan_dua_bulan[] = "penjualan_dua_bulan_$d->kode_produk";

            $s_penjualan_last_bulan[] = "penjualan_last_bulan_$d->kode_produk";

            $s_target_last[] = "target_last_$d->kode_produk";
        }

        $qlasttarget = Detailtargetkomisi::join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target')
            ->join('salesman', 'marketing_komisi_target_detail.kode_salesman', '=', 'salesman.kode_salesman')
            ->select(
                'marketing_komisi_target_detail.kode_salesman',
                ...$select_produk_last
            )
            ->where('salesman.kode_cabang', $target->kode_cabang)
            ->where('marketing_komisi_target.tahun', $lasttahun)
            ->where('marketing_komisi_target.bulan', $lastbulan)
            ->groupBy('marketing_komisi_target_detail.kode_salesman');


        $qpenjualan = Detailpenjualan::join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->select(
                'marketing_penjualan.kode_salesman',
                ...$select_produk_penjualan,
                ...$select_penjualan_tiga_bulan,
                ...$select_penjualan_dua_bulan,
                ...$select_penjualan_last_bulan
            )
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('salesman.kode_cabang', $target->kode_cabang)
            // ->where('status_promosi', 0)
            ->groupBy('marketing_penjualan.kode_salesman');

        // $s_produk = implode(",", $select_produk);
        $data['detail'] = Detailtargetkomisi::select(
            'marketing_komisi_target_detail.kode_salesman',
            'nama_salesman',
            'salesman.nik',
            'tanggal_masuk',
            ...$select_produk,
            ...$select_target_awal,
            ...$select_target_rsm,
            ...$select_target_gm,
            ...$select_target_dirut,
            ...$select_produk_last,
            ...$s_penjualan,
            ...$s_penjualan_tiga_bulan,
            ...$s_penjualan_dua_bulan,
            ...$s_penjualan_last_bulan,
            ...$s_target_last
        )
            ->join('salesman', 'marketing_komisi_target_detail.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin('hrd_karyawan', 'salesman.nik', '=', 'hrd_karyawan.nik')
            ->leftJoinSub($qpenjualan, 'penjualan', function ($join) {
                $join->on('salesman.kode_salesman', '=', 'penjualan.kode_salesman');
            })
            ->leftJoinSub($qlasttarget, 'lasttarget', function ($join) {
                $join->on('marketing_komisi_target_detail.kode_salesman', '=', 'lasttarget.kode_salesman');
            })
            ->where('kode_target', $kode_target)
            ->groupBy('marketing_komisi_target_detail.kode_salesman', 'nama_salesman', ...$s_penjualan)
            ->get();

        $data['produk'] = $produk;
        $data['lasttigabulan'] = $lasttigabulan;
        $data['lastduabulan'] = $lastduabulan;
        $data['lastbulan'] = $lastbulan;


        return view('marketing.targetkomisi.show', $data);
    }


    public function approve($kode_target)
    {
        $kode_target = Crypt::decrypt($kode_target);
        $target = Targetkomisi::where('kode_target', $kode_target)->first();
        $bulan = $target->bulan;
        $tahun = $target->tahun;
        $lastbulan = getbulandantahunlalu($bulan, $tahun, "bulan");
        $lasttahun = getbulandantahunlalu($bulan, $tahun, "tahun");

        $lastduabulan = getbulandantahunlalu($lastbulan, $lasttahun, "bulan");
        $lastduabulantahun = getbulandantahunlalu($lastbulan, $lasttahun, "tahun");

        $lasttigabulan = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "bulan");
        $lasttigabulantahun = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "tahun");

        // if (in_array($bulan, [1, 2, 3])) {
        //     $bulan = $bulan + 12;
        //     $tahun = $tahun - 1;
        // }

        // $last3bulan = $bulan - 3;


        $start_date = $lasttigabulantahun . "-" . $lasttigabulan . "-01";
        $end_date = date('Y-m-t', strtotime($lasttahun . "-" . $lastbulan . "-01"));


        // dd($start_date, $end_date);
        $data['targetkomisi'] = Targetkomisi::select('marketing_komisi_target.*', 'nama_cabang')
            ->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_target', $kode_target)
            ->first();
        $produk = Detailtargetkomisi::select('kode_produk')
            ->orderBy('kode_produk')
            ->groupBy('kode_produk')
            ->where('kode_target', $kode_target)
            ->get();

        $produk = Detailtargetkomisi::select('marketing_komisi_target_detail.kode_produk', 'isi_pcs_dus')
            ->join('produk', 'marketing_komisi_target_detail.kode_produk', '=', 'produk.kode_produk')
            ->orderBy('marketing_komisi_target_detail.kode_produk')
            ->groupBy('marketing_komisi_target_detail.kode_produk')
            ->where('kode_target', $kode_target)
            ->get();

        $select_produk = [];
        $select_produk_last = [];
        $select_produk_penjualan = [];
        $s_penjualan = [];

        $select_target_awal = [];
        $select_target_rsm = [];
        $select_target_gm = [];
        $select_target_dirut = [];


        $select_penjualan_tiga_bulan = [];
        $select_penjualan_dua_bulan = [];
        $select_penjualan_last_bulan = [];

        $s_penjualan_tiga_bulan = [];
        $s_penjualan_dua_bulan = [];
        $s_penjualan_last_bulan = [];

        $s_target_last = [];

        foreach ($produk as $d) {
            // $select_produk[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `target_" . $d->kode_produk . "`");
            $select_produk[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `target_$d->kode_produk`");

            $select_target_awal[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jml_awal,0)) as `target_awal_$d->kode_produk`");
            $select_target_rsm[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',rsm,0)) as `target_rsm_$d->kode_produk`");
            $select_target_gm[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',gm,0)) as `target_gm_$d->kode_produk`");
            $select_target_dirut[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',direktur,0)) as `target_dirut_$d->kode_produk`");


            $select_produk_last[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `target_last_$d->kode_produk`");
            $select_produk_penjualan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk',jumlah,0)) as `penjualan_$d->kode_produk`");

            $select_penjualan_tiga_bulan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk' AND MONTH(tanggal) = '$lasttigabulan' AND YEAR(tanggal) = '$lasttigabulantahun',jumlah,0)) as `penjualan_tiga_bulan_$d->kode_produk`");

            $select_penjualan_dua_bulan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk' AND MONTH(tanggal) = '$lastduabulan' AND YEAR(tanggal) = '$lastduabulantahun',jumlah,0)) as `penjualan_dua_bulan_$d->kode_produk`");

            $select_penjualan_last_bulan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk' AND MONTH(tanggal) = '$lastbulan' AND YEAR(tanggal) = '$lasttahun',jumlah,0)) as `penjualan_last_bulan_$d->kode_produk`");

            $s_penjualan[] = "penjualan_$d->kode_produk";

            $s_penjualan_tiga_bulan[] = "penjualan_tiga_bulan_$d->kode_produk";

            $s_penjualan_dua_bulan[] = "penjualan_dua_bulan_$d->kode_produk";

            $s_penjualan_last_bulan[] = "penjualan_last_bulan_$d->kode_produk";

            $s_target_last[] = "target_last_$d->kode_produk";
        }

        $qlasttarget = Detailtargetkomisi::join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target')
            ->join('salesman', 'marketing_komisi_target_detail.kode_salesman', '=', 'salesman.kode_salesman')
            ->select(
                'marketing_komisi_target_detail.kode_salesman',
                ...$select_produk_last
            )
            ->where('salesman.kode_cabang', $target->kode_cabang)
            ->where('marketing_komisi_target.tahun', $lasttahun)
            ->where('marketing_komisi_target.bulan', $lastbulan)
            ->groupBy('marketing_komisi_target_detail.kode_salesman');
        $qpenjualan = Detailpenjualan::join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->select(
                'marketing_penjualan.kode_salesman',
                ...$select_produk_penjualan,
                ...$select_penjualan_tiga_bulan,
                ...$select_penjualan_dua_bulan,
                ...$select_penjualan_last_bulan
            )
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('salesman.kode_cabang', $target->kode_cabang)
            // ->where('status_promosi', 0)
            ->groupBy('marketing_penjualan.kode_salesman');

        // $s_produk = implode(",", $select_produk);
        $data['detail'] = Detailtargetkomisi::select(
            'marketing_komisi_target_detail.kode_salesman',
            'nama_salesman',
            'salesman.nik',
            'tanggal_masuk',
            ...$select_produk,
            ...$select_target_awal,
            ...$select_target_rsm,
            ...$select_target_gm,
            ...$select_target_dirut,
            ...$s_penjualan,
            ...$s_penjualan_tiga_bulan,
            ...$s_penjualan_dua_bulan,
            ...$s_penjualan_last_bulan,
            ...$s_target_last
        )
            ->join('salesman', 'marketing_komisi_target_detail.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin('hrd_karyawan', 'salesman.nik', '=', 'hrd_karyawan.nik')
            ->leftJoinSub($qpenjualan, 'penjualan', function ($join) {
                $join->on('salesman.kode_salesman', '=', 'penjualan.kode_salesman');
            })
            ->leftJoinSub($qlasttarget, 'lasttarget', function ($join) {
                $join->on('salesman.kode_salesman', '=', 'lasttarget.kode_salesman');
            })

            ->where('kode_target', $kode_target)
            ->groupBy('marketing_komisi_target_detail.kode_salesman', 'nama_salesman', ...$s_penjualan)
            ->get();


        //dd($data['detail']);
        $data['produk'] = $produk;
        $data['lasttigabulan'] = $lasttigabulan;
        $data['lastduabulan'] = $lastduabulan;
        $data['lastbulan'] = $lastbulan;
        return view('marketing.targetkomisi.approve', $data);
    }


    public function approvestore($kode_target, Request $request)
    {
        $kode_target = Crypt::decrypt($kode_target);
        $targetkomisi = Targetkomisi::where('kode_target', $kode_target)->first();
        $tanggal = $targetkomisi->tahun . "-" . $targetkomisi->bulan . "-01";
        DB::beginTransaction();
        try {
            //Update Status Disposisi
            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            $tanggal_hariini = date('Y-m-d');
            $lastdisposisi = Disposisitargetkomisi::whereRaw('date(created_at)="' . $tanggal_hariini . '"')
                ->orderBy('kode_disposisi', 'desc')
                ->first();
            $last_kodedisposisi = $lastdisposisi != null ? $lastdisposisi->kode_disposisi : '';
            $format = "DPTK" . date('Ymd');
            $kode_disposisi = buatkode($last_kodedisposisi, $format, 4);

            Disposisitargetkomisi::where('kode_target', $kode_target)->where('id_penerima', auth()->user()->id)->update([
                'status' => 1
            ]);
            if (auth()->user()->roles->pluck('name')[0] == 'regional sales manager') {
                $user_penerima = User::role('gm marketing')
                    ->where('status', 1)
                    ->first();
                Disposisitargetkomisi::create([
                    'kode_disposisi' => $kode_disposisi,
                    'kode_target' => $kode_target,
                    'id_pengirim' => auth()->user()->id,
                    'id_penerima' => $user_penerima->id,
                    'status' => 0
                ]);
            } else if (auth()->user()->roles->pluck('name')[0] == 'gm marketing') {
                $user_penerima = User::role('direktur')
                    ->where('status', 1)
                    ->first();
                Disposisitargetkomisi::create([
                    'kode_disposisi' => $kode_disposisi,
                    'kode_target' => $kode_target,
                    'id_pengirim' => auth()->user()->id,
                    'id_penerima' => $user_penerima->id,
                    'status' => 0
                ]);
            } else if (auth()->user()->roles->pluck('name')[0] == 'direktur') {
                Targetkomisi::where('kode_target', $kode_target)->update(['status' => 1]);
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Target Berhasil Diteruskan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_target)
    {
        $kode_target = Crypt::decrypt($kode_target);
        $target = Targetkomisi::where('kode_target', $kode_target)->first();
        $bulan = $target->bulan;
        $tahun = $target->tahun;
        $lastbulan = getbulandantahunlalu($bulan, $tahun, "bulan");
        $lasttahun = getbulandantahunlalu($bulan, $tahun, "tahun");

        $lastduabulan = getbulandantahunlalu($lastbulan, $lasttahun, "bulan");
        $lastduabulantahun = getbulandantahunlalu($lastbulan, $lasttahun, "tahun");

        $lasttigabulan = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "bulan");
        $lasttigabulantahun = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "tahun");

        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['targetkomisi'] = Targetkomisi::select('marketing_komisi_target.*', 'nama_cabang')
            ->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_target', $kode_target)
            ->first();
        $produk = Detailtargetkomisi::select('kode_produk')
            ->orderBy('kode_produk')
            ->groupBy('kode_produk')
            ->where('kode_target', $kode_target)
            ->get();
        $data['produk'] = $produk;
        $data['lasttigabulan'] = $lasttigabulan;
        $data['lastduabulan'] = $lastduabulan;
        $data['lastbulan'] = $lastbulan;
        return view('marketing.targetkomisi.edit', $data);
    }

    public function update($kode_target, Request $request)
    {
        $kode_target = Crypt::decrypt($kode_target);
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
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
        $produk = Detailtargetkomisi::select('kode_produk')
            ->orderBy('kode_produk')
            ->groupBy('kode_produk')
            ->where('kode_target', $kode_target)
            ->get();

        $kode_target_new =  $kode_cabang . $bln . $tahun;
        $kode_salesman = $request->kode_salesman;

        // dd($request->kode_salesman);
        //dd($request->BB);
        for ($i = 0; $i < count($kode_salesman); $i++) {
            foreach ($produk as $p) {
                $kode_produk = $p->kode_produk;
                ${$kode_produk} = $request->$kode_produk;
                ${'target_rsm_' . $kode_produk} = $request->{'rsm_' . $kode_produk};
                ${'target_awal_' . $kode_produk} = $request->{'target_awal_' . $kode_produk};
                ${'target_gm_' . $kode_produk} = $request->{'gm_' . $kode_produk};
                ${'target_dirut_' . $kode_produk} = $request->{'dirut_' . $kode_produk};
                $data[] = [
                    'kode_target' => $kode_target_new,
                    'kode_salesman' => $kode_salesman[$i],
                    'kode_produk' => $kode_produk,
                    'jml_awal' => toNumber(${'target_awal_' . $kode_produk}[$i]),
                    'rsm' => toNumber(${'target_rsm_' . $kode_produk}[$i]),
                    'gm' => toNumber(${'target_gm_' . $kode_produk}[$i]),
                    'direktur' => toNumber(${'target_dirut_' . $kode_produk}[$i]),
                    'jumlah' => toNumber(${$kode_produk}[$i])
                ];
            }
        }

        // dd($data);
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektarget = Targetkomisi::where('kode_target', $kode_target_new)
                ->where('kode_target', '!=', $kode_target)
                ->count();
            if ($cektarget > 0) {
                return Redirect::back()->with(messageError('Data Target Sudah Ada'));
            }
            $timestamp = Carbon::now();
            foreach ($data as &$record) {
                $record['created_at'] = $timestamp;
                $record['updated_at'] = $timestamp;
            }
            Targetkomisi::where('kode_target', $kode_target)->update([
                'kode_target' => $kode_target_new,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'kode_cabang' => $kode_cabang,
            ]);
            Detailtargetkomisi::where('kode_target', $kode_target)->delete();
            Detailtargetkomisi::insert($data);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($kode_target)
    {
        $kode_target = Crypt::decrypt($kode_target);
        $targetkomisi = Targetkomisi::where('kode_target', $kode_target)->first();
        $tanggal = $targetkomisi->tahun . "-" . $targetkomisi->bulan . "-01";
        try {
            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Targetkomisi::where('kode_target', $kode_target)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function gettargetsalesman(Request $request)
    {

        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');
        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }
        $query = Salesman::query();
        $query->where('kode_cabang', $kode_cabang);
        $query->where('status_aktif_salesman', 1);
        $query->where('nama_salesman', '!=', '-');
        $query->orderBy('nama_salesman');
        $data['salesman'] = $query->get();
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();

        return view('marketing.targetkomisi.gettargetsalesman', $data);
    }


    public function gettargetsalesmanedit(Request $request)
    {
        $kode_target = $request->kode_target;
        $target = Targetkomisi::where('kode_target', $kode_target)->first();
        $bulan = $target->bulan;
        $tahun = $target->tahun;
        $lastbulan = getbulandantahunlalu($bulan, $tahun, "bulan");
        $lasttahun = getbulandantahunlalu($bulan, $tahun, "tahun");

        $lastduabulan = getbulandantahunlalu($lastbulan, $lasttahun, "bulan");
        $lastduabulantahun = getbulandantahunlalu($lastbulan, $lasttahun, "tahun");

        $lasttigabulan = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "bulan");
        $lasttigabulantahun = getbulandantahunlalu($lastduabulan, $lastduabulantahun, "tahun");

        // if (in_array($bulan, [1, 2, 3])) {
        //     $bulan = $bulan + 12;
        //     $tahun = $tahun - 1;
        // }

        // $last3bulan = $bulan - 3;


        $start_date = $lasttigabulantahun . "-" . $lasttigabulan . "-01";
        $end_date = date('Y-m-t', strtotime($lasttahun . "-" . $lastbulan . "-01"));


        // dd($start_date, $end_date);
        $data['targetkomisi'] = Targetkomisi::select('marketing_komisi_target.*', 'nama_cabang')
            ->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_target', $kode_target)
            ->first();
        $produk = Detailtargetkomisi::select('kode_produk')
            ->orderBy('kode_produk')
            ->groupBy('kode_produk')
            ->where('kode_target', $kode_target)
            ->get();

        $produk = Detailtargetkomisi::select('marketing_komisi_target_detail.kode_produk', 'isi_pcs_dus')
            ->join('produk', 'marketing_komisi_target_detail.kode_produk', '=', 'produk.kode_produk')
            ->orderBy('marketing_komisi_target_detail.kode_produk')
            ->groupBy('marketing_komisi_target_detail.kode_produk')
            ->where('kode_target', $kode_target)
            ->get();

        $select_produk = [];
        $select_produk_penjualan = [];
        $s_penjualan = [];
        $select_penjualan_tiga_bulan = [];
        $select_penjualan_dua_bulan = [];
        $select_penjualan_last_bulan = [];

        $select_target_awal = [];
        $select_target_rsm = [];
        $select_target_gm = [];
        $select_target_dirut = [];


        $s_penjualan_tiga_bulan = [];
        $s_penjualan_dua_bulan = [];
        $s_penjualan_last_bulan = [];
        $s_target_last = [];



        foreach ($produk as $d) {
            // $select_produk[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `target_" . $d->kode_produk . "`");
            $select_produk[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `target_$d->kode_produk`");
            $select_target_awal[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jml_awal,0)) as `target_awal_$d->kode_produk`");
            $select_target_rsm[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',rsm,0)) as `target_rsm_$d->kode_produk`");
            $select_target_gm[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',gm,0)) as `target_gm_$d->kode_produk`");
            $select_target_dirut[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',direktur,0)) as `target_dirut_$d->kode_produk`");


            $select_produk_last[] = DB::raw("SUM(IF(kode_produk='$d->kode_produk',jumlah,0)) as `target_last_$d->kode_produk`");
            $select_produk_penjualan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk',jumlah,0)) as `penjualan_$d->kode_produk`");

            $select_penjualan_tiga_bulan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk' AND MONTH(tanggal) = '$lasttigabulan' AND YEAR(tanggal) = '$lasttigabulantahun',jumlah,0)) as `penjualan_tiga_bulan_$d->kode_produk`");

            $select_penjualan_dua_bulan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk' AND MONTH(tanggal) = '$lastduabulan' AND YEAR(tanggal) = '$lastduabulantahun',jumlah,0)) as `penjualan_dua_bulan_$d->kode_produk`");

            $select_penjualan_last_bulan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$d->kode_produk' AND MONTH(tanggal) = '$lastbulan' AND YEAR(tanggal) = '$lasttahun',jumlah,0)) as `penjualan_last_bulan_$d->kode_produk`");

            $s_penjualan[] = "penjualan_$d->kode_produk";

            $s_penjualan_tiga_bulan[] = "penjualan_tiga_bulan_$d->kode_produk";

            $s_penjualan_dua_bulan[] = "penjualan_dua_bulan_$d->kode_produk";

            $s_penjualan_last_bulan[] = "penjualan_last_bulan_$d->kode_produk";

            $s_target_last[] = "target_last_$d->kode_produk";
        }

        $qlasttarget = Detailtargetkomisi::join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target')
            ->join('salesman', 'marketing_komisi_target_detail.kode_salesman', '=', 'salesman.kode_salesman')
            ->select(
                'marketing_komisi_target_detail.kode_salesman',
                ...$select_produk_last
            )
            ->where('salesman.kode_cabang', $target->kode_cabang)
            ->where('marketing_komisi_target.tahun', $lasttahun)
            ->where('marketing_komisi_target.bulan', $lastbulan)
            ->groupBy('marketing_komisi_target_detail.kode_salesman');

        $qpenjualan = Detailpenjualan::join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->select(
                'marketing_penjualan.kode_salesman',
                ...$select_produk_penjualan,
                ...$select_penjualan_tiga_bulan,
                ...$select_penjualan_dua_bulan,
                ...$select_penjualan_last_bulan

            )
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('salesman.kode_cabang', $target->kode_cabang)
            // ->where('status_promosi', 0)
            ->groupBy('marketing_penjualan.kode_salesman');

        // $s_produk = implode(",", $select_produk);
        $data['detail'] = Detailtargetkomisi::select(
            'marketing_komisi_target_detail.kode_salesman',
            'nama_salesman',
            'salesman.nik',
            'tanggal_masuk',
            ...$select_produk,
            ...$select_target_awal,
            ...$select_target_rsm,
            ...$select_target_gm,
            ...$select_target_dirut,
            ...$s_target_last,
            ...$s_penjualan,
            ...$s_penjualan_tiga_bulan,
            ...$s_penjualan_dua_bulan,
            ...$s_penjualan_last_bulan
        )
            ->join('salesman', 'marketing_komisi_target_detail.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin('hrd_karyawan', 'salesman.nik', '=', 'hrd_karyawan.nik')
            ->leftJoinSub($qpenjualan, 'penjualan', function ($join) {
                $join->on('salesman.kode_salesman', '=', 'penjualan.kode_salesman');
            })
            ->leftJoinSub($qlasttarget, 'lasttarget', function ($join) {
                $join->on('salesman.kode_salesman', '=', 'lasttarget.kode_salesman');
            })
            ->where('kode_target', $kode_target)
            ->groupBy('marketing_komisi_target_detail.kode_salesman', 'nama_salesman', ...$s_penjualan)
            ->get();

        $data['produk'] = $produk;
        $data['lasttigabulan'] = $lasttigabulan;
        $data['lastduabulan'] = $lastduabulan;
        $data['lastbulan'] = $lastbulan;

        return view('marketing.targetkomisi.gettargetsalesman_edit', $data);
    }

    public function cancel($kode_target)
    {
        $kode_target = Crypt::decrypt($kode_target);
        $targetkomisi = Targetkomisi::where('kode_target', $kode_target)->first();
        $tanggal = $targetkomisi->tahun . "-" . $targetkomisi->bulan . "-01";

        DB::beginTransaction();
        try {

            $cektutuplaporan = cektutupLaporan($tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            if (auth()->user()->roles->pluck('name')[0] == "direktur") {
                Targetkomisi::where('kode_target', $kode_target)->update(['status' => 0]);
            } else {
                Disposisitargetkomisi::where('kode_target', $kode_target)
                    ->where('id_pengirim', auth()->user()->id)
                    ->delete();
            }
            Disposisitargetkomisi::where('kode_target', $kode_target)
                ->where('id_penerima', auth()->user()->id)
                ->update(['status' => 0]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dibatalkan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    function gettargetsalesmandashboard(Request $request)
    {

        $user = User::findorFail(auth()->user()->id);
        $start_date = $request->tahun . "-" . $request->bulan . "-01";
        $end_date = date('Y-m-t', strtotime($start_date));
        $data['target'] = Detailtargetkomisi::select(
            'marketing_komisi_target_detail.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'jumlah',
            'realisasi'
        )
            ->join('produk', 'marketing_komisi_target_detail.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target')
            ->leftJoin(
                DB::raw("(
                SELECT
                    produk_harga.kode_produk,
                    SUM(jumlah) as realisasi
                FROM
                    marketing_penjualan_detail
                INNER JOIN produk_harga ON marketing_penjualan_detail.kode_harga = produk_harga.kode_harga
                INNER JOIN marketing_penjualan ON marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur
                WHERE tanggal BETWEEN '$start_date' AND '$end_date' AND kode_salesman = '$user->kode_salesman' AND status_promosi = '0'
                GROUP BY produk_harga.kode_produk
            ) detailpenjualan"),
                function ($join) {
                    $join->on('marketing_komisi_target_detail.kode_produk', '=', 'detailpenjualan.kode_produk');
                }
            )
            ->where('kode_salesman', $user->kode_salesman)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->get();
        return view('dashboard.salesman.gettargetsalesman', $data);
    }



    function gettarget(Request $request)
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
        $start_date = $request->tahun . "-" . $request->bulan . "-01";
        $end_date = date('Y-m-t', strtotime($start_date));

        //Realisasi
        // $query->leftJoin(
        //     DB::raw("(
        //         SELECT
        //             produk_harga.kode_produk,
        //             SUM(jumlah) as realisasi
        //         FROM
        //             marketing_penjualan_detail
        //         INNER JOIN produk_harga ON marketing_penjualan_detail.kode_harga = produk_harga.kode_harga
        //         INNER JOIN marketing_penjualan ON marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur
        //         WHERE tanggal BETWEEN '$start_date' AND '$end_date' AND kode_salesman = '$user->kode_salesman' AND status_promosi = '0'
        //         GROUP BY produk_harga.kode_produk
        //     ) detailpenjualan"),
        //     function ($join) {
        //         $join->on('marketing_komisi_target_detail.kode_produk', '=', 'detailpenjualan.kode_produk');
        //     }
        // );

        $qrealisasi = Detailpenjualan::query();
        $qrealisasi->select(
            'produk_harga.kode_produk',
            DB::raw('SUM(jumlah) as realisasi')
        );
        $qrealisasi->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $qrealisasi->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $qrealisasi->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qrealisasi->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $qrealisasi->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $qrealisasi->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date]);
        $qrealisasi->where('status_promosi', '0');

        if (!empty($kode_cabang)) {
            $qrealisasi->where('salesman.kode_cabang', $kode_cabang);
        } else {
            if ($user->hasRole('regional sales manager')) {
                $qrealisasi->where('cabang.kode_regional', $user->kode_regional);
            }
        }

        if (!empty($request->kode_salesman)) {
            $qrealisasi->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }

        $qrealisasi->groupBy('produk_harga.kode_produk');


        // dd($qrealisasi->get());

        $query = Detailtargetkomisi::query();
        $query->select(
            'marketing_komisi_target_detail.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            DB::raw('SUM(jumlah) as jumlah_target'),
            'realisasi'

        );
        $query->join('produk', 'marketing_komisi_target_detail.kode_produk', '=', 'produk.kode_produk');
        $query->join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target');
        $query->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoinSub($qrealisasi, 'realisasi', function ($join) {
            $join->on('marketing_komisi_target_detail.kode_produk', '=', 'realisasi.kode_produk');
        });
        $query->orderBy('marketing_komisi_target_detail.kode_produk');
        $query->where('bulan', $request->bulan);
        $query->where('tahun', $request->tahun);
        if (!empty($request->kode_salesman)) {
            $query->where('marketing_komisi_target_detail.kode_salesman', $request->kode_salesman);
        }

        if (!empty($kode_cabang)) {
            $query->where('marketing_komisi_target.kode_cabang', $kode_cabang);
        } else {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', $user->kode_regional);
            }
        }
        $query->groupBy('marketing_komisi_target_detail.kode_produk', 'nama_produk', 'isi_pcs_dus', 'realisasi');
        $target = $query->get();

        $data['target'] = $target;
        return view('dashboard.mobile.gettarget', $data);
    }
}

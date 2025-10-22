<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailajuanprogramkumulatif;
use App\Models\Detailpencairan;
use App\Models\Detailpenjualan;
use App\Models\Diskon;
use App\Models\Pelanggan;
use App\Models\Pencairanprogram;
use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PencairanprogramController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }
        $query = Pencairanprogram::query();

        $query->join('cabang', 'marketing_program_pencairan.kode_cabang', '=', 'cabang.kode_cabang');
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

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }


        if (!empty($request->kode_program)) {
            $query->where('kode_program', $request->kode_program);
        }


        if ($user->hasRole('regional sales manager')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_pencairan.om');
                    $query->whereNull('marketing_program_pencairan.rsm');
                } else if ($request->status == 'approved') {
                    $query->whereNotnull('marketing_program_pencairan.rsm');
                    $query->where('status', 0);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_pencairan.om');
            // $query->where('marketing_program_ikatan.status', '!=', 2);
        } else if ($user->hasRole('gm marketing')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_pencairan.rsm');
                    $query->whereNull('marketing_program_pencairan.gm');
                } else if ($request->status == 'approved') {
                    $query->whereNotnull('marketing_program_pencairan.gm');
                    $query->where('status', 0);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_pencairan.rsm');
            // $query->where('marketing_program_ikatan.status', '!=', 2);
        } else if ($user->hasRole('direktur')) {

            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_pencairan.gm');
                    $query->whereNull('marketing_program_pencairan.direktur');
                    $query->where('status', 0);
                } else if ($request->status == 'approved') {
                    $query->where('status', 1);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_pencairan.gm');
            // $query->where('marketing_program_ikatan.status', '!=', 2);
        } else {
            if ($request->status == 'pending') {
                $query->where('status', 0);
            }
            if ($request->status == 'approved') {
                $query->where('status', 1);
            }
            if ($request->status == 'rejected') {
                $query->where('status', 2);
            }
        }

        if ($user->hasRole('direktur')) {
            $query->orderBy('marketing_program_pencairan.status', 'asc');
            $query->orderBy('marketing_program_pencairan.bulan', 'desc');
            $query->orderBy('marketing_program_pencairan.tahun', 'desc');
        } else if ($user->hasRole('staff keuangan')) {
            $query->orderBy('marketing_program_pencairan.keuangan', 'asc');
            $query->orderBy('marketing_program_pencairan.bulan', 'desc');
            $query->orderBy('marketing_program_pencairan.tahun', 'desc');
        } else {
            $query->orderBy('marketing_program_pencairan.status', 'asc');
            $query->orderBy('marketing_program_pencairan.bulan', 'desc');
            $query->orderBy('marketing_program_pencairan.tahun', 'desc');
        }
        $pencairanprogram = $query->paginate(15);
        $pencairanprogram->appends(request()->all());

        $data['pencairanprogram'] = $pencairanprogram;
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['user'] = $user;
        return view('worksheetom.pencairanprogram.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('worksheetom.pencairanprogram.create', $data);
    }

    public function store(Request $request)
    {
        //Kode Pencairan Program PCBDG240001
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            $request->validate([
                'tanggal' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'kode_program' => 'required',
                'keterangan' => 'required'
            ]);

            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $request->validate([
                'tanggal' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'kode_program' => 'required',
                'keterangan' => 'required'
            ]);
            $kode_cabang = $request->kode_cabang;
        }

        $lastpencairan = Pencairanprogram::select('kode_pencairan')->orderBy('kode_pencairan', 'desc')
            ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
            ->where('kode_cabang', $kode_cabang)
            ->first();
        $last_kode_pencairan = $lastpencairan != null ? $lastpencairan->kode_pencairan : '';
        $kode_pencairan = buatkode($last_kode_pencairan, "PC" . $kode_cabang . date('y', strtotime($request->tanggal)), 4);

        try {
            //code...
            Pencairanprogram::create([
                'kode_pencairan' => $kode_pencairan,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'kode_jenis_program' => 'KM',
                'kode_program' => $request->kode_program,
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
        $user = User::find(auth()->user()->id);
        $pencairanprogram = Pencairanprogram::where('kode_pencairan', $kode_pencairan)->first();
        $data['pencairanprogram'] = $pencairanprogram;
        $data['user'] = $user;
        return view('worksheetom.pencairanprogram.setpencairan', $data);
    }

    function tambahpelanggan($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $pencairanprogram = Pencairanprogram::where('kode_pencairan', $kode_pencairan)->first();
        $data['pencairanprogram'] = $pencairanprogram;
        return view('worksheetom.pencairanprogram.tambahpelanggan', $data);
    }

    public function getpelanggan(Request $request)
    {

        $kode_pencairan = Crypt::decrypt($request->kode_pencairan);
        $pencairanprogram = Pencairanprogram::where('kode_pencairan', $kode_pencairan)->first();
        if ($pencairanprogram->kode_program == 'PR001') {
            $produk = ['BB', 'DEP'];
            $kategori_diskon = 'D001';
        } else {
            $produk = ['AB', 'AR', 'AS'];
            $kategori_diskon = 'D002';
        }

        $start_date = $pencairanprogram->tahun . '-' . $pencairanprogram->bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));



        $detailpenjualan = Detailpenjualan::select(
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) as jml_dus'),
            DB::raw('(SELECT diskon FROM produk_diskon WHERE SUM(floor(marketing_penjualan_detail.jumlah/produk.isi_pcs_dus)) BETWEEN produk_diskon.min_qty AND produk_diskon.max_qty AND kode_kategori_diskon="' . $kategori_diskon . '") as diskon'),
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) * (SELECT diskon FROM produk_diskon WHERE SUM(floor(marketing_penjualan_detail.jumlah/produk.isi_pcs_dus)) BETWEEN produk_diskon.min_qty AND produk_diskon.max_qty AND kode_kategori_diskon="' . $kategori_diskon . '") as diskon_reguler'),

        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('salesman.kode_cabang', $pencairanprogram->kode_cabang)
            ->where('status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= 14")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            ->whereNotIn('marketing_penjualan.kode_pelanggan', function ($query) use ($pencairanprogram) {
                $query->select('kode_pelanggan')
                    ->from('marketing_program_pencairan_detail')
                    ->join('marketing_program_pencairan', 'marketing_program_pencairan_detail.kode_pencairan', '=', 'marketing_program_pencairan.kode_pencairan')
                    ->where('bulan', $pencairanprogram->bulan)
                    ->where('tahun', $pencairanprogram->tahun)
                    ->where('kode_program', $pencairanprogram->kode_program);
            })

            ->whereIn('marketing_penjualan.kode_pelanggan', function ($query) use ($pencairanprogram) {
                $query->select('kode_pelanggan')
                    ->from('marketing_program_kumulatif_detail')
                    ->join('marketing_program_kumulatif', 'marketing_program_kumulatif_detail.no_pengajuan', '=', 'marketing_program_kumulatif.no_pengajuan')
                    ->where('status', 1);
            })
            ->groupBy('marketing_penjualan.no_faktur', 'marketing_penjualan.kode_pelanggan', 'nama_pelanggan')
            ->orderBy('nama_pelanggan')
            ->get();

        $diskon = Diskon::where('kode_kategori_diskon', $kategori_diskon)->get();
        $detail = $detailpenjualan->groupBy('kode_pelanggan')
            ->map(function ($group) use ($diskon) {
                $diskon_kumulatif = $diskon->first(function ($diskonItem) use ($group) {
                    return $diskonItem->min_qty <= $group->sum('jml_dus') && $diskonItem->max_qty >= $group->sum('jml_dus');
                })->diskon ?? 0;
                $total_diskon_kumulatif = $diskon_kumulatif * $group->sum('jml_dus');
                $cashback = $total_diskon_kumulatif - $group->sum('diskon_reguler');
                return [
                    'kode_pelanggan' => $group->first()->kode_pelanggan,
                    'nama_pelanggan' => $group->first()->nama_pelanggan,
                    'jml_dus' => $group->sum('jml_dus'),
                    'diskon_reguler' => $group->sum('diskon_reguler'),
                    'diskon_kumulatif' => $total_diskon_kumulatif,
                    'cashback' => $cashback,
                ];
            })
            ->sortBy('nama_pelanggan')
            ->filter(function ($item) {
                return $item['cashback'] > 0;
            })
            ->values()
            ->all();



        $data['detail'] = $detail;
        $data['kode_pencairan'] = $kode_pencairan;
        // $data['bulan'] = $request->bulan;
        // $data['tahun'] = $request->tahun;
        // $data['diskon'] = $request->diskon;
        // $data['kategori_diskon'] = $kategori_diskon;
        // $data['kode_program'] = $request->kode_program;
        // $data['kode_cabang'] = $request->kode_cabang;
        $data['top'] = 14;
        return view('worksheetom.pencairanprogram.getpelanggan', $data);
    }


    public function getpelanggantop30(Request $request)
    {

        $kode_pencairan = Crypt::decrypt($request->kode_pencairan);
        $pencairanprogram = Pencairanprogram::where('kode_pencairan', $kode_pencairan)->first();
        if ($pencairanprogram->kode_program == 'PR001') {
            $produk = ['BB', 'DEP'];
            $kategori_diskon = 'D001';
        } else {
            $produk = ['AB', 'AR', 'AS'];
            $kategori_diskon = 'D002';
        }

        $start_date = $pencairanprogram->tahun . '-' . $pencairanprogram->bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));



        $detailpenjualan = Detailpenjualan::select(
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) as jml_dus'),
            DB::raw('(SELECT diskon FROM produk_diskon WHERE SUM(floor(marketing_penjualan_detail.jumlah/produk.isi_pcs_dus)) BETWEEN produk_diskon.min_qty AND produk_diskon.max_qty AND kode_kategori_diskon="' . $kategori_diskon . '") as diskon'),
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) * (SELECT diskon FROM produk_diskon WHERE SUM(floor(marketing_penjualan_detail.jumlah/produk.isi_pcs_dus)) BETWEEN produk_diskon.min_qty AND produk_diskon.max_qty AND kode_kategori_diskon="' . $kategori_diskon . '") as diskon_reguler'),

        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('salesman.kode_cabang', $pencairanprogram->kode_cabang)
            ->where('status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= 31")
            // ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) > 14")
            // ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) > 14")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            ->whereNotIn('marketing_penjualan.kode_pelanggan', function ($query) use ($pencairanprogram) {
                $query->select('kode_pelanggan')
                    ->from('marketing_program_pencairan_detail')
                    ->join('marketing_program_pencairan', 'marketing_program_pencairan_detail.kode_pencairan', '=', 'marketing_program_pencairan.kode_pencairan')
                    ->where('bulan', $pencairanprogram->bulan)
                    ->where('tahun', $pencairanprogram->tahun)
                    ->where('kode_program', $pencairanprogram->kode_program);
            })

            ->whereIn('marketing_penjualan.kode_pelanggan', function ($query) use ($pencairanprogram) {
                $query->select('kode_pelanggan')
                    ->from('marketing_program_kumulatif_detail')
                    ->join('marketing_program_kumulatif', 'marketing_program_kumulatif_detail.no_pengajuan', '=', 'marketing_program_kumulatif.no_pengajuan')
                    ->where('status', 1);
            })
            ->groupBy('marketing_penjualan.no_faktur', 'marketing_penjualan.kode_pelanggan', 'nama_pelanggan')
            ->orderBy('nama_pelanggan')
            ->get();

        $diskon = Diskon::where('kode_kategori_diskon', $kategori_diskon)->get();
        $detail = $detailpenjualan->groupBy('kode_pelanggan')
            ->map(function ($group) use ($diskon) {
                $diskon_kumulatif = $diskon->first(function ($diskonItem) use ($group) {
                    return $diskonItem->min_qty <= $group->sum('jml_dus') && $diskonItem->max_qty >= $group->sum('jml_dus');
                })->diskon ?? 0;
                $total_diskon_kumulatif = $diskon_kumulatif * $group->sum('jml_dus');
                $cashback = $total_diskon_kumulatif - $group->sum('diskon_reguler');
                return [
                    'kode_pelanggan' => $group->first()->kode_pelanggan,
                    'nama_pelanggan' => $group->first()->nama_pelanggan,
                    'jml_dus' => $group->sum('jml_dus'),
                    'diskon_reguler' => $group->sum('diskon_reguler'),
                    'diskon_kumulatif' => $total_diskon_kumulatif,
                    'cashback' => $cashback,
                ];
            })
            ->sortBy('nama_pelanggan')
            ->filter(function ($item) {
                return $item['cashback'] > 0;
            })
            ->values()
            ->all();



        $data['detail'] = $detail;
        $data['kode_pencairan'] = $kode_pencairan;
        // $data['bulan'] = $request->bulan;
        // $data['tahun'] = $request->tahun;
        // $data['diskon'] = $request->diskon;
        // $data['kategori_diskon'] = $kategori_diskon;
        // $data['kode_program'] = $request->kode_program;
        // $data['kode_cabang'] = $request->kode_cabang;
        $data['top'] = 30;
        return view('worksheetom.pencairanprogram.getpelanggan', $data);
    }


    public function detailfaktur($kode_pelanggan, $kode_pencairan, $top)
    {

        $kode_pencairan = Crypt::decrypt($kode_pencairan);

        $pencairanprogram = Pencairanprogram::where('kode_pencairan', $kode_pencairan)->first();
        $bulan = $pencairanprogram->bulan;
        $tahun = $pencairanprogram->tahun;

        if ($pencairanprogram->kode_program == 'PR001') {
            $produk = ['BB', 'DEP'];
            $kategori_diskon = 'D001';
        } else {
            $produk = ['AB', 'AR', 'AS'];
            $kategori_diskon = 'D002';
        }
        $start_date = $tahun . '-' . $bulan . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));
        $detailpenjualan = Detailpenjualan::select(
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.tanggal_pelunasan',
            'marketing_penjualan.jenis_transaksi',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) as jml_dus'),
            DB::raw('(SELECT diskon FROM produk_diskon WHERE SUM(floor(marketing_penjualan_detail.jumlah/produk.isi_pcs_dus)) BETWEEN produk_diskon.min_qty AND produk_diskon.max_qty AND kode_kategori_diskon="' . $kategori_diskon . '") as diskon'),
            DB::raw('SUM(floor(jumlah/isi_pcs_dus)) * (SELECT diskon FROM produk_diskon WHERE SUM(floor(marketing_penjualan_detail.jumlah/produk.isi_pcs_dus)) BETWEEN produk_diskon.min_qty AND produk_diskon.max_qty AND kode_kategori_diskon="' . $kategori_diskon . '") as diskon_reguler'),

        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
            ->where('status', 1)
            ->whereRaw("datediff(marketing_penjualan.tanggal_pelunasan, marketing_penjualan.tanggal) <= '" . $top . "'")
            ->where('status_batal', 0)
            ->whereIn('produk_harga.kode_produk', $produk)
            ->orderBy('nama_pelanggan')
            ->groupBy(
                'marketing_penjualan.no_faktur',
                'marketing_penjualan.tanggal',
                'marketing_penjualan.tanggal_pelunasan',
                'marketing_penjualan.jenis_transaksi',
                'marketing_penjualan.kode_pelanggan',
                'nama_pelanggan'
            )
            ->get();

        return view('worksheetom.pencairanprogram.detailfaktur', compact('detailpenjualan'));
    }


    public function storepelanggan(Request $request)
    {
        try {
            $cek  = Detailpencairan::where('kode_pencairan', $request->kode_pencairan)
                ->where('kode_pelanggan', $request->kode_pelanggan)
                ->first();
            if ($cek) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data sudah ada'
                ], 400);
            }
            $pelanggan = Detailajuanprogramkumulatif::where('kode_pelanggan', $request->kode_pelanggan)->first();
            $selisih = $request->diskon_kumulatif - $request->diskon_reguler;
            $metode_pembayaran = $selisih < 100000 ? 'VC' : $pelanggan->metode_pembayaran;
            Detailpencairan::create([
                'kode_pencairan' => $request->kode_pencairan,
                'kode_pelanggan' => $request->kode_pelanggan,
                'jumlah' => $request->jml_dus,
                'diskon_reguler' => $request->diskon_reguler,
                'diskon_kumulatif' => $request->diskon_kumulatif,
                'metode_pembayaran' => $metode_pembayaran,
                'top' => $request->top
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getdetailpencairan(Request $request)
    {
        $kode_pencairan = Crypt::decrypt($request->kode_pencairan);
        $user = User::find(auth()->user()->id);
        $detailpencairan = Detailpencairan::where('marketing_program_pencairan_detail.kode_pencairan', $kode_pencairan)
            ->select(
                'marketing_program_pencairan_detail.kode_pelanggan',
                'nama_pelanggan',
                'jumlah',
                'diskon_reguler',
                'diskon_kumulatif',
                'no_rekening',
                'pemilik_rekening',
                'bank',
                'marketing_program_pencairan_detail.metode_pembayaran as metode_bayar',
                'top',
                'om',
                'rsm',
                'gm',
                'direktur'
            )
            ->join('marketing_program_pencairan', 'marketing_program_pencairan_detail.kode_pencairan', '=', 'marketing_program_pencairan.kode_pencairan')
            ->join('pelanggan', 'marketing_program_pencairan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->get();
        $data['detailpencairan'] = $detailpencairan;
        $data['user'] = $user;
        return view('worksheetom.pencairanprogram.getdetailpencairan', $data);
    }

    public function deletedetailpencairan(Request $request)
    {
        $kode_pencairan = Crypt::decrypt($request->kode_pencairan);
        $kode_pelanggan = $request->kode_pelanggan;

        try {
            //code...
            Detailpencairan::where('kode_pencairan', $kode_pencairan)
                ->where('kode_pelanggan', $kode_pelanggan)
                ->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 400);
        }
    }


    public function destroy($kode_pencairan)
    {
        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        try {
            //code...
            Pencairanprogram::where('kode_pencairan', $kode_pencairan)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function approve(Request $request)
    {

        $kode_pencairan = Crypt::decrypt($request->kode_pencairan);
        $detailpencairan = Detailpencairan::where('kode_pencairan', $kode_pencairan)
            ->select(
                'marketing_program_pencairan_detail.kode_pelanggan',
                'nama_pelanggan',
                'no_rekening',
                'pemilik_rekening',
                'bank',
                'metode_pembayaran as metode_bayar',
                'jumlah',
                'diskon_reguler',
                'diskon_kumulatif'
            )
            ->orderBy('metode_pembayaran')
            ->join('pelanggan', 'marketing_program_pencairan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->get();
        $data['detailpencairan'] = $detailpencairan;
        $pencairanprogram = Pencairanprogram::where('kode_pencairan', $kode_pencairan)->first();
        $data['pencairanprogram'] = $pencairanprogram;
        return view('worksheetom.pencairanprogram.approve', $data);
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
                Pencairanprogram::where('kode_pencairan', $kode_pencairan)
                    ->update([
                        'status' => $status
                    ]);
            } else {
                Pencairanprogram::where('kode_pencairan', $kode_pencairan)
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


    public function cetak($kode_pencairan, Request $request)
    {

        $kode_pencairan = Crypt::decrypt($kode_pencairan);
        $detailpencairan = Detailpencairan::where('kode_pencairan', $kode_pencairan)
            ->select(
                'marketing_program_pencairan_detail.kode_pelanggan',
                'nama_pelanggan',
                'no_rekening',
                'pemilik_rekening',
                'bank',
                'metode_pembayaran as metode_bayar',
                'jumlah',
                'diskon_reguler',
                'diskon_kumulatif'
            )
            ->orderBy('metode_pembayaran')
            ->join('pelanggan', 'marketing_program_pencairan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->get();
        $data['detailpencairan'] = $detailpencairan;
        $pencairanprogram = Pencairanprogram::where('kode_pencairan', $kode_pencairan)->first();
        $data['pencairanprogram'] = $pencairanprogram;

        if ($request->export == 'true') {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=$kode_pencairan.xls");
            return view('worksheetom.pencairanprogram.cetak_export', $data);
        }
        return view('worksheetom.pencairanprogram.cetak', $data);
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
        // $kode_pelanggan = Crypt::decrypt($kode_pelanggan);
        try {
            //code...
            // Detailpencairanprogramikatan::where('kode_pencairan', $kode_pencairan)
            //     ->where('kode_pelanggan', $kode_pelanggan)
            //     ->update([fstore
            //         'bukti_transfer' => $request->bukti_transfer
            //     ]);

            Pencairanprogram::where('kode_pencairan', $kode_pencairan)
                ->update([
                    'bukti_transfer' => $request->bukti_transfer
                ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Di Upload'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

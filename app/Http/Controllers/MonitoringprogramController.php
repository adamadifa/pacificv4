<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailpencairan;
use App\Models\Detailpencairanprogramikatan;
use App\Models\Detailpenjualan;
use App\Models\Detailtargetikatan;
use App\Models\Programikatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

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

        $query = Detailpencairanprogramikatan::query();
        $query->select('marketing_pencairan_ikatan_detail.kode_pelanggan', 'nama_pelanggan', DB::raw('SUM(total_reward) as total_reward'));
        $query->join('pelanggan', 'marketing_pencairan_ikatan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('marketing_pencairan_ikatan', 'marketing_pencairan_ikatan_detail.kode_pencairan', '=', 'marketing_pencairan_ikatan.kode_pencairan');
        $query->where('status_pencairan', 0);
        $query->where('marketing_pencairan_ikatan.kode_cabangd', $kode_cabang);
        $query->where('marketing_pencairan_ikatan.status', 1);
        $query->groupBy('marketing_pencairan_ikatan_detail.kode_pelanggan', 'nama_pelanggan');
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

        $query = Detailpencairan::query();
        $query->select('marketing_program_pencairan_detail.kode_pelanggan', 'nama_pelanggan', DB::raw('SUM(diskon_kumulatif-diskon_reguler) as total_reward'));
        $query->join('pelanggan', 'marketing_program_pencairan_detail.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('marketing_program_pencairan', 'marketing_program_pencairan_detail.kode_pencairan', '=', 'marketing_program_pencairan.kode_pencairan');
        $query->where('marketing_program_pencairan.kode_cabang', $kode_cabang);
        $query->where('marketing_program_pencairan.status', 1);
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
}

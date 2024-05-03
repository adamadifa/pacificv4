<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detaildpb;
use App\Models\Detailmutasigudangcabang;
use App\Models\Dpb;
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

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $query = Dpb::query();
        $query->select('no_dpb', 'tanggal_ambil', 'nama_salesman', 'nama_cabang', 'tujuan', 'no_polisi');
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
            'kode_driver' => 'required'
        ]);

        $kode_produk = $request->kode_produk;
        $jml_dus = $request->jml_dus;
        $jml_pack = $request->jml_pack;
        $jml_pcs = $request->jml_pcs;
        $isi_pcs_dus = $request->isi_pcs_dus;
        $isi_pcs_pack = $request->isi_pcs_pack;


        DB::beginTransaction();
        try {

            for ($i = 0; $i < count($kode_produk); $i++) {

                $dus = !empty($jml_dus[$i]) ?  $jml_dus[$i] : 0;
                $pack = !empty($jml_pack[$i]) ?  $jml_pack[$i] : 0;
                $pcs = !empty($jml_pcs[$i]) ?  $jml_pcs[$i] : 0;

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
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (Exception $e) {
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

        $data['mutasi_dpb'] = Detailmutasigudangcabang::select(
            'gudang_cabang_mutasi_detail.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pack_dus',
            'isi_pcs_pack',
            DB::raw("SUM(IF(jenis_mutasi='PJ',jumlah,0)) as jml_penjualan")
        )
            ->join('produk', 'gudang_cabang_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi')
            ->where('no_dpb', $no_dpb)
            ->orderBy('nama_produk')
            ->groupBy('gudang_cabang_mutasi_detail.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pack_dus', 'isi_pcs_pack')
            ->get();

        // dd($data['mutasi_dpb']);
        return view('gudangcabang.dpb.show', $data);
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
}

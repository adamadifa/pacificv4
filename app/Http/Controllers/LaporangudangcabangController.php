<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detaildpb;
use App\Models\Detailmutasigudangcabang;
use App\Models\Detailsaldoawalgudangcabang;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LaporangudangcabangController extends Controller
{
    public function index()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['produk'] = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('gudangcabang.laporan.index', $data);
    }

    public function cetakpersediaangs(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang_gs;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }

        $bulan = date("m", strtotime($request->dari));
        $tahun = date("Y", strtotime($request->dari));
        $start_date = $tahun . "-" . $bulan . "-01";

        $query = Detailmutasigudangcabang::query();
        $query->select(
            'gudang_cabang_mutasi_detail.no_mutasi',
            'gudang_cabang_mutasi.tanggal',
            'gudang_cabang_mutasi.jenis_mutasi',
            'gudang_cabang_mutasi.no_surat_jalan',
            'gudang_jadi_mutasi.no_dok',
            'gudang_cabang_mutasi.tanggal_kirim',
            'gudang_cabang_mutasi.no_dpb',
            'salesman.nama_salesman',
            'gudang_cabang_jenis_mutasi.jenis_mutasi as nama_jenis_mutasi',
            'gudang_cabang_mutasi.keterangan',
            'produk.isi_pcs_dus',
            'produk.isi_pcs_pack',
            'in_out_good',
            'gudang_cabang_mutasi.created_at',
            'gudang_cabang_mutasi.updated_at',
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='SJ',gudang_cabang_mutasi_detail.jumlah,0))  as pusat"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='TI',gudang_cabang_mutasi_detail.jumlah,0))  as transit_in"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RT',gudang_cabang_mutasi_detail.jumlah,0))  as retur"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='HK',gudang_cabang_mutasi_detail.jumlah,0))  as hutang_kirim"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PT',gudang_cabang_mutasi_detail.jumlah,0))  as pelunasan_ttr"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PB',gudang_cabang_mutasi_detail.jumlah,0))  as penyesuaian_bad"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RK',gudang_cabang_mutasi_detail.jumlah,0))  as repack"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PY' AND in_out_good='I',gudang_cabang_mutasi_detail.jumlah,0))  as penyesuaian_in"),

            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PJ',gudang_cabang_mutasi_detail.jumlah,0))  as penjualan"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PR',gudang_cabang_mutasi_detail.jumlah,0))  as promosi"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RP',gudang_cabang_mutasi_detail.jumlah,0))  as reject_pasar"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RM',gudang_cabang_mutasi_detail.jumlah,0))  as reject_mobil"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RG',gudang_cabang_mutasi_detail.jumlah,0))  as reject_gudang"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='TO',gudang_cabang_mutasi_detail.jumlah,0))  as transit_out"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='TR',gudang_cabang_mutasi_detail.jumlah,0))  as ttr"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='GB',gudang_cabang_mutasi_detail.jumlah,0))  as ganti_barang"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PH',gudang_cabang_mutasi_detail.jumlah,0))  as pelunasan_hutangkirim"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PY' AND in_out_good='O',gudang_cabang_mutasi_detail.jumlah,0))  as penyesuaian_out"),
        );
        $query->join('produk', 'gudang_cabang_mutasi_detail.kode_produk', '=', 'produk.kode_produk');
        $query->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi');
        $query->join('gudang_cabang_jenis_mutasi', 'gudang_cabang_mutasi.jenis_mutasi', '=', 'gudang_cabang_jenis_mutasi.kode_jenis_mutasi');
        $query->leftJoin('gudang_jadi_mutasi', 'gudang_cabang_mutasi.no_mutasi', '=', 'gudang_jadi_mutasi.no_mutasi');
        $query->leftJoin('gudang_cabang_dpb', 'gudang_cabang_mutasi.no_dpb', '=', 'gudang_cabang_dpb.no_dpb');
        $query->leftJoin('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman');


        $query->whereBetween('gudang_cabang_mutasi.tanggal', [$request->dari, $request->sampai]);
        $query->where('gudang_cabang_mutasi_detail.kode_produk', $request->kode_produk_gs);
        $query->where('gudang_cabang_mutasi.kode_cabang', $kode_cabang);
        $query->whereNotNull('in_out_good');
        $query->orderBy('gudang_cabang_mutasi.tanggal');
        $query->orderBy('order');
        $query->orderBy('gudang_cabang_mutasi.no_dpb');
        $query->groupBy(
            'gudang_cabang_mutasi_detail.no_mutasi',
            'gudang_cabang_mutasi.tanggal',
            'gudang_cabang_mutasi.jenis_mutasi',
            'gudang_cabang_mutasi.no_surat_jalan',
            'gudang_jadi_mutasi.no_dok',
            'gudang_cabang_mutasi.tanggal_kirim',
            'gudang_cabang_mutasi.no_dpb',
            'salesman.nama_salesman',
            'gudang_cabang_jenis_mutasi.jenis_mutasi',
            'gudang_cabang_mutasi.keterangan',
            'produk.isi_pcs_dus',
            'produk.isi_pcs_pack',
            'in_out_good',
            'gudang_cabang_mutasi.created_at',
            'gudang_cabang_mutasi.updated_at'

        );
        $data['mutasi'] = $query->get();


        $saldo_awal = Detailsaldoawalgudangcabang::select('gudang_cabang_saldoawal_detail.kode_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'jumlah')
            ->join('gudang_cabang_saldoawal', 'gudang_cabang_saldoawal_detail.kode_saldo_awal', '=', 'gudang_cabang_saldoawal.kode_saldo_awal')
            ->join('produk', 'gudang_cabang_saldoawal_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('kode_cabang', $kode_cabang)
            ->where('kondisi', 'GS')
            ->where('gudang_cabang_saldoawal_detail.kode_produk', $request->kode_produk_gs)
            ->first();

        $mutasi_saldo_awal = Detailmutasigudangcabang::select(
            'gudang_cabang_mutasi_detail.kode_produk',
            'isi_pcs_dus',
            DB::raw("SUM(IF( `in_out_good` = 'I', jumlah, 0)) -SUM(IF( `in_out_good` = 'O', jumlah, 0)) as sisa_mutasi")
        )
            ->join('produk', 'gudang_cabang_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi')
            ->where('gudang_cabang_mutasi.tanggal', '>=', $start_date)
            ->where('gudang_cabang_mutasi.tanggal', '<', $request->dari)
            ->where('gudang_cabang_mutasi_detail.kode_produk', $request->kode_produk_gs)
            ->where('gudang_cabang_mutasi.kode_cabang', $kode_cabang)
            ->whereNotNull('in_out_good')
            ->groupBy('gudang_cabang_mutasi_detail.kode_produk', 'isi_pcs_dus')
            ->first();

        $sisa_mutasi_desimal = $mutasi_saldo_awal != NULL ? $mutasi_saldo_awal->sisa_mutasi / $mutasi_saldo_awal->isi_pcs_dus : 0;
        $sisa_mutasi_pcs = $mutasi_saldo_awal != NULL ? $mutasi_saldo_awal->sisa_mutasi : 0;
        if ($saldo_awal != NULL) {
            $saldo_awal_desimal = ($saldo_awal->jumlah / $saldo_awal->isi_pcs_dus) + $sisa_mutasi_desimal;
            $saldo_awal_pcs = $saldo_awal->jumlah + $sisa_mutasi_pcs;
        } else {
            $saldo_awal_desimal = 0;
            $saldo_awal_pcs = 0;
        }


        $produk = Produk::where('kode_produk', $request->kode_produk_gs)->first();
        $data['produk'] = $produk;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['ceksaldo'] = $saldo_awal;
        $data['saldo_awal'] = $saldo_awal_desimal;
        $data['saldo_awal_pcs'] = $saldo_awal_pcs;

        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Persediaan Good Stok $produk->nama_produk-$request->dari-$request->sampai-$time.xls");
        }
        return view('gudangcabang.laporan.goodstok_cetak', $data);
    }

    public function cetakpersediaanbs(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang_bs;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }

        $bulan = date("m", strtotime($request->dari));
        $tahun = date("Y", strtotime($request->dari));
        $start_date = $tahun . "-" . $bulan . "-01";

        $query = Detailmutasigudangcabang::query();
        $query->select(
            'gudang_cabang_mutasi_detail.no_mutasi',
            'gudang_cabang_mutasi.tanggal',
            'gudang_cabang_mutasi.jenis_mutasi',
            'gudang_cabang_jenis_mutasi.jenis_mutasi as nama_jenis_mutasi',
            'gudang_cabang_mutasi.keterangan',
            'in_out_bad',
            'gudang_cabang_mutasi.created_at',
            'gudang_cabang_mutasi.updated_at',

            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RP',gudang_cabang_mutasi_detail.jumlah,0))  as reject_pasar"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RM',gudang_cabang_mutasi_detail.jumlah,0))  as reject_mobil"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RG',gudang_cabang_mutasi_detail.jumlah,0))  as reject_gudang"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PB' AND in_out_bad = 'I',gudang_cabang_mutasi_detail.jumlah,0))  as penyesuaian_bad_in"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PB' AND in_out_bad = 'O',gudang_cabang_mutasi_detail.jumlah,0))  as penyesuaian_bad_out"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RK',gudang_cabang_mutasi_detail.jumlah,0))  as repack"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='KP',gudang_cabang_mutasi_detail.jumlah,0))  as kirim_pusat")
        );

        $query->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi');
        $query->join('gudang_cabang_jenis_mutasi', 'gudang_cabang_mutasi.jenis_mutasi', '=', 'gudang_cabang_jenis_mutasi.kode_jenis_mutasi');


        $query->whereBetween('gudang_cabang_mutasi.tanggal', [$request->dari, $request->sampai]);
        $query->where('gudang_cabang_mutasi_detail.kode_produk', $request->kode_produk_bs);
        $query->where('gudang_cabang_mutasi.kode_cabang', $kode_cabang);
        $query->whereNotNull('in_out_bad');
        $query->orderBy('gudang_cabang_mutasi.tanggal');
        $query->orderBy('order');
        $query->groupBy(
            'gudang_cabang_mutasi_detail.no_mutasi',
            'gudang_cabang_mutasi.tanggal',
            'gudang_cabang_mutasi.jenis_mutasi',
            'gudang_cabang_jenis_mutasi.jenis_mutasi',
            'gudang_cabang_mutasi.keterangan',
            'in_out_bad',
            'gudang_cabang_mutasi.created_at',
            'gudang_cabang_mutasi.updated_at',

        );
        $data['mutasi'] = $query->get();

        $saldo_awal = Detailsaldoawalgudangcabang::select('gudang_cabang_saldoawal_detail.kode_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'jumlah')
            ->join('gudang_cabang_saldoawal', 'gudang_cabang_saldoawal_detail.kode_saldo_awal', '=', 'gudang_cabang_saldoawal.kode_saldo_awal')
            ->join('produk', 'gudang_cabang_saldoawal_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('kode_cabang', $kode_cabang)
            ->where('kondisi', 'BS')
            ->where('gudang_cabang_saldoawal_detail.kode_produk', $request->kode_produk_bs)
            ->first();

        $mutasi_saldo_awal = Detailmutasigudangcabang::select(
            'gudang_cabang_mutasi_detail.kode_produk',
            'isi_pcs_dus',
            DB::raw("SUM(IF( `in_out_bad` = 'I', jumlah, 0)) -SUM(IF( `in_out_bad` = 'O', jumlah, 0)) as sisa_mutasi")
        )
            ->join('produk', 'gudang_cabang_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi')
            ->where('gudang_cabang_mutasi.tanggal', '>=', $start_date)
            ->where('gudang_cabang_mutasi.tanggal', '<', $request->dari)
            ->where('gudang_cabang_mutasi_detail.kode_produk', $request->kode_produk_bs)
            ->where('gudang_cabang_mutasi.kode_cabang', $kode_cabang)
            ->whereNotNull('in_out_bad')
            ->groupBy('gudang_cabang_mutasi_detail.kode_produk', 'isi_pcs_dus')
            ->first();

        $sisa_mutasi_desimal = $mutasi_saldo_awal != NULL ? $mutasi_saldo_awal->sisa_mutasi / $mutasi_saldo_awal->isi_pcs_dus : 0;
        $sisa_mutasi_pcs = $mutasi_saldo_awal != NULL ? $mutasi_saldo_awal->sisa_mutasi : 0;
        if ($saldo_awal != NULL) {
            $saldo_awal_desimal = ($saldo_awal->jumlah / $saldo_awal->isi_pcs_dus) + $sisa_mutasi_desimal;
            $saldo_awal_jumlah = $saldo_awal->jumlah + $sisa_mutasi_pcs;
        } else {
            $saldo_awal_desimal = 0;
            $saldo_awal_jumlah = 0;
        }


        $produk = Produk::where('kode_produk', $request->kode_produk_bs)->first();
        $data['produk'] = $produk;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['ceksaldo'] = $saldo_awal;
        $data['saldo_awal'] = $saldo_awal_desimal;
        $data['saldo_awal_jumlah'] = $saldo_awal_jumlah;

        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Persediaan Bad Stok $produk->nama_produk-$request->dari-$request->sampai-$time.xls");
        }
        return view('gudangcabang.laporan.badstok_cetak', $data);
    }



    public function cetakrekappersediaan(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang_persediaan;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }

        $bulan = date("m", strtotime($request->dari));
        $tahun = date("Y", strtotime($request->dari));
        $start_date = $tahun . "-" . $bulan . "-01";

        $query = Produk::query();
        $query->select(
            'produk.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pcs_pack',
            DB::raw("IFNULL(saldo_awal,0) + IFNULL(sisa_mutasi,0) as saldo_awal"),
            'pusat',
            'transit_in',
            'retur',
            'hutang_kirim',
            'pelunasan_ttr',
            'penyesuaian_bad',
            'repack',
            'penyesuaian_in',

            'penjualan',
            'promosi',
            'reject_pasar',
            'reject_mobil',
            'reject_gudang',
            'transit_out',
            'ttr',
            'ganti_barang',
            'pelunasan_hutangkirim',
            'penyesuaian_out'
        );
        $query->leftJoin(
            DB::raw("(
                SELECT kode_produk,jumlah as saldo_awal
                FROM gudang_cabang_saldoawal_detail
                INNER JOIN gudang_cabang_saldoawal ON gudang_cabang_saldoawal_detail.kode_saldo_awal = gudang_cabang_saldoawal.kode_saldo_awal
                WHERE kondisi ='GS' AND bulan ='$bulan' AND tahun='$tahun' AND kode_cabang='$kode_cabang'
            ) saldo_awal"),
            function ($join) {
                $join->on('produk.kode_produk', '=', 'saldo_awal.kode_produk');
            }
        );

        $query->leftJoin(
            DB::raw("(
                SELECT kode_produk,
                SUM(IF( `in_out_good` = 'I', jumlah, 0)) -SUM(IF( `in_out_good` = 'O', jumlah, 0)) as sisa_mutasi
                FROM gudang_cabang_mutasi_detail
                INNER JOIN gudang_cabang_mutasi ON gudang_cabang_mutasi_detail.no_mutasi = gudang_cabang_mutasi.no_mutasi
                WHERE gudang_cabang_mutasi.tanggal >= '$start_date' AND gudang_cabang_mutasi.tanggal < '$request->dari'
                AND gudang_cabang_mutasi.kode_cabang = '$kode_cabang' AND in_out_good IS NOT NULL
                GROUP BY gudang_cabang_mutasi_detail.kode_produk
            ) mutasi_saldo_awal"),
            function ($join) {
                $join->on('produk.kode_produk', '=', 'mutasi_saldo_awal.kode_produk');
            }
        );


        $query->leftJoin(
            DB::raw("(
                SELECT kode_produk,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='SJ',gudang_cabang_mutasi_detail.jumlah,0))  as pusat,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='TI',gudang_cabang_mutasi_detail.jumlah,0)) as transit_in,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RT',gudang_cabang_mutasi_detail.jumlah,0)) as retur,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='HK',gudang_cabang_mutasi_detail.jumlah,0)) as hutang_kirim,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PT',gudang_cabang_mutasi_detail.jumlah,0)) as pelunasan_ttr,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PB',gudang_cabang_mutasi_detail.jumlah,0)) as penyesuaian_bad,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RK',gudang_cabang_mutasi_detail.jumlah,0)) as repack,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PY' AND in_out_good='I',gudang_cabang_mutasi_detail.jumlah,0)) as penyesuaian_in,

                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PJ',gudang_cabang_mutasi_detail.jumlah,0))  as penjualan,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PR',gudang_cabang_mutasi_detail.jumlah,0))  as promosi,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RP',gudang_cabang_mutasi_detail.jumlah,0))  as reject_pasar,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RM',gudang_cabang_mutasi_detail.jumlah,0))  as reject_mobil,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RG',gudang_cabang_mutasi_detail.jumlah,0))  as reject_gudang,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='TO',gudang_cabang_mutasi_detail.jumlah,0))  as transit_out,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='TR',gudang_cabang_mutasi_detail.jumlah,0))  as ttr,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='GB',gudang_cabang_mutasi_detail.jumlah,0))  as ganti_barang,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PH',gudang_cabang_mutasi_detail.jumlah,0))  as pelunasan_hutangkirim,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PY' AND in_out_good='O',gudang_cabang_mutasi_detail.jumlah,0)) as penyesuaian_out
                FROM gudang_cabang_mutasi_detail
                INNER JOIN gudang_cabang_mutasi ON gudang_cabang_mutasi_detail.no_mutasi = gudang_cabang_mutasi.no_mutasi
                WHERE  tanggal BETWEEN '$request->dari' AND '$request->sampai' AND kode_cabang='$kode_cabang' AND in_out_good IS NOT NULL
                GROUP BY kode_produk
            ) mutasi"),
            function ($join) {
                $join->on('produk.kode_produk', '=', 'mutasi.kode_produk');
            }
        );
        $query->where('status_aktif_produk', 1);
        $data['rekapgs'] = $query->get();


        $querybs = Produk::query();
        $querybs->select(
            'produk.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pcs_pack',
            DB::raw("IFNULL(saldo_awal,0) + IFNULL(sisa_mutasi,0) as saldo_awal"),
            'reject_pasar',
            'reject_mobil',
            'reject_gudang',
            'penyesuaian_bad_in',
            'penyesuaian_bad_out',
            'kirim_pusat',
            'repack'
        );
        $querybs->leftJoin(
            DB::raw("(
                SELECT kode_produk,jumlah as saldo_awal
                FROM gudang_cabang_saldoawal_detail
                INNER JOIN gudang_cabang_saldoawal ON gudang_cabang_saldoawal_detail.kode_saldo_awal = gudang_cabang_saldoawal.kode_saldo_awal
                WHERE kondisi ='BS' AND bulan ='$bulan' AND tahun='$tahun' AND kode_cabang='$kode_cabang'
            ) saldo_awal"),
            function ($join) {
                $join->on('produk.kode_produk', '=', 'saldo_awal.kode_produk');
            }
        );

        $querybs->leftJoin(
            DB::raw("(
                SELECT kode_produk,
                SUM(IF( `in_out_bad` = 'I', jumlah, 0)) -SUM(IF( `in_out_bad` = 'O', jumlah, 0)) as sisa_mutasi
                FROM gudang_cabang_mutasi_detail
                INNER JOIN gudang_cabang_mutasi ON gudang_cabang_mutasi_detail.no_mutasi = gudang_cabang_mutasi.no_mutasi
                WHERE gudang_cabang_mutasi.tanggal >= '$start_date' AND gudang_cabang_mutasi.tanggal < '$request->dari'
                AND gudang_cabang_mutasi.kode_cabang = '$kode_cabang' AND in_out_bad IS NOT NULL
                GROUP BY gudang_cabang_mutasi_detail.kode_produk
            ) mutasi_saldo_awal"),
            function ($join) {
                $join->on('produk.kode_produk', '=', 'mutasi_saldo_awal.kode_produk');
            }
        );

        $querybs->leftJoin(
            DB::raw("(
                SELECT kode_produk,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RP',gudang_cabang_mutasi_detail.jumlah,0))  as reject_pasar,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RM',gudang_cabang_mutasi_detail.jumlah,0))  as reject_mobil,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RG',gudang_cabang_mutasi_detail.jumlah,0))  as reject_gudang,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PB' AND in_out_bad = 'I',gudang_cabang_mutasi_detail.jumlah,0))  as penyesuaian_bad_in,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PB' AND in_out_bad = 'O',gudang_cabang_mutasi_detail.jumlah,0))  as penyesuaian_bad_out,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RK',gudang_cabang_mutasi_detail.jumlah,0))  as repack,
                SUM(IF(gudang_cabang_mutasi.jenis_mutasi='KP',gudang_cabang_mutasi_detail.jumlah,0))  as kirim_pusat
                FROM gudang_cabang_mutasi_detail
                INNER JOIN gudang_cabang_mutasi ON gudang_cabang_mutasi_detail.no_mutasi = gudang_cabang_mutasi.no_mutasi
                WHERE  tanggal BETWEEN '$request->dari' AND '$request->sampai' AND kode_cabang='$kode_cabang' AND in_out_bad IS NOT NULL
                GROUP BY kode_produk
            ) mutasi"),
            function ($join) {
                $join->on('produk.kode_produk', '=', 'mutasi.kode_produk');
            }
        );
        $querybs->where('status_aktif_produk', 1);
        $data['rekapbs'] = $querybs->get();

        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $time = date('H:i:s');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=REKAP PERSEDIAAN GUDANG CABANG-$request->dari-$request->sampai-$time.xls");
        }
        return view('gudangcabang.laporan.rekappersediaan_cetak', $data);
    }

    public function cetakmutasidpb(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang_mutasidpb;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }

        $bulan = date("m", strtotime($request->dari));
        $tahun = date("Y", strtotime($request->dari));
        $start_date = $tahun . "-" . $bulan . "-01";

        $results = Detailmutasigudangcabang::select(
            'gudang_cabang_mutasi.tanggal',
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='SJ' OR gudang_cabang_mutasi.jenis_mutasi='TI',gudang_cabang_mutasi_detail.jumlah,0))  as pusat"),
            DB::raw('0 as jml_pengambilan'),
            DB::raw('0 as jml_pengembalian'),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RP',gudang_cabang_mutasi_detail.jumlah,0))  as reject_pasar"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RM',gudang_cabang_mutasi_detail.jumlah,0))  as reject_mobil"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RG',gudang_cabang_mutasi_detail.jumlah,0))  as reject_gudang"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RK',gudang_cabang_mutasi_detail.jumlah,0))  as repack"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PY' AND in_out_good='I',gudang_cabang_mutasi_detail.jumlah,0)) as penyesuaian_in"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PY' AND in_out_good='O',gudang_cabang_mutasi_detail.jumlah,0)) as penyesuaian_out")
        )
            ->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi')
            ->whereBetween('gudang_cabang_mutasi.tanggal', [$request->dari, $request->sampai])
            ->where('gudang_cabang_mutasi_detail.kode_produk', $request->kode_produk_mutasidpb)
            ->where('gudang_cabang_mutasi.kode_cabang', $kode_cabang)
            ->whereIn('gudang_cabang_mutasi.jenis_mutasi', ['SJ', 'RP', 'RM', 'RG', 'RK', 'PY', 'TI'])
            ->groupBy('gudang_cabang_mutasi.tanggal');


        $results->unionAll(
            Detaildpb::select(
                'gudang_cabang_dpb.tanggal_ambil as tanggal',
                DB::raw('0 as pusat'),
                DB::raw("SUM(jml_ambil) as jml_pengambilan"),
                DB::raw('0 as jml_pengembalian'),
                DB::raw('0 as reject_pasar'),
                DB::raw('0 as reject_mobil'),
                DB::raw('0 as reject_gudang'),
                DB::raw('0 as repack'),
                DB::raw('0 as penyesuaian_in'),
                DB::raw('0 as penyesuaian_out')
            )
                ->join('gudang_cabang_dpb', 'gudang_cabang_dpb_detail.no_dpb', '=', 'gudang_cabang_dpb.no_dpb')
                ->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman')
                ->whereBetween('gudang_cabang_dpb.tanggal_ambil', [$request->dari, $request->sampai])
                ->where('salesman.kode_cabang', $kode_cabang)
                ->where('gudang_cabang_dpb_detail.kode_produk', $request->kode_produk_mutasidpb)
                ->groupBy('gudang_cabang_dpb.tanggal_ambil')
        );

        $results->unionAll(
            Detaildpb::select(
                'gudang_cabang_dpb.tanggal_kembali as tanggal',
                DB::raw('0 as pusat'),
                DB::raw('0 as jml_pengambilan'),
                DB::raw("SUM(jml_kembali) as jml_pengembalian"),
                DB::raw('0 as reject_pasar'),
                DB::raw('0 as reject_mobil'),
                DB::raw('0 as reject_gudang'),
                DB::raw('0 as repack'),
                DB::raw('0 as penyesuaian_in'),
                DB::raw('0 as penyesuaian_out')
            )
                ->join('gudang_cabang_dpb', 'gudang_cabang_dpb_detail.no_dpb', '=', 'gudang_cabang_dpb.no_dpb')
                ->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman')
                ->whereBetween('gudang_cabang_dpb.tanggal_kembali', [$request->dari, $request->sampai])
                ->where('salesman.kode_cabang', $kode_cabang)
                ->where('gudang_cabang_dpb_detail.kode_produk', $request->kode_produk_mutasidpb)
                ->groupBy('gudang_cabang_dpb.tanggal_kembali')
        );

        $mutasidpb = $results->get();

        $data['mutasidpb'] = $mutasidpb->groupBy('tanggal')
            ->map(function ($item) {
                return [
                    'tanggal' => $item->first()->tanggal,
                    'pusat' => $item->sum(function ($row) {
                        return  $row->pusat;
                    }),
                    'jml_pengambilan' => $item->sum(function ($row) {
                        return  $row->jml_pengambilan;
                    }),
                    'jml_pengembalian' => $item->sum(function ($row) {
                        return  $row->jml_pengembalian;
                    }),
                    'reject_pasar' => $item->sum(function ($row) {
                        return  $row->reject_pasar;
                    }),
                    'reject_mobil' => $item->sum(function ($row) {
                        return  $row->reject_mobil;
                    }),
                    'reject_gudang' => $item->sum(function ($row) {
                        return  $row->reject_gudang;
                    }),
                    'repack' => $item->sum(function ($row) {
                        return  $row->repack;
                    }),
                    'penyesuaian_in' => $item->sum(function ($row) {
                        return  $row->penyesuaian_in;
                    }),
                    'penyesuaian_out' => $item->sum(function ($row) {
                        return  $row->penyesuaian_out;
                    }),
                ];
            })
            ->sortBy('tanggal')
            ->values()
            ->all();

        $saldo_awal = Detailsaldoawalgudangcabang::select('gudang_cabang_saldoawal_detail.kode_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'jumlah')
            ->join('gudang_cabang_saldoawal', 'gudang_cabang_saldoawal_detail.kode_saldo_awal', '=', 'gudang_cabang_saldoawal.kode_saldo_awal')
            ->join('produk', 'gudang_cabang_saldoawal_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('kode_cabang', $kode_cabang)
            ->where('kondisi', 'GS')
            ->where('gudang_cabang_saldoawal_detail.kode_produk', $request->kode_produk_mutasidpb)
            ->first();


        if ($saldo_awal != NULL) {
            $saldo_awal_desimal = $saldo_awal->jumlah / $saldo_awal->isi_pcs_dus;
            $saldo_awal_jumlah = $saldo_awal->jumlah;
        } else {
            $saldo_awal_desimal = 0;
            $saldo_awal_jumlah = 0;
        }

        $produk = Produk::where('kode_produk', $request->kode_produk_mutasidpb)->first();
        $data['produk'] = $produk;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['ceksaldo'] = $saldo_awal;
        $data['saldo_awal'] = $saldo_awal_desimal;
        $data['saldo_awal_jumlah'] = $saldo_awal_jumlah;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=MUTASI DPB.xls");
        }
        return view('gudangcabang.laporan.mutasidpb_cetak', $data);
    }


    public function cetakrekonsiliasibj(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $user = User::findorFail(auth()->user()->id);
        $roles_show_cabang = config('global.roles_show_cabang');

        if ($user->hasRole($roles_show_cabang)) {
            $kode_cabang = $request->kode_cabang_rekonsiliasi;
        } else {
            $kode_cabang = auth()->user()->kode_cabang;
        }

        if (!empty($request->kode_salesman)) {
            $whereSalesman = "AND marketing_penjualan.kode_salesman = '$request->kode_salesman'";
            $whereSalesmandpb = "AND gudang_cabang_dpb.kode_salesman = '$request->kode_salesman'";
        } else {
            $whereSalesman = "";
            $whereSalesmandpb = "";
        }
        // dd('test');
        if ($request->jenis_rekonsiliasi == '1') {
            $data['rekonsiliasi'] = Produk::select(
                'produk.kode_produk',
                'nama_produk',
                'isi_pcs_dus',
                'isi_pack_dus',
                'isi_pcs_pack',
                'total',
                'totalpersediaan'
            )
                ->leftJoin(
                    DB::raw("(
                    SELECT kode_produk, SUM(jumlah) as total
                    FROM marketing_penjualan_detail
                    INNER JOIN produk_harga ON marketing_penjualan_detail.kode_harga = produk_harga.kode_harga
                    INNER JOIN marketing_penjualan ON marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur
                    INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                    WHERE tanggal BETWEEN '$request->dari' AND '$request->sampai' AND salesman.kode_cabang ='$kode_cabang' AND status_promosi = '0' AND status_batal='0'" . $whereSalesman . "
                    GROUP BY kode_produk
                ) detailpenjualan"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'detailpenjualan.kode_produk');
                    }
                )
                ->leftJoin(
                    DB::raw("(
                    SELECT kode_produk,SUM(jumlah) as totalpersediaan
                    FROM gudang_cabang_mutasi_detail
                    INNER JOIN gudang_cabang_mutasi ON gudang_cabang_mutasi_detail.no_mutasi = gudang_cabang_mutasi.no_mutasi
                    LEFT JOIN gudang_cabang_dpb ON gudang_cabang_mutasi.no_dpb = gudang_cabang_dpb.no_dpb
                    WHERE jenis_mutasi = 'PJ'
                    AND tanggal BETWEEN '$request->dari' AND '$request->sampai' AND gudang_cabang_mutasi.kode_cabang ='$kode_cabang' " . $whereSalesmandpb . "
                    GROUP BY kode_produk
                ) persediaan"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'persediaan.kode_produk');
                    }
                )
                ->where('status_aktif_produk', 1)
                ->get();
        } else if ($request->jenis_rekonsiliasi == '2') {
            $data['rekonsiliasi'] = Produk::select(
                'produk.kode_produk',
                'nama_produk',
                'isi_pcs_dus',
                'isi_pack_dus',
                'isi_pcs_pack',
                'total',
                'totalpersediaan'
            )
                ->leftJoin(
                    DB::raw("(
                    SELECT kode_produk, SUM(jumlah) as total
                    FROM marketing_retur_detail
                    INNER JOIN produk_harga ON marketing_retur_detail.kode_harga = produk_harga.kode_harga
                    INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
                    INNER JOIN marketing_penjualan ON marketing_retur.no_faktur = marketing_penjualan.no_faktur
                    INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                    WHERE marketing_retur.tanggal BETWEEN '$request->dari' AND '$request->sampai' AND salesman.kode_cabang ='$kode_cabang'" . $whereSalesman . "
                    GROUP BY kode_produk
                ) detailpenjualan"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'detailpenjualan.kode_produk');
                    }
                )
                ->leftJoin(
                    DB::raw("(
                    SELECT kode_produk,SUM(jumlah) as totalpersediaan
                    FROM gudang_cabang_mutasi_detail
                    INNER JOIN gudang_cabang_mutasi ON gudang_cabang_mutasi_detail.no_mutasi = gudang_cabang_mutasi.no_mutasi
                    LEFT JOIN gudang_cabang_dpb ON gudang_cabang_mutasi.no_dpb = gudang_cabang_dpb.no_dpb
                    WHERE jenis_mutasi = 'RT'
                    AND tanggal BETWEEN '$request->dari' AND '$request->sampai' AND gudang_cabang_mutasi.kode_cabang ='$kode_cabang'" . $whereSalesmandpb . "
                    GROUP BY kode_produk
                ) persediaan"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'persediaan.kode_produk');
                    }
                )
                ->where('status_aktif_produk', 1)
                ->get();
        } elseif ($request->jenis_rekonsiliasi == '3') {
            $data['rekonsiliasi'] = Produk::select(
                'produk.kode_produk',
                'nama_produk',
                'isi_pcs_dus',
                'isi_pack_dus',
                'isi_pcs_pack',
                'total',
                'totalpersediaan'
            )
                ->leftJoin(
                    DB::raw("(
                    SELECT kode_produk, SUM(jumlah) as total
                    FROM marketing_penjualan_detail
                    INNER JOIN produk_harga ON marketing_penjualan_detail.kode_harga = produk_harga.kode_harga
                    INNER JOIN marketing_penjualan ON marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur
                    INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                    WHERE tanggal BETWEEN '$request->dari' AND '$request->sampai' AND salesman.kode_cabang ='$kode_cabang' AND status_promosi = '1' AND status_batal = '0'" . $whereSalesman . "
                    GROUP BY kode_produk
                ) detailpenjualan"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'detailpenjualan.kode_produk');
                    }
                )
                ->leftJoin(
                    DB::raw("(
                    SELECT kode_produk,SUM(jumlah) as totalpersediaan
                    FROM gudang_cabang_mutasi_detail
                    INNER JOIN gudang_cabang_mutasi ON gudang_cabang_mutasi_detail.no_mutasi = gudang_cabang_mutasi.no_mutasi
                    LEFT JOIN gudang_cabang_dpb ON gudang_cabang_mutasi.no_dpb = gudang_cabang_dpb.no_dpb
                    WHERE jenis_mutasi = 'PR'
                    AND tanggal BETWEEN '$request->dari' AND '$request->sampai' AND gudang_cabang_mutasi.kode_cabang ='$kode_cabang'" . $whereSalesmandpb . "
                    GROUP BY kode_produk
                ) persediaan"),
                    function ($join) {
                        $join->on('produk.kode_produk', '=', 'persediaan.kode_produk');
                    }
                )
                ->where('status_aktif_produk', 1)
                ->get();
        }

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        return view('gudangcabang.laporan.rekonsiliasibj_cetak', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailmutasigudangcabang;
use App\Models\Detailsaldoawalgudangcabang;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $data['produk'] = Produk::where('kode_produk', $request->kode_produk_gs)->first();
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

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
        $data['ceksaldo'] = $saldo_awal;
        $data['saldo_awal'] = $saldo_awal_desimal;
        $data['saldo_awal_pcs'] = $saldo_awal_pcs;
        return view('gudangcabang.laporan.goodstok_cetak', $data);
    }
}

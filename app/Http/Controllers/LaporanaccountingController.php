<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailmutasigudangcabang;
use App\Models\Detailmutasigudangjadi;
use App\Models\Detailsaldoawalgudangcabang;
use App\Models\Detailsaldoawalgudangjadi;
use App\Models\Saldoawalgudangcabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanaccountingController extends Controller
{
    public function index()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('accounting.laporan.index', $data);
    }


    public function cetakrekappersediaan(Request $request)
    {
        $dari = $request->tahun . "-" . $request->bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;

        $querysaldoawalgudang = Detailsaldoawalgudangjadi::query();
        $querysaldoawalgudang->select(
            'gudang_jadi_saldoawal_detail.kode_produk',
            'nama_produk',
            DB::raw('SUM(jumlah) as saldo_awal'),
            DB::raw('SUM(0) as jml_fsthp'),
            DB::raw('SUM(0) as jml_repack'),
            DB::raw('SUM(0) as jml_lainlain_in'),
            DB::raw('SUM(0) as jml_surat_jalan'),
            DB::raw('SUM(0) as jml_reject'),
            DB::raw('SUM(0) as jml_lainlain_out'),
            DB::raw('SUM(0) as jml_mutasi')
        );
        $querysaldoawalgudang->join('gudang_jadi_saldoawal', 'gudang_jadi_saldoawal_detail.kode_saldo_awal', 'gudang_jadi_saldoawal.kode_saldo_awal');
        $querysaldoawalgudang->join('produk', 'gudang_jadi_saldoawal_detail.kode_produk', 'produk.kode_produk');
        $querysaldoawalgudang->where('bulan', $request->bulan);
        $querysaldoawalgudang->where('tahun', $request->tahun);
        $querysaldoawalgudang->groupBy('gudang_jadi_saldoawal_detail.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack');


        $querymutasigudang = Detailmutasigudangjadi::query();
        $querymutasigudang->select(
            'gudang_jadi_mutasi_detail.kode_produk',
            'nama_produk',
            DB::raw('SUM(0) as saldo_awal'),
            DB::raw("SUM(IF(jenis_mutasi = 'FS', jumlah, 0)) as jml_fsthp"),
            DB::raw("SUM(IF(jenis_mutasi = 'RP', jumlah, 0)) as jml_repack"),
            DB::raw("SUM(IF(jenis_mutasi = 'RJ', jumlah, 0)) as jml_reject"),
            DB::raw("SUM(IF(jenis_mutasi = 'LN' AND in_out = 'I', jumlah, 0)) as jml_lainlain_in"),
            DB::raw("SUM(IF(jenis_mutasi = 'LN' AND in_out = 'O', jumlah, 0)) as jml_lainlain_out"),
            DB::raw("SUM(IF(jenis_mutasi = 'SJ', jumlah, 0)) as jml_surat_jalan"),
            DB::raw("SUM(IF(in_out = 'I', jumlah, 0)) - SUM(IF(in_out = 'O', jumlah, 0)) as jml_mutasi")
        );
        $querymutasigudang->join('gudang_jadi_mutasi', 'gudang_jadi_mutasi_detail.no_mutasi', 'gudang_jadi_mutasi.no_mutasi');
        $querymutasigudang->join('produk', 'gudang_jadi_mutasi_detail.kode_produk', 'produk.kode_produk');
        $querymutasigudang->whereBetween('gudang_jadi_mutasi.tanggal', [$dari, $sampai]);
        $querymutasigudang->groupBy('gudang_jadi_mutasi_detail.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack');
        $query_gudang = $querymutasigudang->union($querysaldoawalgudang)->get();

        $data['rekapgudang'] = $query_gudang->groupBy('kode_produk', 'nama_produk')
            ->map(function ($item) {
                return [
                    'kode_produk' => $item->first()->kode_produk,
                    'nama_produk' => $item->first()->nama_produk,
                    'saldo_awal' => $item->sum('saldo_awal'),
                    'jml_fsthp' => $item->sum('jml_fsthp'),
                    'jml_repack' => $item->sum('jml_repack'),
                    'jml_lainlain_in' => $item->sum('jml_lainlain_in'),
                    'jml_surat_jalan' => $item->sum('jml_surat_jalan'),
                    'jml_reject' => $item->sum('jml_reject'),
                    'jml_lainlain_out' => $item->sum('jml_lainlain_out'),
                    'jml_mutasi' => $item->sum('jml_mutasi'),
                ];
            })
            ->sortBy('kode_produk')
            ->values()
            ->all();


        $querysaldoawal = DB::table('gudang_cabang_saldoawal_detail');
        $querysaldoawal->select(
            'gudang_cabang_saldoawal_detail.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pcs_pack',
            'gudang_cabang_saldoawal.kode_cabang',
            DB::raw("SUM(jumlah) as saldo_awal"),
            DB::raw("SUM(0) as pusat"),
            DB::raw("SUM(0) as transit_in"),
            DB::raw("SUM(0) as retur"),
            DB::raw("SUM(0) as hutang_kirim"),
            DB::raw("SUM(0) as pelunasan_ttr"),
            DB::raw("SUM(0) as penyesuaian_bad"),
            DB::raw("SUM(0) as repack"),
            DB::raw("SUM(0) as penyesuaian_in"),
            DB::raw("SUM(0) as penjualan"),
            DB::raw("SUM(0) as promosi"),
            DB::raw("SUM(0) as reject_pasar"),
            DB::raw("SUM(0) as reject_mobil"),
            DB::raw("SUM(0) as reject_gudang"),
            DB::raw("SUM(0) as transit_out"),
            DB::raw("SUM(0) as ttr"),
            DB::raw("SUM(0) as ganti_barang"),
            DB::raw("SUM(0) as pelunasan_hutangkirim"),
            DB::raw("SUM(0) as penyesuaian_out")
        );
        $querysaldoawal->join('gudang_cabang_saldoawal', 'gudang_cabang_saldoawal_detail.kode_saldo_awal', '=', 'gudang_cabang_saldoawal.kode_saldo_awal');
        $querysaldoawal->join('produk', 'gudang_cabang_saldoawal_detail.kode_produk', '=', 'produk.kode_produk');
        $querysaldoawal->where('kondisi', 'GS');
        $querysaldoawal->where('bulan', $request->bulan);
        $querysaldoawal->where('tahun', $request->tahun);
        $querysaldoawal->orderBy('kode_cabang');
        $querysaldoawal->groupBy('gudang_cabang_saldoawal_detail.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'gudang_cabang_saldoawal.kode_cabang');


        $querymutasi = DB::table('gudang_cabang_mutasi_detail');
        $querymutasi->select(
            'gudang_cabang_mutasi_detail.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pcs_pack',
            'gudang_cabang_mutasi.kode_cabang',
            DB::raw("SUM(0) as saldo_awal"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='SJ',gudang_cabang_mutasi_detail.jumlah,0)) as pusat"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='TI',gudang_cabang_mutasi_detail.jumlah,0)) as transit_in"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RT',gudang_cabang_mutasi_detail.jumlah,0)) as retur"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='HK',gudang_cabang_mutasi_detail.jumlah,0)) as hutang_kirim"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PT',gudang_cabang_mutasi_detail.jumlah,0)) as pelunasan_ttr"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PB',gudang_cabang_mutasi_detail.jumlah,0)) as penyesuaian_bad"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RK',gudang_cabang_mutasi_detail.jumlah,0)) as repack"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PY' AND in_out_good='I',gudang_cabang_mutasi_detail.jumlah,0)) as penyesuaian_in"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PJ',gudang_cabang_mutasi_detail.jumlah,0)) as penjualan"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PR',gudang_cabang_mutasi_detail.jumlah,0)) as promosi"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RP',gudang_cabang_mutasi_detail.jumlah,0)) as reject_pasar"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RM',gudang_cabang_mutasi_detail.jumlah,0)) as reject_mobil"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='RG',gudang_cabang_mutasi_detail.jumlah,0)) as reject_gudang"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='TO',gudang_cabang_mutasi_detail.jumlah,0)) as transit_out"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='TR',gudang_cabang_mutasi_detail.jumlah,0)) as ttr"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='GB',gudang_cabang_mutasi_detail.jumlah,0)) as ganti_barang"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PH',gudang_cabang_mutasi_detail.jumlah,0)) as pelunasan_hutangkirim"),
            DB::raw("SUM(IF(gudang_cabang_mutasi.jenis_mutasi='PY' AND in_out_good='O',gudang_cabang_mutasi_detail.jumlah,0)) as penyesuaian_out")
        );
        $querymutasi->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi');
        $querymutasi->join('produk', 'gudang_cabang_mutasi_detail.kode_produk', '=', 'produk.kode_produk');
        $querymutasi->whereNotNull('in_out_good');
        $querymutasi->whereBetween('gudang_cabang_mutasi.tanggal', [$dari, $sampai]);
        $querymutasi->orderBy('gudang_cabang_mutasi.kode_cabang', 'asc');
        $querymutasi->groupBy('gudang_cabang_mutasi_detail.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'gudang_cabang_mutasi.kode_cabang');
        // dd($querymutasi->get());
        $query = $querymutasi->unionAll($querysaldoawal);
        // Step 1: Eksekusi Query untuk mendapatkan hasil gabungan dari unionAll
        $datarekap = $query->get();

        // Step 2: Gabungkan data berdasarkan kode_cabang dan kode_produk
        $rekap = $datarekap->groupBy(function ($item) {
            // Menggabungkan berdasarkan kode_cabang dan kode_produk sebagai kunci
            return $item->kode_cabang . '-' . $item->kode_produk . '-' . $item->isi_pcs_dus . '-' . $item->isi_pcs_pack . '-' . $item->nama_produk;
        })->map(function ($items, $key) {
            // Step 3: Melakukan rekap dengan menghitung total dari setiap kolom yang ingin direkap
            return [
                'kode_cabang' => $items->first()->kode_cabang,
                'kode_produk' => $items->first()->kode_produk,
                'nama_produk' => $items->first()->nama_produk,
                'isi_pcs_dus' => $items->first()->isi_pcs_dus,
                'isi_pcs_pack' => $items->first()->isi_pcs_pack,
                'saldo_awal' => $items->sum('saldo_awal'),
                'pusat' => $items->sum('pusat'),
                'transit_in' => $items->sum('transit_in'),
                'retur' => $items->sum('retur'),
                'hutang_kirim' => $items->sum('hutang_kirim'),
                'pelunasan_ttr' => $items->sum('pelunasan_ttr'),
                'penyesuaian_bad' => $items->sum('penyesuaian_bad'),
                'repack' => $items->sum('repack'),
                'penyesuaian_in' => $items->sum('penyesuaian_in'),
                'penjualan' => $items->sum('penjualan'),
                'promosi' => $items->sum('promosi'),
                'reject_pasar' => $items->sum('reject_pasar'),
                'reject_mobil' => $items->sum('reject_mobil'),
                'reject_gudang' => $items->sum('reject_gudang'),
                'transit_out' => $items->sum('transit_out'),
                'ttr' => $items->sum('ttr'),
                'ganti_barang' => $items->sum('ganti_barang'),
                'pelunasan_hutangkirim' => $items->sum('pelunasan_hutangkirim'),
                'penyesuaian_out' => $items->sum('penyesuaian_out'),
            ];
        });

        // Step 4: Konversi hasil ke dalam array jika diperlukan
        $rekap = $rekap->sortBy(['kode_cabang', 'kode_produk'])->values()->toArray();

        $data['rekappersediaan'] = $rekap;
        return view('accounting.laporan.rekappersediaan_cetak', $data);
    }
}

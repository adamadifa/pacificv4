<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Costratio;
use App\Models\Detailbarangkeluargudangbahan;
use App\Models\Detailbarangkeluargudanglogistik;
use App\Models\Detailhargaawalhpp;
use App\Models\Detailhpp;
use App\Models\Detailmutasigudangcabang;
use App\Models\Detailmutasigudangjadi;
use App\Models\Detailmutasiproduksi;
use App\Models\Detailsaldoawalgudangcabang;
use App\Models\Detailsaldoawalgudangjadi;
use App\Models\Detailsaldoawalmutasiproduksi;
use App\Models\Produk;
use App\Models\Saldoawalgudangcabang;
use App\Models\User;
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
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Persediaan $request->dari-$request->sampai.xls");
        }
        return view('accounting.laporan.rekappersediaan_cetak', $data);
    }


    public function cetakrekapbj(Request $request)
    {
        $dari = $request->tahun . "-" . $request->bulan . "-01";
        $sampai = date("Y-m-t", strtotime($dari));
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;

        $cabang = Cabang::orderBy('kode_cabang')->get();
        $selectSaldocabang = [];
        $selectMutasicabang = [];
        $selectHargaawal = [];


        $selectColumsaldocabang = [];
        $selectColumnmutasicabang = [];
        $selectColumnhargaawal = [];
        foreach ($cabang as $c) {
            $selectSaldocabang[] = DB::raw("SUM(IF(gudang_cabang_saldoawal.kode_cabang = '$c->kode_cabang',jumlah,0)) as saldoawal_" . $c->kode_cabang);
            $selectMutasicabang[] = DB::raw("SUM(IF(gudang_cabang_mutasi.kode_cabang = '$c->kode_cabang' AND in_out_good='I',jumlah,0)) - SUM(IF(gudang_cabang_mutasi.kode_cabang = '$c->kode_cabang' AND in_out_good='O',jumlah,0)) as mutasi_" . $c->kode_cabang);
            $selectMutasicabang[] = DB::raw("SUM(IF(jenis_mutasi = 'SJ' AND gudang_cabang_mutasi.kode_cabang='$c->kode_cabang', jumlah, 0)) as pusat_" . $c->kode_cabang);
            $selectMutasicabang[] = DB::raw("SUM(IF(jenis_mutasi = 'TI' AND gudang_cabang_mutasi.kode_cabang='$c->kode_cabang', jumlah, 0)) as transit_in_" . $c->kode_cabang);
            $selectMutasicabang[] = DB::raw("SUM(IF(jenis_mutasi = 'RT' AND gudang_cabang_mutasi.kode_cabang='$c->kode_cabang', jumlah, 0)) as retur_" . $c->kode_cabang);
            $selectMutasicabang[] = DB::raw("SUM(IF(jenis_mutasi = 'PY' AND gudang_cabang_mutasi.kode_cabang='$c->kode_cabang' AND in_out_good = 'I' OR jenis_mutasi = 'HK' AND gudang_cabang_mutasi.kode_cabang='$c->kode_cabang' AND in_out_good = 'I'
            OR jenis_mutasi = 'PT' AND gudang_cabang_mutasi.kode_cabang='$c->kode_cabang' AND in_out_good = 'I', jumlah, 0)) as lainlain_" . $c->kode_cabang);
            $selectMutasicabang[] = DB::raw("SUM(IF(jenis_mutasi = 'RK' AND gudang_cabang_mutasi.kode_cabang='$c->kode_cabang', jumlah, 0)) as repack_" . $c->kode_cabang);


            $selectHargaawal[] = DB::raw("SUM(IF(lokasi = '$c->kode_cabang',harga_awal,0)) as hargaawal_" . $c->kode_cabang);

            $selectColumsaldocabang[] = "saldoawal_" . $c->kode_cabang;
            $selectColumnmutasicabang[] = "mutasi_" . $c->kode_cabang;
            $selectColumnmutasicabang[] = "pusat_" . $c->kode_cabang;
            $selectColumnmutasicabang[] = "transit_in_" . $c->kode_cabang;
            $selectColumnmutasicabang[] = "retur_" . $c->kode_cabang;
            $selectColumnmutasicabang[] = "lainlain_" . $c->kode_cabang;
            $selectColumnmutasicabang[] = "repack_" . $c->kode_cabang;

            $selectColumnhargaawal[] = "hargaawal_" . $c->kode_cabang;
        }

        //Saldo cabang
        $qsaldoawal = Detailsaldoawalgudangcabang::query();
        $qsaldoawal->select(
            'gudang_cabang_saldoawal_detail.kode_produk',
            ...$selectSaldocabang
        );
        $qsaldoawal->join('gudang_cabang_saldoawal', 'gudang_cabang_saldoawal_detail.kode_saldo_awal', '=', 'gudang_cabang_saldoawal.kode_saldo_awal');
        $qsaldoawal->where('bulan', $request->bulan);
        $qsaldoawal->where('tahun', $request->tahun);
        $qsaldoawal->where('kondisi', 'GS');
        $qsaldoawal->groupBy('gudang_cabang_saldoawal_detail.kode_produk');

        //Mutasi Cabang

        $qmutasicabang = Detailmutasigudangcabang::query();
        $qmutasicabang->select(
            'gudang_cabang_mutasi_detail.kode_produk',
            ...$selectMutasicabang
        );
        $qmutasicabang->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi');
        $qmutasicabang->whereBetween('gudang_cabang_mutasi.tanggal', [$dari, $sampai]);
        $qmutasicabang->groupBy('gudang_cabang_mutasi_detail.kode_produk');


        $qhpp = Detailhpp::query();
        $qhpp->select('accounting_hpp_detail.kode_produk', 'harga_hpp');
        $qhpp->join('accounting_hpp', 'accounting_hpp_detail.kode_hpp', 'accounting_hpp.kode_hpp');
        $qhpp->where('bulan', $request->bulan);
        $qhpp->where('tahun', $request->tahun);

        $qsaldoawalproduksi = Detailsaldoawalmutasiproduksi::query();
        $qsaldoawalproduksi->select('produksi_mutasi_saldoawal_detail.kode_produk', 'jumlah as saldoawal_produksi');
        $qsaldoawalproduksi->join('produksi_mutasi_saldoawal', 'produksi_mutasi_saldoawal_detail.kode_saldo_awal', '=', 'produksi_mutasi_saldoawal.kode_saldo_awal');
        $qsaldoawalproduksi->where('produksi_mutasi_saldoawal.bulan', $request->bulan);
        $qsaldoawalproduksi->where('produksi_mutasi_saldoawal.tahun', $request->tahun);


        $qmutasiproduksi = Detailmutasiproduksi::query();
        $qmutasiproduksi->select(
            'produksi_mutasi_detail.kode_produk',
            DB::raw("SUM(IF(produksi_mutasi.jenis_mutasi='BPBJ',jumlah,0)) as produksi_bpbj"),
            DB::raw("SUM(IF(produksi_mutasi.jenis_mutasi='FSTHP',jumlah,0)) as produksi_fsthp"),
        );
        $qmutasiproduksi->join('produksi_mutasi', 'produksi_mutasi_detail.no_mutasi', '=', 'produksi_mutasi.no_mutasi');
        $qmutasiproduksi->whereBetween('produksi_mutasi.tanggal_mutasi', [$dari, $sampai]);
        $qmutasiproduksi->groupBy('produksi_mutasi_detail.kode_produk');

        $qhargaawal = Detailhargaawalhpp::query();
        $qhargaawal->select(
            'accounting_hpp_hargaawal_detail.kode_produk',
            DB::raw("SUM(IF(lokasi='PRD',harga_awal,0)) as hargaawal_produksi"),
            DB::raw("SUM(IF(lokasi='GDG',harga_awal,0)) as hargaawal_gudang"),
            ...$selectHargaawal
        );
        $qhargaawal->join('accounting_hpp_hargaawal', 'accounting_hpp_hargaawal_detail.kode_hargaawal', 'accounting_hpp_hargaawal.kode_hargaawal');
        $qhargaawal->where('bulan', $request->bulan);
        $qhargaawal->where('tahun', $request->tahun);
        $qhargaawal->groupBy('accounting_hpp_hargaawal_detail.kode_produk');


        //Gudang Jadi Pusat

        $qsaldoawalgudangjadi = Detailsaldoawalgudangjadi::query();
        $qsaldoawalgudangjadi->select(
            'gudang_jadi_saldoawal_detail.kode_produk',
            'jumlah as saldoawal_gudangjadi'
        );
        $qsaldoawalgudangjadi->join('gudang_jadi_saldoawal', 'gudang_jadi_saldoawal_detail.kode_saldo_awal', '=', 'gudang_jadi_saldoawal.kode_saldo_awal');
        $qsaldoawalgudangjadi->where('gudang_jadi_saldoawal.bulan', $request->bulan);
        $qsaldoawalgudangjadi->where('gudang_jadi_saldoawal.tahun', $request->tahun);


        //Mutasi Gudang Pusat

        $qmutasigudangjadi = Detailmutasigudangjadi::query();
        $qmutasigudangjadi->select(
            'gudang_jadi_mutasi_detail.kode_produk',
            DB::raw("SUM(IF(jenis_mutasi = 'FS', jumlah, 0)) as gudangjadi_fsthp"),
            DB::raw("SUM(IF(jenis_mutasi = 'RP', jumlah, 0)) as gudangjadi_repack"),
            DB::raw("SUM(IF(jenis_mutasi = 'RJ', jumlah, 0)) as gudangjadi_reject"),
            DB::raw("SUM(IF(jenis_mutasi = 'LN' AND `in_out` = 'I', jumlah, 0)) as gudangjadi_lainlain_in"),
            DB::raw("SUM(IF(jenis_mutasi = 'LN' AND `in_out` = 'O', jumlah, 0)) as gudangjadi_lainlain_out"),
            DB::raw("SUM(IF(jenis_mutasi = 'SJ', jumlah, 0)) as gudangjadi_suratjalan")
        );
        $qmutasigudangjadi->join('gudang_jadi_mutasi', 'gudang_jadi_mutasi_detail.no_mutasi', '=', 'gudang_jadi_mutasi.no_mutasi');
        $qmutasigudangjadi->whereBetween('gudang_jadi_mutasi.tanggal', [$dari, $sampai]);
        $qmutasigudangjadi->groupBy('gudang_jadi_mutasi_detail.kode_produk');

        $query = Produk::query();
        $query->select(
            'produk.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'harga_hpp',
            'saldoawal_produksi',
            'saldoawal_gudangjadi',
            'produksi_bpbj',
            'produksi_fsthp',
            'hargaawal_produksi',
            'hargaawal_gudang',
            'gudangjadi_fsthp',
            'gudangjadi_repack',
            'gudangjadi_reject',
            'gudangjadi_lainlain_in',
            'gudangjadi_lainlain_out',
            'gudangjadi_suratjalan',
            ...$selectColumsaldocabang,
            ...$selectColumnmutasicabang,
            ...$selectColumnhargaawal
        );
        $query->leftjoinSub($qsaldoawal, 'saldoawalcabang', function ($join) {
            $join->on('produk.kode_produk', '=', 'saldoawalcabang.kode_produk');
        });

        $query->leftjoinSub($qmutasicabang, 'mutasicabang', function ($join) {
            $join->on('produk.kode_produk', '=', 'mutasicabang.kode_produk');
        });

        $query->leftjoinSub($qhpp, 'hpp', function ($join) {
            $join->on('produk.kode_produk', '=', 'hpp.kode_produk');
        });

        $query->leftjoinSub($qsaldoawalproduksi, 'saldoawalproduksi', function ($join) {
            $join->on('produk.kode_produk', '=', 'saldoawalproduksi.kode_produk');
        });

        $query->leftjoinSub($qmutasiproduksi, 'mutasiproduksi', function ($join) {
            $join->on('produk.kode_produk', '=', 'mutasiproduksi.kode_produk');
        });

        $query->leftjoinSub($qhargaawal, 'hargaawal', function ($join) {
            $join->on('produk.kode_produk', '=', 'hargaawal.kode_produk');
        });

        $query->leftjoinSub($qsaldoawalgudangjadi, 'saldoawalgudangjadi', function ($join) {
            $join->on('produk.kode_produk', '=', 'saldoawalgudangjadi.kode_produk');
        });

        $query->leftjoinSub($qmutasigudangjadi, 'mutasigudangjadi', function ($join) {
            $join->on('produk.kode_produk', '=', 'mutasigudangjadi.kode_produk');
        });

        $query->where('nama_produk', '!=', 'undifined');
        $query->orderBy('produk.kode_produk');
        $rekapbj = $query->get();

        $data['rekapbj'] = $rekapbj;
        $data['cabang'] = $cabang;
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap BJ $request->dari-$request->sampai.xls");
        }
        return view('accounting.laporan.rekapbj_cetak', $data);
    }


    public function cetakcostratio(Request $request)
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

        $dari = $request->tahun . '-' . $request->bulan . '-01';
        $sampai = date('Y-m-t', strtotime($dari));
        if (!empty($kode_cabang)) {
            $cabang = Cabang::where('kode_cabang', $kode_cabang)->get();
        } else {
            $cabang =  Cabang::orderBy('kode_cabang')->get();
        }

        $selectColumncabang = [];
        $selectColumnLogistik = [];
        foreach ($cabang as $c) {
            $selectColumncabang[] = DB::raw("SUM(IF(accounting_costratio.kode_cabang = '$c->kode_cabang',jumlah,0)) as jmlbiaya_" . $c->kode_cabang);
            $selectColumnlogistik[] = DB::raw("SUM(IF(gudang_logistik_barang_keluar_detail.kode_cabang='$c->kode_cabang', jumlah *
            CASE
                WHEN sa.hargasaldoawal IS NULL THEN gm.hargapemasukan
                WHEN gm.hargapemasukan IS NULL THEN sa.hargasaldoawal
                ELSE (sa.totalsa + gm.totalpemasukan) / (sa.qtysaldoawal + gm.qtypemasukan)
            END, 0)) as logistik_" . $c->kode_cabang);
        }
        $query = Costratio::query();
        $query->select(
            'accounting_costratio.kode_akun',
            'nama_akun',
            'coa.kode_kategori',
            'nama_kategori',
            ...$selectColumncabang
        );
        $query->leftJoin('coa', 'accounting_costratio.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoin('coa_kategori', 'coa.kode_kategori', '=', 'coa_kategori.kode_kategori');
        $query->whereBetween('tanggal', [$dari, $sampai]);
        if (!empty($kode_cabang)) {
            $query->where('kode_cabang', $kode_cabang);
        }

        if ($request->formatlaporan == 2) {
            $query->orderBy('coa.kode_kategori', 'asc');
        }

        $query->groupBy('accounting_costratio.kode_akun', 'nama_akun', 'coa.kode_kategori', 'nama_kategori');
        $query->orderBy('coa.kode_akun');
        $costratio = $query->get();

        $qlogistik = Detailbarangkeluargudanglogistik::query();
        $qlogistik->select(
            DB::raw(" SUM(IF(gudang_logistik_barang_keluar_detail.kode_cabang IS NOT NULL,jumlah *
            CASE
            WHEN sa.hargasaldoawal IS NULL THEN gm.hargapemasukan
            WHEN gm.hargapemasukan IS NULL THEN sa.hargasaldoawal
            ELSE
            (sa.totalsa + gm.totalpemasukan) / (sa.qtysaldoawal + gm.qtypemasukan)
            END ,0)) as total_logistik"),
            ...$selectColumnlogistik

        );
        $qlogistik->join('gudang_logistik_barang_keluar', 'gudang_logistik_barang_keluar_detail.no_bukti', '=', 'gudang_logistik_barang_keluar.no_bukti');
        $qlogistik->join('pembelian_barang', 'gudang_logistik_barang_keluar_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $qlogistik->leftJoin(
            DB::raw("(
                SELECT gudang_logistik_saldoawal_detail.kode_barang,SUM(harga) AS hargasaldoawal,
                SUM( jumlah ) AS qtysaldoawal,
                SUM(harga*jumlah) AS totalsa FROM gudang_logistik_saldoawal_detail
                INNER JOIN gudang_logistik_saldoawal ON gudang_logistik_saldoawal_detail.kode_saldo_awal=gudang_logistik_saldoawal.kode_saldo_awal
                WHERE bulan = '$request->bulan' AND tahun = '$request->tahun'
                GROUP BY kode_barang
            ) sa"),
            function ($join) {
                $join->on('gudang_logistik_barang_keluar_detail.kode_barang', '=', 'sa.kode_barang');
            }
        );

        $qlogistik->leftJoin(
            DB::raw("(
                SELECT gudang_logistik_barang_masuk_detail.kode_barang,
                SUM(penyesuaian) AS penyesuaian,
                SUM( jumlah ) AS qtypemasukan,
                SUM( harga ) AS hargapemasukan,
                SUM(harga * jumlah) AS totalpemasukan FROM
                gudang_logistik_barang_masuk_detail
                INNER JOIN gudang_logistik_barang_masuk ON gudang_logistik_barang_masuk_detail.no_bukti = gudang_logistik_barang_masuk.no_bukti
                WHERE tanggal BETWEEN '$dari' AND '$sampai'
                GROUP BY kode_barang
            ) gm"),
            function ($join) {
                $join->on('gudang_logistik_barang_keluar_detail.kode_barang', '=', 'gm.kode_barang');
            }
        );

        $qlogistik->where('pembelian_barang.kode_kategori', 'K001');
        $qlogistik->whereBetween('tanggal', [$dari, $sampai]);
        $logistik = $qlogistik->first();


        $qbahan = Detailbarangkeluargudangbahan::query();
        $qbahan->select(DB::raw("SUM(
                CASE
                WHEN satuan = 'KG' THEN qty_berat * 1000
                WHEN satuan = 'LITER' THEN qty_berat * 1000 * IFNULL((SELECT harga FROM harga_minyak WHERE bulan ='$request->bulan' AND tahun = '$request->tahun'),0)
                ELSE qty_unit
                END
                *
                CASE
                WHEN satuan ='KG' THEN (harga +totalharga + IF(qtypengganti2=0,(qtypengganti2*1000) * 0,( (qtypengganti2 *1000) * (IF(qtypemb2=0,(harga / (qtyberatsa *1000)),totalharga / (qtypemb2*1000))))) + IF(qtylainnya2=0,(qtylainnya2*1000) * 0,( (qtylainnya2 *1000) * (IF(qtypemb2=0,(harga / (qtyberatsa *1000)),totalharga / (qtypemb2*1000)))))) / ( (qtyberatsa*1000) + (qtypemb2 * 1000) + (qtylainnya2*1000) + (qtypengganti2*1000))
                ELSE
                (harga + totalharga + IF(qtylainnya1=0,qtylainnya1*0,qtylainnya1 * IF(qtylainnya1=0,0,IF(qtypemb1=0,harga/qtyunitsa,totalharga/qtypemb1 ))) + IF(qtypengganti1=0,qtypengganti1*0,qtypengganti1 * IF(qtypengganti1=0,0,IF(qtypemb1=0,harga/qtyunitsa,totalharga/qtypemb1 )))) / (qtyunitsa + qtypemb1 + qtylainnya1 + qtypengganti1)
                END
            ) as total"));
        $qbahan->join('pembelian_barang', 'gudang_bahan_barang_keluar_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $qbahan->join('gudang_bahan_barang_keluar', 'gudang_bahan_barang_keluar_detail.no_bukti', '=', 'gudang_bahan_barang_keluar.no_bukti');
        $qbahan->leftJoin(
            DB::raw("(
                SELECT
                gudang_bahan_barang_masuk_detail.kode_barang,
                SUM( IF( kode_asal_barang = 'PMB' , qty_unit ,0 )) AS qtypemb1,
                SUM( IF( kode_asal_barang = 'LNY' , qty_unit ,0 )) AS qtylainnya1,
                SUM( IF( kode_asal_barang = 'RTP' , qty_unit ,0 )) AS qtypengganti1,

                SUM( IF( kode_asal_barang = 'PMB' , qty_berat ,0 )) AS qtypemb2,
                SUM( IF( kode_asal_barang = 'LNY' , qty_berat ,0 )) AS qtylainnya2,
                SUM( IF( kode_asal_barang = 'RTP' , qty_berat ,0 )) AS qtypengganti2,
                SUM( (IF( kode_asal_barang = 'PMB' , qty_berat ,0 )) + (IF( kode_asal_barang = 'LNY' , qty_berat ,0 ))) AS pemasukanqtyberat
                FROM
                gudang_bahan_barang_masuk_detail
                INNER JOIN gudang_bahan_barang_masuk ON gudang_bahan_barang_masuk_detail.no_bukti = gudang_bahan_barang_masuk.no_bukti
                WHERE gudang_bahan_barang_masuk.tanggal BETWEEN '$dari' AND '$sampai'
                GROUP BY gudang_bahan_barang_masuk_detail.kode_barang
            ) gm"),
            function ($join) {
                $join->on('gudang_bahan_barang_keluar_detail.kode_barang', '=', 'gm.kode_barang');
            }
        );

        $qbahan->leftJoin(
            DB::raw("(
                SELECT SUM((jumlah*harga)+penyesuaian) as totalharga,kode_barang
                FROM pembelian_detail
                INNER JOIN pembelian ON pembelian_detail.no_bukti = pembelian.no_bukti
                WHERE pembelian.tanggal BETWEEN '$dari' AND '$sampai'
                GROUP BY kode_barang
            ) dp"),
            function ($join) {
                $join->on('gudang_bahan_barang_keluar_detail.kode_barang', '=', 'dp.kode_barang');
            }
        );

        $qbahan->leftJoin(
            DB::raw("(
                SELECT kode_barang,harga
                FROM gudang_bahan_saldoawal_harga_detail
                INNER JOIN gudang_bahan_saldoawal_harga ON gudang_bahan_saldoawal_harga_detail.kode_saldo_awal = gudang_bahan_saldoawal_harga.kode_saldo_awal
                WHERE bulan = '$request->bulan' AND tahun = '$request->tahun'
                GROUP BY kode_barang,harga
            ) hrgsa"),
            function ($join) {
                $join->on('gudang_bahan_barang_keluar_detail.kode_barang', '=', 'hrgsa.kode_barang');
            }
        );

        $qbahan->leftJoin(
            DB::raw("(
                SELECT gudang_bahan_saldoawal_detail.kode_barang,
                SUM( qty_unit ) AS qtyunitsa,
                SUM( qty_berat ) AS qtyberatsa
                FROM gudang_bahan_saldoawal_detail
                INNER JOIN gudang_bahan_saldoawal ON gudang_bahan_saldoawal_detail.kode_saldo_awal=gudang_bahan_saldoawal.kode_saldo_awal
                WHERE bulan = '$request->bulan' AND tahun = '$request->tahun' GROUP BY gudang_bahan_saldoawal_detail.kode_barang
            ) sa"),
            function ($join) {
                $join->on('gudang_bahan_barang_keluar_detail.kode_barang', '=', 'sa.kode_barang');
            }
        );

        $qbahan->whereBetween('gudang_bahan_barang_keluar.tanggal', [$dari, $sampai]);
        $qbahan->where('gudang_bahan_barang_keluar.kode_jenis_pengeluaran', 'CBG');
        $bahan = $qbahan->first();


        dd($bahan);

        $data['logistik'] = $logistik;
        $data['costratio'] = $costratio;
        $data['cabang'] = $cabang;
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=COSTRATIO $request->dari-$request->sampai.xls");
        }

        $data['cabang'] = $cabang;
        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        $data['costratio'] = $costratio;
        return view('accounting.laporan.costratio_cetak', $data);
    }
}

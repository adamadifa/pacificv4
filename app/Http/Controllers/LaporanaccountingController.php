<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Coa;
use App\Models\Costratio;
use App\Models\Detailbarangkeluargudangbahan;
use App\Models\Detailbarangkeluargudanglogistik;
use App\Models\Detailhargaawalhpp;
use App\Models\Detailhpp;
use App\Models\Detailmutasigudangcabang;
use App\Models\Detailmutasigudangjadi;
use App\Models\Detailmutasiproduksi;
use App\Models\Detailpembelian;
use App\Models\Detailpenjualan;
use App\Models\Detailretur;
use App\Models\Detailsaldoawalbukubesar;
use App\Models\Detailsaldoawalgudangcabang;
use App\Models\Detailsaldoawalgudangjadi;
use App\Models\Detailsaldoawalmutasiproduksi;
use App\Models\Detailsaldoawalpiutangpelanggan;
use App\Models\Historibayarpenjualan;
use App\Models\Jurnalkoreksi;
use App\Models\Jurnalumum;
use App\Models\Kaskecil;
use App\Models\Ledger;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Saldoawalgudangcabang;
use App\Models\Saldoawalkaskecil;
use App\Models\Saldoawalledger;
use App\Models\Saldoawalpiutangpelanggan;
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
        $data['coa'] = Coa::orderby('kode_akun')
            ->whereNotIn('kode_akun', ['0-0000', '1'])->get();
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
        $selectColumnbahan = [];
        $selectColumnbruto = [];
        $selectColumnpotongan = [];
        $selectColumnretur = [];
        $selectColumnsaldoawalpiutang = [];
        $selectColumnpenjualan = [];

        foreach ($cabang as $c) {
            $selectColumncabang[] = DB::raw("SUM(IF(accounting_costratio.kode_cabang = '$c->kode_cabang',jumlah,0)) as jmlbiaya_" . $c->kode_cabang);
            $selectColumnLogistik[] = DB::raw("SUM(IF(gudang_logistik_barang_keluar_detail.kode_cabang='$c->kode_cabang', jumlah *
            CASE
                WHEN sa.hargasaldoawal IS NULL THEN gm.hargapemasukan
                WHEN gm.hargapemasukan IS NULL THEN sa.hargasaldoawal
                ELSE (sa.totalsa + gm.totalpemasukan) / (sa.qtysaldoawal + gm.qtypemasukan)
            END, 0)) as logistik_" . $c->kode_cabang);


            $selectColumnbahan[] = DB::raw("SUM(IF(gudang_bahan_barang_keluar.kode_cabang='$c->kode_cabang',
            CASE
            WHEN satuan = 'KG' THEN qty_berat * 1000
            WHEN satuan = 'LITER' THEN qty_berat * 1000 * IFNULL((SELECT harga FROM harga_minyak WHERE bulan ='$request->bulan' AND tahun = '$request->tahun' AND kode_cabang = '$c->kode_cabang'),0)
            ELSE qty_unit
            END
            *
            CASE
            WHEN satuan ='KG' THEN (harga +totalharga + IF(qtypengganti2=0,(qtypengganti2*1000) * 0,( (qtypengganti2 *1000) * (IF(qtypemb2=0,(harga / (qtyberatsa *1000)),totalharga / (qtypemb2*1000))))) + IF(qtylainnya2=0,(qtylainnya2*1000) * 0,( (qtylainnya2 *1000) * (IF(qtypemb2=0,(harga / (qtyberatsa *1000)),totalharga / (qtypemb2*1000)))))) / ( (qtyberatsa*1000) + (qtypemb2 * 1000) + (qtylainnya2*1000) + (qtypengganti2*1000))
            ELSE
            (harga + totalharga + IF(qtylainnya1=0,qtylainnya1*0,qtylainnya1 * IF(qtylainnya1=0,0,IF(qtypemb1=0,harga/qtyunitsa,totalharga/qtypemb1 ))) + IF(qtypengganti1=0,qtypengganti1*0,qtypengganti1 * IF(qtypengganti1=0,0,IF(qtypemb1=0,harga/qtyunitsa,totalharga/qtypemb1 )))) / (qtyunitsa + qtypemb1 + qtylainnya1 + qtypengganti1)
            END,0)
            ) as bahan_" . $c->kode_cabang);


            $selectColumnbruto[] =  DB::raw("SUM(IF(salesman.kode_cabang='$c->kode_cabang' AND produk.kode_kategori_produk='P01',subtotal,0)) as bruto_aida_" . $c->kode_cabang);
            $selectColumnbruto[] =  DB::raw("SUM(IF(salesman.kode_cabang='$c->kode_cabang' AND produk.kode_kategori_produk='P02',subtotal,0)) as bruto_swan_" . $c->kode_cabang);
            $selectColumnpotongan[] =  DB::raw("SUM(IF(salesman.kode_cabang='$c->kode_cabang',(potongan_aida + potis_aida + peny_aida),0)) as potongan_aida_" . $c->kode_cabang);
            $selectColumnpotongan[] =  DB::raw("SUM(IF(salesman.kode_cabang='$c->kode_cabang',(potongan_swan + potongan_sp + potongan_sambal + potongan_stick + potis_swan + potis_stick + peny_swan + peny_stick),0)) as potongan_swan_" . $c->kode_cabang);
            $selectColumnpotongan[] =  DB::raw("SUM(IF(salesman.kode_cabang='$c->kode_cabang',ppn,0)) as ppn_" . $c->kode_cabang);
            $selectColumnretur[] =  DB::raw("SUM(IF(salesman.kode_cabang='$c->kode_cabang' AND produk.kode_kategori_produk='P01',subtotal,0)) as retur_aida_" . $c->kode_cabang);
            $selectColumnretur[] =  DB::raw("SUM(IF(salesman.kode_cabang='$c->kode_cabang' AND produk.kode_kategori_produk='P02',subtotal,0)) as retur_swan_" . $c->kode_cabang);


            $selectColumnsaldoawalpiutang[] = DB::raw("SUM(IF(salesman.kode_cabang = '$c->kode_cabang',
                IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)-

                IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
                INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
                WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF'
                AND marketing_retur.tanggal BETWEEN '$dari' AND '$sampai'),0) -

                IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar
                WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur
                AND marketing_penjualan_historibayar.tanggal BETWEEN '$dari' AND '$sampai'),0)
                ,0)) as piutang_" . $c->kode_cabang);

            $selectColumnpenjualan[] = DB::raw("SUM(IF(salesman.kode_cabang = '$c->kode_cabang',
                    IFNULL((SELECT SUM(subtotal)
                    FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur),0) -
                    penyesuaian - potongan - potongan_istimewa + ppn -
                    IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
                    INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
                    WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur
                    AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$dari' AND '$sampai'),0) -
                    IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar
                    WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur
                    AND marketing_penjualan_historibayar.tanggal BETWEEN '$dari' AND '$sampai'),0)
                 ,0)) as penjualan_" . $c->kode_cabang);
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
            ...$selectColumnLogistik

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
            ) as total"), ...$selectColumnbahan);
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


        //Penjualan

        $qpenjualanbruto = Detailpenjualan::query();
        $qpenjualanbruto->select(
            DB::raw("SUM(IF(kode_kategori_produk='P01',subtotal,0)) as total_bruto_aida"),
            DB::raw("SUM(IF(kode_kategori_produk='P02',subtotal,0)) as total_bruto_swan"),
            ...$selectColumnbruto
        );
        $qpenjualanbruto->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $qpenjualanbruto->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $qpenjualanbruto->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qpenjualanbruto->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $qpenjualanbruto->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai]);
        $qpenjualanbruto->where('status_batal', 0);
        $penjualanbruto = $qpenjualanbruto->first();


        $qpenjualanpotongan = Penjualan::query();
        $qpenjualanpotongan->select(
            DB::raw("SUM(ppn) as ppn"),
            DB::raw("SUM(potongan_aida + potis_aida + peny_aida) as potongan_aida"),
            DB::raw("SUM(potongan_swan + potongan_sp + potongan_sambal + potongan_stick + potis_swan + potis_stick + peny_swan + peny_stick) as potongan_swan"),
            ...$selectColumnpotongan
        );
        $qpenjualanpotongan->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $qpenjualanpotongan->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai]);
        $qpenjualanpotongan->where('status_batal', 0);
        $penjualanpotongan = $qpenjualanpotongan->first();



        //Retur

        $qretur = Detailretur::query();
        $qretur->select(
            DB::raw("SUM(IF(kode_kategori_produk='P01',subtotal,0)) as total_retur_aida"),
            DB::raw("SUM(IF(kode_kategori_produk='P02',subtotal,0)) as total_retur_swan"),
            ...$selectColumnretur
        );
        $qretur->join('produk_harga', 'marketing_retur_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $qretur->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $qretur->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $qretur->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qretur->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $qretur->whereBetween('marketing_retur.tanggal', [$dari, $sampai]);
        $qretur->where('jenis_retur', 'PF');
        $retur = $qretur->first();

        // dd($retur);


        //Piutang
        // $saldoawal = Saldoawalpiutangpelanggan::where('bulan', $bulan)->where('tahun', $tahun)->first();
        $saldoawal = Saldoawalpiutangpelanggan::where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)->first();
        $saldoawal_date = $saldoawal->tanggal;

        $saldoawal_enddate = date('Y-m-t', strtotime($saldoawal_date));
        // dd($saldoawal->kode_saldo_awal);
        $querysaldoawal = Detailsaldoawalpiutangpelanggan::query();
        $querysaldoawal->select(
            DB::raw("SUM(
             IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)-
             IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
             INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
             WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF'
             AND marketing_retur.tanggal BETWEEN '$dari' AND '$sampai'),0) -
             IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar
             WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur
             AND marketing_penjualan_historibayar.tanggal BETWEEN '$dari' AND '$sampai'),0)) as saldo_awal_piutang"),
            ...$selectColumnsaldoawalpiutang
        );
        $querysaldoawal->join('marketing_saldoawal_piutang', 'marketing_saldoawal_piutang_detail.kode_saldo_awal', '=', 'marketing_saldoawal_piutang.kode_saldo_awal');
        $querysaldoawal->join('marketing_penjualan', 'marketing_saldoawal_piutang_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $querysaldoawal->leftJoin(
            DB::raw("(
                  SELECT
                     marketing_penjualan.no_faktur,
                     IF( salesbaru IS NULL, marketing_penjualan.kode_salesman, salesbaru ) AS kode_salesman_baru,
                     IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS kode_cabang_baru
                 FROM
                     marketing_penjualan
                 INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                 LEFT JOIN (
                 SELECT
                     no_faktur,
                     marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                     salesman.kode_cabang AS cabangbaru
                 FROM
                     marketing_penjualan_movefaktur
                     INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                 WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$sampai'
                 ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
             ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $querysaldoawal->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');

        // $querysaldoawal->where('bulan', $bulan);
        // $querysaldoawal->where('tahun', $tahun);
        $querysaldoawal->where('marketing_saldoawal_piutang.kode_saldo_awal', $saldoawal->kode_saldo_awal);

        $querysaldoawal->whereRaw("IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)- IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
             INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$dari' AND '$sampai'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur  AND marketing_penjualan_historibayar.tanggal BETWEEN '$dari' AND '$sampai'),0) != 0");
        $querysaldoawal->whereRaw("datediff('$sampai', marketing_penjualan.tanggal) > 31");

        $saldoawalpiutang = $querysaldoawal->first();



        $querypenjualan = Penjualan::query();
        $querypenjualan->select(

            DB::raw("SUM(
            IFNULL((SELECT SUM(subtotal)
            FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur),0) -
            penyesuaian - potongan - potongan_istimewa + ppn -
            IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
            WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur
            AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$dari' AND '$sampai'),0) -
            IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar
            WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur
            AND marketing_penjualan_historibayar.tanggal BETWEEN '$dari' AND '$sampai'),0)
         ) as total_penjualan"),

            ...$selectColumnpenjualan

        );
        $querypenjualan->leftJoin(
            DB::raw("(
             SELECT
                 marketing_penjualan.no_faktur,
                 IF( salesbaru IS NULL, marketing_penjualan.kode_salesman, salesbaru ) AS kode_salesman_baru,
                 IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS kode_cabang_baru
             FROM
                 marketing_penjualan
             INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
             LEFT JOIN (
             SELECT
                 no_faktur,
                 marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                 salesman.kode_cabang AS cabangbaru
             FROM
                 marketing_penjualan_movefaktur
                 INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
             WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$sampai'
             ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
         ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $querypenjualan->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $querypenjualan->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai]);
        $querypenjualan->where('jenis_transaksi', 'K');
        $querypenjualan->where('status_batal', 0);
        $querypenjualan->whereRaw("datediff('$sampai', marketing_penjualan.tanggal) > 31");

        $penjualan = $querypenjualan->first();

        $data['penjualan'] = $penjualan;
        $data['saldoawalpiutang'] = $saldoawalpiutang;
        $data['penjualanbruto'] = $penjualanbruto;
        $data['penjualanpotongan'] = $penjualanpotongan;
        $data['retur'] = $retur;
        $data['logistik'] = $logistik;
        $data['bahan'] = $bahan;
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
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Cost Ratio.xls");
        }
        if ($request->formatlaporan == 2) {
            return view('accounting.laporan.costratio2_cetak', $data);
        } else {

            return view('accounting.laporan.costratio_cetak', $data);
        }
    }

    public function cetakjurnalumum(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);

        $query =  Jurnalumum::query();
        $query->join('coa', 'accounting_jurnalumum.kode_akun', '=', 'coa.kode_akun');
        $query->whereBetween('tanggal', [$request->dari, $request->sampai]);

        if ($user->hasRole('general affair') || $user->hasRole('manager general affair')) {
            $query->where('kode_dept', 'GAF');
        }

        $query->orderBy('tanggal');
        $query->orderBy('kode_ju');
        $jurnalumum = $query->get();

        $data['jurnalumum'] = $jurnalumum;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Jurnal Umum.xls");
        }
        return view('accounting.laporan.jurnalumum_cetak', $data);
    }

    public function cetakbukubesar(Request $request)
    {
        //Saldo Awal
        $bulan = !empty($request->dari) ? date('m', strtotime($request->dari)) : '';
        $tahun = !empty($request->dari) ? date('Y', strtotime($request->dari)) : '';
        $start_date = $tahun . "-" . $bulan . "-01";



        $saldoawal = Detailsaldoawalbukubesar::query();

        $saldoawal->join('bukubesar_saldoawal', 'bukubesar_saldoawal.kode_saldo_awal', '=', 'bukubesar_saldoawal_detail.kode_saldo_awal');
        $saldoawal->join('coa', 'bukubesar_saldoawal_detail.kode_akun', '=', 'coa.kode_akun');
        $saldoawal->select(
            'bukubesar_saldoawal_detail.kode_akun',
            'coa.jenis_akun',
            'nama_akun',

            // Set tanggal 1 pada bulan yang dipilih sebagai default tanggal
            DB::raw("CONCAT('$tahun-$bulan-01') as tanggal"),
            'bukubesar_saldoawal_detail.kode_saldo_awal as no_bukti',
            DB::raw("'SALDO AWAL' AS sumber"),
            DB::raw("'Saldo Awal' as keterangan"),
            // 'bukubesar_saldoawal_detail.jumlah as jml_kredit',


            DB::raw('IF(coa.jenis_akun ="1",bukubesar_saldoawal_detail.jumlah,0) as jml_kredit'),
            DB::raw('IF(coa.jenis_akun !="1" || coa.jenis_akun IS NULL,bukubesar_saldoawal_detail.jumlah,0) as jml_debet'),
            DB::raw('0 as urutan')
        );
        $saldoawal->where('bukubesar_saldoawal.bulan', $bulan);
        $saldoawal->where('bukubesar_saldoawal.tahun', $tahun);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $saldoawal->whereBetween('bukubesar_saldoawal_detail.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $saldoawal->orderBy('bukubesar_saldoawal_detail.kode_akun');


        // ->get()->toArray();
        // Mengubah $saldo_awal_ledger menjadi koleksi
        $saldoawalCollection = collect($saldoawal);
        // dd($saldoawalCollection);
        //Ledger BANK
        $ledger = Ledger::query();
        $ledger->select(
            'bank.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_ledger.tanggal',
            'keuangan_ledger.no_bukti',
            DB::raw('CONCAT_WS(" - ", bank.nama_bank, bank.no_rekening) AS sumber'),
            'keuangan_ledger.keterangan',
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_debet'),
            DB::raw('IF(coa.jenis_akun="1" AND debet_kredit="D",1,2) as urutan')
        );
        $ledger->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
        $ledger->join('coa', 'bank.kode_akun', '=', 'coa.kode_akun');


        $ledger->whereBetween('keuangan_ledger.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $ledger->whereBetween('bank.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $ledger->orderBy('bank.kode_akun');
        $ledger->orderBy('tanggal');
        $ledger->orderBy('keuangan_ledger.no_bukti');


        $ledger_transaksi = Ledger::query();
        $ledger_transaksi->select(
            'keuangan_ledger.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_ledger.tanggal',
            'keuangan_ledger.no_bukti',
            DB::raw('CONCAT_WS(" - ", bank.nama_bank, bank.no_rekening) AS sumber'),
            'keuangan_ledger.keterangan',
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_debet'),
            DB::raw('IF((coa.jenis_akun = "1" AND debet_kredit = "K") OR ((coa.jenis_akun = "1" OR coa.jenis_akun IS NULL) AND debet_kredit = "D"), 1, 2) as urutan')
        );
        $ledger_transaksi->whereBetween('keuangan_ledger.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $ledger_transaksi->whereBetween('keuangan_ledger.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $ledger_transaksi->join('coa', 'keuangan_ledger.kode_akun', '=', 'coa.kode_akun');
        $ledger_transaksi->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
        $ledger_transaksi->orderBy('keuangan_ledger.kode_akun');
        $ledger_transaksi->orderBy('keuangan_ledger.tanggal');
        $ledger_transaksi->orderBy('keuangan_ledger.no_bukti');


        //Pembelian

        $pembelian = Detailpembelian::query();
        $pembelian->select(
            'pembelian_detail.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'pembelian.tanggal',
            'pembelian.no_bukti',
            DB::raw("'PEMBELIAN' AS sumber"),
            DB::raw('IF(pembelian_detail.kode_transaksi="PNJ",pembelian_detail.keterangan_penjualan,CONCAT(pembelian_barang.nama_barang, " - ", COALESCE(pembelian_detail.keterangan, ""))) as keterangan'),
            DB::raw('IF(pembelian_detail.kode_transaksi="PNJ",pembelian_detail.jumlah * harga + penyesuaian,0) as jml_kredit'),
            DB::raw('IF(pembelian_detail.kode_transaksi="PMB",pembelian_detail.jumlah * harga + penyesuaian,0) as jml_debet'),
            DB::raw('IF(pembelian_detail.kode_transaksi="PMB",2,1) as urutan')
        );
        $pembelian->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $pembelian->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $pembelian->join('coa', 'pembelian_detail.kode_akun', '=', 'coa.kode_akun');
        $pembelian->whereBetween('pembelian.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $pembelian->whereBetween('pembelian_detail.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $pembelian->orderBy('pembelian_detail.kode_akun');
        $pembelian->orderBy('pembelian.tanggal');
        $pembelian->orderBy('pembelian.no_bukti');


        //JURNAL UMUM

        $jurnalumum = Jurnalumum::query();
        $jurnalumum->select(
            'accounting_jurnalumum.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'accounting_jurnalumum.tanggal',
            'accounting_jurnalumum.kode_ju as no_bukti',
            DB::raw("'JURNAL UMUM' AS sumber"),
            'accounting_jurnalumum.keterangan',
            DB::raw('IF(accounting_jurnalumum.debet_kredit="K",accounting_jurnalumum.jumlah,0) as jml_kredit'),
            DB::raw('IF(accounting_jurnalumum.debet_kredit="D",accounting_jurnalumum.jumlah,0) as jml_debet'),
            DB::raw('IF(accounting_jurnalumum.debet_kredit="D",2,1) as urutan')
        );
        $jurnalumum->whereBetween('accounting_jurnalumum.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $jurnalumum->whereBetween('accounting_jurnalumum.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $jurnalumum->join('coa', 'accounting_jurnalumum.kode_akun', '=', 'coa.kode_akun');

        $jurnalumum->orderBy('accounting_jurnalumum.kode_akun');
        $jurnalumum->orderBy('accounting_jurnalumum.tanggal');
        $jurnalumum->orderBy('accounting_jurnalumum.kode_ju');



        //JURNAL Koreksi

        $jurnalkoreksi = Jurnalkoreksi::query();
        $jurnalkoreksi->select(
            'pembelian_jurnalkoreksi.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'pembelian_jurnalkoreksi.tanggal',
            'pembelian_jurnalkoreksi.no_bukti',
            DB::raw("'JURNAL KOREKSI' AS sumber"),
            'pembelian_jurnalkoreksi.keterangan',
            DB::raw('IF(pembelian_jurnalkoreksi.debet_kredit="K",pembelian_jurnalkoreksi.jumlah*harga,0) as jml_kredit'),
            DB::raw('IF(pembelian_jurnalkoreksi.debet_kredit="D",pembelian_jurnalkoreksi.jumlah*harga,0) as jml_debet'),
            DB::raw('IF(pembelian_jurnalkoreksi.debet_kredit="D",2,1) as urutan')
        );
        $jurnalkoreksi->whereBetween('pembelian_jurnalkoreksi.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $jurnalkoreksi->whereBetween('pembelian_jurnalkoreksi.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $jurnalkoreksi->join('coa', 'pembelian_jurnalkoreksi.kode_akun', '=', 'coa.kode_akun');

        $jurnalkoreksi->orderBy('pembelian_jurnalkoreksi.kode_akun');
        $jurnalkoreksi->orderBy('pembelian_jurnalkoreksi.tanggal');
        $jurnalkoreksi->orderBy('pembelian_jurnalkoreksi.no_bukti');



        //    dd($jurnalumum->get());
        $coa_kas_kecil = Coa::where('kode_transaksi', 'KKL');
        $coa_piutangcabang = Coa::where('kode_transaksi', 'PCB');

        //Kas Kecil
        $kaskecil = Kaskecil::query();
        $kaskecil->select(
            'coa_kas_kecil.kode_akun',
            'coa_kas_kecil.jenis_akun',
            'nama_akun',
            'keuangan_kaskecil.tanggal',
            'keuangan_kaskecil.no_bukti',
            DB::raw("CONCAT('KAS KECIL ', keuangan_kaskecil.kode_cabang) AS sumber"),
            'keuangan_kaskecil.keterangan',
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",2,1) as urutan')
        );
        $kaskecil->leftJoinSub($coa_kas_kecil, 'coa_kas_kecil', function ($join) {
            $join->on('keuangan_kaskecil.kode_cabang', '=', 'coa_kas_kecil.kode_cabang_coa');
        });
        $kaskecil->where(function ($query) {
            $query->where('keuangan_kaskecil.keterangan', '!=', 'Penerimaan Kas Kecil')
                ->orWhere('keuangan_kaskecil.kode_cabang', '=', 'PST');
        });







        $kaskecil->whereBetween('keuangan_kaskecil.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kaskecil->whereBetween('coa_kas_kecil.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kaskecil->orderBy('coa_kas_kecil.kode_akun');
        $kaskecil->orderBy('keuangan_kaskecil.tanggal');
        $kaskecil->orderBy('keuangan_kaskecil.no_bukti');


        //dd($kaskecil->get());

        $kaskecil_transaksi = Kaskecil::query();
        $kaskecil_transaksi->select(
            'keuangan_kaskecil.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_kaskecil.tanggal',
            'keuangan_kaskecil.no_bukti',
            DB::raw("CONCAT('KAS KECIL ', keuangan_kaskecil.kode_cabang) AS sumber"),
            'keuangan_kaskecil.keterangan',
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",1,2) as urutan')
        );
        $kaskecil_transaksi->whereBetween('keuangan_kaskecil.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kaskecil_transaksi->whereBetween('keuangan_kaskecil.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kaskecil_transaksi->where('keuangan_kaskecil.keterangan', '!=', 'Penerimaan Kas Kecil');
        $kaskecil_transaksi->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.kode_akun');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.tanggal');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.no_bukti');

        //Kas Bank Perantara
        $kasbankperantara = Kaskecil::query();
        $kasbankperantara->select(
            'keuangan_kaskecil.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_kaskecil.tanggal',
            'keuangan_kaskecil.no_bukti',
            DB::raw("'KAS KECIL' AS sumber"),
            'keuangan_kaskecil.keterangan',
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",1,2) as urutan')
        );
        $kasbankperantara->whereBetween('keuangan_kaskecil.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kasbankperantara->whereBetween('keuangan_kaskecil.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kasbankperantara->where('keuangan_kaskecil.kode_akun', '1-1104');
        $kasbankperantara->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
        $kasbankperantara->orderBy('keuangan_kaskecil.kode_akun');
        $kasbankperantara->orderBy('keuangan_kaskecil.tanggal');
        $kasbankperantara->orderBy('keuangan_kaskecil.no_bukti');



        //Piutang dari Kas Besar Penjualan
        $piutangcabang = Historibayarpenjualan::query();
        $piutangcabang->select(
            'coa_piutangcabang.kode_akun',
            'coa_piutangcabang.jenis_akun',
            'nama_akun',
            'marketing_penjualan_historibayar.tanggal',
            'marketing_penjualan_historibayar.no_bukti',
            DB::raw("'KAS BESAR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_penjualan_historibayar.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            'marketing_penjualan_historibayar.jumlah as jml_debet',
            DB::raw('1 as urutan')
        );
        $piutangcabang->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $piutangcabang->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $piutangcabang->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $piutangcabang->leftJoinSub($coa_piutangcabang, 'coa_piutangcabang', function ($join) {
            $join->on('salesman.kode_cabang', '=', 'coa_piutangcabang.kode_cabang_coa');
        });
        $piutangcabang->whereBetween('marketing_penjualan_historibayar.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $piutangcabang->whereBetween('coa_piutangcabang.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $piutangcabang->where('marketing_penjualan_historibayar.voucher', 0);
        $piutangcabang->where('marketing_penjualan.status_batal', 0);
        $piutangcabang->orderBy('coa_piutangcabang.kode_akun');
        $piutangcabang->orderBy('marketing_penjualan_historibayar.tanggal');
        $piutangcabang->orderBy('marketing_penjualan_historibayar.no_bukti');


        //Penjualan Produk
        $penjualan_produk = Detailpenjualan::query();
        $penjualan_produk->select(
            'produk.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan Produk ',produk_harga.kode_produk, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('subtotal as jml_kredit'),
            DB::raw('0 as jml_debet'),
            DB::raw('1 as urutan')
        );
        $penjualan_produk->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $penjualan_produk->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $penjualan_produk->join('coa', 'produk.kode_akun', '=', 'coa.kode_akun');
        $penjualan_produk->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $penjualan_produk->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $penjualan_produk->whereBetween('marketing_penjualan.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $penjualan_produk->whereBetween('produk.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $penjualan_produk->where('marketing_penjualan.status_batal', 0);
        $penjualan_produk->orderBy('marketing_penjualan.tanggal');
        $penjualan_produk->orderBy('marketing_penjualan.no_faktur');




        //Putang Datang 1-1401

        //Retur Penjualan
        $returpenjualan = Detailretur::query();
        $returpenjualan->select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as jml_retur'));
        $returpenjualan->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $returpenjualan->where('jenis_retur', 'PF');
        $returpenjualan->whereBetween('marketing_retur.tanggal', [$start_date, $request->sampai]);
        $returpenjualan->groupBy('marketing_retur.no_faktur');

        $detailpenjualan = Detailpenjualan::query();
        $detailpenjualan->select('marketing_penjualan.no_faktur', DB::raw('SUM(subtotal) as jml_bruto_penjualan'));
        $detailpenjualan->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $detailpenjualan->whereBetween('marketing_penjualan.tanggal', [$start_date, $request->sampai]);
        $detailpenjualan->where('status_batal', 0);
        $detailpenjualan->groupBy('marketing_penjualan.no_faktur');

        $penjualannetto = Penjualan::query();
        $penjualannetto->select(
            'marketing_penjualan.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur as no_bukti',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan ',pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            DB::raw('(IFNULL(jml_bruto_penjualan,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) - IFNULL(jml_retur,0)) as jml_debet'),
            DB::raw('1 as urutan')
        );
        $penjualannetto->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $penjualannetto->join('coa', 'marketing_penjualan.kode_akun', '=', 'coa.kode_akun');
        $penjualannetto->leftJoinSub($returpenjualan, 'returpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'returpenjualan.no_faktur');
        });
        $penjualannetto->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'detailpenjualan.no_faktur');
        });
        $penjualannetto->where('marketing_penjualan.status_batal', 0);
        $penjualannetto->whereBetween('marketing_penjualan.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $penjualannetto->whereBetween('marketing_penjualan.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $penjualannetto->orderBy('marketing_penjualan.kode_akun');
        $penjualannetto->orderBy('marketing_penjualan.tanggal');



        //Piutang Datang

        //Kas Besar
        $kasbesarpiutangdagang = Historibayarpenjualan::query();
        $kasbesarpiutangdagang->select(
            'marketing_penjualan_historibayar.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan_historibayar.tanggal',
            'marketing_penjualan_historibayar.no_bukti',
            DB::raw("'KAS BESAR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_penjualan_historibayar.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            'marketing_penjualan_historibayar.jumlah as jml_kredit',
            DB::raw('0 as jml_debet'),
            DB::raw('2 as urutan')
        );
        $kasbesarpiutangdagang->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $kasbesarpiutangdagang->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $kasbesarpiutangdagang->join('coa', 'marketing_penjualan_historibayar.kode_akun', '=', 'coa.kode_akun');
        $kasbesarpiutangdagang->whereBetween('marketing_penjualan_historibayar.tanggal', [$start_date, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kasbesarpiutangdagang->whereBetween('marketing_penjualan_historibayar.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kasbesarpiutangdagang->where('voucher', 0);
        $kasbesarpiutangdagang->orderBy('marketing_penjualan_historibayar.kode_akun');
        $kasbesarpiutangdagang->orderBy('marketing_penjualan_historibayar.tanggal');
        $kasbesarpiutangdagang->orderBy('marketing_penjualan_historibayar.no_bukti');



        //Retur
        $returpenjualanpiutangdagang = Detailretur::query();
        $returpenjualanpiutangdagang->select(
            'marketing_retur.kode_akun_piutang_dagang',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_retur.tanggal',
            DB::raw("marketing_retur.no_retur as no_bukti"),
            DB::raw("'RETUR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_retur.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('SUM(marketing_retur_detail.subtotal) as jml_kredit'),
            DB::raw('0 as jml_debet'),
            DB::raw('2 as urutan')
        );
        $returpenjualanpiutangdagang->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $returpenjualanpiutangdagang->join('coa', 'marketing_retur.kode_akun_piutang_dagang', '=', 'coa.kode_akun');
        $returpenjualanpiutangdagang->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $returpenjualanpiutangdagang->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $returpenjualanpiutangdagang->where('jenis_retur', 'PF');
        $returpenjualanpiutangdagang->whereBetween('marketing_retur.tanggal', [$start_date, $request->sampai]);
        $returpenjualanpiutangdagang->where('marketing_penjualan.status_batal', 0);
        $returpenjualanpiutangdagang->where('marketing_penjualan.tanggal', '<', $start_date);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $returpenjualanpiutangdagang->whereBetween('marketing_retur.kode_akun_piutang_dagang', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $returpenjualanpiutangdagang->groupBy('marketing_retur.kode_akun_piutang_dagang', 'coa.jenis_akun', 'nama_akun', 'marketing_retur.tanggal', 'marketing_retur.no_retur', 'marketing_retur.no_faktur', 'pelanggan.nama_pelanggan');
        $returpenjualanpiutangdagang->orderBy('marketing_retur.tanggal');
        $returpenjualanpiutangdagang->orderBy('marketing_retur.no_retur');


        $retur_penjualan = Detailretur::query();
        $retur_penjualan->select(
            'marketing_retur.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_retur.tanggal',
            DB::raw("marketing_retur.no_retur as no_bukti"),
            DB::raw("'RETUR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_retur.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            'marketing_retur_detail.subtotal as jml_debet',
            DB::raw('1 as urutan')
        );

        $retur_penjualan->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $retur_penjualan->join('coa', 'marketing_retur.kode_akun', '=', 'coa.kode_akun');
        $retur_penjualan->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $retur_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $retur_penjualan->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $retur_penjualan->whereBetween('marketing_retur.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $retur_penjualan->where('marketing_retur.jenis_retur', 'PF');
        $retur_penjualan->orderBy('marketing_retur.tanggal');
        $retur_penjualan->orderBy('marketing_retur.no_retur');



        $potongan_penjualan = Penjualan::query();
        $potongan_penjualan->select(
            'marketing_penjualan.kode_akun_potongan',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur as no_bukti',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan ',marketing_penjualan.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            DB::raw('IFNULL(potongan,0) + IFNULL(potongan_istimewa,0) as jml_debet'),
            DB::raw('1 as urutan')
        );
        $potongan_penjualan->join('coa', 'marketing_penjualan.kode_akun_potongan', '=', 'coa.kode_akun');
        $potongan_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $potongan_penjualan->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        $potongan_penjualan->where('marketing_penjualan.status_batal', 0);
        $potongan_penjualan->orderBy('marketing_penjualan.tanggal');
        $potongan_penjualan->orderBy('marketing_penjualan.no_faktur');
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $potongan_penjualan->whereBetween('marketing_penjualan.kode_akun_potongan', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $potongan_penjualan->orderBy('marketing_penjualan.tanggal');
        $potongan_penjualan->orderBy('marketing_penjualan.no_faktur');



        $penyesuaian_penjualan = Penjualan::query();
        $penyesuaian_penjualan->select(
            'marketing_penjualan.kode_akun_penyesuaian',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur as no_bukti',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan ',marketing_penjualan.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            DB::raw('IFNULL(penyesuaian,0) as jml_debet'),
            DB::raw('1 as urutan')
        );
        $penyesuaian_penjualan->join('coa', 'marketing_penjualan.kode_akun_penyesuaian', '=', 'coa.kode_akun');
        $penyesuaian_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $penyesuaian_penjualan->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        $penyesuaian_penjualan->where('marketing_penjualan.status_batal', 0);
        $penyesuaian_penjualan->orderBy('marketing_penjualan.tanggal');
        $penyesuaian_penjualan->orderBy('marketing_penjualan.no_faktur');
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $penyesuaian_penjualan->whereBetween('marketing_penjualan.kode_akun_penyesuaian', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }

        $penyesuaian_penjualan->orderBy('marketing_penjualan.tanggal');
        $penyesuaian_penjualan->orderBy('marketing_penjualan.no_faktur');



        //dd($penyesuaian_penjualan->get());


        //dd($potongan_penjualan->get());
        // if ($request->kode_akun_dari == '4-2100' || $request->kode_akun_sampai == '4-2100') {
        //     $retur_penjualan = Detailretur::query();
        //     $retur_penjualan->select(
        //         DB::raw("'4-2100' as kode_akun"),
        //         DB::raw("'Retur Penjualan' as nama_akun"),
        //         'marketing_retur.tanggal',
        //         DB::raw("marketing_retur.no_retur as no_bukti"),
        //         DB::raw("'RETUR PENJUALAN' AS sumber"),
        //         DB::raw("CONCAT(marketing_retur.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
        //         DB::raw('0 as jml_kredit'),
        //         'marketing_retur_detail.subtotal as jml_debet',
        //         DB::raw('1 as urutan')
        //     );

        //     $retur_penjualan->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        //     $retur_penjualan->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        //     $retur_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        //     $retur_penjualan->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai]);
        //     $retur_penjualan->where('marketing_retur.jenis_retur', 'PF');
        //     $retur_penjualan->orderBy('marketing_retur.tanggal');
        //     $retur_penjualan->orderBy('marketing_retur.no_retur');
        // } else {
        //     // Jika tidak ada retur_penjualan, buat query kosong agar tidak error pada unionAll
        //     $retur_penjualan = Detailretur::query()->select(
        //         DB::raw("'4-2100' as kode_akun"),
        //         DB::raw("'Retur Penjualan' as nama_akun"),
        //         DB::raw("NULL as tanggal"),
        //         DB::raw("NULL as no_bukti"),
        //         DB::raw("'RETUR PENJUALAN' AS sumber"),
        //         DB::raw("NULL as keterangan"),
        //         DB::raw('0 as jml_kredit'),
        //         DB::raw('0 as jml_debet'),
        //         DB::raw('1 as urutan')
        //     )->whereRaw('0 = 1'); // Query kosong
        // }

        // Melakukan sum debet dan kredit dari data union sebelum tanggal $request->dari, group by kode_akun
        // $mutasi_transaksi = $ledger->unionAll($kaskecil)
        //     ->unionAll($ledger_transaksi)
        //     ->unionAll($piutangcabang)
        //     ->unionAll($pembelian)
        //     ->unionAll($jurnalumum)
        //     ->unionAll($jurnalkoreksi)
        //     ->unionAll($penjualan_produk);



        // Contoh penggunaan: $total_mutasi_per_akun adalah collection, akses per kode_akun



        $hutangdagangdanlainnya = Pembelian::query();
        $hutangdagangdanlainnya->select(
            'pembelian.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'pembelian.tanggal',
            'pembelian.no_bukti',
            DB::raw("'PEMBELIAN' AS sumber"),
            DB::raw("CONCAT(' Pembelian ',pembelian.no_bukti, ' - ', supplier.nama_supplier) as keterangan"),
            DB::raw('detailpembelian.subtotal as jml_kredit'),
            DB::raw('0 as jml_debet'),
            DB::raw('1 as urutan')
        );

        $hutangdagangdanlainnya->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $hutangdagangdanlainnya->join('coa', 'pembelian.kode_akun', '=', 'coa.kode_akun');
        $hutangdagangdanlainnya->leftJoin(
            DB::raw('(
                SELECT no_bukti, SUM( IF ( kode_transaksi = "PMB", ( ( jumlah * harga ) + penyesuaian ), 0 ) ) - SUM( IF ( kode_transaksi = "PNJ", ( jumlah * harga ), 0 ) ) as subtotal
                FROM pembelian_detail
                GROUP BY no_bukti
            ) detailpembelian'),
            function ($join) {
                $join->on('pembelian.no_bukti', '=', 'detailpembelian.no_bukti');
            }
        );
        $hutangdagangdanlainnya->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $hutangdagangdanlainnya->whereBetween('pembelian.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $hutangdagangdanlainnya->orderBy('pembelian.tanggal');
        $hutangdagangdanlainnya->orderBy('pembelian.no_bukti');
        $hutangdagangdanlainnya->orderBy('urutan');

        //dd($hutangdagangdanlainnya->get());
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['saldoawalCollection'] = $saldoawalCollection;

        $union_data = $ledger->unionAll($saldoawal)
            ->unionAll($kaskecil)
            ->unionAll($kaskecil_transaksi)
            ->unionAll($kasbankperantara)
            ->unionAll($ledger_transaksi)
            ->unionAll($piutangcabang)
            ->unionAll($pembelian)
            ->unionAll($jurnalumum)
            ->unionAll($jurnalkoreksi)
            ->unionAll($penjualan_produk)
            ->unionAll($penjualannetto)
            ->unionAll($kasbesarpiutangdagang)
            ->unionAll($returpenjualanpiutangdagang)
            ->unionAll($retur_penjualan)
            ->unionAll($potongan_penjualan)
            ->unionAll($penyesuaian_penjualan)
            ->unionAll($hutangdagangdanlainnya);

        if ($request->formatlaporan == '1') {


            // Ambil data union sebagai subquery
            $mutasi_subquery = DB::query()->fromSub($union_data, 'mutasi')
                ->where('tanggal', '>=', $tahun . '-' . $bulan . '-01')
                ->where('tanggal', '<', $request->dari);

            // Hitung total debet dan kredit sebelum tanggal $request->dari, group by kode_akun
            $mutasiakun = $mutasi_subquery
                ->selectRaw('kode_akun, SUM(jml_debet) as total_debet, SUM(jml_kredit) as total_kredit')
                ->groupBy('kode_akun')
                ->get()->toArray();
            $mutasiakunCollection = collect($mutasiakun);
            $bukubesar = DB::query()->fromSub($union_data, 'bukubesar')
                ->whereBetween('tanggal', [$request->dari, $request->sampai])
                // ->unionAll($retur_penjualan)
                ->orderBy('kode_akun')->orderBy('tanggal')->orderBy('urutan')->orderBy('no_bukti')->get();


            $data['mutasiakunCollection'] = $mutasiakunCollection;
            $data['bukubesar'] = $bukubesar;

            if (isset($_POST['exportButton'])) {
                header("Content-type: application/vnd-ms-excel");
                // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                header("Content-Disposition: attachment; filename=Buku Besar.xls");
            }
            return view('accounting.laporan.lk.bukubesar_cetak', $data);
        } else if ($request->formatlaporan == '2') {



            //Labarugi
            $kode_laba_rugi = array('4,5,6,7,8,9');
            $akun_jangan_ditampilkan = ['0-0000', '1', '2'];
            // Ambil hasil union sebagai subquery, lalu lakukan SUM group by kode_akun

            $rekapakunlabarugi = DB::query()->fromSub($union_data, 'rekap')
                ->selectRaw('kode_akun, nama_akun,
                    SUM(IF(jenis_akun = 1, jml_kredit - jml_debet, jml_debet - jml_kredit)) as saldo_akhir')
                ->whereRaw('LEFT(kode_akun,1) IN (' . implode(',', $kode_laba_rugi) . ')')
                ->groupBy('kode_akun', 'nama_akun')
                ->orderBy('kode_akun');

            $labarugi = Coa::leftJoinSub($rekapakunlabarugi, 'rekapakun', function ($join) {
                $join->on('coa.kode_akun', '=', 'rekapakun.kode_akun');
            })
                ->select('coa.kode_akun', 'coa.nama_akun', 'coa.level', 'coa.sub_akun', 'rekapakun.saldo_akhir')
                ->whereRaw('LEFT(coa.kode_akun,1) IN (' . implode(',', $kode_laba_rugi) . ')')
                ->whereNotIn('coa.kode_akun', $akun_jangan_ditampilkan)
                ->where(function ($query) {
                    // Hanya tampilkan saldo_akhir yang tidak null,
                    // atau jika null hanya untuk level 0 dan 1
                    $query->whereNotNull('rekapakun.saldo_akhir')
                        ->orWhere(function ($q) {
                            $q->whereNull('rekapakun.saldo_akhir')
                                ->whereIn('coa.level', [0, 1, 2]);
                        });
                })
                ->get();
            $kode_akun_pendapatan = 4;
            $kode_akun_pokok_penjualan = 5;
            $kode_akun_pendapatanlain = 8;
            $kode_akun_biayalain = 9;

            $kode_akun_biaya_penjualan = '6-1';
            $kode_akun_biaya_adm = '6-2';

            $subtotal_akun_pendapatan = 0;
            $subtotal_akun_pokok_penjualan = 0;
            $subtotal_akun_pendapatanlain = 0;
            $subtotal_akun_biayalain = 0;
            $subtotal_akun_biaya_penjualan = 0;
            $subtotal_akun_biaya_adm = 0;
            foreach ($labarugi as $index => $d) {

                $kode_akun_minus = [
                    '4-2101',
                    '4-2201',
                    '4-2202',
                    '5-1202',
                    '5-3200',
                    '5-3400',
                    '5-3800',
                    '5-1203',
                ];
                // Hitung indentasi berdasarkan level (misal: 20px per level)
                // $indent = ($d->level ?? 0) * 20;
                if (in_array($d->kode_akun, $kode_akun_minus)) {
                    $saldo_akhir = $d->saldo_akhir * -1;
                    $test = 'minus';
                } else {
                    $saldo_akhir = $d->saldo_akhir;
                    $test = 'plus';
                }



                if (substr($d->kode_akun, 0, 1) == $kode_akun_pendapatan) {
                    $subtotal_akun_pendapatan += $saldo_akhir;
                }

                if (substr($d->kode_akun, 0, 1) == $kode_akun_pokok_penjualan) {
                    $subtotal_akun_pokok_penjualan += $saldo_akhir;
                }

                if (substr($d->kode_akun, 0, 1) == $kode_akun_pendapatanlain) {
                    $subtotal_akun_pendapatanlain += $saldo_akhir;
                }

                if (substr($d->kode_akun, 0, 1) == $kode_akun_biayalain) {
                    $subtotal_akun_biayalain += $saldo_akhir;
                }

                if (substr($d->kode_akun, 0, 3) == $kode_akun_biaya_penjualan) {
                    $subtotal_akun_biaya_penjualan += $saldo_akhir;
                }

                if (substr($d->kode_akun, 0, 3) == $kode_akun_biaya_adm) {
                    $subtotal_akun_biaya_adm += $saldo_akhir;
                }
            }

            $gross_profit = $subtotal_akun_pendapatan - $subtotal_akun_pokok_penjualan;
            $biaya_operasional = $subtotal_akun_biaya_adm + $subtotal_akun_biaya_penjualan;
            $operating_profit = $gross_profit - $biaya_operasional;
            $net_profit_loss = $operating_profit + $subtotal_akun_pendapatanlain - $subtotal_akun_biayalain;
            // echo "Pendapatan: " . $subtotal_akun_pendapatan . "<br>";
            // echo "Pokok Penjualan: " . $subtotal_akun_pokok_penjualan . "<br>";
            // echo "Gross Profit: " . $gross_profit . "<br>";
            // echo "Biaya Operasional: " . $biaya_operasional . "<br>";
            // echo "Operating Profit: " . $operating_profit . "<br>";
            // echo "Pendapatan Lain: " . $subtotal_akun_pendapatanlain . "<br>";
            // echo "Biaya Lain: " . $subtotal_akun_biayalain . "<br>";
            // echo "Net Profit Loss: " . $net_profit_loss . "<br>";
            // die;

            $data['net_profit_loss'] = $net_profit_loss;
            //Neraca
            $neraca = array('1,2,3');
            $akun_jangan_ditampilkan = ['0-0000', '1', '2'];
            // Ambil hasil union sebagai subquery, lalu lakukan SUM group by kode_akun

            $rekapakun = DB::query()->fromSub($union_data, 'rekap')
                ->selectRaw('kode_akun, nama_akun,
                    SUM(IF(jenis_akun = 1, jml_kredit - jml_debet, jml_debet - jml_kredit)) as saldo_akhir')
                ->whereRaw('LEFT(kode_akun,1) IN (' . implode(',', $neraca) . ')')
                ->groupBy('kode_akun', 'nama_akun')
                ->orderBy('kode_akun');

            $data['neraca'] = Coa::leftJoinSub($rekapakun, 'rekapakun', function ($join) {
                $join->on('coa.kode_akun', '=', 'rekapakun.kode_akun');
            })
                ->select('coa.kode_akun', 'coa.nama_akun', 'coa.level', 'coa.sub_akun', 'rekapakun.saldo_akhir')
                ->whereRaw('LEFT(coa.kode_akun,1) IN (' . implode(',', $neraca) . ')')
                ->whereNotIn('coa.kode_akun', $akun_jangan_ditampilkan)
                ->where(function ($query) {
                    // Hanya tampilkan saldo_akhir yang tidak null,
                    // atau jika null hanya untuk level 0 dan 1
                    $query->whereNotNull('rekapakun.saldo_akhir')
                        ->orWhere(function ($q) {
                            $q->whereNull('rekapakun.saldo_akhir')
                                ->whereIn('coa.level', [0, 1, 2]);
                        });
                })
                ->get();




            if (isset($_POST['exportButton'])) {
                header("Content-type: application/vnd-ms-excel");
                // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                header("Content-Disposition: attachment; filename=Neraca.xls");
            }
            return view('accounting.laporan.lk.neraca_cetak', $data);

            // $rekap_akun sekarang berisi total debet dan kredit per kode_akun dari seluruh union
        } else if ($request->formatlaporan == '3') {
            $kode_laba_rugi = array('4,5,6,7,8,9');
            $akun_jangan_ditampilkan = ['0-0000', '1', '2'];
            // Ambil hasil union sebagai subquery, lalu lakukan SUM group by kode_akun

            $rekapakun = DB::query()->fromSub($union_data, 'rekap')
                ->selectRaw('kode_akun, nama_akun,
                    SUM(IF(jenis_akun = 1, jml_kredit - jml_debet, jml_debet - jml_kredit)) as saldo_akhir')
                ->whereRaw('LEFT(kode_akun,1) IN (' . implode(',', $kode_laba_rugi) . ')')
                ->groupBy('kode_akun', 'nama_akun')
                ->orderBy('kode_akun');

            $data['labarugi'] = Coa::leftJoinSub($rekapakun, 'rekapakun', function ($join) {
                $join->on('coa.kode_akun', '=', 'rekapakun.kode_akun');
            })
                ->select('coa.kode_akun', 'coa.nama_akun', 'coa.level', 'coa.sub_akun', 'rekapakun.saldo_akhir')
                ->whereRaw('LEFT(coa.kode_akun,1) IN (' . implode(',', $kode_laba_rugi) . ')')
                ->whereNotIn('coa.kode_akun', $akun_jangan_ditampilkan)
                ->where(function ($query) {
                    // Hanya tampilkan saldo_akhir yang tidak null,
                    // atau jika null hanya untuk level 0 dan 1
                    $query->whereNotNull('rekapakun.saldo_akhir')
                        ->orWhere(function ($q) {
                            $q->whereNull('rekapakun.saldo_akhir')
                                ->whereIn('coa.level', [0, 1, 2]);
                        });
                })
                ->get();

            if (isset($_POST['exportButton'])) {
                header("Content-type: application/vnd-ms-excel");
                // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                header("Content-Disposition: attachment; filename=Laba Rugi.xls");
            }
            return view('accounting.laporan.lk.labarugi_cetak', $data);
        }
    }
}

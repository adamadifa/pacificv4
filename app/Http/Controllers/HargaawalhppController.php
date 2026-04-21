<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailhargaawalhpp;
use App\Models\Detailhpp;
use App\Models\Detailmutasigudangcabang;
use App\Models\Detailmutasigudangjadi;
use App\Models\Detailmutasiproduksi;
use App\Models\Detailsaldoawalmutasiproduksi;
use App\Models\Detailsaldoawalgudangcabang;
use App\Models\Detailsaldoawalgudangjadi;
use App\Models\Hargaawalhpp;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HargaawalhppController extends Controller
{
    public function index()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('accounting.hargaawalhpp.index', $data);
    }

    public function gethargaawal(Request $request)
    {
        $detail = Detailhargaawalhpp::where('lokasi', $request->lokasi)->where('bulan', $request->bulan)->where('tahun', $request->tahun)
            ->join('accounting_hpp_hargaawal', 'accounting_hpp_hargaawal_detail.kode_hargaawal', '=', 'accounting_hpp_hargaawal.kode_hargaawal')
            ->join('produk', 'accounting_hpp_hargaawal_detail.kode_produk', '=', 'produk.kode_produk')
            ->get();

        if ($detail->isEmpty()) {
            $detail = Produk::select('kode_produk', 'nama_produk', DB::raw("'0' as harga"))->where('status_aktif_produk', 1)->get();
        }

        $data['detail'] = $detail;
        return view('accounting.hargaawalhpp.gethargaawalhpp', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        $kode_produk = $request->kode_produk;
        $harga_awal = $request->harga_awal;
        DB::beginTransaction();
        try {
            $bln = $request->bulan < 10 ? "0" . $request->bulan : $request->bulan;
            $kode_hargaawal = "HA" . $request->lokasi . $bln . $request->tahun;
            $cekhpp = Hargaawalhpp::where('kode_hargaawal', $kode_hargaawal)->first();
            if (!empty($cekhpp)) {
                Hargaawalhpp::where('kode_hargaawal', $kode_hargaawal)->delete();
            }

            Hargaawalhpp::create([
                'kode_hargaawal' => $kode_hargaawal,
                'lokasi' => $request->lokasi,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            for ($i = 0; $i < count($kode_produk); $i++) {
                $detail[] = [
                    'kode_hargaawal' => $kode_hargaawal,
                    'kode_produk' => $kode_produk[$i],
                    'harga_awal' => toNumber($harga_awal[$i]),
                ];
            }

            Detailhargaawalhpp::insert($detail);
            DB::commit();
            return redirect('/hargaawalhpp?lokasi=' . $request->lokasi . '&bulan=' . $request->bulan . '&tahun=' . $request->tahun)->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function generateharga(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $lokasi = $request->lokasi;

        // Hitung bulan sebelumnya
        if ($bulan == 1) {
            $bulan_sebelumnya = 12;
            $tahun_sebelumnya = $tahun - 1;
        } else {
            $bulan_sebelumnya = $bulan - 1;
            $tahun_sebelumnya = $tahun;
        }

        $dari = $tahun_sebelumnya . "-" . $bulan_sebelumnya . "-01";
        $sampai = date("Y-m-t", strtotime($dari));

        $cabang = Cabang::orderBy('kode_cabang')->get();
        $selectSaldocabang = [];
        $selectMutasicabang = [];
        $selectHargaawal = [];

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
        }

        //Saldo cabang
        $qsaldoawal = Detailsaldoawalgudangcabang::query();
        $qsaldoawal->select('gudang_cabang_saldoawal_detail.kode_produk', ...$selectSaldocabang);
        $qsaldoawal->join('gudang_cabang_saldoawal', 'gudang_cabang_saldoawal_detail.kode_saldo_awal', '=', 'gudang_cabang_saldoawal.kode_saldo_awal');
        $qsaldoawal->where('bulan', $bulan_sebelumnya)->where('tahun', $tahun_sebelumnya)->where('kondisi', 'GS');
        $qsaldoawal->groupBy('gudang_cabang_saldoawal_detail.kode_produk');

        //Mutasi Cabang
        $qmutasicabang = Detailmutasigudangcabang::query();
        $qmutasicabang->select('gudang_cabang_mutasi_detail.kode_produk', ...$selectMutasicabang);
        $qmutasicabang->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi');
        $qmutasicabang->whereBetween('gudang_cabang_mutasi.tanggal', [$dari, $sampai]);
        $qmutasicabang->groupBy('gudang_cabang_mutasi_detail.kode_produk');

        $qhpp = Detailhpp::query();
        $qhpp->select('accounting_hpp_detail.kode_produk', 'harga_hpp');
        $qhpp->join('accounting_hpp', 'accounting_hpp_detail.kode_hpp', 'accounting_hpp.kode_hpp');
        $qhpp->where('bulan', $bulan_sebelumnya)->where('tahun', $tahun_sebelumnya);

        $qsaldoawalproduksi = Detailsaldoawalmutasiproduksi::query();
        $qsaldoawalproduksi->select('produksi_mutasi_saldoawal_detail.kode_produk', 'jumlah as saldoawal_produksi');
        $qsaldoawalproduksi->join('produksi_mutasi_saldoawal', 'produksi_mutasi_saldoawal_detail.kode_saldo_awal', '=', 'produksi_mutasi_saldoawal.kode_saldo_awal');
        $qsaldoawalproduksi->where('produksi_mutasi_saldoawal.bulan', $bulan_sebelumnya)->where('produksi_mutasi_saldoawal.tahun', $tahun_sebelumnya);

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
        $qhargaawal->where('bulan', $bulan_sebelumnya)->where('tahun', $tahun_sebelumnya);
        $qhargaawal->groupBy('accounting_hpp_hargaawal_detail.kode_produk');

        $qsaldoawalgudangjadi = Detailsaldoawalgudangjadi::query();
        $qsaldoawalgudangjadi->select('gudang_jadi_saldoawal_detail.kode_produk', 'jumlah as saldoawal_gudangjadi');
        $qsaldoawalgudangjadi->join('gudang_jadi_saldoawal', 'gudang_jadi_saldoawal_detail.kode_saldo_awal', '=', 'gudang_jadi_saldoawal.kode_saldo_awal');
        $qsaldoawalgudangjadi->where('gudang_jadi_saldoawal.bulan', $bulan_sebelumnya)->where('gudang_jadi_saldoawal.tahun', $tahun_sebelumnya);

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
            'gudangjadi_suratjalan'
        );

        foreach ($cabang as $c) {
            $query->addSelect(
                "saldoawal_" . $c->kode_cabang,
                "mutasi_" . $c->kode_cabang,
                "pusat_" . $c->kode_cabang,
                "transit_in_" . $c->kode_cabang,
                "retur_" . $c->kode_cabang,
                "lainlain_" . $c->kode_cabang,
                "repack_" . $c->kode_cabang,
                "hargaawal_" . $c->kode_cabang
            );
        }

        $query->leftjoinSub($qsaldoawal, 'saldoawalcabang', function($join) {
            $join->on('produk.kode_produk', '=', 'saldoawalcabang.kode_produk');
        });
        $query->leftjoinSub($qmutasicabang, 'mutasicabang', function($join) {
            $join->on('produk.kode_produk', '=', 'mutasicabang.kode_produk');
        });
        $query->leftjoinSub($qhpp, 'hpp', function($join) {
            $join->on('produk.kode_produk', '=', 'hpp.kode_produk');
        });
        $query->leftjoinSub($qsaldoawalproduksi, 'saldoawalproduksi', function($join) {
            $join->on('produk.kode_produk', '=', 'saldoawalproduksi.kode_produk');
        });
        $query->leftjoinSub($qmutasiproduksi, 'mutasiproduksi', function($join) {
            $join->on('produk.kode_produk', '=', 'mutasiproduksi.kode_produk');
        });
        $query->leftjoinSub($qhargaawal, 'hargaawal', function($join) {
            $join->on('produk.kode_produk', '=', 'hargaawal.kode_produk');
        });
        $query->leftjoinSub($qsaldoawalgudangjadi, 'saldoawalgudangjadi', function($join) {
            $join->on('produk.kode_produk', '=', 'saldoawalgudangjadi.kode_produk');
        });
        $query->leftjoinSub($qmutasigudangjadi, 'mutasigudangjadi', function($join) {
            $join->on('produk.kode_produk', '=', 'mutasigudangjadi.kode_produk');
        });

        $query->where('nama_produk', '!=', 'undifined');
        $query->orderBy('produk.kode_produk');
        $rekapbj = $query->get();

        $prices = [];
        foreach ($rekapbj as $d) {
            $harga_gudang = ($d->saldoawal_produksi + $d->produksi_bpbj != 0)
                ? ($d->saldoawal_produksi * $d->hargaawal_produksi + $d->produksi_bpbj * $d->harga_hpp) / ($d->saldoawal_produksi + $d->produksi_bpbj)
                : 0;

            $all_in_gudang = $d->saldoawal_gudangjadi + $d->gudangjadi_fsthp + $d->gudangjadi_repack + $d->gudangjadi_lainlain_in;
            $harga_kirim_cabang = ($all_in_gudang != 0)
                ? ($d->saldoawal_gudangjadi * $d->hargaawal_gudang + ($d->gudangjadi_fsthp + $d->gudangjadi_repack + $d->gudangjadi_lainlain_in) * $harga_gudang) / $all_in_gudang
                : 0;

            if ($lokasi == 'GDG') {
                $prices[$d->kode_produk] = round($harga_kirim_cabang, 0);
            } else {
                $qty_sa_cabang = ROUND($d->{"saldoawal_$lokasi"} / $d->isi_pcs_dus, 2);
                $qty_pusat_cabang = ROUND($d->{"pusat_$lokasi"} / $d->isi_pcs_dus, 2);
                $qty_transit_in_cabang = ROUND($d->{"transit_in_$lokasi"} / $d->isi_pcs_dus, 2);
                $qty_retur_cabang = ROUND($d->{"retur_$lokasi"} / $d->isi_pcs_dus, 2);
                $qty_lainlain_in_cabang = 0; // Diabaikan agar harga matching dengan Rekap BJ sebelumnya
                $qty_repack_cabang = ROUND($d->{"repack_$lokasi"} / $d->isi_pcs_dus, 2);

                $total_qty_cabang = $qty_sa_cabang + $qty_pusat_cabang + $qty_transit_in_cabang + $qty_retur_cabang + $qty_lainlain_in_cabang + $qty_repack_cabang;
                $harga_cabang = !empty($harga_kirim_cabang) ? $harga_kirim_cabang : $d->{"hargaawal_$lokasi"};

                $harga_sa_cabang = $qty_sa_cabang * $d->{"hargaawal_$lokasi"};
                $harga_pusat_cabang_val = $qty_pusat_cabang * $harga_cabang;
                $harga_transit_in_cabang = $qty_transit_in_cabang * $harga_cabang;
                $harga_retur_cabang = $qty_retur_cabang * $harga_cabang;
                $harga_lainlain_cabang = $qty_lainlain_in_cabang * $harga_cabang;
                $harga_repack_cabang = $qty_repack_cabang * $harga_cabang;

                $total_harga_cabang = $harga_sa_cabang + $harga_pusat_cabang_val + $harga_transit_in_cabang + $harga_retur_cabang + $harga_lainlain_cabang + $harga_repack_cabang;
                $harga_akhir_cabang = !empty($total_qty_cabang) ? ROUND($total_harga_cabang / $total_qty_cabang, 9) : 0;

                $prices[$d->kode_produk] = round($harga_akhir_cabang, 0);
            }
        }

        return response()->json($prices);
    }
}

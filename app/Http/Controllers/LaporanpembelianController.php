<?php

namespace App\Http\Controllers;

use App\Models\Barangpembelian;
use App\Models\Detailkontrabonpembelian;
use App\Models\Detailpembelian;
use App\Models\Historibayarpembelian;
use App\Models\Jurnalkoreksi;
use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LaporanpembelianController extends Controller
{
    public function index()
    {
        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        $data['asal_ajuan'] = config('pembelian.list_asal_pengajuan');
        $data['list_jenis_barang'] = config('pembelian.list_jenis_barang');
        $data['barangbahankemasan'] = Barangpembelian::whereIn('kode_jenis_barang', ['BB', 'BT', 'KM'])->get();
        return view('pembelian.laporan.index', $data);
    }

    public function cetakpembelian(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Detailpembelian::query();
        $query->select(
            'pembelian_detail.*',
            'tanggal',
            'pembelian.kode_supplier',
            'nama_supplier',
            'nama_barang',
            'kode_asal_pengajuan',
            'keterangan',
            'keterangan_penjualan',
            'nama_akun',
            'ppn',
            'kategori_transaksi',
            'jenis_transaksi',
            'pembelian.created_at',
            'pembelian.updated_at'
        );
        $query->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $query->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->join('coa', 'pembelian_detail.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoin('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_supplier)) {
            $query->where('pembelian.kode_supplier', $request->kode_supplier);
        }

        if ($request->ppn === "0") {
            $query->where('pembelian.ppn', 0);
        } else if ($request->ppn == "1") {
            $query->where('pembelian.ppn', 1);
        }

        if (!empty($request->kode_asal_pengajuan)) {
            $query->where('pembelian.kode_asal_pengajuan', $request->kode_asal_pengajuan);
        }

        // if (Auth::user()->level == "general affair") {
        //     $query->whereIn('detail_pembelian.kode_akun', $akun_ga);
        // }
        $query->orderBy('tanggal');
        $query->orderBy('pembelian_detail.no_bukti');
        $query->orderBy('pembelian_detail.kode_transaksi');

        $pmb = $query->get();
        $data['pembelian'] = $pmb;

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['supplier'] = Supplier::where('kode_supplier', $request->kode_supplier)->first();
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Pembelian $request->dari-$request->sampai.xls");
        }
        return view('pembelian.laporan.pembelian_cetak', $data);
    }

    public function cetakpembayaran(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $bank = Historibayarpembelian::select('pembelian_historibayar.kode_bank', 'nama_bank')
            ->join('bank', 'pembelian_historibayar.kode_bank', '=', 'bank.kode_bank')
            ->whereBetween('tanggal', [$request->dari, $request->sampai])
            ->groupBy('kode_bank', 'nama_bank')
            ->get();

        $selectColumnsbank = [];
        foreach ($bank as $b) {
            $selectColumnsbank[] = DB::raw('SUM(IF(kode_bank="' . $b->kode_bank . '",pembelian_kontrabon_detail.jumlah,0)) as ' . $b->kode_bank);
        }


        $query = Detailkontrabonpembelian::select(
            'pembelian_kontrabon_detail.no_bukti',
            'pembelian_kontrabon_detail.no_kontrabon',
            'nama_supplier',
            'pembelian_historibayar.tanggal as tglbayar',
            ...$selectColumnsbank
        );
        $query->join('pembelian_historibayar', 'pembelian_kontrabon_detail.no_kontrabon', '=', 'pembelian_historibayar.no_kontrabon');
        $query->join('pembelian_kontrabon', 'pembelian_kontrabon_detail.no_kontrabon', '=', 'pembelian_kontrabon.no_kontrabon');
        $query->join('supplier', 'pembelian_kontrabon.kode_supplier', '=', 'supplier.kode_supplier');
        $query->whereBetween('pembelian_historibayar.tanggal', [$request->dari, $request->sampai]);
        $query->orderBy('pembelian_historibayar.tanggal');
        $query->groupBy('pembelian_kontrabon_detail.no_kontrabon', 'pembelian_kontrabon_detail.no_bukti', 'pembelian_historibayar.tanggal', 'nama_supplier');
        if (!empty($request->kode_supplier_pembayaran)) {
            $query->where('pembelian_kontrabon.kode_supplier', $request->kode_supplier_pembayaran);
        }
        $data['pembayaran'] = $query->get();
        $data['supplier'] = Supplier::where('kode_supplier', $request->kode_supplier_pembayaran)->first();
        $data['bank'] = $bank;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Pembelian $request->dari-$request->sampai.xls");
        }
        return view('pembelian.laporan.pembayaran_cetak', $data);
    }

    public function cetakrekapsupplier(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }


        $subqueryJurnalkoreksi = Jurnalkoreksi::select('pembelian_jurnalkoreksi.no_bukti', 'kode_barang', DB::raw('SUM(jumlah*harga) as jml_jk'))
            ->where('debet_kredit', 'K')
            ->where('kode_akun', '5-1101')
            ->whereBetween('tanggal', [$request->dari, $request->sampai])
            ->groupBy('pembelian_jurnalkoreksi.no_bukti', 'kode_barang');

        $query = Detailpembelian::query();
        $query->select(
            'pembelian.kode_supplier',
            'nama_supplier',
            DB::raw('SUM(IF(kode_transaksi="PMB",(jumlah*harga)+penyesuaian,0)) - SUM(IFNULL(jml_jk,0)) as total'),
        );
        $query->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $query->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->leftJoinSub($subqueryJurnalkoreksi, 'subqueryJurnalkoreksi', function ($join) {
            $join->on('pembelian_detail.no_bukti', '=', 'subqueryJurnalkoreksi.no_bukti');
            $join->on('pembelian_detail.kode_barang', '=', 'subqueryJurnalkoreksi.kode_barang');
        });
        $query->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai]);
        $query->groupByRaw('pembelian.kode_supplier, nama_supplier');
        $query->orderBy('pembelian.kode_supplier');
        $data['rekapsupplier'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Pembelian Supplier $request->dari-$request->sampai.xls");
        }
        return view('pembelian.laporan.rekapsupplier_cetak', $data);
    }


    public function cetakrekappembelian(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $subqueryJurnalkoreksi = Jurnalkoreksi::select('pembelian_jurnalkoreksi.no_bukti', 'kode_barang', DB::raw('SUM(jumlah*harga) as jml_jk'))
            ->where('debet_kredit', 'K')
            ->where('kode_akun', '5-1101')
            ->whereBetween('tanggal', [$request->dari, $request->sampai])
            ->groupBy('pembelian_jurnalkoreksi.no_bukti', 'kode_barang');
        $query = Detailpembelian::query();
        $query->select(
            'pembelian_detail.*',
            'pembelian.tanggal',
            'pembelian.kode_supplier',
            'nama_supplier',
            'nama_barang',
            'kode_jenis_barang',
            'pembelian.kode_asal_pengajuan',
            'nama_akun',
            'ppn',
            DB::raw('IFNULL(jml_jk,0) as jml_jk'),
        );
        $query->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $query->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->join('coa', 'pembelian_detail.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoinSub($subqueryJurnalkoreksi, 'subqueryJurnalkoreksi', function ($join) {
            $join->on('pembelian_detail.no_bukti', '=', 'subqueryJurnalkoreksi.no_bukti');
            $join->on('pembelian_detail.kode_barang', '=', 'subqueryJurnalkoreksi.kode_barang');
        });
        $query->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai]);

        if (!empty($request->kode_jenis_barang)) {
            $query->where('kode_jenis_barang', $request->kode_jenis_barang);
        }
        if ($request->sortby == "supplier") {
            $query->orderBy('pembelian.kode_supplier');
        } else {
            $query->orderBy('kode_jenis_barang');
            $query->orderBy('pembelian.kode_supplier');
        }
        $query->where('pembelian_detail.kode_transaksi', 'PMB');
        $data['rekappembelian'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['jenis_barang'] = config('pembelian.jenis_barang');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Pembelian  $request->dari-$request->sampai.xls");
        }
        if ($request->sortby == "supplier") {
            return view('pembelian.laporan.rekappembelian_cetak', $data);
        } else {
            return view('pembelian.laporan.rekappembelian_jenisbarang_cetak', $data);
        }
    }


    public function cetakkartuhutang(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Pembelian::query();
        $query->select(
            'pembelian.no_bukti',
            'pembelian.tanggal',
            'pembelian.kode_supplier',
            'nama_supplier',
            'pembelian.kode_akun',
            'nama_akun',
            DB::raw('(IFNULL(IFNULL(totalhutang,0) + IFNULL(penyesuaianbulanlalu,0)+ IFNULL(penyesuaianbulanini,0),0))   as totalhutang'),
            DB::raw('(IFNULL(IFNULL(totalhutang,0) + IFNULL(penyesuaianbulanlalu,0) - IFNULL(jmlbayarbulanlalu,0) ,0))   as sisapiutang'),
            DB::raw('IFNULL(jmlbayarbulanlalu,0) as jmlbayarbulanlalu, IFNULL(jmlbayarbulanini,0) as jmlbayarbulanini,IFNULL(penyesuaianbulanlalu,0) as penyesuaianbulanlalu'),
            DB::raw(' IFNULL(penyesuaianbulanini,0) as penyesuaianbulanini'),
            'pmbbulanini',
            'kategori_transaksi'
        );
        $query->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->join('coa', 'pembelian.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoin(
            DB::raw("(
                SELECT pembelian_detail.no_bukti,
                (SUM(IF(kode_transaksi = 'PMB', ((jumlah*harga)+penyesuaian),0)) - SUM(IF(kode_transaksi = 'PNJ',(jumlah*harga), 0))) as totalhutang
                ,IF(pembelian.tanggal BETWEEN '$request->dari' AND '$request->sampai',(SUM(IF(kode_transaksi = 'PMB', ((jumlah*harga)+penyesuaian), 0 ) ) - SUM(IF(kode_transaksi = 'PNJ',(jumlah*harga), 0 ) ) ),0) as pmbbulanini
                FROM pembelian_detail
                INNER JOIN pembelian ON pembelian_detail.no_bukti = pembelian.no_bukti
                GROUP BY pembelian_detail.no_bukti
            ) detailpembelian"),
            function ($join) {
                $join->on('pembelian.no_bukti', '=', 'detailpembelian.no_bukti');
            }
        );
        $query->leftJoin(
            DB::raw("(
                SELECT no_bukti,SUM(IF(tanggal<'$request->dari',pembelian_kontrabon_detail.jumlah,0)) as jmlbayarbulanlalu,
                SUM(IF(tanggal BETWEEN '$request->dari' AND '$request->sampai',pembelian_kontrabon_detail.jumlah,0)) as jmlbayarbulanini
                FROM pembelian_historibayar hb
                INNER JOIN pembelian_kontrabon_detail on hb.no_kontrabon = pembelian_kontrabon_detail.no_kontrabon
                GROUP BY no_bukti
            ) historibayar"),
            function ($join) {
                $join->on('pembelian.no_bukti', '=', 'historibayar.no_bukti');
            }
        );

        $query->leftJoin(
            DB::raw("(
            SELECT no_bukti,(SUM(IF(tanggal <'$request->dari' AND debet_kredit='K' AND kode_akun='2-1200'
            OR tanggal < '$request->dari' AND debet_kredit ='K' AND kode_akun='2-1300' ,(jumlah*harga),0)) - SUM(IF(tanggal<'$request->dari' AND debet_kredit='D' AND kode_akun='2-1200' OR tanggal<'$request->dari' AND debet_kredit='D' AND kode_akun='2-1300' ,(jumlah*harga),0))) as penyesuaianbulanlalu,

            (SUM(IF(tanggal BETWEEN '$request->dari' AND '$request->sampai'  AND debet_kredit='K' AND kode_akun='2-1200'
            OR tanggal BETWEEN '$request->dari' AND '$request->sampai'  AND debet_kredit='K' AND kode_akun='2-1300'  ,(jumlah*harga),0)) -
            SUM(IF(tanggal BETWEEN '$request->dari' AND '$request->sampai'  AND debet_kredit='D' AND kode_akun='2-1200'
            OR tanggal BETWEEN '$request->dari' AND '$request->sampai'  AND debet_kredit='D' AND kode_akun='2-1300'  ,(jumlah*harga),0))) as penyesuaianbulanini

            FROM pembelian_jurnalkoreksi jk
            GROUP BY no_bukti
            ) jurnalkoreksi"),
            function ($join) {
                $join->on('pembelian.no_bukti', '=', 'jurnalkoreksi.no_bukti');
            }
        );

        $query->where('pembelian.tanggal', '<=', $request->sampai);
        $query->whereRaw("(IFNULL(IFNULL(totalhutang,0) + IFNULL(penyesuaianbulanlalu,0) - IFNULL(jmlbayarbulanlalu,0) ,0))  != 0");
        if (!empty($request->kode_supplier_kartuhutang)) {
            $query->where('pembelian.kode_supplier', $request->kode_supplier_kartuhutang);
        }

        if (!empty($request->jenis_hutang)) {
            $query->where('pembelian.kode_akun', $request->jenis_hutang);
        }
        $query->orWhere('pembelian.tanggal', '<=', $request->sampai);
        $query->where('jmlbayarbulanini', '!=', 0);
        if (!empty($request->kode_supplier_kartuhutang)) {
            $query->where('pembelian.kode_supplier', $request->kode_supplier_kartuhutang);
        }

        if (!empty($request->jenis_hutang)) {
            $query->where('pembelian.kode_akun', $request->jenis_hutang);
        }
        if ($request->formatlaporan == "1") {
            $query->orderBy('pembelian.tanggal');
            $query->orderBy('pembelian.no_bukti');
        } else {
            $query->orderBy('pembelian.kode_supplier');
        }
        $data['kartuhutang'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['jenis_hutang'] =  $request->jenis_hutang;
        $data['supplier'] = Supplier::where('kode_supplier', $request->kode_supplier_kartuhutang)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Kartu Hutang $request->dari-$request->sampai.xls");
        }
        if ($request->formatlaporan == "1") {
            return view('pembelian.laporan.kartuhutang_cetak', $data);
        } else {
            return view('pembelian.laporan.kartuhutang_rekap_cetak', $data);
        }
    }

    public function cetakauh(Request $request)
    {

        if (lockreport($request->tanggal) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        // $subqueryBayar = Historibayarpembelian::select(
        //     'pembelian_kontrabon_detail.no_bukti',
        //     DB::raw('SUM(pembelian_kontrabon_detail.jumlah) as jmlbayar')
        // )
        //     ->join('pembelian_kontrabon_detail', 'pembelian_historibayar.no_kontrabon', '=', 'pembelian_kontrabon_detail.no_kontrabon')
        //     ->where('pembelian_historibayar.tanggal', '<=', $request->tanggal)
        //     ->groupBy('pembelian_kontrabon_detail.no_bukti');

        // $subqueryJurnalkoreksi = Jurnalkoreksi::select(
        //     'pembelian_jurnalkoreksi.no_bukti',
        //     DB::raw('SUM(IF(debet_kredit="K" AND kode_akun="2-1200" OR debet_kredit="K" AND kode_akun="2-1300",(jumlah*harga),0)) - SUM(IF(debet_kredit="D" AND kode_akun="2-1200" OR debet_kredit="D" AND kode_akun="2-1300",(jumlah*harga),0)) as jml_jk')
        // )
        //     ->where('pembelian_jurnalkoreksi.tanggal', '<=', $request->tanggal)
        //     ->groupBy('pembelian_jurnalkoreksi.no_bukti');

        // $query = Detailpembelian::query();
        // $query->select(
        //     'pembelian_detail.no_bukti',
        //     'pembelian.kode_supplier',
        //     'nama_supplier',
        //     DB::raw('SUM(IF(kode_transaksi="PMB",(jumlah*harga)+penyesuaian,0)) - SUM(IF(kode_transaksi="PNJ",(jumlah*harga),0)) - IFNULL(jmlbayar,0) - IFNULL(jml_jk,0) as total'),
        //     DB::raw("CASE WHEN datediff('$request->tanggal',pembelian.tanggal) between 0 and 30 THEN SUM(IF(kode_transaksi='PMB',(jumlah*harga)+penyesuaian,0)) - SUM(IF(kode_transaksi='PNJ',(jumlah*harga),0)) - IFNULL(jmlbayar,0) - IFNULL(jml_jk,0) END as `bulanberjalan`")
        // );
        // $query->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        // $query->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        // $query->leftJoinSub($subqueryBayar, 'subqueryBayar', function ($join) {
        //     $join->on('pembelian_detail.no_bukti', '=', 'subqueryBayar.no_bukti');
        // });
        // $query->leftJoinSub($subqueryJurnalkoreksi, 'subqueryJurnalkoreksi', function ($join) {
        //     $join->on('pembelian_detail.no_bukti', '=', 'subqueryJurnalkoreksi.no_bukti');
        // });
        // $query->where('pembelian.tanggal', '<=', $request->tanggal);

        // $query->havingRaw("SUM(IF(kode_transaksi='PMB',(jumlah*harga)+penyesuaian,0)) - SUM(IF(kode_transaksi='PNJ',(jumlah*harga),0)) - IFNULL(jmlbayar,0) - IFNULL(jml_jk,0) != 0");
        // $query->groupBy('pembelian_detail.no_bukti', 'pembelian.kode_supplier', 'nama_supplier', 'jmlbayar', 'jml_jk');
        // $query->orderBy('pembelian.kode_supplier');
        // $data['auh'] = $query->get();

        $auh = DB::select("SELECT * FROM
        (
            SELECT pembelian_detail.no_bukti,pembelian.kode_supplier,nama_supplier,
            (SUM(IF(kode_transaksi = 'PMB', ((jumlah*harga)+penyesuaian),0)) - SUM(IF(kode_transaksi = 'PNJ',(jumlah*harga),0)))-IFNULL(jmlbayar,0)+IFNULL(jmlpenyesuaian,0) as sisahutang,
            CASE
            WHEN  datediff('$request->tanggal', pembelian.tanggal) < 30  THEN
            (SUM(IF(kode_transaksi = 'PMB', ((jumlah*harga)+penyesuaian),0)) - SUM(IF(kode_transaksi = 'PNJ',(jumlah*harga),0)))-IFNULL(jmlbayar,0)+IFNULL(jmlpenyesuaian,0) END as bulanberjalan,

            CASE
            WHEN  datediff('$request->tanggal', pembelian.tanggal) between 30 and 59  THEN
            (SUM(IF(kode_transaksi = 'PMB', ((jumlah*harga)+penyesuaian),0)) - SUM(IF(kode_transaksi = 'PNJ',(jumlah*harga),0)))-IFNULL(jmlbayar,0)+IFNULL(jmlpenyesuaian,0) END as satubulan,

            CASE
            WHEN  datediff('$request->tanggal', pembelian.tanggal) between 60 and 89  THEN
            (SUM(IF(kode_transaksi = 'PMB', ((jumlah*harga)+penyesuaian),0)) - SUM(IF(kode_transaksi = 'PNJ',(jumlah*harga),0)))-IFNULL(jmlbayar,0)+IFNULL(jmlpenyesuaian,0) END as duabulan,

            CASE
            WHEN  datediff('$request->tanggal', pembelian.tanggal) >= 90  THEN
            (SUM(IF(kode_transaksi = 'PMB', ((jumlah*harga)+penyesuaian),0)) - SUM(IF(kode_transaksi = 'PNJ',(jumlah*harga),0)))-IFNULL(jmlbayar,0)+IFNULL(jmlpenyesuaian,0) END as lebihtigabulan
        FROM pembelian_detail
        INNER JOIN pembelian ON pembelian_detail.no_bukti = pembelian.no_bukti
        INNER JOIN supplier ON pembelian.kode_supplier = supplier.kode_supplier
        LEFT JOIN (
            SELECT no_bukti,SUM(IF(hb.tanggal <='$request->tanggal',pembelian_kontrabon_detail.jumlah,0)) as jmlbayar
            FROM pembelian_historibayar hb
            INNER JOIN pembelian_kontrabon_detail on hb.no_kontrabon = pembelian_kontrabon_detail.no_kontrabon
            GROUP BY no_bukti
        ) hb ON hb.no_bukti = pembelian_detail.no_bukti
        LEFT JOIN (
            SELECT no_bukti,(SUM(IF(jk.tanggal <'$request->tanggal' AND debet_kredit='K' AND kode_akun='2-1200'
            OR jk.tanggal <'$request->tanggal' AND debet_kredit='K' AND kode_akun='2-1300' ,(jumlah*harga),0)) - SUM(IF(jk.tanggal<'$request->tanggal' AND debet_kredit='D' AND kode_akun='2-1200'
            OR jk.tanggal <'$request->tanggal' AND debet_kredit='D' AND kode_akun='2-1300' ,(jumlah*harga),0)))  as jmlpenyesuaian
        FROM pembelian_jurnalkoreksi jk
        GROUP BY no_bukti
        ) jk ON jk.no_bukti = pembelian_detail.no_bukti
        WHERE pembelian.tanggal <='$request->tanggal'
        GROUP BY pembelian_detail.no_bukti,pembelian.kode_supplier,nama_supplier,hb.jmlbayar,jk.jmlpenyesuaian,pembelian.tanggal
        ORDER BY pembelian.kode_supplier ASC
        ) as kp WHERE sisahutang !=0");


        $data['auh'] = $auh;
        $data['tanggal'] = $request->tanggal;
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Analisa Umur Hutang $request->sampai.xls");
        }
        return view('pembelian.laporan.auh_cetak', $data);
    }

    public function cetakbahankemasan(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $query = Detailpembelian::query();
        $query->select(
            'pembelian_detail.kode_barang',
            'nama_barang',
            'satuan',
            'kode_jenis_barang',
            DB::raw('SUM(pembelian_detail.jumlah) as totalqty'),
            DB::raw('SUM((jumlah*harga) + penyesuaian) as totalharga'),
            'jml_jk'
        );
        $query->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $query->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->leftJoin(
            DB::raw("(
                SELECT
                kode_barang,
                SUM(jumlah * harga) AS jml_jk
            FROM
                pembelian_jurnalkoreksi
            WHERE debet_kredit = 'K' AND kode_akun = '5-1101' AND tanggal BETWEEN '$request->dari' AND '$request->sampai'
            GROUP BY
                kode_barang
            ) jurnal_koreksi"),
            function ($join) {
                $join->on('pembelian_detail.kode_barang', '=', 'jurnal_koreksi.kode_barang');
            }
        );
        $query->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai]);
        if ($request->jenis_barang == "1") {
            $query->whereIn('kode_jenis_barang', ['BB', 'BT']);
        } else if ($request->jenis_barang == "2") {
            $query->where('kode_jenis_barang', 'KM');
        } else {
            $query->whereIn('kode_jenis_barang', ['BB', 'BT', 'KM']);
        }

        $query->orderBy('kode_jenis_barang');
        $query->orderByRaw('SUBSTRING_INDEX(pembelian_detail.kode_barang, "-", 1), CAST(SUBSTRING_INDEX(pembelian_detail.kode_barang, "-", -1) AS UNSIGNED)');
        $query->groupBy('pembelian_detail.kode_barang', 'nama_barang', 'satuan', 'kode_jenis_barang', 'jml_jk');
        $data['bahankemasan'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['jenis_barang'] = config('pembelian.jenis_barang');
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Bahan Kemasan $request->dari-$request->sampai.xls");
        }
        return view('pembelian.laporan.bahankemasan_cetak', $data);
    }

    public function cetakrekapbahankemasan(Request $request)
    {


        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Detailpembelian::query();
        $query->select(
            'pembelian_detail.no_bukti',
            'pembelian.tanggal',
            'pembelian.kode_supplier',
            'nama_supplier',
            'nama_barang',
            'jumlah',
            'harga',
            'penyesuaian',
            'jml_jk'
        );
        $query->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $query->leftJoin(
            DB::raw("(
                SELECT
                no_bukti,
                kode_barang,
                SUM(jumlah * harga) AS jml_jk
            FROM
                pembelian_jurnalkoreksi
            WHERE debet_kredit = 'K' AND kode_akun = '5-1101' AND tanggal BETWEEN '$request->dari' AND '$request->sampai'
            GROUP BY
                no_bukti,
                kode_barang
            ) jurnal_koreksi"),
            function ($join) {
                $join->on('pembelian_detail.no_bukti', '=', 'jurnal_koreksi.no_bukti');
                $join->on('pembelian_detail.kode_barang', '=', 'jurnal_koreksi.kode_barang');
            }
        );
        $query->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai]);
        $query->where('pembelian_detail.kode_barang', $request->kode_barang);
        if (!empty($request->kode_supplier_rekapbahankemasan)) {
            $query->where('pembelian.kode_supplier', $request->kode_supplier_rekapbahankemasan);
        }
        $query->orderBy('pembelian.kode_supplier');
        $query->orderBy('pembelian.tanggal');
        $data['rekapbahankemasan'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['supplier'] = Supplier::where('kode_supplier', $request->kode_supplier_rekapbahankemasan)->first();
        $data['barang'] = Barangpembelian::where('kode_barang', $request->kode_barang)->first();
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Bahan Kemasan $request->dari-$request->sampai.xls");
        }
        return view('pembelian.laporan.rekapbahankemasan_cetak', $data);
    }

    public function cetakjurnalkoreksi(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $query = Jurnalkoreksi::query();
        $query->leftJoin('pembelian', 'pembelian_jurnalkoreksi.no_bukti', '=', 'pembelian.no_bukti');
        $query->leftJoin('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $query->leftJoin('pembelian_barang', 'pembelian_jurnalkoreksi.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->leftJoin('coa', 'pembelian_jurnalkoreksi.kode_akun', '=', 'coa.kode_akun');
        $query->whereBetween('pembelian_jurnalkoreksi.tanggal', [$request->dari, $request->sampai]);
        $jurnalkoreksi = $query->get();
        $data['jurnalkoreksi'] = $jurnalkoreksi;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Jurnal Koreksi $request->dari-$request->sampai.xls");
        }
        return view('pembelian.laporan.jurnalkoreksi_cetak', $data);
    }

    public function cetakrekapakun(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Detailpembelian::query();
        $query->select(
            'pembelian_detail.kode_akun',
            'jk.jurnaldebet',
            'jk.jurnalkredit',
            'coa.nama_akun',
            'pembelian_detail.kode_transaksi',
            DB::raw('SUM((ROUND(jumlah * harga,2)) + ROUND(penyesuaian,2)) as total')
        );
        $query->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $query->leftJoin('coa', 'pembelian_detail.kode_akun', '=', 'coa.kode_akun');
        $query->leftJoin(
            DB::raw("(
                SELECT kode_akun,
                SUM(IF(debet_kredit='D',(pembelian_jurnalkoreksi.jumlah*pembelian_jurnalkoreksi.harga),0)) as jurnaldebet,
                SUM(IF(debet_kredit='K',(pembelian_jurnalkoreksi.jumlah*pembelian_jurnalkoreksi.harga),0)) as jurnalkredit
                FROM pembelian_jurnalkoreksi
                WHERE pembelian_jurnalkoreksi.tanggal BETWEEN '$request->dari' AND '$request->sampai'
                GROUP BY kode_akun
            ) jk"),
            function ($join) {
                $join->on('pembelian_detail.kode_akun', '=', 'jk.kode_akun');
            }
        );

        $query->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai]);
        if ($request->ppn === "0") {
            $query->where('pembelian.ppn', 0);
        } else if ($request->ppn === "1") {
            $query->where('pembelian.ppn', 1);
        }
        $query->groupBy(
            'pembelian_detail.kode_akun',
            'jk.jurnaldebet',
            'jk.jurnalkredit',
            'coa.nama_akun',
            'pembelian_detail.kode_transaksi'
        );
        $query->orderBy('pembelian_detail.kode_akun');
        $pmb = $query->get();

        $hutang = Detailpembelian::select(
            'pembelian.kode_akun',
            'nama_akun',
            DB::raw('IFNULL(jurnaldebet,0) as jurnaldebet'),
            DB::raw('IFNULL(jurnalkredit,0) as jurnalkredit'),
            DB::raw("SUM(IF( pembelian_detail.kode_transaksi = 'PMB',( ROUND(pembelian_detail.jumlah * pembelian_detail.harga,2) ) + penyesuaian, 0 )) AS pmb"),
            DB::raw("SUM(IF( pembelian_detail.kode_transaksi = 'PNJ', ( ROUND(pembelian_detail.jumlah * pembelian_detail.harga,2) ), 0 )) AS pnj")
        )
            ->leftjoin('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti')
            ->leftjoin('coa', 'pembelian.kode_akun', '=', 'coa.kode_akun')
            ->leftJoin(
                DB::raw("(
                    SELECT kode_akun,
                    SUM(IF(debet_kredit='D',(pembelian_jurnalkoreksi.jumlah*pembelian_jurnalkoreksi.harga),0)) as jurnaldebet,
                    SUM(IF(debet_kredit='K',(pembelian_jurnalkoreksi.jumlah*pembelian_jurnalkoreksi.harga),0)) as jurnalkredit
                    FROM pembelian_jurnalkoreksi
                    WHERE tanggal BETWEEN '$request->dari' AND '$request->sampai'
                    GROUP BY kode_akun
                ) jk"),
                function ($join) {
                    $join->on('pembelian.kode_akun', '=', 'jk.kode_akun');
                }
            )
            ->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai])
            ->groupByRaw('pembelian.kode_akun,nama_akun,jurnaldebet,jurnalkredit')
            ->get();

        $akunpembelian = Detailpembelian::select('pembelian_detail.kode_akun')
            ->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti')
            ->whereBetween('pembelian.tanggal', [$request->dari, $request->sampai])
            ->groupBy('pembelian_detail.kode_akun')
            ->get();

        $akun_pmb = [];
        foreach ($akunpembelian as $d) {
            $akun_pmb[] = $d->kode_akun;
        }

        $jurnalkoreksi = Jurnalkoreksi::select(
            'pembelian_jurnalkoreksi.kode_akun',
            'nama_akun',
            DB::raw('SUM(IF(debet_kredit="D",jumlah*harga,0)) as jurnaldebet'),
            DB::raw('SUM(IF(debet_kredit="K",jumlah*harga,0)) as jurnalkredit')
        )
            ->join('coa', 'pembelian_jurnalkoreksi.kode_akun', 'coa.kode_akun')
            ->whereNotIn('pembelian_jurnalkoreksi.kode_akun', $akun_pmb)
            ->whereNotIn('pembelian_jurnalkoreksi.kode_akun', ['2-1200', '2-1300'])
            ->whereBetween('tanggal', [$request->dari, $request->sampai])
            ->groupByRaw('pembelian_jurnalkoreksi.kode_akun,nama_akun')
            ->get();


        $data['pmb'] = $pmb;
        $data['jurnalkoreksi'] = $jurnalkoreksi;
        $data['hutang'] = $hutang;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        // $data['ppn'] = $request->ppn;
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Akun $request->dari-$request->sampai.xls");
        }
        return view('pembelian.laporan.rekapakun_cetak', $data);
    }

    public function cetakrekapkontrabon(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $query = Detailkontrabonpembelian::query();
        $query->select(
            'no_dokumen',
            'nama_supplier',
            DB::raw('SUM(pembelian_kontrabon_detail.jumlah) as jumlah'),
            'ppn',
            'no_rekening_supplier'
        );

        $query->leftJoin('pembelian_kontrabon', 'pembelian_kontrabon_detail.no_kontrabon', '=', 'pembelian_kontrabon.no_kontrabon');
        $query->leftJoin('supplier', 'pembelian_kontrabon.kode_supplier', '=', 'supplier.kode_supplier');
        $query->leftJoin('pembelian', 'pembelian_kontrabon_detail.no_bukti', '=', 'pembelian.no_bukti');
        $query->whereBetween('pembelian_kontrabon.tanggal', [$request->dari, $request->sampai]);
        $query->where('ppn', 0);
        $query->orderBy('pembelian_kontrabon.tanggal');
        $query->groupByRaw("pembelian_kontrabon_detail.no_kontrabon,no_dokumen,nama_supplier,ppn,no_rekening_supplier");
        $data['kb'] = $query->get();

        $query2 = Detailkontrabonpembelian::query();
        $query2->select(
            'no_dokumen',
            'nama_supplier',
            DB::raw('SUM(pembelian_kontrabon_detail.jumlah) as jumlah'),
            'ppn',
            'no_rekening_supplier'
        );

        $query2->leftJoin('pembelian_kontrabon', 'pembelian_kontrabon_detail.no_kontrabon', '=', 'pembelian_kontrabon.no_kontrabon');
        $query2->leftJoin('supplier', 'pembelian_kontrabon.kode_supplier', '=', 'supplier.kode_supplier');
        $query2->leftJoin('pembelian', 'pembelian_kontrabon_detail.no_bukti', '=', 'pembelian.no_bukti');
        $query2->whereBetween('pembelian_kontrabon.tanggal', [$request->dari, $request->sampai]);
        $query2->where('ppn', 1);
        $query->orderBy('pembelian_kontrabon.tanggal');
        $query2->groupByRaw("pembelian_kontrabon_detail.no_kontrabon,no_dokumen,nama_supplier,ppn,no_rekening_supplier");
        $data['pf'] = $query2->get();


        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Kontrabon $request->dari-$request->sampai.xls");
        }
        return view('pembelian.laporan.rekapkontrabon_cetak', $data);
    }
}

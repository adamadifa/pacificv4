<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailpenjualan;
use App\Models\Detailretur;
use App\Models\Historibayarpenjualan;
use App\Models\Penjualan;
use App\Models\Salesman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanmarketingController extends Controller
{
    public function index()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('marketing.laporan.index', $data);
    }


    public function cetakpenjualan(Request $request)
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

        if (empty($kode_cabang)) {
            return $this->cetakrekappenjualanallcabang($request);
        } else {
            if ($request->formatlaporan == '2') {
                return $this->cetakpenjualanformatsatubaris($request);
            } else if ($request->formatlaporan == '5') {
                return $this->cetakpenjualanformatkomisi($request);
            } else if ($request->formatlaporan == '3') {
                return $this->cetakpenjualanformatpo($request);
            }
        }
    }

    public function cetakrekappenjualanallcabang(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);



        $subqueryRetur = Detailretur::select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as total_retur'))
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai])
            ->where('jenis_retur', 'PF')
            ->groupBy('marketing_retur.no_faktur');




        // dd($subqueryRetur->get());

        $qpenjualan = Cabang::query();
        $qpenjualan->select(
            'cabang.kode_cabang',
            'nama_cabang',
            'total_bruto',
            'total_retur',
            'total_penyesuaian',
            'total_potongan',
            'total_potongan_istimewa',
            'total_ppn'

        );
        $qpenjualan->leftJoin(
            DB::raw("(
                    SELECT salesman.kode_cabang, SUM(subtotal) as total_bruto
                    FROM marketing_penjualan_detail
                    INNER JOIN marketing_penjualan ON marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur
                    INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                    WHERE tanggal BETWEEN '$request->dari' AND '$request->sampai'
                    GROUP BY salesman.kode_cabang
                ) detailpenjualan"),
            function ($join) {
                $join->on('cabang.kode_cabang', '=', 'detailpenjualan.kode_cabang');
            }
        );

        $qpenjualan->leftJoin(
            DB::raw("(
                    SELECT salesman.kode_cabang, SUM(potongan) as total_potongan,
                    SUM(penyesuaian) as total_penyesuaian,
                    SUM(potongan_istimewa) as total_potongan_istimewa,
                    SUM(ppn) as total_ppn
                    FROM marketing_penjualan
                    INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                    WHERE tanggal BETWEEN '$request->dari' AND '$request->sampai'
                    GROUP BY salesman.kode_cabang
                ) penjualan"),
            function ($join) {
                $join->on('cabang.kode_cabang', '=', 'penjualan.kode_cabang');
            }
        );
        $qpenjualan->leftJoin(
            DB::raw("(
                    SELECT salesman.kode_cabang, SUM(subtotal) as total_retur
                    FROM marketing_retur_detail
                    INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
                    INNER JOIN marketing_penjualan ON marketing_retur.no_faktur = marketing_penjualan.no_faktur
                    INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                    WHERE marketing_retur.tanggal BETWEEN '$request->dari' AND '$request->sampai'
                    AND jenis_retur = 'PF'
                    GROUP BY salesman.kode_cabang
                ) detailretur"),
            function ($join) {
                $join->on('cabang.kode_cabang', '=', 'detailretur.kode_cabang');
            }
        );

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $qpenjualan->where('cabang.kode_regional', $user->kode_regional);
            } else {
                $qpenjualan->where('cabang.kode_cabang', $user->kode_cabang);
            }
        }
        $penjualan = $qpenjualan->get();
        $data['penjualan'] = $penjualan;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        return view('marketing.laporan.penjualan_rekapallcabang_cetak', $data);
    }

    public function cetakpenjualanformatsatubaris(Request $request)
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

        $produk = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->select('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->orderBy('produk_harga.kode_produk')
            ->groupBy('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->get();


        $selectColumnkodeproduk = [];
        foreach ($produk as $d) {
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",jumlah,0)) as `qty_' . $d->kode_produk . '`');
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.harga_dus,0)) as `harga_dus_' . $d->kode_produk . '`');
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.harga_pack,0)) as `harga_pack_' . $d->kode_produk . '`');
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.harga_pcs,0)) as `harga_pcs_' . $d->kode_produk . '`');
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.subtotal,0)) as `subtotal_' . $d->kode_produk . '`');

            // $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '" AND status_promosi="1",jumlah,0)) as `qty_promosi_' . $d->kode_produk . '`');
        }


        $subqueryRetur = Detailretur::select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as total_retur'))
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('jenis_retur', 'PF')
            ->groupBy('marketing_retur.no_faktur');




        // dd($subqueryRetur->get());

        $qpenjualan = Detailpenjualan::query();
        $qpenjualan->select(
            'marketing_penjualan_detail.no_faktur',
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'pelanggan.hari',
            'salesman.nama_salesman',
            'klasifikasi',
            'nama_wilayah',
            DB::raw('SUM(subtotal) as bruto'),
            'total_retur',
            'potongan_aida',
            'potongan_swan',
            'potongan_stick',
            'potongan_sp',
            'potongan_sambal',
            'potongan_istimewa',
            'penyesuaian',
            'potongan',
            'ppn',
            'jenis_transaksi',
            'status',
            ...$selectColumnkodeproduk
        );

        $qpenjualan->addSelect(DB::raw('(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur) as total_bayar'));
        $qpenjualan->addSelect(DB::raw('(SELECT MAX(tanggal) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur) as lastpayment'));
        $qpenjualan->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $qpenjualan->rightjoin('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qpenjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $qpenjualan->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $qpenjualan->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $qpenjualan->leftJoin('marketing_klasifikasi_outlet', 'pelanggan.kode_klasifikasi', 'marketing_klasifikasi_outlet.kode_klasifikasi');
        $qpenjualan->leftJoin('wilayah', 'pelanggan.kode_wilayah', 'wilayah.kode_wilayah');
        $qpenjualan->leftJoinsub($subqueryRetur, 'retur', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'retur.no_faktur');
        });




        $qpenjualan->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        $qpenjualan->where('salesman.kode_cabang', $kode_cabang);
        if (!empty($request->kode_salesman)) {
            $qpenjualan->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $qpenjualan->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }

        if (!empty($request->jenis_transaksi)) {
            $qpenjualan->where('marketing_penjualan.jenis_transaksi', $request->jenis_transaksi);
        }
        $qpenjualan->orderBy('marketing_penjualan.tanggal');
        $qpenjualan->orderBy('marketing_penjualan.no_faktur');
        $qpenjualan->groupBy(
            'marketing_penjualan_detail.no_faktur',
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'pelanggan.hari',
            'salesman.nama_salesman',
            'klasifikasi',
            'nama_wilayah',
            'total_retur',
            'potongan_aida',
            'potongan_swan',
            'potongan_stick',
            'potongan_sp',
            'potongan_sambal',
            'potongan_istimewa',
            'penyesuaian',
            'potongan',
            'ppn',
            'jenis_transaksi',
            'status'
        );

        //dd($subqueryDetailpenjualan->first());

        // $qpenjualan = Penjualan::query();
        // $qpenjualan->select(
        //     'marketing_penjualan.no_faktur',
        //     'marketing_penjualan.tanggal',
        //     'marketing_penjualan.kode_pelanggan',
        //     'pelanggan.nama_pelanggan',
        //     'pelanggan.hari',
        //     'salesman.nama_salesman',
        //     'klasifikasi',
        //     'nama_wilayah',
        //     ...$selectColumns,

        // );
        // $qpenjualan->leftJoinsub($subqueryDetailpenjualan, 'detailpenjualan', function ($join) {
        //     $join->on('marketing_penjualan.no_faktur', '=', 'detailpenjualan.no_faktur');
        // });
        // $qpenjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        // $qpenjualan->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        // $qpenjualan->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        // $qpenjualan->leftJoin('marketing_klasifikasi_outlet', 'pelanggan.kode_klasifikasi', 'marketing_klasifikasi_outlet.kode_klasifikasi');
        // $qpenjualan->leftJoin('wilayah', 'pelanggan.kode_wilayah', 'wilayah.kode_wilayah');
        // $qpenjualan->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        // $qpenjualan->where('salesman.kode_cabang', $kode_cabang);

        $penjualan = $qpenjualan->get();

        // dd($penjualan);

        $data['penjualan'] = $penjualan;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['produk'] = $produk;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Laporan Penjualan Format Satu Baris $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.penjualan_formatsatubaris_cetak', $data);
    }

    public function cetakpenjualanformatkomisi(Request $request)
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

        $produk = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->select('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->orderBy('produk_harga.kode_produk')
            ->groupBy('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->get();


        $selectColumnkodeproduk = [];
        foreach ($produk as $d) {
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '" AND status_promosi="0",jumlah,0)) as `qty_' . $d->kode_produk . '`');
            // $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.harga_dus,0)) as `harga_dus_' . $d->kode_produk . '`');
            // $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.harga_pack,0)) as `harga_pack_' . $d->kode_produk . '`');
            // $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.harga_pcs,0)) as `harga_pcs_' . $d->kode_produk . '`');
            // $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.subtotal,0)) as `subtotal_' . $d->kode_produk . '`');

            // $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '" AND status_promosi="1",jumlah,0)) as `qty_promosi_' . $d->kode_produk . '`');
        }


        $subqueryRetur = Detailretur::select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as total_retur'))
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('jenis_retur', 'PF')
            ->groupBy('marketing_retur.no_faktur');




        // dd($subqueryRetur->get());

        $qpenjualan = Detailpenjualan::query();
        $qpenjualan->select(
            'marketing_penjualan_detail.no_faktur',
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'pelanggan.hari',
            'salesman.nama_salesman',
            'klasifikasi',
            'nama_wilayah',
            DB::raw('SUM(subtotal) as bruto'),
            'total_retur',
            'potongan_aida',
            'potongan_swan',
            'potongan_stick',
            'potongan_sp',
            'potongan_sambal',
            'potongan_istimewa',
            'penyesuaian',
            'potongan',
            'ppn',
            'jenis_transaksi',
            'status',
            ...$selectColumnkodeproduk
        );

        $qpenjualan->addSelect(DB::raw('(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur) as total_bayar'));
        $qpenjualan->addSelect(DB::raw('(SELECT MAX(tanggal) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur) as lastpayment'));
        $qpenjualan->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $qpenjualan->rightjoin('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qpenjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $qpenjualan->leftJoin(
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
                    MAX(id) AS id,
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                WHERE tanggal <= '$request->dari'
                GROUP BY
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru,
                    salesman.kode_cabang
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $qpenjualan->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $qpenjualan->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $qpenjualan->leftJoin('marketing_klasifikasi_outlet', 'pelanggan.kode_klasifikasi', 'marketing_klasifikasi_outlet.kode_klasifikasi');
        $qpenjualan->leftJoin('wilayah', 'pelanggan.kode_wilayah', 'wilayah.kode_wilayah');
        $qpenjualan->leftJoinsub($subqueryRetur, 'retur', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'retur.no_faktur');
        });




        $qpenjualan->whereBetween('marketing_penjualan.tanggal_pelunasan', [$request->dari, $request->sampai]);
        $qpenjualan->where('kode_cabang_baru', $kode_cabang);

        if (!empty($request->kode_salesman)) {
            $qpenjualan->where('kode_salesman_baru', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $qpenjualan->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }

        if (!empty($request->jenis_transaksi)) {
            $qpenjualan->where('marketing_penjualan.jenis_transaksi', $request->jenis_transaksi);
        }
        $qpenjualan->orderBy('marketing_penjualan.tanggal');
        $qpenjualan->orderBy('marketing_penjualan.no_faktur');
        $qpenjualan->groupBy(
            'marketing_penjualan_detail.no_faktur',
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'pelanggan.hari',
            'salesman.nama_salesman',
            'klasifikasi',
            'nama_wilayah',
            'total_retur',
            'potongan_aida',
            'potongan_swan',
            'potongan_stick',
            'potongan_sp',
            'potongan_sambal',
            'potongan_istimewa',
            'penyesuaian',
            'potongan',
            'ppn',
            'jenis_transaksi',
            'status'
        );

        $penjualan = $qpenjualan->get();

        // dd($penjualan);

        $data['penjualan'] = $penjualan;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['produk'] = $produk;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Laporan Penjualan Format Satu Baris $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.penjualan_formatkomisi_cetak', $data);
    }


    public function cetakpenjualanformatpo(Request $request)
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

        $produk = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->select('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->orderBy('produk_harga.kode_produk')
            ->groupBy('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->get();


        $selectColumnkodeproduk = [];
        foreach ($produk as $d) {
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",jumlah,0)) as `qty_' . $d->kode_produk . '`');
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.harga_dus,0)) as `harga_dus_' . $d->kode_produk . '`');
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.harga_pack,0)) as `harga_pack_' . $d->kode_produk . '`');
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.harga_pcs,0)) as `harga_pcs_' . $d->kode_produk . '`');
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",marketing_penjualan_detail.subtotal,0)) as `subtotal_' . $d->kode_produk . '`');

            // $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '" AND status_promosi="1",jumlah,0)) as `qty_promosi_' . $d->kode_produk . '`');
        }


        $subqueryRetur = Detailretur::select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as total_retur'))
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('jenis_retur', 'PF')
            ->groupBy('marketing_retur.no_faktur');




        // dd($subqueryRetur->get());

        $qpenjualan = Detailpenjualan::query();
        $qpenjualan->select(
            'marketing_penjualan_detail.no_faktur',
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'pelanggan.hari',
            'salesman.nama_salesman',
            'klasifikasi',
            'nama_wilayah',
            DB::raw('SUM(subtotal) as bruto'),
            'total_retur',
            'potongan_aida',
            'potongan_swan',
            'potongan_stick',
            'potongan_sp',
            'potongan_sambal',
            'potongan_istimewa',
            'penyesuaian',
            'potongan',
            'ppn',
            'jenis_transaksi',
            'status',
            ...$selectColumnkodeproduk
        );

        $qpenjualan->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $qpenjualan->rightjoin('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qpenjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $qpenjualan->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $qpenjualan->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $qpenjualan->leftJoin('marketing_klasifikasi_outlet', 'pelanggan.kode_klasifikasi', 'marketing_klasifikasi_outlet.kode_klasifikasi');
        $qpenjualan->leftJoin('wilayah', 'pelanggan.kode_wilayah', 'wilayah.kode_wilayah');
        $qpenjualan->leftJoinsub($subqueryRetur, 'retur', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'retur.no_faktur');
        });




        $qpenjualan->whereBetween('marketing_penjualan.created_at', [$request->dari, $request->sampai]);
        $qpenjualan->where('salesman.kode_cabang', $kode_cabang);
        $qpenjualan->where('salesman.kode_kategori_salesman', 'TO');
        if (!empty($request->kode_salesman)) {
            $qpenjualan->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $qpenjualan->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }

        if (!empty($request->jenis_transaksi)) {
            $qpenjualan->where('marketing_penjualan.jenis_transaksi', $request->jenis_transaksi);
        }
        $qpenjualan->orderBy('marketing_penjualan.created_at');
        $qpenjualan->orderBy('marketing_penjualan.no_faktur');
        $qpenjualan->groupBy(
            'marketing_penjualan_detail.no_faktur',
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'pelanggan.hari',
            'salesman.nama_salesman',
            'klasifikasi',
            'nama_wilayah',
            'total_retur',
            'potongan_aida',
            'potongan_swan',
            'potongan_stick',
            'potongan_sp',
            'potongan_sambal',
            'potongan_istimewa',
            'penyesuaian',
            'potongan',
            'ppn',
            'jenis_transaksi',
            'status'
        );


        $penjualan = $qpenjualan->get();

        // dd($penjualan);

        $data['penjualan'] = $penjualan;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['produk'] = $produk;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Laporan Penjualan Format Satu Baris $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.penjualan_formatpo_cetak', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailgiro;
use App\Models\Detailpenjualan;
use App\Models\Detailretur;
use App\Models\Detailtransfer;
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
            } else if ($request->formatlaporan == '1') {
                return $this->cetakpenjualanformatstandar($request);
            }
        }
    }


    public function cetakpenjualanformatstandar(Request $request)
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
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'pelanggan.hari',
            'salesman.nama_salesman',
            'klasifikasi',
            'nama_wilayah',
            'produk.nama_produk',
            'marketing_penjualan_detail.jumlah',
            'marketing_penjualan_detail.harga_dus',
            'marketing_penjualan_detail.harga_pack',
            'marketing_penjualan_detail.harga_pcs',
            'marketing_penjualan_detail.status_promosi',
            'produk.isi_pcs_dus',
            'produk.isi_pcs_pack',
            'marketing_penjualan_detail.subtotal',
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
            'marketing_penjualan.status',
            'marketing_penjualan.created_at',
            'marketing_penjualan.updated_at',
            'users.name as nama_user'
        );
        $qpenjualan->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) as total_bruto'));


        $qpenjualan->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $qpenjualan->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');

        $qpenjualan->rightjoin('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qpenjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $qpenjualan->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $qpenjualan->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $qpenjualan->leftJoin('marketing_klasifikasi_outlet', 'pelanggan.kode_klasifikasi', 'marketing_klasifikasi_outlet.kode_klasifikasi');
        $qpenjualan->leftJoin('wilayah', 'pelanggan.kode_wilayah', 'wilayah.kode_wilayah');
        $qpenjualan->leftJoinsub($subqueryRetur, 'retur', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'retur.no_faktur');
        });

        $qpenjualan->leftJoin('users', 'marketing_penjualan.id_user', '=', 'users.id');


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




        $penjualan = $qpenjualan->get();

        //dd($penjualan);

        $data['penjualan'] = $penjualan;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Laporan Penjualan Format Satu Baris $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.penjualan_formatstandar_cetak', $data);
    }
    public function cetakrekappenjualanallcabang(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $user = User::findorfail(auth()->user()->id);








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

    //Kasbesar

    public function cetakkasbesar(Request $request)
    {
        if ($request->formatlaporan == '1') {
            return $this->cetakkasbesardetail($request);
        } else if ($request->formatlaporan == '2') {
            return $this->cetakkasbesbesarrekap($request);
        } else if ($request->formatlaporan == '3') {
            return $this->cetakkasbesarlhp($request);
        }
    }

    public function cetakkasbesardetail(Request $request)
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

        $query = Historibayarpenjualan::query();
        $query->select(
            'marketing_penjualan_historibayar.no_faktur',
            DB::raw('datediff(marketing_penjualan_historibayar.tanggal,marketing_penjualan.tanggal) as ljt'),
            'salesman.nama_salesman',
            'nama_wilayah',
            'penagih.nama_salesman as penagih',
            'marketing_penjualan.tanggal as tgltransaksi',
            'marketing_penjualan_historibayar.tanggal as tglbayar',
            'marketing_penjualan_historibayar.jumlah as jmlbayar',
            'marketing_penjualan_historibayar.jumlah as lastpayment',
            'giro_to_cash',
            'voucher',
            'jenis_voucher',
            'marketing_penjualan.status',
            'marketing_penjualan.jenis_transaksi',
            'marketing_penjualan_historibayar.jenis_bayar',
            'marketing_penjualan_giro.no_giro',
            'marketing_penjualan_giro.bank_pengirim as bank_pengirim_giro',
            'marketing_penjualan_giro_detail.jumlah as jumlah_giro',

            'marketing_penjualan_transfer.bank_pengirim as bank_pengirim_transfer',
            'marketing_penjualan_transfer_detail.jumlah as jumlah_transfer',
            'marketing_penjualan_historibayar.kode_salesman',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            'users.name as nama_user',
            'marketing_penjualan_historibayar.created_at',
            'marketing_penjualan_historibayar.no_bukti',

            'marketing_penjualan.potongan',
            'marketing_penjualan.penyesuaian',
            'marketing_penjualan.potongan_istimewa',
            'marketing_penjualan.ppn',
        );
        $query->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan_historibayar.no_faktur) as total_bruto'));
        $query->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
        WHERE no_faktur = marketing_penjualan_historibayar.no_faktur AND jenis_retur="PF") as total_retur'));
        $query->addSelect(DB::raw('(SELECT SUM(jumlah)
        FROM marketing_penjualan_historibayar as historibayar
        WHERE historibayar.no_faktur = marketing_penjualan_historibayar.no_faktur
        AND historibayar.tanggal <= marketing_penjualan_historibayar.tanggal AND historibayar.tanggal >= marketing_penjualan.tanggal) as totalbayar'));

        $query->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $query->leftJoin(
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
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('salesman as penagih', 'marketing_penjualan_historibayar.kode_salesman', '=', 'penagih.kode_salesman');

        $query->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti');
        $query->leftJoin('marketing_penjualan_giro', 'marketing_penjualan_historibayar_giro.kode_giro', '=', 'marketing_penjualan_giro.kode_giro');
        $query->leftJoin('marketing_penjualan_giro_detail', function ($join) {
            $join->on('marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
                ->on('marketing_penjualan_giro_detail.no_faktur', '=', 'marketing_penjualan_historibayar.no_faktur');
        });

        $query->leftJoin('marketing_penjualan_historibayar_transfer', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_transfer.no_bukti');
        $query->leftJoin('marketing_penjualan_transfer', 'marketing_penjualan_historibayar_transfer.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer');
        $query->leftJoin('marketing_penjualan_transfer_detail', function ($join) {
            $join->on('marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer')
                ->on('marketing_penjualan_transfer_detail.no_faktur', '=', 'marketing_penjualan_historibayar.no_faktur');
        });

        $query->leftJoin('users', 'marketing_penjualan_historibayar.id_user', '=', 'users.id');
        $query->join('cabang', 'pindahfaktur.kode_cabang_baru', '=', 'cabang.kode_cabang');
        $query->orderBy('marketing_penjualan_historibayar.tanggal');
        $query->orderBy('marketing_penjualan_historibayar.no_faktur');
        $query->whereBetween('marketing_penjualan_historibayar.tanggal', [$request->dari, $request->sampai]);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if (empty($kode_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $query->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $query->where('kode_cabang_baru', $user->kode_cabang);
                }
            } else {
                $query->where('kode_cabang_baru', $kode_cabang);
            }
        } else {
            $query->where('kode_cabang_baru', $user->kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $query->where('marketing_penjualan_historibayar.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }

        if (!empty($request->jenis_bayar)) {
            $query->where('marketing_penjualan_historibayar.jenis_bayar', $request->jenis_bayar);
        }
        $query->where('voucher', 0);

        $data['kasbesar'] = $query->get();

        $qvoucher = Historibayarpenjualan::query();
        $qvoucher->select(
            'marketing_penjualan_historibayar.tanggal as tglbayar',
            'marketing_penjualan_historibayar.no_faktur',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'marketing_penjualan_historibayar.jumlah as jmlbayar',
            'nama_voucher'

        );
        $qvoucher->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qvoucher->leftJoin(
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
        $qvoucher->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $qvoucher->join('cabang', 'pindahfaktur.kode_cabang_baru', '=', 'cabang.kode_cabang');
        $qvoucher->join('jenis_voucher', 'marketing_penjualan_historibayar.jenis_voucher', '=', 'jenis_voucher.id');
        $qvoucher->whereBetween('marketing_penjualan_historibayar.tanggal', [$request->dari, $request->sampai]);
        $qvoucher->where('voucher', 1);
        if (!$user->hasRole($roles_access_all_cabang)) {
            if (empty($kode_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $qvoucher->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $qvoucher->where('kode_cabang_baru', $user->kode_cabang);
                }
            } else {
                $qvoucher->where('kode_cabang_baru', $kode_cabang);
            }
        } else {
            $qvoucher->where('kode_cabang_baru', $user->kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $qvoucher->where('marketing_penjualan_historibayar.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $qvoucher->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }

        if (!empty($request->jenis_bayar)) {
            $qvoucher->where('marketing_penjualan_historibayar.jenis_bayar', $request->jenis_bayar);
        }
        $query->orderBy('marketing_penjualan_historibayar.tanggal');
        $query->orderBy('marketing_penjualan_historibayar.no_faktur');

        $data['voucher'] = $qvoucher->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();

        return view('marketing.laporan.kasbesar_cetak', $data);
    }

    public function cetakkasbesbesarrekap(Request $request)
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
            $query = Historibayarpenjualan::query();
            $query->select(
                'cabang.kode_cabang',
                'cabang.nama_cabang',
                DB::raw("SUM(IF(voucher=1,jumlah,0)) as voucher"),
                DB::raw("SUM(IF(voucher=0,jumlah,0)) as cash_in"),
                DB::raw("SUM(jumlah) as total"),
            );
            $query->join('salesman', 'marketing_penjualan_historibayar.kode_salesman', '=', 'salesman.kode_salesman');
            $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
            $query->whereBetween('marketing_penjualan_historibayar.tanggal', [$request->dari, $request->sampai]);
            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $query->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $query->where('cabang.kode_cabang', $user->kode_cabang);
                }
            }
            if (!empty($request->jenis_bayar)) {
                $query->where('marketing_penjualan_historibayar.jenis_bayar', $request->jenis_bayar);
            }

            $query->groupBy('cabang.kode_cabang', 'cabang.nama_cabang');
            $query->orderBy('cabang.kode_cabang');
            $data['rekap'] = $query->get();
            $data['dari'] = $request->dari;
            $data['sampai'] = $request->sampai;
            return view('marketing.laporan.kasbesar_rekapcabang_cetak', $data);
        } else {
            $query = Historibayarpenjualan::query();
            $query->select(
                'marketing_penjualan_historibayar.kode_salesman',
                'salesman.nama_salesman',
                DB::raw("SUM(IF(voucher=1,jumlah,0)) as voucher"),
                DB::raw("SUM(IF(voucher=0,jumlah,0)) as cash_in"),
                DB::raw("SUM(jumlah) as total"),
            );
            $query->join('salesman', 'marketing_penjualan_historibayar.kode_salesman', '=', 'salesman.kode_salesman');
            $query->whereBetween('marketing_penjualan_historibayar.tanggal', [$request->dari, $request->sampai]);

            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $query->where('salesman.kode_cabang', $request->kode_cabang);
                } else {
                    $query->where('cabang.kode_cabang', $user->kode_cabang);
                }
            } else {
                $query->where('salesman.kode_cabang', $request->kode_cabang);
            }
            if (!empty($request->jenis_bayar)) {
                $query->where('marketing_penjualan_historibayar.jenis_bayar', $request->jenis_bayar);
            }

            $query->groupBy('marketing_penjualan_historibayar.kode_salesman', 'salesman.nama_salesman');
            $query->orderBy('salesman.nama_salesman');
            $data['rekap'] = $query->get();
            $data['dari'] = $request->dari;
            $data['sampai'] = $request->sampai;
            return view('marketing.laporan.kasbesar_rekapsalesman_cetak', $data);
        }
    }

    public function cetakkasbesarlhp(Request $request)
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

        $query = Historibayarpenjualan::query();
        $query->select(
            'marketing_penjualan_historibayar.no_faktur',
            'marketing_penjualan_historibayar.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'marketing_penjualan.jenis_transaksi',
            'marketing_penjualan_historibayar.jumlah as jmlbayar',
            'marketing_penjualan_historibayar_giro.giro_to_cash',
            'voucher',
            'nama_voucher',
            'marketing_penjualan_historibayar.jenis_bayar',
        );
        $query->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'marketing_penjualan_historibayar.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti');
        $query->leftJoin('marketing_penjualan_historibayar_transfer', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_transfer.no_bukti');
        $query->leftJoin('jenis_voucher', 'marketing_penjualan_historibayar.jenis_voucher', '=', 'jenis_voucher.id');
        $query->orderBy('marketing_penjualan_historibayar.tanggal');
        $query->orderBy('marketing_penjualan_historibayar.no_faktur');

        $query->whereBetween('marketing_penjualan_historibayar.tanggal', [$request->dari, $request->sampai]);
        $query->where('voucher', 0);
        $query->whereNull('marketing_penjualan_historibayar_giro.kode_giro');
        $query->whereNull('marketing_penjualan_historibayar_transfer.kode_transfer');



        if (!$user->hasRole($roles_access_all_cabang)) {
            if (empty($kode_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $query->where('cabang.kode_regionald', $user->kode_regional);
                } else {
                    $query->where('salesman.kode_cabang', $user->kode_cabang);
                }
            } else {
                $query->where('salesman.kode_cabang', $kode_cabang);
            }
        } else {
            $query->where('salesman.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $query->where('marketing_penjualan_historibayar.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }

        if (!empty($request->jenis_bayar)) {
            $query->where('marketing_penjualan_historibayar.jenis_bayar', $request->jenis_bayar);
        }


        $query->orwhereBetween('marketing_penjualan_historibayar.tanggal', [$request->dari, $request->sampai]);
        $query->where('voucher', 0);
        $query->whereNotNull('marketing_penjualan_historibayar_giro.kode_giro');
        $query->whereNull('marketing_penjualan_historibayar_transfer.kode_transfer');
        $query->where('marketing_penjualan_historibayar_giro.giro_to_cash', 1);


        if (!$user->hasRole($roles_access_all_cabang)) {
            if (empty($kode_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $query->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $query->where('salesman.kode_cabang', $user->kode_cabang);
                }
            } else {
                $query->where('salesman.kode_cabang', $kode_cabang);
            }
        } else {
            $query->where('salesman.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $query->where('marketing_penjualan_historibayar.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }

        if (!empty($request->jenis_bayar)) {
            $query->where('marketing_penjualan_historibayar.jenis_bayar', $request->jenis_bayar);
        }


        $querygiro = Detailgiro::query();
        $querygiro->select(
            'marketing_penjualan_giro_detail.no_faktur',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            'marketing_penjualan_giro.tanggal',
            'no_giro',
            'bank_pengirim',
            'jumlah as jmlbayar',
            'marketing_penjualan_giro.jatuh_tempo',
            'marketing_penjualan_giro.status'
        );
        $querygiro->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro');
        $querygiro->join('marketing_penjualan', 'marketing_penjualan_giro_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $querygiro->join('salesman', 'marketing_penjualan_giro.kode_salesman', '=', 'salesman.kode_salesman');
        $querygiro->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $querygiro->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $querygiro->whereBetween('marketing_penjualan_giro.tanggal', [$request->dari, $request->sampai]);
        if (!$user->hasRole($roles_access_all_cabang)) {
            if (empty($kode_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $querygiro->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $querygiro->where('salesman.kode_cabang', $user->kode_cabang);
                }
            } else {
                $querygiro->where('salesman.kode_cabang', $kode_cabang);
            }
        } else {
            $querygiro->where('salesman.kode_cabang', $kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $querygiro->where('marketing_penjualan_giro.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $querygiro->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }



        $querytransfer = Detailtransfer::query();
        $querytransfer->select(
            'marketing_penjualan_transfer_detail.kode_transfer',
            'marketing_penjualan_transfer_detail.no_faktur',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            'marketing_penjualan_transfer.tanggal',
            'bank_pengirim',
            'jumlah as jmlbayar',
            'marketing_penjualan_transfer.jatuh_tempo',
            'marketing_penjualan_transfer.status',
            'historibayartransfer.giro_to_cash'
        );
        $querytransfer->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer');
        $querytransfer->leftJoin(
            DB::raw("(
                SELECT marketing_penjualan_historibayar_transfer.no_bukti,kode_transfer,no_faktur,tanggal,giro_to_cash
                FROM marketing_penjualan_historibayar_transfer
                INNER JOIN marketing_penjualan_historibayar ON marketing_penjualan_historibayar_transfer.no_bukti = marketing_penjualan_historibayar.no_bukti
                INNER JOIN marketing_penjualan_historibayar_giro ON marketing_penjualan_historibayar.no_bukti = marketing_penjualan_historibayar_giro.no_bukti
                ) historibayartransfer"),
            function ($join) {
                $join->on('marketing_penjualan_transfer_detail.kode_transfer', '=', 'historibayartransfer.kode_transfer');
                $join->on('marketing_penjualan_transfer_detail.no_faktur', '=', 'historibayartransfer.no_faktur');
            }
        );
        $querytransfer->join('marketing_penjualan', 'marketing_penjualan_transfer_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $querytransfer->join('salesman', 'marketing_penjualan_transfer.kode_salesman', '=', 'salesman.kode_salesman');
        $querytransfer->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $querytransfer->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $querytransfer->whereBetween('marketing_penjualan_transfer.tanggal', [$request->dari, $request->sampai]);
        if (!$user->hasRole($roles_access_all_cabang)) {
            if (empty($kode_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $querytransfer->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $querytransfer->where('salesman.kode_cabang', $user->kode_cabang);
                }
            } else {
                $querytransfer->where('salesman.kode_cabang', $kode_cabang);
            }
        } else {
            $querytransfer->where('salesman.kode_cabang', $kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $querytransfer->where('marketing_penjualan_transfer.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $querytransfer->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }


        $queryvoucher = Historibayarpenjualan::query();
        $queryvoucher->select(
            'marketing_penjualan_historibayar.no_faktur',
            'marketing_penjualan_historibayar.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'marketing_penjualan.jenis_transaksi',
            'marketing_penjualan_historibayar.jumlah as jmlbayar',
            'marketing_penjualan_historibayar_giro.giro_to_cash',
            'voucher',
            'nama_voucher',
            'marketing_penjualan_historibayar.jenis_bayar',
        );
        $queryvoucher->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $queryvoucher->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $queryvoucher->join('salesman', 'marketing_penjualan_historibayar.kode_salesman', '=', 'salesman.kode_salesman');
        $queryvoucher->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $queryvoucher->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti');
        $queryvoucher->leftJoin('marketing_penjualan_historibayar_transfer', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_transfer.no_bukti');
        $queryvoucher->leftJoin('jenis_voucher', 'marketing_penjualan_historibayar.jenis_voucher', '=', 'jenis_voucher.id');
        $queryvoucher->orderBy('marketing_penjualan_historibayar.tanggal');
        $queryvoucher->orderBy('marketing_penjualan_historibayar.no_faktur');

        $queryvoucher->whereBetween('marketing_penjualan_historibayar.tanggal', [$request->dari, $request->sampai]);
        $queryvoucher->where('voucher', 1);

        if (!$user->hasRole($roles_access_all_cabang)) {
            if (empty($kode_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $queryvoucher->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $queryvoucher->where('salesman.kode_cabang', $user->kode_cabang);
                }
            } else {
                $queryvoucher->where('salesman.kode_cabang', $kode_cabang);
            }
        } else {
            $queryvoucher->where('salesman.kode_cabang', $kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $queryvoucher->where('marketing_penjualan_historibayar.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $queryvoucher->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }


        $data['kasbesar'] = $query->get();
        $data['kasbesargiro'] = $querygiro->get();
        $data['kasbesartransfer'] = $querytransfer->get();
        $data['kasbesarvoucher'] = $queryvoucher->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        return view('marketing.laporan.kasbesar_lhp_cetak', $data);
    }


    public function cetakretur(Request $request)
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

        $qretur = Detailretur::query();
        $qretur->select(
            'marketing_retur.no_retur',
            'marketing_retur.tanggal',
            'marketing_retur.no_faktur',
            'marketing_retur.no_ref',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'nama_wilayah',
            'hari',
            'produk.nama_produk',
            'produk.isi_pcs_dus',
            'produk.isi_pcs_pack',
            'marketing_retur_detail.jumlah',
            'marketing_retur_detail.harga_dus',
            'marketing_retur_detail.harga_pack',
            'marketing_retur_detail.harga_pcs',
            'marketing_retur_detail.subtotal',
            'marketing_penjualan.jenis_transaksi',
            'marketing_retur.jenis_retur',
            'marketing_retur.created_at',
            'marketing_retur.updated_at',

        );


        $qretur->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail WHERE no_retur = marketing_retur.no_retur) as total'));
        $qretur->join('produk_harga', 'marketing_retur_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $qretur->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $qretur->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $qretur->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qretur->leftJoin(
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
                WHERE tanggal <= '$request->tanggal'
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
        $qretur->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $qretur->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $qretur->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $qretur->leftJoin('marketing_klasifikasi_outlet', 'pelanggan.kode_klasifikasi', 'marketing_klasifikasi_outlet.kode_klasifikasi');
        $qretur->leftJoin('wilayah', 'pelanggan.kode_wilayah', 'wilayah.kode_wilayah');
        $qretur->leftJoin('users', 'marketing_retur.id_user', '=', 'users.id');


        $qretur->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai]);
        if (!empty($kode_cabang)) {
            $qretur->where('salesman.kode_cabang', $kode_cabang);
        } else {
            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $qretur->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $qretur->where('salesman.kode_cabang', $user->kode_cabang);
                }
            }
        }
        if (!empty($request->kode_salesman)) {
            $qretur->where('kode_salesman_baru', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $qretur->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }


        $qretur->orderBy('marketing_retur.tanggal');
        $qretur->orderBy('marketing_retur.no_faktur');




        $retur = $qretur->get();


        $data['retur'] = $retur;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Laporan Retur  $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.retur_cetak', $data);
    }

    public function cetaktunaikredit(Request $request)
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

        $query = Detailpenjualan::select(
            'produk_harga.kode_produk',
            'produk.nama_produk',
            'produk.isi_pcs_dus',
            'produk.isi_pcs_pack',
            DB::raw("SUM(IF(jenis_transaksi = 'T', jumlah, 0)) AS qty_tunai"),
            DB::raw("SUM(IF(jenis_transaksi = 'K', jumlah, 0)) AS qty_kredit"),
            DB::raw("SUM(IF(jenis_transaksi = 'T', subtotal, 0)) AS bruto_tunai"),
            DB::raw("SUM(IF(jenis_transaksi = 'K', subtotal, 0)) AS bruto_kredit"),
            DB::raw('SUM(jumlah) as qty_total'),
            DB::raw('SUM(subtotal) as bruto_total')
        );
        $query->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $query->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $query->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        $query->where('status_promosi', 0);
        if (!empty($kode_cabang)) {
            $query->where('salesman.kode_cabang', $kode_cabang);
        } else {
            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $query->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $query->where('salesman.kode_cabang', $user->kode_cabang);
                }
            }
        }
        if (!empty($request->kode_salesman)) {
            $query->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }


        $query->groupBy('produk_harga.kode_produk');
        $query->orderBy('produk_harga.kode_produk');
        $penjualan = $query->get();
        $data['penjualan'] = $penjualan;
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        return view('marketing.laporan.tunaikredit_cetak', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detaildpb;
use App\Models\Detailgiro;
use App\Models\Detailpenjualan;
use App\Models\Detailratiodriverhelper;
use App\Models\Detailretur;
use App\Models\Detailsaldoawalpiutangpelanggan;
use App\Models\Detailtargetkomisi;
use App\Models\Detailtransfer;
use App\Models\Dpb;
use App\Models\Dpbdriverhelper;
use App\Models\Historibayarpenjualan;
use App\Models\Kategorikomisi;
use App\Models\Kendaraan;
use App\Models\Penjualan;
use App\Models\Ratiokomisidriverhelper;
use App\Models\Retur;
use App\Models\Saldoawalpiutangpelanggan;
use App\Models\Salesman;
use App\Models\Targetkomisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LaporanmarketingController extends Controller
{
    public function index()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
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
            'users.name as nama_user',
            'marketing_penjualan.status_batal'
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
            'status_batal',
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
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$request->dari'
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
        $qpenjualan->where('marketing_penjualan.status_batal', 0);
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
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$request->dari'
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
                    $query->where('kode_cabang_baru', $kode_cabang);
                }
            } else {
                $query->where('kode_cabang_baru', $kode_cabang);
            }
        } else {
            $query->where('kode_cabang_baru', $kode_cabang);
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
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$request->dari'
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

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Kas Besar $request->dari-$request->sampai.xls");
        }
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
            if (isset($_POST['exportButton'])) {
                header("Content-type: application/vnd-ms-excel");
                // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                header("Content-Disposition: attachment; filename=Rekap Kas Besar Cabang  $request->dari-$request->sampai.xls");
            }
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
                    $query->where('salesman.kode_cabang', $user->kode_cabang);
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
            if (isset($_POST['exportButton'])) {
                header("Content-type: application/vnd-ms-excel");
                // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
                header("Content-Disposition: attachment; filename=Rekap Kas Besar Salesman  $request->dari-$request->sampai.xls");
            }
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
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Kas Besar LHP  $request->dari-$request->sampai.xls");
        }
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
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$request->dari'
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
        $query->where('status_batal', 0);
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

        $queryretur = Detailretur::select(
            DB::raw('SUM(IF(jenis_transaksi="T",subtotal,0)) as retur_tunai'),
            DB::raw('SUM(IF(jenis_transaksi="K",subtotal,0)) as retur_kredit'),
            DB::raw('SUM(subtotal) as retur_total')
        );
        $queryretur->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $queryretur->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $queryretur->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $queryretur->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $queryretur->whereBetween('marketing_retur.tanggal', [$request->dari, $request->sampai]);
        $queryretur->where('jenis_retur', 'PF');
        if (!empty($kode_cabang)) {
            $queryretur->where('salesman.kode_cabang', $kode_cabang);
        } else {
            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $queryretur->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $queryretur->where('salesman.kode_cabang', $user->kode_cabang);
                }
            }
        }
        if (!empty($request->kode_salesman)) {
            $queryretur->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }


        $queryPotongan = Penjualan::select(
            DB::raw('SUM(potongan) as potongan_total'),
            DB::raw('SUM(potongan_istimewa) as potongan_istimewa_total'),
            DB::raw('SUM(penyesuaian) as penyesuaian_total'),
            DB::raw('SUM(ppn) as ppn_total'),
            DB::raw('SUM(IF(jenis_transaksi="T", potongan, 0)) as potongan_tunai'),
            DB::raw('SUM(IF(jenis_transaksi="T", potongan_istimewa, 0)) as potongan_istimewa_tunai'),
            DB::raw('SUM(IF(jenis_transaksi="T", penyesuaian, 0)) as penyesuaian_tunai'),
            DB::raw('SUM(IF(jenis_transaksi="T", ppn, 0)) as ppn_tunai'),
            DB::raw('SUM(IF(jenis_transaksi="K", potongan, 0)) as potongan_kredit'),
            DB::raw('SUM(IF(jenis_transaksi="K", potongan_istimewa, 0)) as potongan_istimewa_kredit'),
            DB::raw('SUM(IF(jenis_transaksi="K", penyesuaian, 0)) as penyesuaian_kredit'),
            DB::raw('SUM(IF(jenis_transaksi="K", ppn, 0)) as ppn_kredit')
        );
        $queryPotongan->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $queryPotongan->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $queryPotongan->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        if (!empty($kode_cabang)) {
            $queryPotongan->where('salesman.kode_cabang', $kode_cabang);
        } else {
            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $queryPotongan->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $queryPotongan->where('salesman.kode_cabang', $user->kode_cabang);
                }
            }
        }
        if (!empty($request->kode_salesman)) {
            $queryPotongan->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }


        $data['penjualan'] = $penjualan;
        $data['retur'] = $queryretur->first();
        $data['potongan'] = $queryPotongan->first();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Tunai Kredit  $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.tunaikredit_cetak', $data);
    }

    public function cetakdpp(Request $request)
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
            ->when(!empty($kode_cabang), function ($query) use ($kode_cabang) {
                return $query->where('salesman.kode_cabang', $kode_cabang);
            })
            ->orderBy('produk_harga.kode_produk')
            ->groupBy('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->get();


        $selectColumnkodeproduk = [];
        foreach ($produk as $d) {
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",jumlah,0)) as `qty_' . $d->kode_produk . '`');
        }

        $query = Detailpenjualan::select(
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'nama_wilayah',
            'klasifikasi',
            'salesman.nama_salesman',
            ...$selectColumnkodeproduk
        );
        $query->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $query->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftjoin('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->leftjoin('marketing_klasifikasi_outlet', 'pelanggan.kode_klasifikasi', '=', 'marketing_klasifikasi_outlet.kode_klasifikasi');
        $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        $query->where('status_batal', 0);
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

        if (!empty($request->kode_pelanggan)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }

        $query->orderBy('marketing_penjualan.tanggal', 'asc');
        $query->orderBy('pelanggan.nama_pelanggan', 'asc');
        $query->groupBy(
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'nama_wilayah',
            'klasifikasi',
            'salesman.nama_salesman'
        );
        $data['dpp'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        $data['produk'] = $produk;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Data Pengambilan Pelanggan  $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.dpp_cetak', $data);
    }

    public function cetakomsetpelanggan(Request $request)
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

        $qdetailpenjualan = Detailpenjualan::query();
        $qdetailpenjualan->select(
            'marketing_penjualan_detail.no_faktur',
            DB::raw('SUM(subtotal) as total_bruto'),
        );
        $qdetailpenjualan->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qdetailpenjualan->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $qdetailpenjualan->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $qdetailpenjualan->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        if (!empty($kode_cabang)) {
            $qdetailpenjualan->where('salesman.kode_cabang', $kode_cabang);
        } else {
            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $qdetailpenjualan->where('cabang.kode_regional', $user->kode_regional);
                } else {
                    $qdetailpenjualan->where('salesman.kode_cabang', $user->kode_cabang);
                }
            }
        }
        if (!empty($request->kode_salesman)) {
            $qdetailpenjualan->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }

        if (!empty($request->kode_pelanggan)) {
            $qdetailpenjualan->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }
        $qdetailpenjualan->groupBy('marketing_penjualan_detail.no_faktur');
        $subqueryDetailpenjualan = $qdetailpenjualan;

        $query =  Penjualan::query();
        $query->select(
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            'nama_salesman',
            'nama_wilayah',
            'klasifikasi',
            DB::raw('SUM(total_bruto - potongan + penyesuaian + potongan_istimewa + ppn) as total_netto'),
        );




        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftjoin('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->leftjoin('marketing_klasifikasi_outlet', 'pelanggan.kode_klasifikasi', '=', 'marketing_klasifikasi_outlet.kode_klasifikasi');
        $query->leftjoinSub($subqueryDetailpenjualan, 'dp', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'dp.no_faktur');
        });
        $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        $query->where('status_batal', 0);
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

        if (!empty($request->kode_pelanggan)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }
        $query->groupBy(
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            'nama_salesman',
            'nama_wilayah',
            'klasifikasi',
        );
        $query->orderBy('pelanggan.nama_pelanggan', 'asc');

        $data['omsetpelanggan'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Omset Pelanggan $request->dari-$request->sampai.xls");
        }

        return view('marketing.laporan.omsetpelanggan_cetak', $data);
    }


    public function cetakrekappelanggan(Request $request)
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
            ->when(!empty($kode_cabang), function ($query) use ($kode_cabang) {
                return $query->where('salesman.kode_cabang', $kode_cabang);
            })
            ->orderBy('produk_harga.kode_produk')
            ->groupBy('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->get();


        $selectColumnkodeproduk = [];
        foreach ($produk as $d) {
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(produk_harga.kode_produk="' . $d->kode_produk . '",jumlah,0)) as `qty_' . $d->kode_produk . '`');
        }

        $query = Detailpenjualan::select(
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'nama_wilayah',
            'klasifikasi',
            'salesman.nama_salesman',
            DB::raw('COUNT(DISTINCT(kode_sku)) as total_sku'),
            ...$selectColumnkodeproduk
        );
        $query->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $query->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $query->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftjoin('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->leftjoin('marketing_klasifikasi_outlet', 'pelanggan.kode_klasifikasi', '=', 'marketing_klasifikasi_outlet.kode_klasifikasi');
        $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        $query->where('status_batal', 0);
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

        if (!empty($request->kode_pelanggan)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan);
        }


        $query->orderBy('pelanggan.nama_pelanggan', 'asc');
        $query->groupBy(
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'nama_wilayah',
            'klasifikasi',
            'salesman.nama_salesman'
        );
        $data['rekappelanggan'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        $data['produk'] = $produk;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Pelanggan  $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.rekappelanggan_cetak', $data);
    }

    public function cetakrekapkendaraan(Request $request)
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

        $query = Detaildpb::query();
        $query->select(
            'gudang_cabang_dpb_detail.kode_produk',
            'produk.nama_produk',
            'produk.isi_pcs_dus',
            DB::raw('SUM(jml_ambil) as jml_ambil'),
            DB::raw('SUM(jml_kembali) as jml_kembali'),
            'mutasigudangcabang.jml_penjualan',
            'mutasigudangcabang.jml_gantibarang',
            'mutasigudangcabang.jml_pelunasanhutangkirim',
            'mutasigudangcabang.jml_promosi',
            'mutasigudangcabang.jml_ttr',
            'gudang_cabang_dpb.kode_kendaraan'
        );
        $query->join('gudang_cabang_dpb', 'gudang_cabang_dpb_detail.no_dpb', '=', 'gudang_cabang_dpb.no_dpb');
        $query->join('produk', 'gudang_cabang_dpb_detail.kode_produk', '=', 'produk.kode_produk');
        $query->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman');
        $query->leftJoin(
            DB::raw("(
                    SELECT kode_produk,
                    SUM(IF(jenis_mutasi = 'PJ',jumlah,0)) as jml_penjualan,
                    SUM(IF(jenis_mutasi = 'PH',jumlah,0)) as jml_pelunasanhutangkirim,
                    SUM(IF(jenis_mutasi = 'PR',jumlah,0)) as jml_promosi,
                    SUM(IF(jenis_mutasi = 'TR',jumlah,0)) as jml_ttr,
                    SUM(IF(jenis_mutasi = 'GB',jumlah,0)) as jml_gantibarang,
                    SUM(IF(jenis_mutasi = 'RT',jumlah,0)) as jml_retur,
                    SUM(IF(jenis_mutasi = 'PT',jumlah,0)) as jml_pelunasanttr,
                    SUM(IF(jenis_mutasi = 'HK',jumlah,0)) as jml_hutangkirim,
                    kode_kendaraan
                    FROM gudang_cabang_mutasi_detail
                    INNER JOIN gudang_cabang_mutasi ON gudang_cabang_mutasi_detail.no_mutasi = gudang_cabang_mutasi.no_mutasi
                    INNER JOIN gudang_cabang_dpb ON gudang_cabang_mutasi.no_dpb = gudang_cabang_dpb.no_dpb
                    WHERE gudang_cabang_mutasi.tanggal BETWEEN '$request->dari' AND '$request->sampai' AND gudang_cabang_dpb.kode_kendaraan = '$request->kode_kendaraan'
                    GROUP BY kode_produk,kode_kendaraan
                ) mutasigudangcabang"),
            function ($join) {
                $join->on('gudang_cabang_dpb_detail.kode_produk', '=', 'mutasigudangcabang.kode_produk');
            }
        );
        $query->whereBetween('tanggal_ambil', [$request->dari, $request->sampai]);
        $query->where('gudang_cabang_dpb.kode_kendaraan', $request->kode_kendaraan);
        $query->where('salesman.kode_cabang', $kode_cabang);
        $query->orderBy('gudang_cabang_dpb_detail.kode_produk', 'asc');
        $query->groupBy(
            'gudang_cabang_dpb_detail.kode_produk',
            'gudang_cabang_dpb.kode_kendaraan',
            'produk.isi_pcs_dus',
            'jml_penjualan',
            'jml_gantibarang',
            'jml_pelunasanhutangkirim',
            'jml_promosi',
            'jml_ttr'
        );



        $qkendaraan = Dpb::query();
        $qkendaraan->select(
            'gudang_cabang_dpb.tanggal_ambil',
            'kode_kendaraan',
            DB::raw('count(no_dpb) as jml_ambil')
        );
        $qkendaraan->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman');
        $qkendaraan->where('kode_kendaraan', $request->kode_kendaraan);
        $qkendaraan->where('salesman.kode_cabang', $kode_cabang);
        $qkendaraan->wherebetween('tanggal_ambil', [$request->dari, $request->sampai]);
        $qkendaraan->groupBy('tanggal_ambil', 'kode_kendaraan');
        $qkendaraan->orderBy('tanggal_ambil');



        $data['rekapkendaraan'] = $query->get();
        $data['historikendaraan'] = $qkendaraan->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['kendaraan'] = Kendaraan::where('kode_kendaraan', $request->kode_kendaraan)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Kendaraan  $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.rekapkendaraan_cetak', $data);
    }



    public function cetakrekapwilayah(Request $request)
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

        $selectColumns = [];


        for ($i = 1; $i <= 12; $i++) {
            $selectColumns[] =  DB::raw('SUM(IF(MONTH(tanggal)=' . $i . ', (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn, 0)) as bulan_' . $i);
        }
        $query = Penjualan::query();
        $query->select(
            'pelanggan.kode_wilayah',
            'nama_wilayah',
            DB::raw('SUM((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn) as total'),
            ...$selectColumns

        );
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->whereRaw('YEAR(tanggal)="' . $request->tahun . '"');
        $query->where('salesman.kode_cabang', $kode_cabang);
        $query->where('status_batal', 0);
        $query->groupBy('pelanggan.kode_wilayah', 'nama_wilayah');
        $query->groupBy('nama_wilayah');

        $data['rekapwilayah'] = $query->get();
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['tahun'] = $request->tahun;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Wilayah  $request->tahun.xls");
        }
        return view('marketing.laporan.rekapwilayah_cetak', $data);
    }


    public function cetakanalisatransaksi(Request $request)
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

        $querypenjualan = Penjualan::query();
        $querypenjualan->select(
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            DB::raw('SUM(IF(DAY(tanggal) BETWEEN 1 AND 7 AND jenis_transaksi="T", (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn, 0)) as tunai_minggu_1'),
            DB::raw('SUM(IF(DAY(tanggal) BETWEEN 8 AND 14 AND jenis_transaksi="T", (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn, 0)) as tunai_minggu_2'),
            DB::raw('SUM(IF(DAY(tanggal) BETWEEN 15 AND 21 AND jenis_transaksi="T", (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn, 0)) as tunai_minggu_3'),
            DB::raw('SUM(IF(DAY(tanggal) BETWEEN 22 AND 31 AND jenis_transaksi="T", (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn, 0)) as tunai_minggu_4'),

            DB::raw('SUM(IF(DAY(tanggal) BETWEEN 1 AND 7 AND jenis_transaksi="K", (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn, 0)) as kredit_minggu_1'),
            DB::raw('SUM(IF(DAY(tanggal) BETWEEN 8 AND 14 AND jenis_transaksi="K", (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn, 0)) as kredit_minggu_2'),
            DB::raw('SUM(IF(DAY(tanggal) BETWEEN 15 AND 21 AND jenis_transaksi="K", (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn, 0)) as kredit_minggu_3'),
            DB::raw('SUM(IF(DAY(tanggal) BETWEEN 22 AND 31 AND jenis_transaksi="K", (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn, 0)) as kredit_minggu_4'),

            DB::raw('SUM((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn) as total_penjualan'),


            DB::raw('0 as cash_minggu_1'),
            DB::raw('0 as cash_minggu_2'),
            DB::raw('0 as cash_minggu_3'),
            DB::raw('0 as cash_minggu_4'),

            DB::raw('0 as titipan_minggu_1'),
            DB::raw('0 as titipan_minggu_2'),
            DB::raw('0 as titipan_minggu_3'),
            DB::raw('0 as titipan_minggu_4'),

            DB::raw('0 as transfer_minggu_1'),
            DB::raw('0 as transfer_minggu_2'),
            DB::raw('0 as transfer_minggu_3'),
            DB::raw('0 as transfer_minggu_4'),


            DB::raw('0 as giro_minggu_1'),
            DB::raw('0 as giro_minggu_2'),
            DB::raw('0 as giro_minggu_3'),
            DB::raw('0 as giro_minggu_4'),

            DB::raw('0 as total_pembayaran'),
            DB::raw('0 as qty'),

        );
        $querypenjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $querypenjualan->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $querypenjualan->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai]);
        if (!empty($kode_cabang)) {
            $querypenjualan->where('salesman.kode_cabang', $kode_cabang);
        }
        if (!empty($request->kode_salesman)) {
            $querypenjualan->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }
        $querypenjualan->where('status_batal', 0);
        $querypenjualan->groupBy('marketing_penjualan.kode_pelanggan', 'nama_pelanggan');
        $querypenjualan->groupBy('nama_pelanggan');

        $querypembayaran = Historibayarpenjualan::query();
        $querypembayaran->select(
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',


            DB::raw('0 as tunai_minggu_1'),
            DB::raw('0 as tunai_minggu_2'),
            DB::raw('0 as tunai_minggu_3'),
            DB::raw('0 as tunai_minggu_4'),

            DB::raw('0 as kredit_minggu_1'),
            DB::raw('0 as kredit_minggu_2'),
            DB::raw('0 as kredit_minggu_3'),
            DB::raw('0 as kredit_minggu_4'),

            DB::raw('0 as total_penjualan'),

            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 1 AND 7 AND marketing_penjualan_historibayar.jenis_bayar="TN", marketing_penjualan_historibayar.jumlah, 0)) as cash_minggu_1'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 8 AND 14 AND marketing_penjualan_historibayar.jenis_bayar="TN", marketing_penjualan_historibayar.jumlah, 0)) as cash_minggu_2'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 15 AND 21 AND marketing_penjualan_historibayar.jenis_bayar="TN", marketing_penjualan_historibayar.jumlah, 0)) as cash_minggu_3'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 22 AND 31 AND marketing_penjualan_historibayar.jenis_bayar="TN", marketing_penjualan_historibayar.jumlah, 0)) as cash_minggu_4'),

            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 1 AND 7 AND marketing_penjualan_historibayar.jenis_bayar="TP", marketing_penjualan_historibayar.jumlah, 0)) as titipan_minggu_1'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 8 AND 14 AND marketing_penjualan_historibayar.jenis_bayar="TP", marketing_penjualan_historibayar.jumlah, 0)) as titipan_minggu_2'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 15 AND 21 AND marketing_penjualan_historibayar.jenis_bayar="TP", marketing_penjualan_historibayar.jumlah, 0)) as titipan_minggu_3'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 22 AND 31 AND marketing_penjualan_historibayar.jenis_bayar="TP", marketing_penjualan_historibayar.jumlah, 0)) as titipan_minggu_4'),

            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 1 AND 7 AND marketing_penjualan_historibayar.jenis_bayar="TR", marketing_penjualan_historibayar.jumlah, 0)) as transfer_minggu_1'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 8 AND 14 AND marketing_penjualan_historibayar.jenis_bayar="TR", marketing_penjualan_historibayar.jumlah, 0)) as transfer_minggu_2'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 15 AND 21 AND marketing_penjualan_historibayar.jenis_bayar="TR", marketing_penjualan_historibayar.jumlah, 0)) as transfer_minggu_3'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 22 AND 31 AND marketing_penjualan_historibayar.jenis_bayar="TR", marketing_penjualan_historibayar.jumlah, 0)) as transfer_minggu_4'),

            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 1 AND 7 AND marketing_penjualan_historibayar.jenis_bayar="GR", marketing_penjualan_historibayar.jumlah, 0)) as giro_minggu_1'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 8 AND 14 AND marketing_penjualan_historibayar.jenis_bayar="GR", marketing_penjualan_historibayar.jumlah, 0)) as giro_minggu_2'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 15 AND 21 AND marketing_penjualan_historibayar.jenis_bayar="GR", marketing_penjualan_historibayar.jumlah, 0)) as giro_minggu_3'),
            DB::raw('SUM(IF(DAY(marketing_penjualan_historibayar.tanggal) BETWEEN 22 AND 31 AND marketing_penjualan_historibayar.jenis_bayar="GR", marketing_penjualan_historibayar.jumlah, 0)) as giro_minggu_4'),

            DB::raw('SUM(marketing_penjualan_historibayar.jumlah) as total_pembayaran'),

            DB::raw("0 as qty")
        );
        $querypembayaran->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $querypembayaran->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $querypembayaran->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $querypembayaran->whereBetween('marketing_penjualan_historibayar.tanggal', [$dari, $sampai]);
        if (!empty($kode_cabang)) {
            $querypembayaran->where('salesman.kode_cabang', $kode_cabang);
        }
        if (!empty($request->kode_salesman)) {
            $querypembayaran->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }
        $querypembayaran->where('status_batal', 0);
        $querypembayaran->groupBy('marketing_penjualan.kode_pelanggan', 'nama_pelanggan');
        $querypembayaran->groupBy('nama_pelanggan');


        $queryqty = Detailpenjualan::query();
        $queryqty->select(
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            DB::raw("0 as tunai_minggu_1"),
            DB::raw("0 as tunai_minggu_2"),
            DB::raw("0 as tunai_minggu_3"),
            DB::raw("0 as tunai_minggu_4"),

            DB::raw('0 as kredit_minggu_1'),
            DB::raw('0 as kredit_minggu_2'),
            DB::raw('0 as kredit_minggu_3'),
            DB::raw('0 as kredit_minggu_4'),

            DB::raw('0 as total_penjualan'),

            DB::raw('0 as cash_minggu_1'),
            DB::raw('0 as cash_minggu_2'),
            DB::raw('0 as cash_minggu_3'),
            DB::raw('0 as cash_minggu_4'),

            DB::raw('0 as titipan_minggu_1'),
            DB::raw('0 as titipan_minggu_2'),
            DB::raw('0 as titipan_minggu_3'),
            DB::raw('0 as titipan_minggu_4'),


            DB::raw('0 as transfer_minggu_1'),
            DB::raw('0 as transfer_minggu_2'),
            DB::raw('0 as transfer_minggu_3'),
            DB::raw('0 as transfer_minggu_4'),

            DB::raw('0 as giro_minggu_1'),
            DB::raw('0 as giro_minggu_2'),
            DB::raw('0 as giro_minggu_3'),
            DB::raw('0 as giro_minggu_4'),

            DB::raw('0 as total_pembayaran'),

            DB::raw('SUM(ROUND(jumlah/isi_pcs_dus)) as qty')
        );
        $queryqty->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $queryqty->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $queryqty->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $queryqty->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $queryqty->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $queryqty->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai]);
        if (!empty($kode_cabang)) {
            $queryqty->where('salesman.kode_cabang', $kode_cabang);
        }
        if (!empty($request->kode_salesman)) {
            $queryqty->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }
        $queryqty->where('status_batal', 0);
        $queryqty->groupBy('marketing_penjualan.kode_pelanggan', 'nama_pelanggan');
        $queryqty->groupBy('nama_pelanggan');


        $query_analisa = $querypenjualan->unionAll($querypembayaran)->unionAll($queryqty)->get();

        $analisatransaksi = $query_analisa->groupBy('kode_pelanggan', 'nama_pelanggan')
            ->map(function ($item) {
                return [
                    'kode_pelanggan' => $item->first()->kode_pelanggan,
                    'nama_pelanggan' => $item->first()->nama_pelanggan,
                    'tunai_minggu_1' => $item->sum('tunai_minggu_1'),
                    'tunai_minggu_2' => $item->sum('tunai_minggu_2'),
                    'tunai_minggu_3' => $item->sum('tunai_minggu_3'),
                    'tunai_minggu_4' => $item->sum('tunai_minggu_4'),
                    'kredit_minggu_1' => $item->sum('kredit_minggu_1'),
                    'kredit_minggu_2' => $item->sum('kredit_minggu_2'),
                    'kredit_minggu_3' => $item->sum('kredit_minggu_3'),
                    'kredit_minggu_4' => $item->sum('kredit_minggu_4'),
                    'cash_minggu_1' => $item->sum('cash_minggu_1'),
                    'cash_minggu_2' => $item->sum('cash_minggu_2'),
                    'cash_minggu_3' => $item->sum('cash_minggu_3'),
                    'cash_minggu_4' => $item->sum('cash_minggu_4'),
                    'titipan_minggu_1' => $item->sum('titipan_minggu_1'),
                    'titipan_minggu_2' => $item->sum('titipan_minggu_2'),
                    'titipan_minggu_3' => $item->sum('titipan_minggu_3'),
                    'titipan_minggu_4' => $item->sum('titipan_minggu_4'),
                    'transfer_minggu_1' => $item->sum('transfer_minggu_1'),
                    'transfer_minggu_2' => $item->sum('transfer_minggu_2'),
                    'transfer_minggu_3' => $item->sum('transfer_minggu_3'),
                    'transfer_minggu_4' => $item->sum('transfer_minggu_4'),
                    'giro_minggu_1' => $item->sum('giro_minggu_1'),
                    'giro_minggu_2' => $item->sum('giro_minggu_2'),
                    'giro_minggu_3' => $item->sum('giro_minggu_3'),
                    'giro_minggu_4' => $item->sum('giro_minggu_4'),
                    'total_pembayaran' => $item->sum('total_pembayaran'),
                    'total_penjualan' => $item->sum('total_penjualan'),
                    'qty' => $item->sum('qty'),
                ];
            })
            ->sortBy('nama_pelanggan')
            ->values()
            ->all();

        $data['analisatransaksi'] = $analisatransaksi;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Analisa Transaksi  $request->bulan-$request->tahun.xls");
        }
        return view('marketing.laporan.analisatransaksi_cetak', $data);
    }


    public function cetaktunaitransfer(Request $request)
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

        $query = Penjualan::query();
        $query->select(
            'marketing_penjualan.no_faktur',
            'tanggal',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            'nama_salesman',
            DB::raw("(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn  as total"),
            DB::raw("(SELECT SUM(subtotal) FROM marketing_retur_detail INNER JOIN marketing_retur ON marketing_retur_detail.no_retur= marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur='PF') as totalretur"),
            'totalbayar'
        );

        $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->leftJoin(
            DB::raw("(
            SELECT no_faktur, SUM(jumlah) as totalbayar
            FROM marketing_penjualan_historibayar
            WHERE tanggal BETWEEN '$request->dari' AND '$request->sampai'
            GROUP BY no_faktur
        ) historibayar"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'historibayar.no_faktur');
            }
        );
        $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        $query->where('jenis_transaksi', 'T');
        $query->where('marketing_penjualan.jenis_bayar', 'TR');
        $query->where('marketing_penjualan.status_batal', 0);
        if (!empty($kode_cabang)) {
            $query->where('salesman.kode_cabang', $kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $query->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }
        $query->orderBy('marketing_penjualan.tanggal');

        $data['tunaitransfer'] = $query->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Tunai Transfer  $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.tunaitransfer_cetak', $data);
    }


    public function cetakeffectivecall(Request $request)
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

        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Effective Call  $request->dari-$request->sampai.xls");
        }

        if ($request->formatlaporan == '1') {
            $query = Penjualan::query();
            $query->select(
                'marketing_penjualan.kode_salesman',
                'salesman.nama_salesman',
                DB::raw('COUNT(no_faktur) as total_ec')
            );
            $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
            $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
            $query->where('salesman.kode_cabang', $kode_cabang);
            $query->where('status_batal', 0);
            $query->groupBy('marketing_penjualan.kode_salesman', 'salesman.nama_salesman');
            $ec = $query->get();
            $data['ec'] = $ec;
            return view('marketing.laporan.effectivecall_cetak', $data);
        } else {
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

            $selectColumn = [];
            foreach ($produk as $p) {
                $selectColumn[] = DB::raw("SUM(IF(produk_harga.kode_produk = '$p->kode_produk',1,0)) as ec_" . $p->kode_produk);
            }

            $query = Detailpenjualan::query();
            $query->select(
                'marketing_penjualan.kode_salesman',
                'nama_salesman',
                ...$selectColumn
            );
            $query->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
            $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
            $query->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
            $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
            $query->where('status_promosi', 0);
            $query->where('status_batal', 0);
            $query->where('salesman.kode_cabang', $kode_cabang);
            $query->groupBy('marketing_penjualan.kode_salesman', 'salesman.nama_salesman');
            $ec = $query->get();
            $data['ec'] = $ec;
            $data['produk'] = $produk;
            return view('marketing.laporan.effectivecall_produk_cetak', $data);
        }
    }

    public function cetakkartupiutang(Request $request)
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

        $bulan = date('m', strtotime($request->dari));
        $tahun = date('Y', strtotime($request->dari));


        // $saldoawal = Saldoawalpiutangpelanggan::where('bulan', $bulan)->where('tahun', $tahun)->first();
        $saldoawal = Saldoawalpiutangpelanggan::where('tanggal', '<=', $request->dari)->orderBy('tanggal', 'desc')->first();
        $saldoawal_date = $saldoawal->tanggal;

        $querysaldoawal = Detailsaldoawalpiutangpelanggan::query();
        $querysaldoawal->select(
            'marketing_saldoawal_piutang_detail.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'salesman.nama_salesman',
            'wilayah.nama_wilayah',
            'pelanggan.hari',
            'pelanggan.ljt',
            'kode_cabang_baru',
            DB::raw("IFNULL((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur),0) - potongan - potongan_istimewa - penyesuaian + ppn -  IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal < '$request->dari'),0)
            as total_piutang"),


            DB::raw("IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)- IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal >= '$saldoawal_date' AND marketing_retur.tanggal < '$request->dari'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal >= '$saldoawal_date' AND marketing_penjualan_historibayar.tanggal < '$request->dari'),0) as saldo_awal"),
            DB::raw('0 as bruto'),
            DB::raw('0 as penyesuaian'),
            DB::raw('0 as potongan'),
            DB::raw('0 as potongan_istimewa'),
            DB::raw('0 as ppn'),
            DB::raw("(SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$request->dari' AND '$request->sampai') as retur"),
            DB::raw('0 as netto'),
            DB::raw("(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$request->dari' AND '$request->sampai') as jmlbayar"),

            DB::raw("(SELECT MAX(tanggal) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal <= '$request->sampai') as last_payment"),

            DB::raw("datediff('$request->sampai', marketing_penjualan.tanggal) as usia_piutang")
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
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$request->dari'
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $querysaldoawal->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $querysaldoawal->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $querysaldoawal->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        // $querysaldoawal->where('bulan', $bulan);
        // $querysaldoawal->where('tahun', $tahun);
        $querysaldoawal->where('marketing_saldoawal_piutang.kode_saldo_awal', $saldoawal->kode_saldo_awal);
        $querysaldoawal->whereRaw("IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)- IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal >= '$saldoawal_date' AND marketing_retur.tanggal < '$request->dari'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal >= '$saldoawal_date' AND marketing_penjualan_historibayar.tanggal < '$request->dari'),0) != 0");

        $querysaldoawal->orderBy('marketing_penjualan.tanggal', 'asc');
        $querysaldoawal->orderBy('marketing_penjualan.no_faktur', 'asc');
        if (!empty($kode_cabang)) {
            $querysaldoawal->where('kode_cabang_baru', $kode_cabang);
        }
        if (!empty($request->kode_salesman)) {
            $querysaldoawal->where('kode_salesman_baru', $request->kode_salesman);
        }

        if ($request->formatlaporan == '1') {
            $querysaldoawal->whereRaw("datediff('$request->sampai', marketing_penjualan.tanggal) > 15");
        } else if ($request->formatlaporan == '2') {
            $querysaldoawal->whereRaw("datediff('$request->sampai', marketing_penjualan.tanggal) <= 15");
        }


        $querysaldoawalbulanini = Penjualan::query();
        $querysaldoawalbulanini->select(
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'salesman.nama_salesman',
            'wilayah.nama_wilayah',
            'pelanggan.hari',
            'pelanggan.ljt',
            'kode_cabang_baru',


            DB::raw("IFNULL((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur),0) - potongan - potongan_istimewa - penyesuaian + ppn -  IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal >= '$saldoawal_date' AND marketing_retur.tanggal < '$request->dari'),0)
            as total_piutang"),


            DB::raw("IFNULL((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur),0) - potongan - potongan_istimewa - penyesuaian + ppn -  IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal >= '$saldoawal_date' AND marketing_retur.tanggal < '$request->dari'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal >= '$saldoawal_date' AND marketing_penjualan_historibayar.tanggal < '$request->dari'),0)
            as saldo_awal"),

            DB::raw('0 as bruto'),
            DB::raw('0 as penyesuaian'),
            DB::raw('0 as potongan'),
            DB::raw('0 as potongan_istimewa'),
            DB::raw('0 as ppn'),

            DB::raw("(SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$request->dari' AND '$request->sampai') as retur"),
            DB::raw('0 as netto'),

            DB::raw("(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$request->dari' AND '$request->sampai') as jmlbayar"),

            DB::raw("(SELECT MAX(tanggal) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal <= '$request->sampai') as last_payment"),

            DB::raw("datediff('$request->sampai', marketing_penjualan.tanggal) as usia_piutang")
        );
        $querysaldoawalbulanini->leftJoin(
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
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$request->dari'
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $querysaldoawalbulanini->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $querysaldoawalbulanini->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $querysaldoawalbulanini->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $querysaldoawalbulanini->where('marketing_penjualan.tanggal', '>=', $saldoawal_date);
        $querysaldoawalbulanini->where('marketing_penjualan.tanggal', '<', $request->dari);
        $querysaldoawalbulanini->where('jenis_transaksi', 'K');
        $querysaldoawalbulanini->where('status_batal', 0);
        $querysaldoawalbulanini->whereRaw("IFNULL((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur),0) - potongan - potongan_istimewa - penyesuaian + ppn -  IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal >= '$saldoawal_date' AND marketing_retur.tanggal < '$request->dari'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal >= '$saldoawal_date' AND marketing_penjualan_historibayar.tanggal < '$request->dari'),0) != 0");
        if (!empty($kode_cabang)) {
            $querysaldoawalbulanini->where('kode_cabang_baru', $kode_cabang);
        }
        if (!empty($request->kode_salesman)) {
            $querysaldoawalbulanini->where('kode_salesman_baru', $request->kode_salesman);
        }

        if ($request->formatlaporan == '1') {
            $querysaldoawalbulanini->whereRaw("datediff('$request->sampai', marketing_penjualan.tanggal) > 15");
        } else if ($request->formatlaporan == '2') {
            $querysaldoawalbulanini->whereRaw("datediff('$request->sampai', marketing_penjualan.tanggal) <= 15");
        }

        $querysaldoawalbulanini->orderBy('marketing_penjualan.tanggal', 'asc');



        $querypenjualan = Penjualan::query();
        $querypenjualan->select(
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'salesman.nama_salesman',
            'wilayah.nama_wilayah',
            'pelanggan.hari',
            'pelanggan.ljt',
            'kode_cabang_baru',


            DB::raw("IFNULL((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur),0) - potongan - potongan_istimewa - penyesuaian + ppn -  IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$request->dari' AND '$request->sampai'),0)
            as total_piutang"),


            DB::raw('0 as saldo_awal'),

            DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur) as bruto'),
            'penyesuaian',
            'potongan',
            'potongan_istimewa',
            'ppn',

            DB::raw("(SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$request->dari' AND '$request->sampai') as retur"),

            DB::raw("(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur)
            - penyesuaian - potongan - potongan_istimewa + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON  marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$request->dari' AND '$request->sampai'),0)
            as netto"),

            DB::raw("(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$request->dari' AND '$request->sampai') as jmlbayar"),

            DB::raw("(SELECT MAX(tanggal) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$request->dari' AND '$request->sampai') as last_payment"),

            DB::raw("datediff('$request->sampai', marketing_penjualan.tanggal) as usia_piutang")
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
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$request->dari'
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $querypenjualan->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $querypenjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $querypenjualan->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $querypenjualan->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        $querypenjualan->where('jenis_transaksi', 'K');
        $querypenjualan->where('status_batal', 0);
        if (!empty($kode_cabang)) {
            $querypenjualan->where('kode_cabang_baru', $kode_cabang);
        }
        if (!empty($request->kode_salesman)) {
            $querypenjualan->where('kode_salesman_baru', $request->kode_salesman);
        }

        if ($request->formatlaporan == '1') {
            $querypenjualan->whereRaw("datediff('$request->sampai', marketing_penjualan.tanggal) > 15");
        } else if ($request->formatlaporan == '2') {
            $querypenjualan->whereRaw("datediff('$request->sampai', marketing_penjualan.tanggal) <= 15");
        }
        $querypenjualan->orderBy('marketing_penjualan.tanggal', 'asc');


        // dd($querypenjualan->get());

        $querykartupiutang = $querysaldoawal->unionAll($querysaldoawalbulanini)->unionAll($querypenjualan)->orderBy('tanggal')->get();



        $data['kartupiutang'] =  $querykartupiutang;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Kartu Piutang  $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.kartupiutang_cetak', $data);
    }


    public function cetakaup(Request $request)
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



        // $saldoawal = Saldoawalpiutangpelanggan::where('bulan', $bulan)->where('tahun', $tahun)->first();
        $saldoawal = Saldoawalpiutangpelanggan::where('tanggal', '<=', $request->tanggal)->orderBy('tanggal', 'desc')->first();
        $saldoawal_date = $saldoawal->tanggal;

        $querysaldoawal = Detailsaldoawalpiutangpelanggan::query();
        $querysaldoawal->select(
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'salesman.nama_salesman',
            'wilayah.nama_wilayah',
            'pelanggan.ljt',



            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 0 and 15,
            IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)
            - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_0_15"),



            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 16 and 31,
            IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)
            - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_16_31"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 32 and 45,
            IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)
            - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_32_45"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 46 and 60,
            IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)
            - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_46_60"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 61 and 90,
            IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)
            - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_61_90"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 91 and 180,
            IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)
            - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_91_180"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 181 and 360,
            IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)
            - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_181_360"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 361 and 720,
            IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)
            - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_361_720"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) > 720,
            IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)
            - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_lebih_720"),

            DB::raw("SUM( IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)- IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0)) as total")
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
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$request->tanggal'
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $querysaldoawal->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $querysaldoawal->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $querysaldoawal->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');

        // $querysaldoawal->where('bulan', $bulan);
        // $querysaldoawal->where('tahun', $tahun);
        $querysaldoawal->where('marketing_saldoawal_piutang.kode_saldo_awal', $saldoawal->kode_saldo_awal);
        if (!empty($kode_cabang)) {
            $querysaldoawal->where('kode_cabang_baru', $kode_cabang);
        }
        if (!empty($request->kode_salesman)) {
            $querysaldoawal->where('kode_salesman_baru', $request->kode_salesman);
        }



        $querysaldoawal->groupBy(
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'salesman.nama_salesman',
            'wilayah.nama_wilayah',
            'pelanggan.ljt',
        );




        $querypenjualan = Penjualan::query();
        $querypenjualan->select(
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'salesman.nama_salesman',
            'wilayah.nama_wilayah',
            'pelanggan.ljt',

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 0 and 15,
            (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_0_15"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 16 and 31,
        (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_16_31"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 32 and 45,
        (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_32_45"),


            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 46 and 60,
        (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_46_60"),


            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 61 and 90,
        (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_61_90"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 91 and 180,
        (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_91_180"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 181 and 360,
        (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_181_360"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 361 and 720,
        (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_361_720"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal)  > 720,
        (SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0),0)) as umur_lebih_720"),

            DB::raw("SUM((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
            INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$saldoawal_date' AND '$request->tanggal'),0)) as total")



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
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$request->tanggal'
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );
        $querypenjualan->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $querypenjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $querypenjualan->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $querypenjualan->whereBetween('marketing_penjualan.tanggal', [$saldoawal_date, $request->tanggal]);
        $querypenjualan->where('jenis_transaksi', 'K');
        $querypenjualan->where('status_batal', 0);
        if (!empty($kode_cabang)) {
            $querypenjualan->where('kode_cabang_baru', $kode_cabang);
        }
        if (!empty($request->kode_salesman)) {
            $querypenjualan->where('kode_salesman_baru', $request->kode_salesman);
        }


        $querypenjualan->groupBy(
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'salesman.nama_salesman',
            'wilayah.nama_wilayah',
            'pelanggan.ljt',
        );


        $queryaup = $querysaldoawal->unionAll($querypenjualan)->get();

        $data['aup'] = $queryaup->groupBy('kode_pelanggan')
            ->map(function ($item) {
                return [
                    'kode_pelanggan' => $item->first()->kode_pelanggan,
                    'nama_pelanggan' => $item->first()->nama_pelanggan,
                    'nama_salesman' => $item->first()->nama_salesman,
                    'nama_wilayah' => $item->first()->nama_wilayah,
                    'ljt' => $item->first()->ljt,

                    'umur_0_15' => $item->sum(function ($row) {
                        return  $row->umur_0_15;
                    }),
                    'umur_16_31' => $item->sum(function ($row) {
                        return  $row->umur_16_31;
                    }),

                    'umur_32_45' => $item->sum(function ($row) {
                        return  $row->umur_32_45;
                    }),
                    'umur_46_60' => $item->sum(function ($row) {
                        return  $row->umur_46_60;
                    }),
                    'umur_61_90' => $item->sum(function ($row) {
                        return  $row->umur_61_90;
                    }),
                    'umur_91_180' => $item->sum(function ($row) {
                        return  $row->umur_91_180;
                    }),
                    'umur_181_360' => $item->sum(function ($row) {
                        return  $row->umur_181_360;
                    }),

                    'umur_361_720' => $item->sum(function ($row) {
                        return  $row->umur_361_720;
                    }),

                    'umur_lebih_720' => $item->sum(function ($row) {
                        return  $row->umur_lebih_720;
                    }),

                    'total' => $item->sum(function ($row) {
                        return  $row->total;
                    }),

                ];
            })
            ->sortBy('nama_pelanggan')
            ->values()
            ->all();


        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        $data['tanggal'] = $request->tanggal;

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=AUP  $request->dari-$request->sampai.xls");
        }
        return view('marketing.laporan.aup_cetak', $data);
    }

    public function cetaklebihsatufaktur(Request $request)
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



        //Cari Pelanggan Yang Memiliki Lebih dari 2 faktur
        $qpelangganBelumlunas2faktur = Penjualan::query();
        $qpelangganBelumlunas2faktur->select('kode_pelanggan');
        $qpelangganBelumlunas2faktur->groupBy('kode_pelanggan');
        $qpelangganBelumlunas2faktur->join('salesman', 'salesman.kode_salesman', '=', 'marketing_penjualan.kode_salesman');
        $qpelangganBelumlunas2faktur->where('status_batal', 0);
        // $qpelangganBelumlunas2faktur->where('status', 0);
        $qpelangganBelumlunas2faktur->whereRaw("(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal <= '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal <= '$request->tanggal'),0)  > 0");
        $qpelangganBelumlunas2faktur->where('marketing_penjualan.tanggal', '<=', $request->tanggal);
        $qpelangganBelumlunas2faktur->having(DB::raw('COUNT(no_faktur)'), '>', 1);
        if (!empty($kode_cabang)) {
            $qpelangganBelumlunas2faktur->where('salesman.kode_cabang', $kode_cabang);
        }
        if (!empty($request->kode_salesman)) {
            $qpelangganBelumlunas2faktur->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }

        $pelangganBelumlunas2faktur = $qpelangganBelumlunas2faktur->get();
        $kode_pelanggan = [];
        foreach ($pelangganBelumlunas2faktur as $d) {
            $kode_pelanggan[] = $d->kode_pelanggan;
        }

        //dd($kode_pelanggan);

        // dd($kode_pelanggan);
        //Tampilkan Fakturnya
        $query = Penjualan::query();
        $query->select(
            'marketing_penjualan.no_faktur',
            'tanggal',
            'marketing_penjualan.kode_pelanggan',
            'nama_pelanggan',
            'nama_wilayah',
            'keterangan',
            DB::raw("(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal <= '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal <= '$request->tanggal'),0) as sisa_piutang")
        );
        $query->join('salesman', 'salesman.kode_salesman', '=', 'marketing_penjualan.kode_salesman');
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->where('status_batal', 0);

        $query->whereRaw("(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn - IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal <= '$request->tanggal'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal <= '$request->tanggal'),0)  > 0");

        $query->whereIn('marketing_penjualan.kode_pelanggan', $kode_pelanggan);
        $query->where('marketing_penjualan.tanggal', '<=', $request->tanggal);
        if (!empty($kode_cabang)) {
            $query->where('salesman.kode_cabang', $kode_cabang);
        }
        if (!empty($request->kode_salesman)) {
            $query->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        }
        $query->orderBy('marketing_penjualan.kode_pelanggan');
        $query->orderBy('marketing_penjualan.tanggal');

        $lebihsatufaktur = $query->get();
        $data['lebihsatufaktur'] = $lebihsatufaktur;
        $data['tanggal'] = $request->tanggal;
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang)->first();
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        return view('marketing.laporan.lebihsatufaktur_cetak', $data);
    }


    public function cetaklhp(Request $request)
    {

        $request->validate([
            'kode_salesman' => 'required',
            'tanggal' => 'required'
        ]);

        if (lockreport($request->tanggal) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }

        $produk = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->select('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->where('marketing_penjualan.tanggal', $request->tanggal)
            ->where('marketing_penjualan.kode_salesman', $request->kode_salesman)
            ->orderBy('produk_harga.kode_produk')
            ->groupBy('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->get();


        $selectColumnkodeproduk = [];
        $selectColumnkodeprodukhb = [];
        foreach ($produk as $d) {
            $selectColumnkodeproduk[] = DB::raw('SUM(IF(kode_produk="' . $d->kode_produk . '",jumlah,0)) as `qty_' . $d->kode_produk . '`');
            $selectColumnkodeprodukhb[] = DB::raw('SUM(0) as `qty_' . $d->kode_produk . '`');
        }


        $qdetailpenjualan = Detailpenjualan::query();
        $qdetailpenjualan->select(
            'marketing_penjualan_detail.no_faktur',
            'nama_pelanggan',
            DB::raw("SUM(0) as jml_tunai"),
            DB::raw("SUM(IF(jenis_transaksi='K',subtotal,0)) - IF(jenis_transaksi='K',potongan + potongan_istimewa + penyesuaian - ppn,0)  as jml_kredit"),
            DB::raw("SUM(0) as jml_titipan"),
            DB::raw("SUM(0) as jml_giro"),
            DB::raw("SUM(0) as jml_transfer"),
            DB::raw("SUM(0) as jml_voucher"),
            'status_batal',

            ...$selectColumnkodeproduk

        );
        $qdetailpenjualan->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qdetailpenjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $qdetailpenjualan->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $qdetailpenjualan->where('marketing_penjualan.kode_salesman', $request->kode_salesman);
        $qdetailpenjualan->where('marketing_penjualan.tanggal', $request->tanggal);
        $qdetailpenjualan->groupBy('marketing_penjualan_detail.no_faktur', 'nama_pelanggan');
        $qdetailpenjualan->orderBy('marketing_penjualan_detail.no_faktur');



        $qhistoribayar = Historibayarpenjualan::query();
        $qhistoribayar->select(
            'marketing_penjualan_historibayar.no_faktur',
            'nama_pelanggan',
            DB::raw("SUM(IF(marketing_penjualan_historibayar.jenis_bayar = 'TN' AND voucher='0',jumlah,0)) as jml_tunai"),
            DB::raw("SUM(0) as jml_kredit"),
            DB::raw("SUM(IF( marketing_penjualan_historibayar.jenis_bayar = 'TP' AND voucher = '0',jumlah,0)) as jml_titipan"),
            DB::raw("SUM(0) as jml_giro"),
            DB::raw("SUM(0) as jml_transfer"),
            DB::raw("SUM(IF( marketing_penjualan_historibayar.voucher = '1',jumlah,0)) as jml_voucher"),
            DB::raw('0 as status_batal'),
            ...$selectColumnkodeprodukhb
        );
        $qhistoribayar->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $qhistoribayar->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $qhistoribayar->whereNotIN('marketing_penjualan_historibayar.jenis_bayar', ['TR', 'GR']);

        $qhistoribayar->where('marketing_penjualan_historibayar.kode_salesman', $request->kode_salesman);
        $qhistoribayar->where('marketing_penjualan_historibayar.tanggal', $request->tanggal);
        $qhistoribayar->groupBy('marketing_penjualan_historibayar.no_faktur', 'nama_pelanggan');


        $qgiro = Detailgiro::query();
        $qgiro->select(
            'no_faktur',
            'nama_pelanggan',
            DB::raw("SUM(0) as jml_tunai"),
            DB::raw("SUM(0) as jml_kredit"),
            DB::raw("SUM(0) as jml_titipan"),
            DB::raw("SUM(jumlah) as jml_giro"),
            DB::raw("SUM(0) as jml_transfer"),
            DB::raw("SUM(0) as jml_voucher"),
            DB::raw('0 as status_batal'),
            ...$selectColumnkodeprodukhb
        );
        $qgiro->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro');
        $qgiro->join('pelanggan', 'marketing_penjualan_giro.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $qgiro->where('marketing_penjualan_giro.kode_salesman', $request->kode_salesman);
        $qgiro->where('marketing_penjualan_giro.tanggal', $request->tanggal);
        $qgiro->groupBy('marketing_penjualan_giro_detail.no_faktur', 'nama_pelanggan');

        $qtransfer = Detailtransfer::query();
        $qtransfer->select(
            'no_faktur',
            'nama_pelanggan',
            DB::raw("SUM(0) as jml_tunai"),
            DB::raw("SUM(0) as jml_kredit"),
            DB::raw("SUM(0) as jml_titipan"),
            DB::raw("SUM(0) as jml_giro"),
            DB::raw("SUM(jumlah) as jml_transfer"),
            DB::raw("SUM(0) as jml_voucher"),
            DB::raw('0 as status_batal'),
            ...$selectColumnkodeprodukhb
        );
        $qtransfer->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer');
        $qtransfer->join('pelanggan', 'marketing_penjualan_transfer.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $qtransfer->where('marketing_penjualan_transfer.kode_salesman', $request->kode_salesman);
        $qtransfer->where('marketing_penjualan_transfer.tanggal', $request->tanggal);
        $qtransfer->groupBy('marketing_penjualan_transfer_detail.no_faktur', 'nama_pelanggan');

        $query_lhp = $qdetailpenjualan->unionAll($qhistoribayar)->unionAll($qgiro)->unionAll($qtransfer)->get();
        $lhp = $query_lhp->groupBy('no_faktur', 'nama_pelanggan')
            ->map(function ($item) use ($produk) {
                $result = [
                    'no_faktur' => $item->first()->no_faktur,
                    'nama_pelanggan' => $item->first()->nama_pelanggan,
                    'jml_tunai' => $item->sum('jml_tunai'),
                    'jml_kredit' => $item->sum('jml_kredit'),
                    'jml_titipan' => $item->sum('jml_titipan'),
                    'jml_giro' => $item->sum('jml_giro'),
                    'jml_transfer' => $item->sum('jml_transfer'),
                    'jml_voucher' => $item->sum('jml_voucher'),
                    'status_batal' => $item->first()->status_batal,
                ];
                foreach ($produk as $p) {
                    $result['qty_' . $p->kode_produk] = $item->sum('qty_' . $p->kode_produk);
                }

                return $result;
            })
            ->sortBy('no_faktur')
            ->values()
            ->all();

        $detailproduk = Detailpenjualan::join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->select('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', DB::raw('SUM(jumlah) as qty'))
            ->where('marketing_penjualan.kode_salesman', $request->kode_salesman)
            ->where('marketing_penjualan.tanggal', $request->tanggal)
            ->where('status_batal', 0)
            ->groupBy('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->orderBy('produk_harga.kode_produk')
            ->get();
        $data['detailproduk'] = $detailproduk;
        $data['lhp'] = $lhp;
        $data['salesman'] = Salesman::where('kode_salesman', $request->kode_salesman)->first();
        $data['cabang'] = Cabang::where('kode_cabang', $data['salesman']->kode_cabang)->first();
        $data['tanggal'] = $request->tanggal;
        $data['produk'] = $produk;
        return view('marketing.laporan.lhp_cetak', $data);
    }

    public function cetakdppp(Request $request)
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

        $lastyear = $request->tahun - 1;
        $start_date_lastyear = $lastyear . "-" . $request->bulan . "-01";
        $end_date_lastyear = date('Y-m-t', strtotime($start_date_lastyear));

        $start_date = $request->tahun . "-" . $request->bulan . "-01";
        $end_date = date('Y-m-t', strtotime($start_date));

        $start_year_date_lastyear = $lastyear . "-01-01";
        $start_year_date = $request->tahun . "-01-01";

        $produk_now = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->select('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->orderBy('produk_harga.kode_produk')
            ->groupBy('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack');

        $produk_last = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->select('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->whereBetween('marketing_penjualan.tanggal', [$start_date_lastyear, $end_date_lastyear])
            ->orderBy('produk_harga.kode_produk')
            ->groupBy('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack');


        $qproduk = $produk_now->unionAll($produk_last)->get();

        $produk = $qproduk->groupBy('kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->map(function ($item) {
                return [
                    'kode_produk' => $item->first()->kode_produk,
                    'nama_produk' => $item->first()->nama_produk,
                    'isi_pcs_dus' => $item->first()->isi_pcs_dus,
                ];
            })
            ->sortBy('nama_produk')
            ->values()
            ->all();

        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Laporan Data Pertumbuhan Produk $request->bulan - $request->tahun.xls");
        }
        if (empty($kode_cabang) && $user->hasRole($roles_access_all_cabang) || $user->hasRole('regional sales manager')) {
            return $this->cetakdppallcabang($request, $produk);
        } else {
            return $this->cetakdppcabang($request, $produk, $kode_cabang);
        }
    }

    public function cetakdppallcabang(Request $request, $produk)
    {

        $user = User::findorfail(auth()->user()->id);
        $lastyear = $request->tahun - 1;
        $start_date_lastyear = $lastyear . "-" . $request->bulan . "-01";
        $end_date_lastyear = date('Y-m-t', strtotime($start_date_lastyear));

        $start_date = $request->tahun . "-" . $request->bulan . "-01";
        $end_date = date('Y-m-t', strtotime($start_date));

        $start_year_date_lastyear = $lastyear . "-01-01";
        $start_year_date = $request->tahun . "-01-01";

        $formatlaporan = $request->formatlaporan;

        $selectColumnproduklastyear = [];
        $selectcolumnproduk = [];
        $selectColumntarget = [];
        foreach ($produk as $d) {
            $selectColumnproduklastyear[] = DB::raw('SUM(0) as `realisasi_' . $d['kode_produk'] . '`');
            $selectColumnproduklastyear[] = DB::raw('SUM(0) as `realisasi_sampaidengan_' . $d['kode_produk'] . '`');

            $selectColumnproduklastyear[] = DB::raw('SUM(IF(produk_harga.kode_produk="' . $d['kode_produk'] . '" AND marketing_penjualan.tanggal BETWEEN "' . $start_date_lastyear . '" AND "' . $end_date_lastyear . '", marketing_penjualan_detail.jumlah, 0))  as `realisasi_lastyear_' . $d['kode_produk'] . '`');
            $selectColumnproduklastyear[] = DB::raw('SUM(IF(marketing_penjualan.tanggal BETWEEN "' . $start_year_date_lastyear . '" AND "' . $end_date_lastyear . '" AND produk_harga.kode_produk="' . $d['kode_produk'] . '", marketing_penjualan_detail.jumlah, 0)) as `realisasi_lastyear_sampaidengan_' . $d['kode_produk'] . '`');
            $selectColumnproduklastyear[] = DB::raw('SUM(0) as `target_' . $d['kode_produk'] . '`');
            $selectColumnproduklastyear[] = DB::raw('SUM(0) as `target_sampaidengan_' . $d['kode_produk'] . '`');


            $selectcolumnproduk[] = DB::raw('SUM(IF(produk_harga.kode_produk="' . $d['kode_produk'] . '" AND marketing_penjualan.tanggal BETWEEN "' . $start_date . '" AND "' . $end_date . '", marketing_penjualan_detail.jumlah, 0))  as `realisasi_' . $d['kode_produk'] . '`');

            $selectcolumnproduk[] = DB::raw('SUM(IF(marketing_penjualan.tanggal BETWEEN "' . $start_year_date . '" AND "' . $end_date . '" AND produk_harga.kode_produk="' . $d['kode_produk'] . '", marketing_penjualan_detail.jumlah, 0)) as `realisasi_sampaidengan_' . $d['kode_produk'] . '`');

            $selectcolumnproduk[] = DB::raw('SUM(0) as `realisasi_lastyear_' . $d['kode_produk'] . '`');
            $selectcolumnproduk[] = DB::raw('SUM(0) as `realisasi_lastyear_sampaidengan_' . $d['kode_produk'] . '`');
            $selectcolumnproduk[] = DB::raw('SUM(0) as `target_' . $d['kode_produk'] . '`');
            $selectcolumnproduk[] = DB::raw('SUM(0) as `target_sampaidengan_' . $d['kode_produk'] . '`');

            $selectColumntarget[] = DB::raw('SUM(0) as `realisasi_' . $d['kode_produk'] . '`');
            $selectColumntarget[] = DB::raw('SUM(0) as `realisasi_sampaidengan_' . $d['kode_produk'] . '`');
            $selectColumntarget[] = DB::raw('SUM(0) as `realisasi_lastyear_' . $d['kode_produk'] . '`');
            $selectColumntarget[] = DB::raw('SUM(0) as `realisasi_lastyear_sampaidengan_' . $d['kode_produk'] . '`');
            $selectColumntarget[] = DB::raw('SUM(IF(kode_produk="' . $d['kode_produk'] . '" AND bulan="' . $request->bulan . '",jumlah,0)) as `target_' . $d['kode_produk'] . '`');
            $selectColumntarget[] = DB::raw('SUM(IF(kode_produk="' . $d['kode_produk'] . '" AND bulan BETWEEN 1 AND ' . $request->bulan . ',jumlah,0)) as `target_sampaidengan_' . $d['kode_produk'] . '`');
        }

        $penjualanlastyear = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'salesman.kode_cabang',
                'cabang.nama_cabang',
                ...$selectColumnproduklastyear
            )

            ->whereBetween('marketing_penjualan.tanggal', [$start_year_date_lastyear, $end_date_lastyear])
            ->when($user->hasRole('regional sales manager'), function ($query) {
                return $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            })
            ->where('status_promosi', 0)
            ->where('marketing_penjualan.status_batal', 0)
            ->groupBy('salesman.kode_cabang', 'nama_cabang');

        //dd($penjualanlastyear->get());
        $penjualan = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'salesman.kode_cabang',
                'cabang.nama_cabang',
                ...$selectcolumnproduk
            )


            ->whereBetween('marketing_penjualan.tanggal', [$start_year_date, $end_date])
            ->when($user->hasRole('regional sales manager'), function ($query) {
                return $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            })
            ->where('status_promosi', 0)
            ->where('marketing_penjualan.status_batal', 0)
            ->groupBy('salesman.kode_cabang', 'nama_cabang');

        $target = Detailtargetkomisi::join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target')
            ->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'marketing_komisi_target.kode_cabang',
                'nama_cabang',
                ...$selectColumntarget
            )

            ->where('marketing_komisi_target.tahun', $request->tahun)
            ->where('marketing_komisi_target.bulan', '<=', $request->bulan)
            ->when($user->hasRole('regional sales manager'), function ($query) {
                return $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            })
            ->groupBy('marketing_komisi_target.kode_cabang', 'nama_cabang');

        // dd($penjualan->get());
        $qdppp = $penjualan->unionAll($penjualanlastyear)->unionAll($target)->get();

        $dppp = $qdppp->groupBy('kode_cabang', 'nama_cabang')
            ->map(function ($item) use ($produk) {
                $result =  [
                    'kode_cabang' => $item->first()->kode_cabang,
                    'nama_cabang' => $item->first()->nama_cabang,
                ];

                foreach ($produk as $p) {
                    $result['realisasi_lastyear_' . $p['kode_produk']] = $item->sum('realisasi_lastyear_' . $p['kode_produk']);
                    $result['realisasi_lastyear_sampaidengan_' . $p['kode_produk']] = $item->sum('realisasi_lastyear_sampaidengan_' . $p['kode_produk']);
                    $result['realisasi_' . $p['kode_produk']] = $item->sum('realisasi_' . $p['kode_produk']);
                    $result['realisasi_sampaidengan_' . $p['kode_produk']] = $item->sum('realisasi_sampaidengan_' . $p['kode_produk']);
                    $result['target_' . $p['kode_produk']] = $item->sum('target_' . $p['kode_produk']);
                    $result['target_sampaidengan_' . $p['kode_produk']] = $item->sum('target_sampaidengan_' . $p['kode_produk']);
                }

                return $result;
            })
            ->sortBy('kode_cabang')
            ->values()
            ->all();



        $data['dppp'] = $dppp;
        $data['produk'] = $produk;
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['lastyear'] = $lastyear;
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang)->first();
        return view('marketing.laporan.dppp_all_cabang_cetak', $data);
    }


    public function cetakdppcabang(Request $request, $produk, $kode_cabang)
    {

        $user = User::findorfail(auth()->user()->id);
        $lastyear = $request->tahun - 1;
        $start_date_lastyear = $lastyear . "-" . $request->bulan . "-01";
        $end_date_lastyear = date('Y-m-t', strtotime($start_date_lastyear));

        $start_date = $request->tahun . "-" . $request->bulan . "-01";
        $end_date = date('Y-m-t', strtotime($start_date));

        $start_year_date_lastyear = $lastyear . "-01-01";
        $start_year_date = $request->tahun . "-01-01";

        $formatlaporan = $request->formatlaporan;

        $selectColumnproduklastyear = [];
        $selectcolumnproduk = [];
        $selectColumntarget = [];
        foreach ($produk as $d) {
            $selectColumnproduklastyear[] = DB::raw('SUM(0) as `realisasi_' . $d['kode_produk'] . '`');
            $selectColumnproduklastyear[] = DB::raw('SUM(0) as `realisasi_sampaidengan_' . $d['kode_produk'] . '`');

            $selectColumnproduklastyear[] = DB::raw('SUM(IF(produk_harga.kode_produk="' . $d['kode_produk'] . '" AND marketing_penjualan.tanggal BETWEEN "' . $start_date_lastyear . '" AND "' . $end_date_lastyear . '", marketing_penjualan_detail.jumlah, 0))  as `realisasi_lastyear_' . $d['kode_produk'] . '`');
            $selectColumnproduklastyear[] = DB::raw('SUM(IF(marketing_penjualan.tanggal BETWEEN "' . $start_year_date_lastyear . '" AND "' . $end_date_lastyear . '" AND produk_harga.kode_produk="' . $d['kode_produk'] . '", marketing_penjualan_detail.jumlah, 0)) as `realisasi_lastyear_sampaidengan_' . $d['kode_produk'] . '`');
            $selectColumnproduklastyear[] = DB::raw('SUM(0) as `target_' . $d['kode_produk'] . '`');
            $selectColumnproduklastyear[] = DB::raw('SUM(0) as `target_sampaidengan_' . $d['kode_produk'] . '`');


            $selectcolumnproduk[] = DB::raw('SUM(IF(produk_harga.kode_produk="' . $d['kode_produk'] . '" AND marketing_penjualan.tanggal BETWEEN "' . $start_date . '" AND "' . $end_date . '", marketing_penjualan_detail.jumlah, 0))  as `realisasi_' . $d['kode_produk'] . '`');

            $selectcolumnproduk[] = DB::raw('SUM(IF(marketing_penjualan.tanggal BETWEEN "' . $start_year_date . '" AND "' . $end_date . '" AND produk_harga.kode_produk="' . $d['kode_produk'] . '", marketing_penjualan_detail.jumlah, 0)) as `realisasi_sampaidengan_' . $d['kode_produk'] . '`');

            $selectcolumnproduk[] = DB::raw('SUM(0) as `realisasi_lastyear_' . $d['kode_produk'] . '`');
            $selectcolumnproduk[] = DB::raw('SUM(0) as `realisasi_lastyear_sampaidengan_' . $d['kode_produk'] . '`');
            $selectcolumnproduk[] = DB::raw('SUM(0) as `target_' . $d['kode_produk'] . '`');
            $selectcolumnproduk[] = DB::raw('SUM(0) as `target_sampaidengan_' . $d['kode_produk'] . '`');

            $selectColumntarget[] = DB::raw('SUM(0) as `realisasi_' . $d['kode_produk'] . '`');
            $selectColumntarget[] = DB::raw('SUM(0) as `realisasi_sampaidengan_' . $d['kode_produk'] . '`');
            $selectColumntarget[] = DB::raw('SUM(0) as `realisasi_lastyear_' . $d['kode_produk'] . '`');
            $selectColumntarget[] = DB::raw('SUM(0) as `realisasi_lastyear_sampaidengan_' . $d['kode_produk'] . '`');
            $selectColumntarget[] = DB::raw('SUM(IF(kode_produk="' . $d['kode_produk'] . '" AND bulan="' . $request->bulan . '",jumlah,0)) as `target_' . $d['kode_produk'] . '`');
            $selectColumntarget[] = DB::raw('SUM(IF(kode_produk="' . $d['kode_produk'] . '" AND bulan BETWEEN 1 AND ' . $request->bulan . ',jumlah,0)) as `target_sampaidengan_' . $d['kode_produk'] . '`');
        }

        $penjualanlastyear = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'marketing_penjualan.kode_salesman',
                'salesman.nama_salesman',
                ...$selectColumnproduklastyear
            )

            ->whereBetween('marketing_penjualan.tanggal', [$start_year_date_lastyear, $end_date_lastyear])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('status_promosi', 0)
            ->where('marketing_penjualan.status_batal', 0)
            ->groupBy('marketing_penjualan.kode_salesman', 'nama_salesman');

        //dd($penjualanlastyear->get());
        $penjualan = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'marketing_penjualan.kode_salesman',
                'salesman.nama_salesman',
                ...$selectcolumnproduk
            )


            ->whereBetween('marketing_penjualan.tanggal', [$start_year_date, $end_date])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('status_promosi', 0)
            ->where('marketing_penjualan.status_batal', 0)
            ->groupBy('marketing_penjualan.kode_salesman', 'nama_salesman')
            ->where('status_promosi', 0)
            ->where('marketing_penjualan.status_batal', 0)
            ->groupBy('marketing_penjualan.kode_salesman', 'nama_salesman');

        $target = Detailtargetkomisi::join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target')
            ->join('salesman', 'marketing_komisi_target_detail.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'marketing_komisi_target_detail.kode_salesman',
                'nama_salesman',
                ...$selectColumntarget
            )
            ->where('salesman.kode_cabang', $kode_cabang)
            ->where('marketing_komisi_target.tahun', $request->tahun)
            ->where('marketing_komisi_target.bulan', '<=', $request->bulan)
            ->groupBy('marketing_komisi_target_detail.kode_salesman', 'nama_salesman');

        // dd($penjualan->get());
        $qdppp = $penjualan->unionAll($penjualanlastyear)->unionAll($target)->get();

        $dppp = $qdppp->groupBy('kode_salesman', 'nama_salesman')
            ->map(function ($item) use ($produk) {
                $result =  [
                    'kode_salesman' => $item->first()->kode_salesman,
                    'nama_salesman' => $item->first()->nama_salesman,
                ];

                foreach ($produk as $p) {
                    $result['realisasi_lastyear_' . $p['kode_produk']] = $item->sum('realisasi_lastyear_' . $p['kode_produk']);
                    $result['realisasi_lastyear_sampaidengan_' . $p['kode_produk']] = $item->sum('realisasi_lastyear_sampaidengan_' . $p['kode_produk']);
                    $result['realisasi_' . $p['kode_produk']] = $item->sum('realisasi_' . $p['kode_produk']);
                    $result['realisasi_sampaidengan_' . $p['kode_produk']] = $item->sum('realisasi_sampaidengan_' . $p['kode_produk']);
                    $result['target_' . $p['kode_produk']] = $item->sum('target_' . $p['kode_produk']);
                    $result['target_sampaidengan_' . $p['kode_produk']] = $item->sum('target_sampaidengan_' . $p['kode_produk']);
                }

                return $result;
            })
            ->sortBy('kode_salesman')
            ->values()
            ->all();



        $data['dppp'] = $dppp;
        $data['produk'] = $produk;
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['lastyear'] = $lastyear;
        $data['cabang'] = Cabang::where('kode_cabang', $request->kode_cabang)->first();
        return view('marketing.laporan.dppp_cabang_cetak', $data);
    }


    public function cetakharganet(Request $request)
    {
        $dari = $request->tahun . "-" . $request->bulan . "-01";
        $sampai = date('Y-m-t', strtotime($dari));

        $produk = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->select('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'kode_kategori_produk')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
            ->orderBy('produk_harga.kode_produk')
            ->groupBy('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->get();


        $selectColumnproduk = [];
        $selectColumprodukretur = [];
        foreach ($produk as $p) {
            $selectColumnproduk[] = DB::raw("SUM(IF(produk_harga.kode_produk='$p->kode_produk',marketing_penjualan_detail.subtotal,0)) as `bruto_$p->kode_produk`");
            $selectColumnproduk[] = DB::raw("SUM(IF(produk_harga.kode_produk='$p->kode_produk' AND jenis_transaksi='T',marketing_penjualan_detail.subtotal,0)) as `bruto_tunai_$p->kode_produk`");
            $selectColumnproduk[] = DB::raw("SUM(IF(produk_harga.kode_produk='$p->kode_produk' AND jenis_transaksi='K',marketing_penjualan_detail.subtotal,0)) as `bruto_kredit_$p->kode_produk`");
            $selectColumnproduk[] = DB::raw("SUM(IF(produk_harga.kode_produk='$p->kode_produk',floor(marketing_penjualan_detail.jumlah /isi_pcs_dus),0)) as `qtydus_$p->kode_produk`");
            $selectColumnproduk[] = DB::raw("SUM(IF(produk_harga.kode_produk='$p->kode_produk',jumlah,0)) as `qty_$p->kode_produk`");

            $selectColumnkodeprodukretur[] = DB::raw("SUM(IF(produk_harga.kode_produk='$p->kode_produk' AND jenis_retur ='GB',marketing_retur_detail.subtotal,0)) as `retur_gb_$p->kode_produk`");
            $selectColumnkodeprodukretur[] = DB::raw("SUM(IF(produk_harga.kode_produk='$p->kode_produk' ,marketing_retur_detail.subtotal,0)) as `retur_total_$p->kode_produk`");
        }


        $detail = Detailpenjualan::select(
            DB::raw("SUM(subtotal) as bruto_total"),
            DB::raw("SUM(IF(jenis_transaksi='T',subtotal,0)) as bruto_total_tunai"),
            DB::raw("SUM(IF(jenis_transaksi='K',subtotal,0)) as bruto_total_kredit"),
            DB::raw("SUM(IF( produk.kode_kategori_produk='P01' ,floor(marketing_penjualan_detail.jumlah /isi_pcs_dus),0)) as `qtyAida`"),
            DB::raw("SUM(IF( produk.kode_kategori_produk='P02' ,floor(marketing_penjualan_detail.jumlah /isi_pcs_dus),0)) as `qtySwan`"),
            DB::raw("SUM((marketing_penjualan_detail.jumlah /isi_pcs_dus)) as `qtyTotal`"),
            ...$selectColumnproduk
        )
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('status_promosi', 0)
            ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
            ->first();

        $penjualan = Penjualan::select(
            DB::raw("SUM(ppn) as ppn_total"),
            DB::raw("SUM(IF(jenis_transaksi='T',ppn,0)) as ppn_total_tunai"),
            DB::raw("SUM(IF(jenis_transaksi='K',ppn,0)) as ppn_total_kredit"),
            DB::raw("SUM(potongan_aida) as potongan_aida"),
            DB::raw("SUM(potongan_swan + potongan_stick + potongan_sp + potongan_sambal) as potongan_swan"),
            DB::raw("SUM(penyesuaian) as penyesuaian"),

        )
            ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
            ->first();


        $retur = Detailretur::select(
            ...$selectColumnkodeprodukretur
        )
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->join('produk_harga', 'marketing_retur_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->whereBetween('marketing_retur.tanggal', [$dari, $sampai])
            ->first();

        $data['detail'] = $detail;
        $data['penjualan'] = $penjualan;
        $data['retur'] = $retur;
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['produk'] = $produk;
        return view('marketing.laporan.harganet_cetak', $data);
    }

    public function cetakkomisisalesman(Request $request)
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

        $bulanlalu = getbulandantahunlalu($request->bulan, $request->tahun, 'bulan');
        $tahunlalu = getbulandantahunlalu($request->bulan, $request->tahun, 'tahun');

        if ($bulanlalu == 1) {
            $blnlast1 = 12;
            $thnlast1 = $request->tahun - 1;
        } else {
            $blnlast1 = $bulanlalu - 1;
            $thnlast1 = $request->tahun;
        }

        if ($request->bulan == 12) {
            $bln = 1;
            $thn = $request->tahun + 1;
        } else {
            $bln = $request->bulan + 1;
            $thn = $request->tahun;
        }

        $start_date_bulanlalu = $tahunlalu . "-" . $bulanlalu . "-01";
        $end_date_bulanlalu = date('Y-m-t', strtotime($start_date_bulanlalu));

        $dari = $request->tahun . "-" . $request->bulan . "-01";
        $sampai = date('Y-m-t', strtotime($dari));

        $ceknextBulan = DB::table('keuangan_setoranpusat')->where('omset_bulan', $request->bulan)->where('omset_tahun', $request->tahun)
            ->select('keuangan_ledger.tanggal as tgl_diterimapusat')
            ->leftJoin('keuangan_ledger_setoranpusat', 'keuangan_setoranpusat.kode_setoran', '=', 'keuangan_ledger_setoranpusat.kode_setoran')
            ->leftJoin('keuangan_ledger', 'keuangan_ledger_setoranpusat.no_bukti', '=', 'keuangan_ledger.no_bukti')
            ->whereRaw('MONTH(keuangan_ledger.tanggal) = ' . $bln)
            ->whereRaw('YEAR(keuangan_ledger.tanggal) = ' . $thn)
            ->where('kode_cabang', $kode_cabang)
            ->orderBy('tgl_diterimapusat', 'desc')
            ->first();

        if ($ceknextBulan ==  null) {
            $end = date("Y-m-t", strtotime($dari));
        } else {
            $end = $ceknextBulan->tgl_diterimapusat;
        }


        $kategori_komisi = Kategorikomisi::orderBy('kode_kategori')->get();
        $produk = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->select('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
            ->where('salesman.kode_cabang', $kode_cabang)
            ->orderBy('produk_harga.kode_produk')
            ->groupBy('produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack')
            ->get();

        $selectColumntarget = [];
        $selectColumnRealisasi = [];
        $selectColumnRealisasikendaraan = [];

        $selectTarget = [];
        $selectRealisasi = [];
        $selectKendaraan = [];
        foreach ($kategori_komisi as $k) {
            $selectColumntarget[] = DB::raw("SUM(IF(produk.kode_kategori_komisi='$k->kode_kategori',jumlah,0)) as `target_$k->kode_kategori`");
            $selectColumnRealisasi[] = DB::raw("SUM(IF(produk.kode_kategori_komisi='$k->kode_kategori',jumlah / isi_pcs_dus,0)) as `realisasi_$k->kode_kategori`");
            $selectTarget[] = "target_$k->kode_kategori";
            $selectRealisasi[] = "realisasi_$k->kode_kategori";
        }

        foreach ($produk as $p) {
            $selectColumnRealisasikendaraan[] = DB::raw("SUM(IF(produk_harga.kode_produk='$p->kode_produk',jumlah/isi_pcs_dus,0)) as `qty_kendaraan_$p->kode_produk`");
            $selectKendaraan[] = "qty_kendaraan_$p->kode_produk";
        }
        $salesman_target = Detailtargetkomisi::join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target')
            ->select('kode_salesman')
            ->where('kode_cabang', $kode_cabang)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->groupBy('kode_salesman');

        $subqueryTarget = Detailtargetkomisi::join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target')
            ->join('produk', 'marketing_komisi_target_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('marketing_komisi_target.kode_cabang', $kode_cabang)
            ->where('marketing_komisi_target.bulan', $request->bulan)
            ->where('marketing_komisi_target.tahun', $request->tahun)
            ->select('kode_salesman', ...$selectColumntarget)
            ->groupBy('kode_salesman');

        $subqueryRealisasi = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->leftJoin(
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
                    WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur) AND tanggal <= '$dari'
                    ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
                ) pindahfaktur"),
                function ($join) {
                    $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
                }
            )
            ->where('kode_cabang_baru', $kode_cabang)
            ->whereBetween('marketing_penjualan.tanggal_pelunasan', [$dari, $sampai])
            ->where('status_promosi', 0)
            ->where('status_batal', 0)
            ->select('kode_salesman_baru', ...$selectColumnRealisasi)
            ->groupBy('kode_salesman_baru');

        $subqueryKendaraan = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('status_promosi', 0)
            ->where('status_batal', 0)
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
            ->select('marketing_penjualan.kode_salesman', ...$selectColumnRealisasikendaraan)
            ->groupBy('marketing_penjualan.kode_salesman');
        // dd($subqueryRealisasi->get());

        $subqueryOA = Penjualan::select('marketing_penjualan.kode_salesman', DB::raw('COUNT(DISTINCT(kode_pelanggan)) as realisasi_oa'))
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
            ->where('status_batal', 0)
            ->groupBy('marketing_penjualan.kode_salesman');

        //Penjualan vs AVG
        $subqueryPenjvsavgdata = DB::table('marketing_penjualan')->select(
            'marketing_penjualan.kode_pelanggan',
            'marketing_penjualan.kode_salesman',
            DB::raw("SUM(IF(marketing_penjualan.tanggal BETWEEN '$start_date_bulanlalu' AND '$end_date_bulanlalu',(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur GROUP BY no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn,0)) as penjualanbulanlalu"),
            DB::raw("SUM(IF(marketing_penjualan.tanggal BETWEEN '$dari' AND '$sampai',(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur GROUP BY no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn,0)) as penjualanbulanini"),
        )

            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('salesman.kode_cabang', $kode_cabang)
            ->whereBetween('marketing_penjualan.tanggal', [$start_date_bulanlalu, $sampai])
            ->where('status_batal', 0)
            ->groupBy('marketing_penjualan.kode_pelanggan', 'marketing_penjualan.kode_salesman');


        $subqueryPenjvsavg = DB::table(DB::raw("({$subqueryPenjvsavgdata->toSql()}) as sub"))
            ->mergeBindings($subqueryPenjvsavgdata) // Bind subquery bindings
            ->select('kode_salesman', DB::raw('COUNT(kode_pelanggan) as realisasi_penjvsavg'))
            ->whereRaw('penjualanbulanini >= penjualanbulanlalu')
            ->where('penjualanbulanlalu', '>', 0)
            ->groupBy('kode_salesman');





        $data['kategori_komisi'] = $kategori_komisi;
        $data['komisi'] = Salesman::select(
            'salesman.kode_salesman',
            'salesman.nama_salesman',
            'status_komisi_salesman as status_komisi',
            'realisasi_oa',
            'realisasi_penjvsavg',
            'realisasi_cashin',
            ...$selectTarget,
            ...$selectRealisasi,
            ...$selectKendaraan
        )
            ->leftjoinSub($subqueryTarget, 'target', function ($join) {
                $join->on('salesman.kode_salesman', '=', 'target.kode_salesman');
            })
            ->leftjoinSub($subqueryRealisasi, 'realisasi', function ($join) {
                $join->on('salesman.kode_salesman', '=', 'realisasi.kode_salesman_baru');
            })
            ->leftjoinSub($subqueryKendaraan, 'kendaraan', function ($join) {
                $join->on('salesman.kode_salesman', '=', 'kendaraan.kode_salesman');
            })

            ->leftjoinSub($subqueryOA, 'oa', function ($join) {
                $join->on('salesman.kode_salesman', '=', 'oa.kode_salesman');
            })

            ->leftjoinSub($subqueryPenjvsavg, 'penjvsavg', function ($join) {
                $join->on('salesman.kode_salesman', '=', 'penjvsavg.kode_salesman');
            })

            ->leftJoin(
                DB::raw("(
                SELECT salesman.kode_salesman,
                (IFNULL(jml_belumsetorbulanlalu,0)+IFNULL(totalsetoran,0)) + IFNULL(jml_gmlast,0) - IFNULL(jml_gmnow,0) - IFNULL(jml_belumsetorbulanini,0) as realisasi_cashin
                FROM salesman
                LEFT JOIN (
                    SELECT kode_salesman,jumlah as jml_belumsetorbulanlalu FROM keuangan_belumsetor_detail
                    INNER JOIN keuangan_belumsetor ON keuangan_belumsetor_detail.kode_belumsetor = keuangan_belumsetor.kode_belumsetor
                    WHERE bulan='$bulanlalu' AND tahun='$tahunlalu'
                ) bs ON (salesman.kode_salesman = bs.kode_salesman)

                LEFT JOIN (
                    SELECT kode_salesman, SUM(lhp_tunai+lhp_tagihan) as totalsetoran FROM keuangan_setoranpenjualan
                    WHERE tanggal BETWEEN '$dari' AND '$sampai' GROUP BY kode_salesman
                ) sp ON (salesman.kode_salesman = sp.kode_salesman)

                LEFT JOIN (
                    SELECT
                    IFNULL(hb.kode_salesman,marketing_penjualan_giro.kode_salesman) as kode_salesman,
                    SUM(jumlah) AS jml_gmlast
                    FROM
                    marketing_penjualan_giro_detail
                    INNER JOIN marketing_penjualan_giro ON marketing_penjualan_giro_detail.kode_giro = marketing_penjualan_giro.kode_giro
                    INNER JOIN marketing_penjualan ON marketing_penjualan_giro_detail.no_faktur = marketing_penjualan.no_faktur
                    LEFT JOIN ( SELECT kode_giro,kode_salesman, tanggal as tglbayar FROM marketing_penjualan_historibayar
                    LEFT JOIN marketing_penjualan_historibayar_giro ON marketing_penjualan_historibayar.no_bukti = marketing_penjualan_historibayar_giro.no_bukti
                    GROUP BY kode_giro, tanggal,kode_salesman ) AS hb ON marketing_penjualan_giro.kode_giro = hb.kode_giro
                    WHERE
                    MONTH (marketing_penjualan_giro.tanggal) = '$bulanlalu'
                    AND YEAR (marketing_penjualan_giro.tanggal) = '$tahunlalu'
                    AND omset_tahun = '$request->tahun'
                    AND omset_bulan = '$request->bulan'
                    OR  MONTH (marketing_penjualan_giro.tanggal) = '$blnlast1'
                    AND YEAR (marketing_penjualan_giro.tanggal) = '$thnlast1'
                    AND omset_tahun = '$request->tahun'
                    AND omset_bulan = '$request->bulan'
                    GROUP BY
                    IFNULL( hb.kode_salesman, marketing_penjualan_giro.kode_salesman )
                ) gmlast ON (salesman.kode_salesman = gmlast.kode_salesman)
                LEFT JOIN (
                SELECT
                    IFNULL(hb.kode_salesman,marketing_penjualan_giro.kode_salesman) as kode_salesman,
                    SUM(jumlah) AS jml_gmnow
                FROM
                    marketing_penjualan_giro_detail
                    INNER JOIN marketing_penjualan_giro ON marketing_penjualan_giro_detail.kode_giro = marketing_penjualan_giro.kode_giro
                    INNER JOIN marketing_penjualan ON marketing_penjualan_giro_detail.no_faktur = marketing_penjualan.no_faktur
                    LEFT JOIN ( SELECT kode_giro,kode_salesman, tanggal as tglbayar FROM marketing_penjualan_historibayar
                    LEFT JOIN marketing_penjualan_historibayar_giro ON marketing_penjualan_historibayar.no_bukti = marketing_penjualan_historibayar_giro.no_bukti
                    GROUP BY kode_giro, tanggal,kode_salesman ) AS hb ON marketing_penjualan_giro.kode_giro = hb.kode_giro
                WHERE
                    marketing_penjualan_giro.tanggal >= '$dari'
                    AND marketing_penjualan_giro.tanggal <= '$sampai' AND tglbayar IS NULL AND omset_bulan = '0' AND omset_tahun = '0'
                    OR  marketing_penjualan_giro.tanggal >= '$dari'
                    AND marketing_penjualan_giro.tanggal <= '$sampai' AND tglbayar >= '$end'
                    AND omset_bulan > '$request->bulan'
                    AND omset_tahun >= '$request->tahun'
                GROUP BY
                IFNULL( hb.kode_salesman, marketing_penjualan_giro.kode_salesman )
                ) gmnow ON (salesman.kode_salesman = gmnow.kode_salesman)

                LEFT JOIN (
                    SELECT keuangan_belumsetor_detail.kode_salesman, SUM(jumlah) as jml_belumsetorbulanini
                    FROM keuangan_belumsetor_detail
                    INNER JOIN keuangan_belumsetor ON keuangan_belumsetor_detail.kode_belumsetor = keuangan_belumsetor.kode_belumsetor
                    WHERE bulan ='$request->bulan' AND tahun ='$request->tahun' GROUP BY kode_salesman
                ) bsnow ON (salesman.kode_salesman = bsnow.kode_salesman)
                ) hb"),
                function ($join) {
                    $join->on('salesman.kode_salesman', '=', 'hb.kode_salesman');
                }
            )

            ->leftJoin(
                DB::raw("(
                    SELECT
                        salesbarunew,IFNULL( SUM(jumlah ), 0 ) - SUM(IFNULL( totalretur, 0 )) - SUM(IFNULL( totalbayar, 0 )) AS sisapiutangsaldo
                    FROM
                    marketing_saldoawal_piutang_detail spf
                    INNER JOIN marketing_saldoawal_piutang ON spf.kode_saldo_awal = marketing_saldoawal_piutang.kode_saldo_awal
                    INNER JOIN marketing_penjualan ON spf.no_faktur = marketing_penjualan.no_faktur
                    INNER JOIN pelanggan ON marketing_penjualan.kode_pelanggan = pelanggan.kode_pelanggan
                    LEFT JOIN (
                            SELECT
                                pj.no_faktur,IF( salesbaru IS NULL, pj.kode_salesman, salesbaru ) AS salesbarunew,salesman.nama_salesman AS nama_sales,
                                IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS cabangbarunew
                            FROM
                                marketing_penjualan pj
                            INNER JOIN salesman ON pj.kode_salesman = salesman.kode_salesman
                            LEFT JOIN (
                                SELECT
                                    id,
                                    no_faktur,
                                    move_faktur.kode_salesman_baru AS salesbaru,
                                    salesman.kode_cabang AS cabangbaru
                                FROM
                                    marketing_penjualan_movefaktur move_faktur
                                INNER JOIN salesman ON move_faktur.kode_salesman = salesman.kode_salesman
                                WHERE id IN ( SELECT max( id ) FROM marketing_penjualan_movefaktur WHERE tanggal <= '$sampai' GROUP BY no_faktur )
                            ) move_fak ON ( pj.no_faktur = move_fak.no_faktur )
                        ) pjmove ON ( marketing_penjualan.no_faktur = pjmove.no_faktur )
                        LEFT JOIN (
                            SELECT
                                marketing_retur.no_faktur AS no_faktur,
                                SUM((SELECT(subtotal) FROM marketing_retur_detail WHERE no_retur = marketing_retur.no_retur)) AS totalretur
                            FROM
                                marketing_retur
                            WHERE
                                tanggal BETWEEN '$dari' AND '$sampai' AND jenis_retur='PF'
                            GROUP BY
                                marketing_retur.no_faktur
                        ) r ON ( marketing_penjualan.no_faktur = r.no_faktur )
                        LEFT JOIN (
                            SELECT no_faktur, sum( marketing_penjualan_historibayar.jumlah ) AS totalbayar
                            FROM marketing_penjualan_historibayar
                            WHERE tanggal BETWEEN '$dari' AND '$sampai' GROUP BY no_faktur
                        ) hb ON ( marketing_penjualan.no_faktur = hb.no_faktur )
                    WHERE
                        datediff( '$sampai', marketing_penjualan.tanggal ) > 30 AND bulan = '$request->bulan' AND tahun = '$request->tahun'
                    GROUP BY
                        salesbarunew
                ) spf"),
                function ($join) {
                    $join->on('salesman.kode_salesman', '=', 'spf.salesbarunew');
                }
            )
            ->whereIn('salesman.kode_salesman', $salesman_target)
            ->orderBy('nama_salesman')
            ->get();

        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        $data['produk'] = $produk;
        return view('marketing.laporan.komisi_salesman_cetak', $data);
    }

    public function cetakkomisidriverhelper(Request $request)
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

        $last_ratio = Ratiokomisidriverhelper::where('kode_cabang', $kode_cabang)->orderBy('tanggal_berlaku', 'desc')->first();
        if ($last_ratio == null) {
            return Redirect::back()->with(messageError('Ratio Belum Diset'));
        }


        $subqueryRatio = Detailratiodriverhelper::where('kode_ratio', $last_ratio->kode_ratio);
        $data['komisi'] = Dpbdriverhelper::select(
            'gudang_cabang_dpb_driverhelper.kode_driver_helper',
            'posisi',
            'driver_helper.nama_driver_helper',
            'ratio_default',
            'ratio_helper',
            DB::raw('SUM(CASE WHEN gudang_cabang_dpb_driverhelper.kode_posisi = \'D\' THEN (SELECT SUM(ROUND(gudang_cabang_dpb_detail.jml_penjualan / produk.isi_pcs_dus, 3)) FROM gudang_cabang_dpb_detail JOIN produk ON gudang_cabang_dpb_detail.kode_produk = produk.kode_produk WHERE gudang_cabang_dpb_detail.no_dpb = gudang_cabang_dpb_driverhelper.no_dpb) ELSE 0 END) AS qty_driver'),
            DB::raw('SUM(CASE WHEN gudang_cabang_dpb_driverhelper.kode_posisi = \'H\' THEN gudang_cabang_dpb_driverhelper.jumlah ELSE 0 END) AS qty_helper')
        )
            ->join('gudang_cabang_dpb', 'gudang_cabang_dpb_driverhelper.no_dpb', '=', 'gudang_cabang_dpb.no_dpb')
            ->join('driver_helper', 'gudang_cabang_dpb_driverhelper.kode_driver_helper', '=', 'driver_helper.kode_driver_helper')
            ->leftjoinSub($subqueryRatio, 'ratio', function ($join) {
                $join->on('gudang_cabang_dpb_driverhelper.kode_driver_helper', '=', 'ratio.kode_driver_helper');
            })
            ->whereBetween('gudang_cabang_dpb.tanggal_ambil', [$request->dari, $request->sampai])
            ->where('driver_helper.kode_cabang', $kode_cabang)
            ->groupBy('gudang_cabang_dpb_driverhelper.kode_driver_helper', 'driver_helper.nama_driver_helper', 'ratio_default', 'ratio_helper', 'posisi')
            ->get();
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['cabang'] = Cabang::where('kode_cabang', $kode_cabang)->first();
        return view('marketing.laporan.komisidriverhelper_cetak', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Charts\HasilproduksiChart;
use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Checkinpenjualan;
use App\Models\Detaildpb;
use App\Models\Detailmutasigudangcabang;
use App\Models\Detailmutasigudangjadi;
use App\Models\Detailpenjualan;
use App\Models\Detailretur;
use App\Models\Detailsaldoawalgudangcabang;
use App\Models\Detailsaldoawalgudangjadi;
use App\Models\Detailsaldoawalpiutangpelanggan;
use App\Models\Detailtargetkomisi;
use App\Models\Dpb;
use App\Models\Historibayarpenjualan;
use App\Models\Karyawan;
use App\Models\Kategoritransaksimutasikeuangan;
use App\Models\Kendaraan;
use App\Models\Mutasigudangjadi;
use App\Models\Mutasikeuangan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Saldoawalgudangcabang;
use App\Models\Saldoawalgudangjadi;
use App\Models\Saldoawalledger;
use App\Models\Saldoawalmutasikeungan;
use App\Models\Saldokasbesarkeuangan;
use App\Models\Salesman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // dd(session('screen_width'), session('screen_height'));
        // $ip = request()->ip(); // dapatkan IP pengguna
        // $locationData = file_get_contents("http://ipinfo.io/{$ip}/json");
        // $location = json_decode($locationData);

        // dd($location);

        $default_marketing = ['super admin', 'direktur', 'gm marketing', 'gm administrasi', 'regional sales manager', 'manager keuangan', 'manager audit'];
        $user = User::findorfail(auth()->user()->id);
        if ($user->hasAnyRole($default_marketing)) {
            return $this->marketing();
        } else if ($user->hasRole(['operation manager', 'sales marketing manager'])) {
            return $this->operationmanager();
        } else if ($user->hasRole('salesman')) {
            return $this->salesman();
        } else if ($user->hasRole('admin penjualan')) {
            return $this->operationmanager();
        } else if ($user->hasRole('admin persediaan cabang')) {
            return $this->operationmanager();
        } else if ($user->hasRole('gm operasional') || $user->hasRole('spv produksi') || $user->hasRole('manager produksi') || $user->hasRole('admin gudang pusat') || $user->hasRole('manager gudang') || $user->hasRole('spv gudang pusat') || $user->hasRole('spv pdqc')) {
            return $this->operasional();
        } else if ($user->hasAnyRole(['asst. manager hrd', 'spv presensi', 'spv recruitment'])) {
            return $this->hrd();
        } else if ($user->hasAnyRole(['owner'])) {
            return $this->owner($request);
        } else {
            return $this->dashboarddefault();
        }
    }


    function dashboarddefault()
    {
        return view('dashboard.default');
    }



    public function dashboardowner(Request $request)
    {
        return $this->owner($request);
    }
    function owner($request)
    {

        $bulan = date('m', strtotime(date('Y-m-d')));
        $tahun = date('Y', strtotime(date('Y-m-d')));

        $saldoawal = Saldoawalledger::where('bulan', $bulan)->where('tahun', $tahun);

        $start_date = $tahun . "-" . $bulan . "-01";
        $mutasi  = Mutasikeuangan::select(
            'kode_bank',
            DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as kredit"),
            DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as debet"),
        )
            ->where('tanggal', '>=', $start_date)
            ->where('tanggal', '<=', date('Y-m-d'))
            ->groupBy('kode_bank');


        $mutasi_kategori  = Mutasikeuangan::select(
            'keuangan_mutasi.kode_kategori',
            DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as kredit"),
            DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as debet"),
        )
            ->when(!empty($request->dari) && !empty($request->sampai), function ($query) use ($request) {
                $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
            })
            ->when(empty($request->dari) && empty($request->sampai), function ($query) use ($start_date) {
                $query->where('tanggal', '>=', $start_date)->where('tanggal', '<=', date('Y-m-d'));
            })
            ->groupBy('keuangan_mutasi.kode_kategori');

        $rekapdebetkreditbytanggal  = Mutasikeuangan::select(
            'kode_bank',
            DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as rekap_kredit"),
            DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as rekap_debet"),
        )
            ->when($request->dari && $request->sampai, function ($query) use ($request) {
                $query->where('tanggal', '>=', $request->dari)
                    ->where('tanggal', '<=', $request->sampai);
            }, function ($query) {
                $query->where('tanggal', date('Y-m-d'));
            })
            ->groupBy('kode_bank');


        $data['bank'] = Bank::leftJoinSub($mutasi, 'mutasi', function ($join) {
            $join->on('bank.kode_bank', '=', 'mutasi.kode_bank');
        })
            ->leftJoinSub($saldoawal, 'saldoawal', function ($join) {
                $join->on('bank.kode_bank', '=', 'saldoawal.kode_bank');
            })
            ->leftJoinSub($rekapdebetkreditbytanggal, 'rekapdebetkreditbytanggal', function ($join) {
                $join->on('bank.kode_bank', '=', 'rekapdebetkreditbytanggal.kode_bank');
            })
            ->select(
                'bank.*',
                'saldoawal.jumlah as saldoawal',
                DB::raw("(IFNULL(saldoawal.jumlah,0) + IFNULL(mutasi.kredit,0) - IFNULL(mutasi.debet,0)) as saldo"),
                'rekapdebetkreditbytanggal.rekap_kredit',
                'rekapdebetkreditbytanggal.rekap_debet'

            )
            ->where('bank.kode_bank', '!=', 'BK071')
            ->orderBy('bank.nama_bank')
            ->get();


        $data['kategori'] = Kategoritransaksimutasikeuangan::leftJoinSub($mutasi_kategori, 'mutasi_kategori', function ($join) {
            $join->on('keuangan_mutasi_kategori.kode_kategori', '=', 'mutasi_kategori.kode_kategori');
        })

            ->select(
                'keuangan_mutasi_kategori.*',
                'mutasi_kategori.kredit',
                'mutasi_kategori.debet'
            )
            ->orderBy('keuangan_mutasi_kategori.nama_kategori')
            ->get();


        $data['rekap'] = Bank::leftJoinSub($mutasi, 'mutasi', function ($join) {
            $join->on('bank.kode_bank', '=', 'mutasi.kode_bank');
        })
            ->leftJoinSub($saldoawal, 'saldoawal', function ($join) {
                $join->on('bank.kode_bank', '=', 'saldoawal.kode_bank');
            })
            ->leftJoinSub($rekapdebetkreditbytanggal, 'rekapdebetkreditbytanggal', function ($join) {
                $join->on('bank.kode_bank', '=', 'rekapdebetkreditbytanggal.kode_bank');
            })
            ->select(
                DB::raw("SUM((IFNULL(saldoawal.jumlah,0) + IFNULL(mutasi.kredit,0) - IFNULL(mutasi.debet,0))) as total_saldo"),
                DB::raw("SUM(rekapdebetkreditbytanggal.rekap_kredit) as total_rekap_kredit"),
                DB::raw("SUM(rekapdebetkreditbytanggal.rekap_debet) as total_rekap_debet")

            )
            ->where('bank.kode_bank', '!=', 'BK071')
            ->first();



        // $dari = !empty($request->dari) ? $request->dari : $start_date;
        // $sampai = !empty($request->sampai) ? $request->sampai : date('Y-m-d');
        // $data['dari'] = $dari;
        // $data['sampai'] = $sampai;
        $data['mutasi_kategori_detail']  = Mutasikeuangan::select(
            'keuangan_mutasi.tanggal',
            'keuangan_mutasi.kode_kategori',
            'keuangan_mutasi_kategori.nama_kategori',
            // 'bank.nama_bank',
            // 'bank.no_rekening',
            DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as kredit"),
            DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as debet"),
        )
            ->leftJoin('keuangan_mutasi_kategori', 'keuangan_mutasi.kode_kategori', '=', 'keuangan_mutasi_kategori.kode_kategori')
            ->leftJoin('bank', 'keuangan_mutasi.kode_bank', '=', 'bank.kode_bank')
            ->when(!empty($request->dari) && !empty($request->sampai), function ($query) use ($request) {
                $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
            })
            ->when(empty($request->dari) && empty($request->sampai), function ($query) use ($start_date) {
                $query->where('tanggal', '>=', $start_date)->where('tanggal', '<=', date('Y-m-d'));
            })
            ->groupBy(
                'keuangan_mutasi.tanggal',
                'keuangan_mutasi_kategori.nama_kategori',
                'keuangan_mutasi.kode_kategori'
            )
            ->orderBy('keuangan_mutasi.tanggal', 'asc')
            ->get();



        $qsaldo_kasbesar_cabang = Saldokasbesarkeuangan::query();
        $qsaldo_kasbesar_cabang->select(DB::raw('SUM(IF(debet_kredit="K",jumlah,0)) as kredit'), DB::raw('SUM(IF(debet_kredit="D",jumlah,0)) as debet'));
        if (!empty($request->sampai)) {
            $start_date = $request->dari;
            $end_date = $request->sampai;
            $qsaldo_kasbesar_cabang->whereBetween('tanggal', [$start_date, $end_date]);
        } else {
            $start_date = date("Y-m-01", strtotime(date('Y-m-d')));
            $end_date = date('Y-m-d');
            $qsaldo_kasbesar_cabang->whereBetween('tanggal', [$start_date, $end_date]);
        }
        $qsaldo_kasbesar_cabang->where('kode_cabang', 'PST');
        $data['saldo_kasbesar_cabang'] = $qsaldo_kasbesar_cabang->first();

        if ($request->export == 1) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Mutasi Keuangan.xls");
            $dari = !empty($request->dari) ? $request->dari : $start_date;
            $sampai = !empty($request->sampai) ? $request->sampai : date('Y-m-d');
            $data['dari'] = $dari;
            $data['sampai'] = $sampai;
            $data['mutasi_kategori_detail']  = Mutasikeuangan::select(
                'keuangan_mutasi.tanggal',
                'keuangan_mutasi_kategori.nama_kategori',
                // 'bank.nama_bank',
                // 'bank.no_rekening',
                DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as kredit"),
                DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as debet"),
            )
                ->leftJoin('keuangan_mutasi_kategori', 'keuangan_mutasi.kode_kategori', '=', 'keuangan_mutasi_kategori.kode_kategori')
                ->leftJoin('bank', 'keuangan_mutasi.kode_bank', '=', 'bank.kode_bank')
                ->when(!empty($request->dari) && !empty($request->sampai), function ($query) use ($request) {
                    $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
                })
                ->when(empty($request->dari) && empty($request->sampai), function ($query) use ($start_date) {
                    $query->where('tanggal', '>=', $start_date)->where('tanggal', '<=', date('Y-m-d'));
                })
                ->groupBy('keuangan_mutasi.tanggal', 'keuangan_mutasi_kategori.nama_kategori')
                ->orderBy('keuangan_mutasi.tanggal', 'asc')
                ->get();

            return view('keuangan.laporan.mutasikeuangan_kategori_cetak', $data);
        }
        return view('dashboard.owner', $data);
    }

    public function mobilemarketing()
    {
        return view('dashboard.mobile.marketing2');
    }


    function salesman()
    {
        $hariini = date('Y-m-d');
        $data['penjualan'] = Penjualan::select()
            ->select(
                DB::raw("SUM((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn) as total")
            )
            ->where('marketing_penjualan.kode_salesman', auth()->user()->kode_salesman)
            ->where('marketing_penjualan.tanggal', $hariini)
            ->where('marketing_penjualan.status_batal', 0)
            ->first();

        // dd($data['penjualan']);

        $data['pembayaran'] = Historibayarpenjualan::select(
            DB::raw("SUM(jumlah) as total")
        )
            ->join('marketing_penjualan', 'marketing_penjualan.no_faktur', '=', 'marketing_penjualan_historibayar.no_faktur')
            ->where('marketing_penjualan_historibayar.tanggal', $hariini)
            ->where('marketing_penjualan_historibayar.kode_salesman', auth()->user()->kode_salesman)
            ->where('status_batal', 0)
            ->first();

        $data['jmltransaksi'] = Penjualan::where('tanggal', $hariini)
            ->where('kode_salesman', auth()->user()->kode_salesman)
            ->where('status_batal', 0)
            ->count();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('dashboard.salesman', $data);
    }

    function operationmanager()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('dashboard.operationmanager', $data);
        } else {
            return view('dashboard.operationmanager', $data);
        }

        die;


        //Jika Mobile

        //Penjualan Bulan Ini
        // $dari = date('Y-m') . "-01";
        // $sampai = date('Y-m-t', strtotime($dari));

        // $data['penjualan'] = Penjualan::select()
        //     ->select(
        //         DB::raw("SUM((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn) as total")
        //     )
        //     ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
        //     ->where('salesman.kode_cabang', auth()->user()->kode_cabang)
        //     ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
        //     ->where('marketing_penjualan.status_batal', 0)
        //     ->first();

        // // dd($data['penjualan']);

        // $data['pembayaran'] = Historibayarpenjualan::select(
        //     DB::raw("SUM(jumlah) as total")
        // )
        //     ->join('marketing_penjualan', 'marketing_penjualan.no_faktur', '=', 'marketing_penjualan_historibayar.no_faktur')
        //     ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
        //     ->whereBetween('marketing_penjualan_historibayar.tanggal', [$dari, $sampai])
        //     ->where('salesman.kode_cabang', auth()->user()->kode_cabang)
        //     ->where('status_batal', 0)
        //     ->first();

        // $data['jmltransaksi'] = Penjualan::join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
        //     ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
        //     ->where('salesman.kode_cabang', auth()->user()->kode_cabang)
        //     ->where('status_batal', 0)
        //     ->count();
        // $data['list_bulan'] = config('global.list_bulan');
        // $data['start_year'] = config('global.start_year');
        return view('dashboard.mobile.operationmanager', $data);
    }

    public function marketing()
    {
        $user = User::findorfail(auth()->user()->id);

        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $agent = new Agent();

        if ($user->hasRole(['sales marketing manager', 'regional sales manager', 'gm marketing'])) {
            if ($agent->isMobile()) {
                return view('dashboard.mobile.marketing2', $data);
            } else {
                return view('dashboard.marketing', $data);
            }
        } else {
            return view('dashboard.marketing', $data);
        }
    }


    public function operasional()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('dashboard.operasional', $data);
    }

    public function produksi()
    {
        $data['start_year'] = config('global.start_year');
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan_singkat'] = config('global.nama_bulan_singkat');
        return view('dashboard.produksi', $data);
    }

    public function generalaffair()
    {

        $kendaraan = new Kendaraan();
        $data['kir_lewat'] = $kendaraan->getKirJatuhtempo(0)->get();
        $data['kir_bulanini'] = $kendaraan->getKirJatuhtempo(1)->get();
        $data['kir_bulandepan'] = $kendaraan->getKirJatuhtempo(2)->get();
        $data['kir_duabulan'] = $kendaraan->getKirJatuhtempo(3)->get();

        $data['pajaksatutahun_lewat'] = $kendaraan->getPajak1tahunjatuhtempo(0)->get();
        $data['pajaksatutahun_bulanini'] = $kendaraan->getPajak1tahunjatuhtempo(1)->get();
        $data['pajaksatutahun_bulandepan'] = $kendaraan->getPajak1tahunjatuhtempo(2)->get();
        $data['pajaksatutahun_duabulan'] = $kendaraan->getPajak1tahunjatuhtempo(3)->get();


        $data['pajaklimatahun_lewat'] = $kendaraan->getPajak5tahunjatuhtempo(0)->get();
        $data['pajaklimatahun_bulanini'] = $kendaraan->getPajak5tahunjatuhtempo(1)->get();
        $data['pajaklimatahun_bulandepan'] = $kendaraan->getPajak5tahunjatuhtempo(2)->get();
        $data['pajaklimatahun_duabulan'] = $kendaraan->getPajak5tahunjatuhtempo(3)->get();

        $data['rekapkendaraan'] = $kendaraan->getRekapkendaraancabang()->get();
        $data['jmlkendaraan'] = Kendaraan::count();
        return view('dashboard.generalaffair', $data);
    }

    public function hrd()
    {
        $sk = new Karyawan();
        $data['status_karyawan'] = $sk->getRekapstatuskaryawan();
        $data['kontrak_lewat'] = $sk->getRekapkontrak(0);
        $data['kontrak_bulanini'] = $sk->getRekapkontrak(1);
        $data['kontrak_bulandepan'] = $sk->getRekapkontrak(2);
        $data['kontrak_duabulan'] = $sk->getRekapkontrak(3);
        $data['karyawancabang'] = $sk->getRekapkaryawancabang();
        return view('dashboard.hrd', $data);
    }


    public function rekappersediaan()
    {
        $user = User::findorfail(auth()->user()->id);
        $role = $user->getRoleNames()->first();

        // Ambil daftar produk dari tabel produk
        $products = Produk::where('status_aktif_produk', 1)->orderBy('kode_produk')->get();

        // Mendapatkan tanggal hari ini
        $today = Carbon::now()->format('Y-m-d');

        // Subquery untuk mendapatkan tanggal terakhir saldo awal untuk setiap cabang
        $subqueryLastDate = DB::table('gudang_cabang_saldoawal')
            ->select('kode_cabang', DB::raw('MAX(tanggal) as last_date'))
            ->where('kondisi', 'GS')
            ->groupBy('kode_cabang');


        $selectColumnsSaldoawal = [];
        $selectColumnsMutasi = [];
        $selectColumnsDpbambil = [];
        $selectColumnsBuffer = [];
        $selectColumnsMaxstok = [];
        $selectColumnsPenjualan = [];
        $selectColumnsSuratjalangudang = [];
        $selectColumns = [];
        foreach ($products as $produk) {
            $kodeProduk = $produk->kode_produk;
            $selectColumnsSaldoawal[] = DB::raw('SUM(IF(gudang_cabang_saldoawal_detail.kode_produk = "' . $kodeProduk . '", gudang_cabang_saldoawal_detail.jumlah, 0)) AS saldo_' . $kodeProduk);
            $selectColumnsMutasi[] = DB::raw('SUM(IF(gudang_cabang_mutasi_detail.kode_produk = "' . $kodeProduk . '" AND in_out_good = "I", gudang_cabang_mutasi_detail.jumlah, 0)) - SUM(IF(gudang_cabang_mutasi_detail.kode_produk = "' . $kodeProduk . '" AND in_out_good = "O", gudang_cabang_mutasi_detail.jumlah, 0)) AS mutasi_' . $kodeProduk);
            $selectColumnsDpbambil[] = DB::raw('SUM(IF(gudang_cabang_dpb_detail.kode_produk="' . $kodeProduk . '", gudang_cabang_dpb_detail.jml_ambil,0)) as ambil_' . $kodeProduk);
            $selectColumnsDpbkembali[] = DB::raw('SUM(IF(gudang_cabang_dpb_detail.kode_produk="' . $kodeProduk . '", gudang_cabang_dpb_detail.jml_kembali,0)) as kembali_' . $kodeProduk);
            $selectColumnsBuffer[] = DB::raw('SUM(IF(buffer_stok_detail.kode_produk="' . $kodeProduk . '", buffer_stok_detail.jumlah,0)) as buffer_' . $kodeProduk);
            $selectColumnsMaxstok[] = DB::raw('SUM(IF(max_stok_detail.kode_produk="' . $kodeProduk . '", max_stok_detail.jumlah,0)) as max_' . $kodeProduk);
            $selectColumnsPenjualan[] = DB::raw('SUM(IF(produk_harga.kode_produk="' . $kodeProduk . '", marketing_penjualan_detail.jumlah,0)) as penjualan_' . $kodeProduk);
            $selectColumnsSuratjalangudang[] = DB::raw('SUM(IF(gudang_jadi_mutasi_detail.kode_produk="' . $kodeProduk . '", gudang_jadi_mutasi_detail.jumlah,0)) as suratjalan_' . $kodeProduk);
            //Gudang Jadi
            $selectColumnsGudang[] = DB::raw('SUM(IF(produk.kode_produk = "' . $kodeProduk . '", subquerySaldoawalgudang.saldo_awal + subqueryMutasigudang.sisa_mutasi, 0)) AS saldoakhir_' . $kodeProduk);

            $selectColumns[] = 'saldo_' . $kodeProduk;
            $selectColumns[] = 'mutasi_' . $kodeProduk;
            $selectColumns[] = 'ambil_' . $kodeProduk;
            $selectColumns[] = 'kembali_' . $kodeProduk;
            $selectColumns[] = 'buffer_' . $kodeProduk;
            $selectColumns[] = 'max_' . $kodeProduk;
            $selectColumns[] = 'penjualan_' . $kodeProduk;
            $selectColumns[] = 'suratjalan_' . $kodeProduk;
        }

        // Subquery untuk menghitung saldo awal per produk dengan kondisi 'GS'
        $subquerySaldoAwal = DB::table('gudang_cabang_saldoawal_detail')
            ->join('gudang_cabang_saldoawal', 'gudang_cabang_saldoawal_detail.kode_saldo_awal', '=', 'gudang_cabang_saldoawal.kode_saldo_awal')
            ->joinSub($subqueryLastDate, 'subqueryLastDate', function ($join) {
                $join->on('gudang_cabang_saldoawal.kode_cabang', '=', 'subqueryLastDate.kode_cabang')
                    ->on('gudang_cabang_saldoawal.tanggal', '=', 'subqueryLastDate.last_date');
            })
            ->where('gudang_cabang_saldoawal.kondisi', 'GS')
            ->select(
                'gudang_cabang_saldoawal.kode_cabang',
                ...$selectColumnsSaldoawal
            )

            ->groupBy('gudang_cabang_saldoawal.kode_cabang');



        $subqueryMutasi = DB::table('gudang_cabang_mutasi_detail')
            ->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi')
            ->select(
                'gudang_cabang_mutasi.kode_cabang',
                ...$selectColumnsMutasi
            )
            ->joinSub($subqueryLastDate, 'subqueryLastDate', function ($join) {
                $join->on('gudang_cabang_mutasi.kode_cabang', '=', 'subqueryLastDate.kode_cabang');
            })
            ->whereBetween('gudang_cabang_mutasi.tanggal', [
                DB::raw('subqueryLastDate.last_date'),
                $today
            ])
            ->whereIn('jenis_mutasi', ['SJ', 'TI', 'TO', 'RG', 'RP', 'RK', 'PY'])
            ->groupBy('gudang_cabang_mutasi.kode_cabang');


        $subqueryDPB = DB::table('gudang_cabang_dpb_detail')
            ->join('gudang_cabang_dpb', 'gudang_cabang_dpb_detail.no_dpb', '=', 'gudang_cabang_dpb.no_dpb')
            ->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman')
            ->select(
                'salesman.kode_cabang',
                ...$selectColumnsDpbambil,
                ...$selectColumnsDpbkembali
            )
            ->joinSub($subqueryLastDate, 'subqueryLastDate', function ($join) {
                $join->on('salesman.kode_cabang', '=', 'subqueryLastDate.kode_cabang');
            })
            ->whereBetween('gudang_cabang_dpb.tanggal_ambil', [
                DB::raw('subqueryLastDate.last_date'),
                $today
            ])
            ->groupBy('salesman.kode_cabang');

        $subqueryBuffer = DB::table('buffer_stok_detail')
            ->join('buffer_stok', 'buffer_stok_detail.kode_buffer_stok', '=', 'buffer_stok.kode_buffer_stok')
            ->select(
                'buffer_stok.kode_cabang',
                ...$selectColumnsBuffer
            )
            ->groupBy('buffer_stok.kode_cabang');

        $subqueryMaxstok = DB::table('max_stok_detail')
            ->join('max_stok', 'max_stok_detail.kode_max_stok', '=', 'max_stok.kode_max_stok')
            ->select(
                'max_stok.kode_cabang',
                ...$selectColumnsMaxstok
            )
            ->groupBy('max_stok.kode_cabang');

        $subqueryPenjualan = DB::table('marketing_penjualan_detail')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->whereBetween('marketing_penjualan.tanggal', [
                date('Y-m') . '-01',
                $today
            ])
            ->select(
                'salesman.kode_cabang',
                ...$selectColumnsPenjualan
            )
            ->groupBy('salesman.kode_cabang');


        $subquerySuratjalan = DB::table('gudang_jadi_mutasi_detail')
            ->join('gudang_jadi_mutasi', 'gudang_jadi_mutasi_detail.no_mutasi', '=', 'gudang_jadi_mutasi.no_mutasi')
            ->join('marketing_permintaan_kiriman', 'gudang_jadi_mutasi.no_permintaan', '=', 'marketing_permintaan_kiriman.no_permintaan')
            ->joinSub($subqueryLastDate, 'subqueryLastDate', function ($join) {
                $join->on('marketing_permintaan_kiriman.kode_cabang', '=', 'subqueryLastDate.kode_cabang');
            })
            ->whereBetween('gudang_jadi_mutasi.tanggal', [
                DB::raw('subqueryLastDate.last_date'),
                $today
            ])
            ->where('jenis_mutasi', 'SJ')
            ->where('status_surat_jalan', 0)
            ->select(
                'marketing_permintaan_kiriman.kode_cabang',
                ...$selectColumnsSuratjalangudang
            )

            ->groupBy('marketing_permintaan_kiriman.kode_cabang');
        //dd($subquerySuratjalan);
        // Query utama
        $query = Cabang::query();

        $query->leftJoinSub($subquerySaldoAwal, 'subquerySaldoAwal', function ($join) {
            $join->on('cabang.kode_cabang', '=', 'subquerySaldoAwal.kode_cabang');
        });
        $query->leftJoinSub($subqueryMutasi, 'subqueryMutasi', function ($join) {
            $join->on('cabang.kode_cabang', '=', 'subqueryMutasi.kode_cabang');
        });
        $query->leftJoinSub($subqueryDPB, 'subqueryDPB', function ($join) {
            $join->on('cabang.kode_cabang', '=', 'subqueryDPB.kode_cabang');
        });

        //Left Join ke Buffer Stok
        $query->leftJoinSub($subqueryBuffer, 'subqueryBuffer', function ($join) {
            $join->on('cabang.kode_cabang', '=', 'subqueryBuffer.kode_cabang');
        });

        $query->leftJoinSub($subqueryMaxstok, 'subqueryMaxstok', function ($join) {
            $join->on('cabang.kode_cabang', '=', 'subqueryMaxstok.kode_cabang');
        });

        $query->leftJoinSub($subqueryPenjualan, 'subqueryPenjualan', function ($join) {
            $join->on('cabang.kode_cabang', '=', 'subqueryPenjualan.kode_cabang');
        });

        $query->leftJoinSub($subquerySuratjalan, 'subquerySuratjalan', function ($join) {
            $join->on('cabang.kode_cabang', '=', 'subquerySuratjalan.kode_cabang');
        });
        $query->select('cabang.kode_cabang', 'cabang.nama_cabang', ...$selectColumns);

        if ($role == 'regional sales manager') {
            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
        }

        $query->where('cabang.status_aktif_cabang', 1);
        $query->get();
        $results = $query->get();

        $lastsaldo = Detailsaldoawalgudangjadi::join('gudang_jadi_saldoawal', 'gudang_jadi_saldoawal_detail.kode_saldo_awal', '=', 'gudang_jadi_saldoawal.kode_saldo_awal')
            ->orderBy('tanggal', 'DESC')->first();



        $subquerySaldoawalgudang = Detailsaldoawalgudangjadi::join('gudang_jadi_saldoawal', 'gudang_jadi_saldoawal_detail.kode_saldo_awal', '=', 'gudang_jadi_saldoawal.kode_saldo_awal')
            ->where('gudang_jadi_saldoawal_detail.kode_saldo_awal', $lastsaldo->kode_saldo_awal)
            ->select(
                'gudang_jadi_saldoawal_detail.kode_produk',
                'gudang_jadi_saldoawal_detail.jumlah as saldo_awal',
            );


        $subqueryMutasigudang = Detailmutasigudangjadi::join('gudang_jadi_mutasi', 'gudang_jadi_mutasi_detail.no_mutasi', '=', 'gudang_jadi_mutasi.no_mutasi')
            ->whereBetween('gudang_jadi_mutasi.tanggal', [$lastsaldo->tanggal, $today])
            ->select('gudang_jadi_mutasi_detail.kode_produk', DB::raw('SUM(IF(in_out="I",gudang_jadi_mutasi_detail.jumlah,0)) - SUM(IF(in_out="O",gudang_jadi_mutasi_detail.jumlah,0)) as sisa_mutasi'))
            ->groupBy('gudang_jadi_mutasi_detail.kode_produk');



        $rekapgudang = Produk::where('status_aktif_produk', 1)
            //leftjoin ke tabel gudang_jadi_saldoawal_detail untuk mengambil Saldo Awal Terakhir Berdasarkan Tanggal
            ->leftJoinSub($subquerySaldoawalgudang, 'subquerySaldoawalgudang', function ($join) {
                $join->on('produk.kode_produk', '=', 'subquerySaldoawalgudang.kode_produk');
            })
            //Left Join ke Detail Mutasi Gudang Jadi
            ->leftJoinSub($subqueryMutasigudang, 'subqueryMutasigudang', function ($join) {
                $join->on('produk.kode_produk', '=', 'subqueryMutasigudang.kode_produk');
            })
            ->select($selectColumnsGudang)
            ->first();



        $data['rekapgudang'] = $rekapgudang;
        $data['rekappersediaancabang'] = $results;
        $data['products'] = $products;
        return view('dashboard.gudang.rekappersediaan', $data);
    }



    public function rekappersediaancabang()
    {
        $user = User::findorfail(auth()->user()->id);
        $kode_cabang = $user->kode_cabang;
        $today = date('Y-m-d');
        $subqueryBuffer = DB::table('buffer_stok_detail')
            ->join('buffer_stok', 'buffer_stok_detail.kode_buffer_stok', '=', 'buffer_stok.kode_buffer_stok')
            ->select(
                'buffer_stok_detail.kode_produk',
                'buffer_stok_detail.jumlah as buffer_stok'
            )
            ->where('kode_cabang', $kode_cabang);


        $subqueryMaxstok = DB::table('max_stok_detail')
            ->join('max_stok', 'max_stok_detail.kode_max_stok', '=', 'max_stok.kode_max_stok')
            ->select(
                'max_stok_detail.kode_produk',
                'max_stok_detail.jumlah as max_stok'
            )
            ->where('kode_cabang', $kode_cabang);


        $lastsaldo = Saldoawalgudangcabang::where('kode_cabang', $kode_cabang)
            ->where('kondisi', 'GS')
            ->orderBy('tanggal', 'DESC')->first();

        $lastsaldobs = Saldoawalgudangcabang::where('kode_cabang', $kode_cabang)
            ->where('kondisi', 'BS')
            ->orderBy('tanggal', 'DESC')->first();
        $subquerySaldoawal = DB::table('gudang_cabang_saldoawal_detail')
            ->join('gudang_cabang_saldoawal', 'gudang_cabang_saldoawal_detail.kode_saldo_awal', '=', 'gudang_cabang_saldoawal.kode_saldo_awal')

            ->select(
                'gudang_cabang_saldoawal_detail.kode_produk',
                'gudang_cabang_saldoawal_detail.jumlah as saldo_awal'
            )
            ->where('gudang_cabang_saldoawal_detail.kode_saldo_awal', $lastsaldo->kode_saldo_awal);

        $subquerySaldoawalbs = DB::table('gudang_cabang_saldoawal_detail')
            ->join('gudang_cabang_saldoawal', 'gudang_cabang_saldoawal_detail.kode_saldo_awal', '=', 'gudang_cabang_saldoawal.kode_saldo_awal')

            ->select(
                'gudang_cabang_saldoawal_detail.kode_produk',
                'gudang_cabang_saldoawal_detail.jumlah as saldo_awal_bs'
            )
            ->where('gudang_cabang_saldoawal_detail.kode_saldo_awal', $lastsaldobs->kode_saldo_awal);


        $subqueryMutasi = DB::table('gudang_cabang_mutasi_detail')
            ->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi')
            ->select(
                'gudang_cabang_mutasi_detail.kode_produk',
                DB::raw('SUM(IF(in_out_good="I",gudang_cabang_mutasi_detail.jumlah,0)) - SUM(IF(in_out_good="O",gudang_cabang_mutasi_detail.jumlah,0)) as sisa_mutasi')
            )
            ->whereBetween('gudang_cabang_mutasi.tanggal', [
                $lastsaldo->tanggal,
                $today
            ])
            ->where('gudang_cabang_mutasi.kode_cabang', $kode_cabang)
            ->whereIn('jenis_mutasi', ['SJ', 'TI', 'TO', 'RG', 'RP', 'RK', 'PY'])
            ->groupBy('gudang_cabang_mutasi_detail.kode_produk');

        $subqueryMutasibs = DB::table('gudang_cabang_mutasi_detail')
            ->join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi')
            ->select(
                'gudang_cabang_mutasi_detail.kode_produk',
                DB::raw('SUM(IF(in_out_bad="I",gudang_cabang_mutasi_detail.jumlah,0)) - SUM(IF(in_out_bad="O",gudang_cabang_mutasi_detail.jumlah,0)) as sisa_mutasi_bs')
            )
            ->whereBetween('gudang_cabang_mutasi.tanggal', [
                $lastsaldobs->tanggal,
                $today
            ])
            ->where('gudang_cabang_mutasi.kode_cabang', $kode_cabang)
            ->groupBy('gudang_cabang_mutasi_detail.kode_produk');



        $subqueryDPB = DB::table('gudang_cabang_dpb_detail')
            ->join('gudang_cabang_dpb', 'gudang_cabang_dpb_detail.no_dpb', '=', 'gudang_cabang_dpb.no_dpb')
            ->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman')
            ->select(
                'gudang_cabang_dpb_detail.kode_produk',
                DB::raw('SUM(gudang_cabang_dpb_detail.jml_ambil)as dpb_ambil'),
                DB::raw('SUM(gudang_cabang_dpb_detail.jml_kembali)as dpb_kembali'),
            )
            ->whereBetween('gudang_cabang_dpb.tanggal_ambil', [
                $lastsaldo->tanggal,
                $today
            ])
            ->where('kode_cabang', $kode_cabang)
            ->groupBy('gudang_cabang_dpb_detail.kode_produk');

        $subquerySuratjalan = DB::table('gudang_jadi_mutasi_detail')
            ->join('gudang_jadi_mutasi', 'gudang_jadi_mutasi_detail.no_mutasi', '=', 'gudang_jadi_mutasi.no_mutasi')
            ->join('marketing_permintaan_kiriman', 'gudang_jadi_mutasi.no_permintaan', '=', 'marketing_permintaan_kiriman.no_permintaan')
            ->whereBetween('gudang_jadi_mutasi.tanggal', [
                $lastsaldo->tanggal,
                $today
            ])
            ->where('jenis_mutasi', 'SJ')
            ->where('status_surat_jalan', 0)
            ->where('kode_cabang', $kode_cabang)
            ->select(
                'gudang_jadi_mutasi_detail.kode_produk',
                DB::raw('SUM(gudang_jadi_mutasi_detail.jumlah)as suratjalan')
            )

            ->groupBy('gudang_jadi_mutasi_detail.kode_produk');

        $data['rekappersediaan'] = Produk::where('status_aktif_produk', 1)
            ->select(
                'nama_produk',
                'isi_pcs_dus',
                'buffer_stok',
                'max_stok',
                'saldo_awal',
                'saldo_awal_bs',
                'sisa_mutasi',
                'sisa_mutasi_bs',
                'dpb_ambil',
                'dpb_kembali',
                'suratjalan'
            )
            ->leftJoinsub($subqueryBuffer, 'subqueryBuffer', function ($join) {
                $join->on('produk.kode_produk', '=', 'subqueryBuffer.kode_produk');
            })
            ->leftJoinsub($subqueryMaxstok, 'subqueryMaxstok', function ($join) {
                $join->on('produk.kode_produk', '=', 'subqueryMaxstok.kode_produk');
            })
            ->leftJoinsub($subquerySaldoawal, 'subquerySaldoawal', function ($join) {
                $join->on('produk.kode_produk', '=', 'subquerySaldoawal.kode_produk');
            })

            ->leftJoinSub($subquerySaldoawalbs, 'subquerySaldoawalbs', function ($join) {
                $join->on('produk.kode_produk', '=', 'subquerySaldoawalbs.kode_produk');
            })

            ->leftJoinSub($subqueryMutasibs, 'subqueryMutasibs', function ($join) {
                $join->on('produk.kode_produk', '=', 'subqueryMutasibs.kode_produk');
            })
            ->leftJoinSub($subqueryMutasi, 'subqueryMutasi', function ($join) {
                $join->on('produk.kode_produk', '=', 'subqueryMutasi.kode_produk');
            })

            ->leftJoinSub($subqueryDPB, 'subqueryDPB', function ($join) {
                $join->on('produk.kode_produk', '=', 'subqueryDPB.kode_produk');
            })

            ->leftJoinSub($subquerySuratjalan, 'subquerySuratjalan', function ($join) {
                $join->on('produk.kode_produk', '=', 'subquerySuratjalan.kode_produk');
            })
            ->get();
        return view('dashboard.gudang.rekappersediaancabang', $data);
    }
    public function gudang()
    {
        return view('dashboard.gudang');
    }





    //Rekap Penjualan
    public function rekappenjualan(Request $request)
    {
        $exclude = ['TSM', 'GRT'];
        $salesgarut = ['STSM05', 'STSM09', 'STSM11'];
        $start_date = $request->tahun . "-" . $request->bulan . "-01";
        $end_date = date("Y-m-t", strtotime($start_date));
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = auth()->user()->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }
        if (!empty($kode_cabang)) {
            $subqueryDetailpenjualan = DB::table('marketing_penjualan_detail')
                ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
                ->select(
                    'marketing_penjualan.kode_salesman',
                    DB::raw('SUM(marketing_penjualan_detail.subtotal) as total_bruto'),
                )
                ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
                ->groupBy('marketing_penjualan.kode_salesman');

            $subqueryRetur = DB::table('marketing_retur_detail')
                ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
                ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')
                ->select(
                    'marketing_penjualan.kode_salesman',
                    DB::raw('SUM(marketing_retur_detail.subtotal) as total_retur'),
                )
                ->whereBetween('marketing_retur.tanggal', [$start_date, $end_date])
                ->where('jenis_retur', 'PF')
                ->groupBy('marketing_penjualan.kode_salesman');

            $subqueryPenjualan = DB::table('marketing_penjualan')
                ->select(
                    'marketing_penjualan.kode_salesman',
                    DB::raw('SUM(marketing_penjualan.potongan) as total_potongan'),
                    DB::raw('SUM(marketing_penjualan.potongan_istimewa) as total_potongan_istimewa'),
                    DB::raw('SUM(marketing_penjualan.penyesuaian) as total_penyesuaian'),
                    DB::raw('SUM(marketing_penjualan.ppn) as total_ppn'),
                )
                ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
                ->groupBy('marketing_penjualan.kode_salesman');

            $query = Penjualan::query();
            $query->select(
                'marketing_penjualan.kode_salesman',
                'salesman.nama_salesman',
                'total_bruto',
                'total_retur',
                'total_potongan',
                'total_potongan_istimewa',
                'total_penyesuaian',
                'total_ppn',
            );

            $query->leftJoinSub($subqueryDetailpenjualan, 'subqueryDetailpenjualan', function ($join) {
                $join->on('marketing_penjualan.kode_salesman', '=', 'subqueryDetailpenjualan.kode_salesman');
            });

            $query->leftJoinSub($subqueryRetur, 'subqueryRetur', function ($join) {
                $join->on('marketing_penjualan.kode_salesman', '=', 'subqueryRetur.kode_salesman');
            });

            $query->leftJoinSub($subqueryPenjualan, 'subqueryPenjualan', function ($join) {
                $join->on('marketing_penjualan.kode_salesman', '=', 'subqueryPenjualan.kode_salesman');
            });
            $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');

            $query->where('salesman.kode_cabang', $kode_cabang);
            $query->where('nama_salesman', '!=', '-');
            $query->where('status_batal', 0);
            $query->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date]);
            $query->groupBy('marketing_penjualan.kode_salesman', 'salesman.kode_cabang', 'salesman.nama_salesman', 'total_bruto', 'total_retur', 'total_potongan', 'total_potongan_istimewa', 'total_penyesuaian', 'total_ppn');
            $query->orderBy('salesman.nama_salesman');
            $rekappenjualan = $query->get();
            $data['rekappenjualan'] = $rekappenjualan;



            $qpembayaran = Historibayarpenjualan::query();
            $qpembayaran->join('salesman', 'marketing_penjualan_historibayar.kode_salesman', '=', 'salesman.kode_salesman');
            $qpembayaran->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
            $qpembayaran->select(
                'marketing_penjualan_historibayar.kode_salesman',
                'nama_salesman',
                DB::raw('SUM(IF(voucher="1",jumlah,0)) as total_voucher'),
                DB::raw('SUM(IF(voucher="0",jumlah,0)) as total_cashin'),
            );

            $qpembayaran->where('cabang.kode_cabang', $kode_cabang);
            $qpembayaran->where('nama_salesman', '!=', '-');
            $qpembayaran->whereBetween('marketing_penjualan_historibayar.tanggal', [$start_date, $end_date]);
            $qpembayaran->groupBy('salesman.kode_salesman', 'nama_salesman');
            $qpembayaran->orderBy('salesman.nama_salesman');
            $data['rekapkasbesar'] = $qpembayaran->get();

            return view('dashboard.marketing.rekappenjualansalesman', $data);
        } else {
            $subqueryDetailpenjualan = DB::table('marketing_penjualan_detail')
                ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
                ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                ->select(
                    'salesman.kode_cabang',
                    DB::raw('SUM(marketing_penjualan_detail.subtotal) as total_bruto'),
                )
                ->where('status_batal', 0)
                ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
                ->groupBy('salesman.kode_cabang');

            $subqueryRetur = DB::table('marketing_retur_detail')
                ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
                ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')
                ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                ->select(
                    'salesman.kode_cabang',
                    DB::raw('SUM(marketing_retur_detail.subtotal) as total_retur'),
                )
                ->whereBetween('marketing_retur.tanggal', [$start_date, $end_date])
                ->where('jenis_retur', 'PF')
                ->groupBy('salesman.kode_cabang');

            $subqueryPenjualan = DB::table('marketing_penjualan')
                ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                ->select(
                    'salesman.kode_cabang',
                    DB::raw('SUM(marketing_penjualan.potongan) as total_potongan'),
                    DB::raw('SUM(marketing_penjualan.potongan_istimewa) as total_potongan_istimewa'),
                    DB::raw('SUM(marketing_penjualan.penyesuaian) as total_penyesuaian'),
                    DB::raw('SUM(marketing_penjualan.ppn) as total_ppn'),
                )
                ->where('status_batal', 0)
                ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
                ->groupBy('salesman.kode_cabang');

            $query = Cabang::query();
            $query->select(
                'cabang.kode_cabang',
                'nama_cabang',
                'total_bruto',
                'total_retur',
                'total_potongan',
                'total_potongan_istimewa',
                'total_penyesuaian',
                'total_ppn',
            );

            $query->leftJoinSub($subqueryDetailpenjualan, 'subqueryDetailpenjualan', function ($join) {
                $join->on('cabang.kode_cabang', '=', 'subqueryDetailpenjualan.kode_cabang');
            });

            $query->leftJoinSub($subqueryRetur, 'subqueryRetur', function ($join) {
                $join->on('cabang.kode_cabang', '=', 'subqueryRetur.kode_cabang');
            });

            $query->leftJoinSub($subqueryPenjualan, 'subqueryPenjualan', function ($join) {
                $join->on('cabang.kode_cabang', '=', 'subqueryPenjualan.kode_cabang');
            });

            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                } else {
                    $query->where('cabang.kode_cabang', auth()->user()->kode_cabang);
                }
            }
            $rekappenjualan = $query->get();

            $data['rekappenjualan'] = $rekappenjualan;
            $qpembayaran = Historibayarpenjualan::query();
            $qpembayaran->join('salesman', 'marketing_penjualan_historibayar.kode_salesman', '=', 'salesman.kode_salesman');
            $qpembayaran->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang');
            $qpembayaran->select(
                'salesman.kode_cabang',
                'nama_cabang',
                DB::raw('SUM(IF(voucher="1",jumlah,0)) as total_voucher'),
                DB::raw('SUM(IF(voucher="0",jumlah,0)) as total_cashin'),
            );
            if (!$user->hasRole($roles_access_all_cabang)) {
                if ($user->hasRole('regional sales manager')) {
                    $qpembayaran->where('cabang.kode_regional', auth()->user()->kode_regional);
                } else {
                    $qpembayaran->where('cabang.kode_cabang', auth()->user()->kode_cabang);
                }
            }
            $qpembayaran->whereBetween('marketing_penjualan_historibayar.tanggal', [$start_date, $end_date]);
            $qpembayaran->groupBy('salesman.kode_cabang', 'nama_cabang');
            $data['rekapkasbesar'] = $qpembayaran->get();
            return view('dashboard.marketing.rekappenjualan', $data);
        }
        //Berdasarkan Cabang

    }

    public function rekapkendaraan(Request $request)
    {
        $start_date = $request->tahun . "-" . $request->bulan . "-01";
        $end_date = date('Y-m-t', strtotime($start_date));
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');


        $subqueryPenjualan = Detailmutasigudangcabang::join('gudang_cabang_mutasi', 'gudang_cabang_mutasi_detail.no_mutasi', '=', 'gudang_cabang_mutasi.no_mutasi')
            ->join('gudang_cabang_dpb', 'gudang_cabang_mutasi.no_dpb', '=', 'gudang_cabang_dpb.no_dpb')
            ->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('produk', 'gudang_cabang_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->select(
                'gudang_cabang_dpb.kode_kendaraan',
                DB::raw('ROUND(SUM(jumlah / isi_pcs_dus),2) as jml_penjualan'),
            )
            ->whereBetween('gudang_cabang_mutasi.tanggal', [$start_date, $end_date])
            ->where('jenis_mutasi', 'PJ')
            ->groupBy('gudang_cabang_dpb.kode_kendaraan');

        $query = Dpb::query();
        $query->select(
            'gudang_cabang_dpb.kode_kendaraan',
            'no_polisi',
            'merek',
            'tipe_kendaraan',
            'tipe',
            DB::raw('COUNT(no_dpb) as jml_berangkat'),
            'jml_penjualan',
        );
        $query->join('kendaraan', 'gudang_cabang_dpb.kode_kendaraan', '=', 'kendaraan.kode_kendaraan');
        $query->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman');
        $query->leftJoinSub($subqueryPenjualan, 'penjualan', function ($join) {
            $join->on('kendaraan.kode_kendaraan', '=', 'penjualan.kode_kendaraan');
        });
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                if (!empty($request->kode_cabang)) {
                    $query->where('salesman.kode_cabang', $request->kode_cabang);
                } else {
                    $query->where('salesman.kode_regional', auth()->user()->kode_regional);
                }
            } else {
                $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
            }
        } else {
            if (!empty($request->kode_cabang)) {
                $query->where('salesman.kode_cabang', $request->kode_cabang);
            }
        }
        $query->whereBetween('gudang_cabang_dpb.tanggal_ambil', [$start_date, $end_date]);

        $query->groupBy('gudang_cabang_dpb.kode_kendaraan', 'no_polisi', 'merek', 'tipe_kendaraan', 'tipe', 'jml_penjualan');
        $rekapkendaraan = $query->get();
        $data['rekapkendaraan'] = $rekapkendaraan;
        return view('dashboard.marketing.rekapkendaraan', $data);
    }


    public function rekapdppp(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');

        $lastyear = $request->tahun - 1;
        $start_date_lastyear = $lastyear . "-" . $request->bulan . "-01";
        $end_date_lastyear = date('Y-m-t', strtotime($start_date_lastyear));

        $start_date = $request->tahun . "-" . $request->bulan . "-01";
        $end_date = date('Y-m-t', strtotime($start_date));

        $start_year_date_lastyear = $lastyear . "-01-01";
        $start_year_date = $request->tahun . "-01-01";


        $subqueryPenjualanlastyear = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'produk_harga.kode_produk',
                DB::raw('SUM(marketing_penjualan_detail.jumlah) as realisasi_penjualan_lastyear'),
            )

            ->where(function ($query) use ($user, $roles_access_all_cabang, $request) {
                if (!$user->hasRole($roles_access_all_cabang)) {
                    if ($user->hasRole('regional sales manager')) {
                        if (!empty($request->kode_cabang)) {
                            $query->where('salesman.kode_cabang', $request->kode_cabang);
                        } else {
                            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                        }
                    } else {
                        $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {
                    if (!empty($request->kode_cabang)) {
                        $query->where('salesman.kode_cabang', $request->kode_cabang);
                    }
                }
            })
            ->whereBetween('marketing_penjualan.tanggal', [$start_date_lastyear, $end_date_lastyear])
            ->groupBy('produk_harga.kode_produk');


        $subqueryPenjualanlastyearsampaibulanini = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'produk_harga.kode_produk',
                DB::raw('SUM(marketing_penjualan_detail.jumlah) as realisasi_penjualan_lastyear_sampaibulanini'),
            )
            ->where(function ($query) use ($user, $roles_access_all_cabang, $request) {
                if (!$user->hasRole($roles_access_all_cabang)) {
                    if ($user->hasRole('regional sales manager')) {
                        if (!empty($request->kode_cabang)) {
                            $query->where('salesman.kode_cabang', $request->kode_cabang);
                        } else {
                            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                        }
                    } else {
                        $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {
                    if (!empty($request->kode_cabang)) {
                        $query->where('salesman.kode_cabang', $request->kode_cabang);
                    }
                }
            })
            ->whereBetween('marketing_penjualan.tanggal', [$start_year_date_lastyear, $end_date_lastyear])
            ->groupBy('produk_harga.kode_produk');

        $subqueryPenjualan = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'produk_harga.kode_produk',
                DB::raw('SUM(marketing_penjualan_detail.jumlah) as realisasi_penjualan'),
            )

            ->where(function ($query) use ($user, $roles_access_all_cabang, $request) {
                if (!$user->hasRole($roles_access_all_cabang)) {
                    if ($user->hasRole('regional sales manager')) {
                        if (!empty($request->kode_cabang)) {
                            $query->where('salesman.kode_cabang', $request->kode_cabang);
                        } else {
                            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                        }
                    } else {
                        $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {
                    if (!empty($request->kode_cabang)) {
                        $query->where('salesman.kode_cabang', $request->kode_cabang);
                    }
                }
            })
            ->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date])
            ->groupBy('produk_harga.kode_produk');

        $subqueryPenjualansampaibulanini = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'produk_harga.kode_produk',
                DB::raw('SUM(marketing_penjualan_detail.jumlah) as realisasi_penjualan_sampaibulanini'),
            )
            ->where(function ($query) use ($user, $roles_access_all_cabang, $request) {
                if (!$user->hasRole($roles_access_all_cabang)) {
                    if ($user->hasRole('regional sales manager')) {
                        if (!empty($request->kode_cabang)) {
                            $query->where('salesman.kode_cabang', $request->kode_cabang);
                        } else {
                            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                        }
                    } else {
                        $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {
                    if (!empty($request->kode_cabang)) {
                        $query->where('salesman.kode_cabang', $request->kode_cabang);
                    }
                }
            })
            ->whereBetween('marketing_penjualan.tanggal', [$start_year_date, $end_date])
            ->groupBy('produk_harga.kode_produk');

        $subqueryTarget = Detailtargetkomisi::join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target')
            ->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'marketing_komisi_target_detail.kode_produk',
                DB::raw('SUM(marketing_komisi_target_detail.jumlah) as target')
            )
            ->where(function ($query) use ($user, $roles_access_all_cabang, $request) {
                if (!$user->hasRole($roles_access_all_cabang)) {
                    if ($user->hasRole('regional sales manager')) {
                        if (!empty($request->kode_cabang)) {
                            $query->where('marketing_komisi_target.kode_cabang', $request->kode_cabang);
                        } else {
                            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                        }
                    } else {
                        $query->where('marketing_komisi_target.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {
                    if (!empty($request->kode_cabang)) {
                        $query->where('marketing_komisi_target.kode_cabang', $request->kode_cabang);
                    }
                }
            })
            ->where('marketing_komisi_target.tahun', $request->tahun)
            ->where('marketing_komisi_target.bulan', $request->bulan)
            ->groupBy('marketing_komisi_target_detail.kode_produk');

        $subqueryTargetsampaibulanini = Detailtargetkomisi::join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target')
            ->join('cabang', 'marketing_komisi_target.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'marketing_komisi_target_detail.kode_produk',
                DB::raw('SUM(marketing_komisi_target_detail.jumlah) as target_sampaibulanini')
            )
            ->where(function ($query) use ($user, $roles_access_all_cabang, $request) {
                if (!$user->hasRole($roles_access_all_cabang)) {
                    if ($user->hasRole('regional sales manager')) {
                        if (!empty($request->kode_cabang)) {
                            $query->where('marketing_komisi_target.kode_cabang', $request->kode_cabang);
                        } else {
                            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                        }
                    } else {
                        $query->where('marketing_komisi_target.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {
                    if (!empty($request->kode_cabang)) {
                        $query->where('marketing_komisi_target.kode_cabang', $request->kode_cabang);
                    }
                }
            })
            ->where('marketing_komisi_target.tahun', $request->tahun)
            ->where('marketing_komisi_target.bulan', '<=', $request->bulan)
            ->groupBy('marketing_komisi_target_detail.kode_produk');


        $query = Produk::query();
        $query->leftJoinSub($subqueryPenjualanlastyear, 'penjualanlastyear', function ($join) {
            $join->on('produk.kode_produk', '=', 'penjualanlastyear.kode_produk');
        });

        $query->leftJoinSub($subqueryPenjualan, 'penjualan', function ($join) {
            $join->on('produk.kode_produk', '=', 'penjualan.kode_produk');
        });


        $query->leftJoinSub($subqueryPenjualanlastyearsampaibulanini, 'penjualanlastyearsampaibulanini', function ($join) {
            $join->on('produk.kode_produk', '=', 'penjualanlastyearsampaibulanini.kode_produk');
        });


        $query->leftJoinSub($subqueryPenjualansampaibulanini, 'penjualansampaibulanini', function ($join) {
            $join->on('produk.kode_produk', '=', 'penjualansampaibulanini.kode_produk');
        });


        $query->leftJoinSub($subqueryTarget, 'target', function ($join) {
            $join->on('produk.kode_produk', '=', 'target.kode_produk');
        });


        $query->leftJoinSub($subqueryTargetsampaibulanini, 'target_sampaibulanini', function ($join) {
            $join->on('produk.kode_produk', '=', 'target_sampaibulanini.kode_produk');
        });



        $query->select(
            'produk.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'penjualanlastyear.realisasi_penjualan_lastyear',
            'penjualan.realisasi_penjualan',
            'penjualanlastyearsampaibulanini.realisasi_penjualan_lastyear_sampaibulanini',
            'penjualansampaibulanini.realisasi_penjualan_sampaibulanini',
            'target.target',
            'target_sampaibulanini.target_sampaibulanini',

        );
        $query->whereNotNull('penjualanlastyear.realisasi_penjualan_lastyear');
        $query->orwhereNotNull('penjualan.realisasi_penjualan');
        $query->orwhereNotNull('penjualanlastyearsampaibulanini.realisasi_penjualan_lastyear_sampaibulanini');
        $query->orwhereNotNull('penjualansampaibulanini.realisasi_penjualan_sampaibulanini');
        $query->orwhereNotNull('target.target');
        $query->orwhereNotNull('target_sampaibulanini.target_sampaibulanini');
        $query->orderBy('kode_produk');
        $dppp = $query->get();




        //Selling Out

        $subquerySellingoutlastyear = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'produk_harga.kode_produk',
                DB::raw('SUM(marketing_penjualan_detail.jumlah) as selling_out_lastyear'),
            )
            ->where(function ($query) use ($user, $roles_access_all_cabang, $request) {
                if (!$user->hasRole($roles_access_all_cabang)) {
                    if ($user->hasRole('regional sales manager')) {
                        if (!empty($request->kode_cabang)) {
                            $query->where('salesman.kode_cabang', $request->kode_cabang);
                        } else {
                            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                        }
                    } else {
                        $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {
                    if (!empty($request->kode_cabang)) {
                        $query->where('salesman.kode_cabang', $request->kode_cabang);
                    }
                }
            })
            ->where('marketing_penjualan.status', 1)
            ->whereBetween('marketing_penjualan.tanggal_pelunasan', [$start_date_lastyear, $end_date_lastyear])
            ->groupBy('produk_harga.kode_produk');


        $subquerySellingoutlastyearsampaibulanini = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'produk_harga.kode_produk',
                DB::raw('SUM(marketing_penjualan_detail.jumlah) as selling_out_lastyear_sampaibulanini'),
            )
            ->where(function ($query) use ($user, $roles_access_all_cabang, $request) {
                if (!$user->hasRole($roles_access_all_cabang)) {
                    if ($user->hasRole('regional sales manager')) {
                        if (!empty($request->kode_cabang)) {
                            $query->where('salesman.kode_cabang', $request->kode_cabang);
                        } else {
                            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                        }
                    } else {
                        $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {
                    if (!empty($request->kode_cabang)) {
                        $query->where('salesman.kode_cabang', $request->kode_cabang);
                    }
                }
            })
            ->where('marketing_penjualan.status', 1)
            ->whereBetween('marketing_penjualan.tanggal_pelunasan', [$start_year_date_lastyear, $end_date_lastyear])
            ->groupBy('produk_harga.kode_produk');



        $subquerySellingout = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'produk_harga.kode_produk',
                DB::raw('SUM(marketing_penjualan_detail.jumlah) as selling_out'),
            )
            ->where(function ($query) use ($user, $roles_access_all_cabang, $request) {
                if (!$user->hasRole($roles_access_all_cabang)) {
                    if ($user->hasRole('regional sales manager')) {
                        if (!empty($request->kode_cabang)) {
                            $query->where('salesman.kode_cabang', $request->kode_cabang);
                        } else {
                            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                        }
                    } else {
                        $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {
                    if (!empty($request->kode_cabang)) {
                        $query->where('salesman.kode_cabang', $request->kode_cabang);
                    }
                }
            })
            ->where('marketing_penjualan.status', 1)
            ->whereBetween('marketing_penjualan.tanggal_pelunasan', [$start_date, $end_date])
            ->groupBy('produk_harga.kode_produk');

        $subquerySellingoutsampaibulanini = Detailpenjualan::join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'produk_harga.kode_produk',
                DB::raw('SUM(marketing_penjualan_detail.jumlah) as selling_out_sampaibulanini'),
            )
            ->where(function ($query) use ($user, $roles_access_all_cabang, $request) {
                if (!$user->hasRole($roles_access_all_cabang)) {
                    if ($user->hasRole('regional sales manager')) {
                        if (!empty($request->kode_cabang)) {
                            $query->where('salesman.kode_cabang', $request->kode_cabang);
                        } else {
                            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
                        }
                    } else {
                        $query->where('salesman.kode_cabang', auth()->user()->kode_cabang);
                    }
                } else {
                    if (!empty($request->kode_cabang)) {
                        $query->where('salesman.kode_cabang', $request->kode_cabang);
                    }
                }
            })
            ->where('marketing_penjualan.status', 1)
            ->whereBetween('marketing_penjualan.tanggal_pelunasan', [$start_year_date, $end_date])
            ->groupBy('produk_harga.kode_produk');

        $qsellingout  = Produk::query();
        $qsellingout->select(
            'produk.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'selling_out_lastyear',
            'target.target',
            'selling_out',
            'selling_out_sampaibulanini',
            'selling_out_lastyear_sampaibulanini',
            'targetsampaibulanini.target_sampaibulanini'
        );
        $qsellingout->leftJoinSub($subquerySellingoutlastyear, 'sellingoutlastyear', function ($join) {
            $join->on('produk.kode_produk', '=', 'sellingoutlastyear.kode_produk');
        });
        $qsellingout->leftJoinSub($subqueryTarget, 'target', function ($join) {
            $join->on('produk.kode_produk', '=', 'target.kode_produk');
        });

        $qsellingout->leftJoinSub($subqueryTargetsampaibulanini, 'targetsampaibulanini', function ($join) {
            $join->on('produk.kode_produk', '=', 'targetsampaibulanini.kode_produk');
        });

        $qsellingout->leftJoinSub($subquerySellingout, 'sellingout', function ($join) {
            $join->on('produk.kode_produk', '=', 'sellingout.kode_produk');
        });

        $qsellingout->leftJoinSub($subquerySellingoutlastyearsampaibulanini, 'sellingoutlastyearsampaibulanini', function ($join) {
            $join->on('produk.kode_produk', '=', 'sellingoutlastyearsampaibulanini.kode_produk');
        });

        $qsellingout->leftJoinSub($subquerySellingoutsampaibulanini, 'sellingoutsampaibulanini', function ($join) {
            $join->on('produk.kode_produk', '=', 'sellingoutsampaibulanini.kode_produk');
        });


        $qsellingout->whereNotNull('sellingoutlastyear.selling_out_lastyear');
        $qsellingout->whereNotNull('target.target');
        $qsellingout->whereNotNull('sellingout.selling_out');
        $qsellingout->whereNotNull('targetsampaibulanini.target_sampaibulanini');
        $qsellingout->whereNotNull('sellingoutlastyearsampaibulanini.selling_out_lastyear_sampaibulanini');
        $qsellingout->whereNotNull('sellingoutsampaibulanini.selling_out_sampaibulanini');
        $qsellingout->orderBy('kode_produk');
        $selling_out = $qsellingout->get();



        $data['dppp'] = $dppp;
        $data['selling_out'] = $selling_out;
        $data['bulan'] = $request->bulan;
        $data['tahun'] = $request->tahun;
        $data['lastyear'] = $lastyear;


        return view('dashboard.marketing.rekapdppp', $data);
    }


    public function rekapaup(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $start_date = date('Y', strtotime($request->tanggal)) . "-" . date('m', strtotime($request->tanggal)) . "-01";
        $end_date = $request->tanggal;

        $subqueryDetailpenjualan = Detailpenjualan::select('marketing_penjualan_detail.no_faktur', DB::raw('SUM(subtotal) as total_bruto'))
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->groupBy('no_faktur');

        $subqueryRetur = Detailretur::select('no_faktur', DB::raw('SUM(subtotal) as total_retur'))
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->where('jenis_retur', 'PF')
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->groupBy('no_faktur');

        $subqueryBayar = Historibayarpenjualan::select('no_faktur', DB::raw('SUM(jumlah) as total_bayar'))
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->groupBy('no_faktur');



        $query = Penjualan::query();
        $query->select(
            'pindahfaktur.kode_cabang_baru',
            'nama_cabang',
            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 0 and 15,
            IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_0_15"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 16 and 31,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_16_31"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 32 and 45,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_32_45"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 46 and 60,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_46_60"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 61 and 90,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_61_90"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 91 and 180,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_91_180"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) > 180,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_lebih_180")
        );
        $query->leftJoinsub($subqueryDetailpenjualan, 'detailpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'detailpenjualan.no_faktur');
        });
        $query->leftJoinsub($subqueryRetur, 'retur', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'retur.no_faktur');
        });
        $query->leftJoinsub($subqueryBayar, 'bayar', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'bayar.no_faktur');
        });
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
        $query->join('cabang', 'pindahfaktur.kode_cabang_baru', '=', 'cabang.kode_cabang');
        $query->Wherebetween('marketing_penjualan.tanggal', [$start_date, $end_date]);
        $query->where('marketing_penjualan.jenis_transaksi', 'K');

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('pindahfaktur.kode_cabang_baru', auth()->user()->kode_cabang);
            }
        } else {
            if (!empty($request->exclude)) {
                if ($request->exclude == '1') {
                    $query->where('pindahfaktur.kode_cabang_baru', '!=', 'PST');
                }
            }
        }
        $query->groupBy('pindahfaktur.kode_cabang_baru', 'cabang.nama_cabang');
        $query->orderBy('pindahfaktur.kode_cabang_baru');
        // $aup = $query->get();

        $qsaldoawal = Detailsaldoawalpiutangpelanggan::query();
        $qsaldoawal->select(
            'pindahpiutang.kode_cabang_baru',
            'nama_cabang',
            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 0 and 15,
            IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_0_15"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 16 and 31,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_16_31"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 32 and 45,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_32_45"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 46 and 60,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_46_60"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 61 and 90,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_61_90"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 91 and 180,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_91_180"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) > 180,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_lebih_180")
        );
        $qsaldoawal->join('marketing_saldoawal_piutang', 'marketing_saldoawal_piutang_detail.kode_saldo_awal', 'marketing_saldoawal_piutang.kode_saldo_awal');

        $qsaldoawal->join('marketing_penjualan', 'marketing_saldoawal_piutang_detail.no_faktur', 'marketing_penjualan.no_faktur');
        $qsaldoawal->leftJoin(
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
            ) pindahpiutang"),
            function ($join) {
                $join->on('marketing_saldoawal_piutang_detail.no_faktur', '=', 'pindahpiutang.no_faktur');
            }
        );
        $qsaldoawal->leftJoinsub($subqueryRetur, 'retur', function ($join) {
            $join->on('marketing_saldoawal_piutang_detail.no_faktur', '=', 'retur.no_faktur');
        });
        $qsaldoawal->leftJoinsub($subqueryBayar, 'bayar', function ($join) {
            $join->on('marketing_saldoawal_piutang_detail.no_faktur', '=', 'bayar.no_faktur');
        });
        $qsaldoawal->join('cabang', 'pindahpiutang.kode_cabang_baru', '=', 'cabang.kode_cabang');


        $qsaldoawal->where('bulan', date('m', strtotime($request->tanggal)));
        $qsaldoawal->where('tahun', date('Y', strtotime($request->tanggal)));

        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $qsaldoawal->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $qsaldoawal->where('pindahpiutang.kode_cabang_baru', auth()->user()->kode_cabang);
            }
        } else {
            if (!empty($request->exclude)) {
                if ($request->exclude == '1') {
                    $qsaldoawal->where('pindahpiutang.kode_cabang_baru', '!=', 'PST');
                }
            }
        }
        $qsaldoawal->groupBy('pindahpiutang.kode_cabang_baru', 'cabang.nama_cabang');
        $qsaldoawal->orderBy('pindahpiutang.kode_cabang_baru');
        // $data['aup'] = $aup;
        $aup = $query->unionAll($qsaldoawal)->get();

        $data['rekapaup'] = $aup->groupBy('kode_cabang_baru')
            ->map(function ($item) {
                return [
                    'nama_cabang' => $item->first()->nama_cabang,
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
                    'umur_lebih_180' => $item->sum(function ($row) {
                        return  $row->umur_lebih_180;
                    }),
                ];
            })
            ->sortBy('nama_cabang')
            ->values()
            ->all();
        return view('dashboard.marketing.rekapaup', $data);
    }

    public function rekapaupcabang(Request $request)
    {

        $start_date = date('Y', strtotime($request->tanggal)) . "-" . date('m', strtotime($request->tanggal)) . "-01";
        $end_date = $request->tanggal;

        $subqueryDetailpenjualan = Detailpenjualan::select('marketing_penjualan_detail.no_faktur', DB::raw('SUM(subtotal) as total_bruto'))
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->groupBy('no_faktur');

        $subqueryRetur = Detailretur::select('no_faktur', DB::raw('SUM(subtotal) as total_retur'))
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->where('jenis_retur', 'PF')
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->groupBy('no_faktur');

        $subqueryBayar = Historibayarpenjualan::select('no_faktur', DB::raw('SUM(jumlah) as total_bayar'))
            ->whereBetween('tanggal', [$start_date, $end_date])
            ->groupBy('no_faktur');



        $query = Penjualan::query();
        $query->select(
            'pindahfaktur.kode_salesman_baru',
            'nama_salesman',
            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 0 and 15,
            IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_0_15"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 16 and 31,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_16_31"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 32 and 45,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_32_45"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 46 and 60,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_46_60"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 61 and 90,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_61_90"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 91 and 180,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_91_180"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) > 180,IFNULL(total_bruto,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) + IFNULL(ppn,0) - IFNULL(total_retur,0) -IFNULL(total_bayar,0),0) ) as umur_lebih_180")
        );
        $query->leftJoinsub($subqueryDetailpenjualan, 'detailpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'detailpenjualan.no_faktur');
        });
        $query->leftJoinsub($subqueryRetur, 'retur', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'retur.no_faktur');
        });
        $query->leftJoinsub($subqueryBayar, 'bayar', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'bayar.no_faktur');
        });
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
        $query->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $query->Wherebetween('marketing_penjualan.tanggal', [$start_date, $end_date]);
        $query->where('marketing_penjualan.jenis_transaksi', 'K');
        $query->where('pindahfaktur.kode_cabang_baru', $request->kode_cabang);
        $query->groupBy('pindahfaktur.kode_salesman_baru', 'salesman.nama_salesman');
        $query->orderBy('pindahfaktur.kode_salesman_baru', 'asc');
        // $aup = $query->get();

        $qsaldoawal = Detailsaldoawalpiutangpelanggan::query();
        $qsaldoawal->select(
            'pindahpiutang.kode_salesman_baru',
            'nama_salesman',
            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 0 and 15,
            IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_0_15"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 16 and 31,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_16_31"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 32 and 45,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_32_45"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 46 and 60,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_46_60"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 61 and 90,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_61_90"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) between 91 and 180,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_91_180"),

            DB::raw("SUM(IF(datediff('$request->tanggal',marketing_penjualan.tanggal) > 180,IFNULL(marketing_saldoawal_piutang_detail.jumlah,0) - IFNULL(total_retur,0) - IFNULL(total_bayar,0),0) ) as umur_lebih_180")
        );
        $qsaldoawal->join('marketing_saldoawal_piutang', 'marketing_saldoawal_piutang_detail.kode_saldo_awal', 'marketing_saldoawal_piutang.kode_saldo_awal');

        $qsaldoawal->join('marketing_penjualan', 'marketing_saldoawal_piutang_detail.no_faktur', 'marketing_penjualan.no_faktur');
        $qsaldoawal->leftJoin(
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
            ) pindahpiutang"),
            function ($join) {
                $join->on('marketing_saldoawal_piutang_detail.no_faktur', '=', 'pindahpiutang.no_faktur');
            }
        );
        $qsaldoawal->leftJoinsub($subqueryRetur, 'retur', function ($join) {
            $join->on('marketing_saldoawal_piutang_detail.no_faktur', '=', 'retur.no_faktur');
        });
        $qsaldoawal->leftJoinsub($subqueryBayar, 'bayar', function ($join) {
            $join->on('marketing_saldoawal_piutang_detail.no_faktur', '=', 'bayar.no_faktur');
        });
        $qsaldoawal->join('salesman', 'pindahpiutang.kode_salesman_baru', '=', 'salesman.kode_salesman');


        $qsaldoawal->where('bulan', date('m', strtotime($request->tanggal)));
        $qsaldoawal->where('tahun', date('Y', strtotime($request->tanggal)));
        $qsaldoawal->where('pindahpiutang.kode_cabang_baru', $request->kode_cabang);
        $qsaldoawal->groupBy('pindahpiutang.kode_salesman_baru', 'salesman.nama_salesman');
        $qsaldoawal->orderBy('pindahpiutang.kode_salesman_baru', 'asc');
        // $data['aup'] = $aup;
        $aup = $query->unionAll($qsaldoawal)->get();

        $data['rekapaup'] = $aup->groupBy('kode_salesman_baru')
            ->map(function ($item) {
                return [
                    'nama_salesman' => $item->first()->nama_salesman,
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
                    'umur_lebih_180' => $item->sum(function ($row) {
                        return  $row->umur_lebih_180;
                    }),
                ];
            })
            ->sortBy('nama_salesman')
            ->values()
            ->all();
        return view('dashboard.marketing.rekapaup_cabang', $data);
    }

    public function getcheckinsalesman(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $data['cabang'] = Cabang::where('kode_cabang', $user->kode_cabang)->first();
        $data['kunjungan'] = Checkinpenjualan::select(
            'marketing_penjualan_checkin.kode_pelanggan',
            'nama_pelanggan',
            'alamat_pelanggan',
            'checkin_time',
            'checkout_time',
            'marketing_penjualan_checkin.latitude',
            'marketing_penjualan_checkin.longitude',
            'foto'
        )
            ->join('pelanggan', 'marketing_penjualan_checkin.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')

            ->where('marketing_penjualan_checkin.kode_salesman', $user->kode_salesman)
            ->where('tanggal', $request->tanggal)
            ->orderBy('checkin_time', 'asc')
            ->get();

        return view('dashboard.salesman.getcheckinsalesman', $data);
    }

    public function getdpbsalesman(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $data['dpb'] = Detaildpb::select(
            'gudang_cabang_dpb_detail.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'jml_ambil',
            'detailpenjualan.jml_penjualan'
        )
            ->join('produk', 'gudang_cabang_dpb_detail.kode_produk', '=', 'produk.kode_produk')
            ->join('gudang_cabang_dpb', 'gudang_cabang_dpb_detail.no_dpb', '=', 'gudang_cabang_dpb.no_dpb')
            ->leftJoin(
                DB::raw("(
                SELECT
                    produk_harga.kode_produk,
                    SUM(jumlah) as jml_penjualan
                FROM
                    marketing_penjualan_detail
                INNER JOIN produk_harga ON marketing_penjualan_detail.kode_harga = produk_harga.kode_harga
                INNER JOIN marketing_penjualan ON marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur
                WHERE tanggal = '$request->tanggal' AND kode_salesman = '$user->kode_salesman' AND status_promosi = '0'
                GROUP BY produk_harga.kode_produk
            ) detailpenjualan"),
                function ($join) {
                    $join->on('gudang_cabang_dpb_detail.kode_produk', '=', 'detailpenjualan.kode_produk');
                }
            )
            ->where('gudang_cabang_dpb.kode_salesman', $user->kode_salesman)
            ->where('tanggal_ambil', $request->tanggal)
            ->get();

        return view('dashboard.salesman.getdpbsalesman', $data);
    }
}

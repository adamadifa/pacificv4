<?php

namespace App\Http\Controllers;

use App\Charts\HasilproduksiChart;
use App\Models\Cabang;
use App\Models\Detailmutasigudangjadi;
use App\Models\Detailsaldoawalgudangcabang;
use App\Models\Detailsaldoawalgudangjadi;
use App\Models\Karyawan;
use App\Models\Kendaraan;
use App\Models\Mutasigudangjadi;
use App\Models\Produk;
use App\Models\Saldoawalgudangcabang;
use App\Models\Saldoawalgudangjadi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::findorfail(auth()->user()->id);
        if ($user->hasRole(['super admin'])) {
            return $this->marketing();
        } else {
            return $this->marketing();
        }
    }

    public function marketing()
    {
        return view('dashboard.marketing');
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


    public function gudang()
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
        $selectColumnsSaldoawalgudang = [];
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

            //Gudang Jadi
            $selectColumnsGudang[] = DB::raw('SUM(IF(produk.kode_produk = "' . $kodeProduk . '", subquerySaldoAwalgudang.saldo_awal + subqueryMutasigudang.sisa_mutasi, 0)) AS saldoakhir_' . $kodeProduk);

            $selectColumns[] = 'saldo_' . $kodeProduk;
            $selectColumns[] = 'mutasi_' . $kodeProduk;
            $selectColumns[] = 'ambil_' . $kodeProduk;
            $selectColumns[] = 'kembali_' . $kodeProduk;
            $selectColumns[] = 'buffer_' . $kodeProduk;
            $selectColumns[] = 'max_' . $kodeProduk;
            $selectColumns[] = 'penjualan_' . $kodeProduk;
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
                date('Y-m') . '-01', $today
            ])
            ->select(
                'salesman.kode_cabang',
                ...$selectColumnsPenjualan
            )
            ->groupBy('salesman.kode_cabang');
        // dd($subqueryMutasi);

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
        $query->select('cabang.kode_cabang', 'cabang.nama_cabang', ...$selectColumns);

        if ($role == 'regional sales manager') {
            $query->where('cabang.kode_regional', auth()->user()->kode_regional);
        }
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
        return view('dashboard.gudang', $data);
    }
}

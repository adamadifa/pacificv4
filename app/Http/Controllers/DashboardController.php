<?php

namespace App\Http\Controllers;

use App\Charts\HasilproduksiChart;
use App\Models\Cabang;
use App\Models\Detailsaldoawalgudangcabang;
use App\Models\Karyawan;
use App\Models\Kendaraan;
use App\Models\Produk;
use App\Models\Saldoawalgudangcabang;
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
        $selectColumns = [];
        foreach ($products as $produk) {
            $kodeProduk = $produk->kode_produk;
            $selectColumnsSaldoawal[] = DB::raw('SUM(IF(gudang_cabang_saldoawal_detail.kode_produk = "' . $kodeProduk . '", gudang_cabang_saldoawal_detail.jumlah, 0)) AS saldo_' . $kodeProduk);

            $selectColumnsMutasi[] = DB::raw('SUM(IF(gudang_cabang_mutasi_detail.kode_produk = "' . $kodeProduk . '" AND in_out_good = "IN", gudang_cabang_mutasi_detail.jumlah, 0)) - SUM(IF(gudang_cabang_mutasi_detail.kode_produk = "' . $kodeProduk . '" AND in_out_good = "OUT", gudang_cabang_mutasi_detail.jumlah, 0)) AS mutasi_' . $kodeProduk);

            $selectColumns[] = 'saldo_' . $kodeProduk;
            $selectColumns[] = 'mutasi_' . $kodeProduk;
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
            ->whereIn('jenis_mutasi', ['TI', 'TO', 'SJ', 'RG', 'RP', 'RK', 'PY'])
            ->groupBy('gudang_cabang_mutasi.kode_cabang');


        // dd($subqueryMutasi);

        // Query utama
        $results = DB::table('cabang')
            ->leftJoinSub($subquerySaldoAwal, 'subquerySaldoAwal', function ($join) {
                $join->on('cabang.kode_cabang', '=', 'subquerySaldoAwal.kode_cabang');
            })
            ->leftJoinSub($subqueryMutasi, 'subqueryMutasi', function ($join) {
                $join->on('cabang.kode_cabang', '=', 'subqueryMutasi.kode_cabang');
            })
            ->select('cabang.kode_cabang', 'cabang.nama_cabang', ...$selectColumns)
            ->get();

        $data['products'] = $products;
        $data['rekappersediaancabang'] = $results;

        return view('dashboard.gudang', $data);
    }
}

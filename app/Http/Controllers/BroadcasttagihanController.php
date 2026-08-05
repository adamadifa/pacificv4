<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use App\Models\Penjualan;
use App\Jobs\SendBroadcastTagihanJob;
use App\Models\Saldoawalpiutangpelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BroadcasttagihanController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $roles_show_cabang = config('global.roles_show_cabang');

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();

        $today = date('Y-m-d');
        $data = null;

        // Tentukan apakah form pencarian sudah disubmit dengan filter lengkap
        $is_filtered = $request->filled('bulan') && $request->filled('tahun');
        
        // Jika user memiliki role akses multi-cabang, wajib mengisi filter cabang
        if ($user->hasRole($roles_show_cabang)) {
            if (!$request->filled('kode_cabang')) {
                $is_filtered = false;
            }
        }

        if ($is_filtered) {
            $kode_cabang = $request->kode_cabang;
            if (!$user->hasRole($roles_access_all_cabang)) {
                if (!$user->hasRole('regional sales manager')) {
                    $kode_cabang = $user->kode_cabang;
                }
            }

            $bulan = str_pad($request->bulan, 2, '0', STR_PAD_LEFT);
            $tahun = $request->tahun;

            $dari = "{$tahun}-{$bulan}-01";
            $sampai = date('Y-m-t', strtotime($dari));

            $saldoawal = Saldoawalpiutangpelanggan::where('tanggal', '<=', $dari)->orderBy('tanggal', 'desc')->first();
            
            if ($saldoawal) {
                $saldoawal_date = $saldoawal->tanggal;

                // 1. Query Saldo Awal dari bulan sebelumnya
                $querysaldoawal = DB::table('marketing_saldoawal_piutang_detail')
                    ->select(
                        'marketing_saldoawal_piutang_detail.no_faktur',
                        'marketing_penjualan.tanggal',
                        'marketing_penjualan.jatuh_tempo',
                        'marketing_penjualan.kode_pelanggan',
                        'pelanggan.nama_pelanggan',
                        'pelanggan.no_hp_pelanggan',
                        'salesman.nama_salesman',
                        'cabang.nama_cabang',
                        DB::raw("IFNULL(marketing_saldoawal_piutang_detail.jumlah,0)- IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
                        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal >= '$saldoawal_date' AND marketing_retur.tanggal < '$dari'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal >= '$saldoawal_date' AND marketing_penjualan_historibayar.tanggal < '$dari'),0) as saldo_awal"),
                        DB::raw('0 as bruto'),
                        DB::raw("IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
                        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$dari' AND '$sampai'),0) as retur"),
                        DB::raw("IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$dari' AND '$sampai'),0) as jmlbayar")
                    )
                    ->join('marketing_saldoawal_piutang', 'marketing_saldoawal_piutang_detail.kode_saldo_awal', '=', 'marketing_saldoawal_piutang.kode_saldo_awal')
                    ->join('marketing_penjualan', 'marketing_saldoawal_piutang_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
                    ->leftJoin(DB::raw("(
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
                        WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur WHERE tanggal <= '$dari' GROUP BY no_faktur) AND tanggal <= '$dari'
                        ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
                        WHERE marketing_penjualan.status_sampel = 0
                    ) pindahfaktur"), 'marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur')
                    ->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman')
                    ->leftJoin('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
                    ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
                    ->where('marketing_penjualan.status_sampel', 0)
                    ->where('marketing_saldoawal_piutang.kode_saldo_awal', $saldoawal->kode_saldo_awal);

                // 2. Query saldo awal dari penjualan bulan ini sebelum tanggal dari
                $querysaldoawalbulanini = DB::table('marketing_penjualan')
                    ->select(
                        'marketing_penjualan.no_faktur',
                        'marketing_penjualan.tanggal',
                        'marketing_penjualan.jatuh_tempo',
                        'marketing_penjualan.kode_pelanggan',
                        'pelanggan.nama_pelanggan',
                        'pelanggan.no_hp_pelanggan',
                        'salesman.nama_salesman',
                        'cabang.nama_cabang',
                        DB::raw("IFNULL((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur),0) - potongan - potongan_istimewa - penyesuaian + ppn -  IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
                        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal >= '$saldoawal_date' AND marketing_retur.tanggal < '$dari'),0) - IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal >= '$saldoawal_date' AND marketing_penjualan_historibayar.tanggal < '$dari'),0) as saldo_awal"),
                        DB::raw('0 as bruto'),
                        DB::raw("IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
                        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$dari' AND '$sampai'),0) as retur"),
                        DB::raw("IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$dari' AND '$sampai'),0) as jmlbayar")
                    )
                    ->leftJoin(DB::raw("(
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
                        WHERE marketing_penjualan.status_sampel = 0
                    ) pindahfaktur"), 'marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur')
                    ->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman')
                    ->leftJoin('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
                    ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
                    ->where('marketing_penjualan.tanggal', '>=', $saldoawal_date)
                    ->where('marketing_penjualan.tanggal', '<', $dari)
                    ->where('marketing_penjualan.status_sampel', 0)
                    ->where('jenis_transaksi', 'K')
                    ->where('status_batal', 0);

                // 3. Query Penjualan Baru di bulan terpilih
                $querypenjualan = DB::table('marketing_penjualan')
                    ->select(
                        'marketing_penjualan.no_faktur',
                        'marketing_penjualan.tanggal',
                        'marketing_penjualan.jatuh_tempo',
                        'marketing_penjualan.kode_pelanggan',
                        'pelanggan.nama_pelanggan',
                        'pelanggan.no_hp_pelanggan',
                        'salesman.nama_salesman',
                        'cabang.nama_cabang',
                        DB::raw('0 as saldo_awal'),
                        DB::raw("IFNULL((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur),0) - potongan - potongan_istimewa - penyesuaian + ppn as bruto"),
                        DB::raw("IFNULL((SELECT SUM(subtotal) FROM marketing_retur_detail
                        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur WHERE marketing_retur.no_faktur = marketing_penjualan.no_faktur AND jenis_retur ='PF' AND marketing_retur.tanggal BETWEEN '$dari' AND '$sampai'),0) as retur"),
                        DB::raw("IFNULL((SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur AND marketing_penjualan_historibayar.tanggal BETWEEN '$dari' AND '$sampai'),0) as jmlbayar")
                    )
                    ->leftJoin(DB::raw("(
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
                        WHERE marketing_penjualan.status_sampel = 0
                    ) pindahfaktur"), 'marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur')
                    ->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman')
                    ->leftJoin('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
                    ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
                    ->whereBetween('marketing_penjualan.tanggal', [$dari, $sampai])
                    ->where('marketing_penjualan.status_sampel', 0)
                    ->where('jenis_transaksi', 'K')
                    ->where('status_batal', 0);

                if (!empty($kode_cabang)) {
                    $querysaldoawal->where('pindahfaktur.kode_cabang_baru', $kode_cabang);
                    $querysaldoawalbulanini->where('pindahfaktur.kode_cabang_baru', $kode_cabang);
                    $querypenjualan->where('pindahfaktur.kode_cabang_baru', $kode_cabang);
                }

                if (!empty($request->kode_salesman)) {
                    $querysaldoawal->where('pindahfaktur.kode_salesman_baru', $request->kode_salesman);
                    $querysaldoawalbulanini->where('pindahfaktur.kode_salesman_baru', $request->kode_salesman);
                    $querypenjualan->where('pindahfaktur.kode_salesman_baru', $request->kode_salesman);
                }

                if (!empty($request->nama_pelanggan)) {
                    $querysaldoawal->where('pelanggan.nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
                    $querysaldoawalbulanini->where('pelanggan.nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
                    $querypenjualan->where('pelanggan.nama_pelanggan', 'like', '%' . $request->nama_pelanggan . '%');
                }

                $union = $querysaldoawal->unionAll($querysaldoawalbulanini)->unionAll($querypenjualan);

                $results = DB::query()->fromSub($union, 'union_table')
                    ->select(
                        'no_faktur',
                        'tanggal',
                        'jatuh_tempo',
                        'kode_pelanggan',
                        'nama_pelanggan',
                        'no_hp_pelanggan',
                        'nama_salesman',
                        'nama_cabang',
                        DB::raw('COALESCE(saldo_awal, 0) + COALESCE(bruto, 0) - COALESCE(retur, 0) - COALESCE(jmlbayar, 0) as sisa_piutang')
                    )
                    ->havingRaw('sisa_piutang > 0');

                if (!empty($request->status_jatuh_tempo)) {
                    if ($request->status_jatuh_tempo == 'sudah') {
                        $results->where('jatuh_tempo', '<', $today);
                    } elseif ($request->status_jatuh_tempo == 'belum') {
                        $results->where('jatuh_tempo', '>=', $today);
                    }
                }

                $results->orderBy('jatuh_tempo', 'asc');
                
                // Fetch all data without pagination
                $data = $results->get();
            }
        }

        $list_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');

        return view('marketing.broadcasttagihan.index', [
            'data' => $data,
            'cabang' => $cabang,
            'roles_show_cabang' => $roles_show_cabang,
            'today' => $today,
            'list_bulan' => $list_bulan,
            'start_year' => $start_year,
            'is_filtered' => $is_filtered
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required'
        ]);

        $subqueryTotalBruto = DB::table('marketing_penjualan_detail')
            ->select('marketing_penjualan_detail.no_faktur', DB::raw('SUM(subtotal) as total_bruto'))
            ->where('marketing_penjualan_detail.no_faktur', $request->no_faktur)
            ->groupBy('no_faktur');

        $subqueryTotalRetur = DB::table('marketing_retur_detail')
            ->select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as total_retur'))
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->where('marketing_retur.no_faktur', $request->no_faktur)
            ->where('jenis_retur', 'PF')
            ->groupBy('no_faktur');

        $subqueryTotalPembayaran = DB::table('marketing_penjualan_historibayar')
            ->select('marketing_penjualan_historibayar.no_faktur', DB::raw('SUM(jumlah) as total_pembayaran'))
            ->where('marketing_penjualan_historibayar.no_faktur', $request->no_faktur)
            ->groupBy('no_faktur');

        $invoice = DB::table('marketing_penjualan')
            ->select(
                'marketing_penjualan.no_faktur',
                'marketing_penjualan.tanggal',
                'marketing_penjualan.jatuh_tempo',
                'marketing_penjualan.kode_pelanggan',
                'pelanggan.nama_pelanggan',
                'pelanggan.no_hp_pelanggan',
                DB::raw('COALESCE(bruto.total_bruto, 0) - COALESCE(retur.total_retur, 0) - COALESCE(marketing_penjualan.potongan, 0) - COALESCE(marketing_penjualan.potongan_istimewa, 0) - COALESCE(marketing_penjualan.penyesuaian, 0) + COALESCE(marketing_penjualan.ppn, 0) - COALESCE(pembayaran.total_pembayaran, 0) as sisa_piutang')
            )
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->leftJoinSub($subqueryTotalBruto, 'bruto', 'marketing_penjualan.no_faktur', '=', 'bruto.no_faktur')
            ->leftJoinSub($subqueryTotalRetur, 'retur', 'marketing_penjualan.no_faktur', '=', 'retur.no_faktur')
            ->leftJoinSub($subqueryTotalPembayaran, 'pembayaran', 'marketing_penjualan.no_faktur', '=', 'pembayaran.no_faktur')
            ->where('marketing_penjualan.no_faktur', $request->no_faktur)
            ->first();

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Faktur tidak ditemukan atau sudah lunas.'
            ], 404);
        }

        if (empty($invoice->no_hp_pelanggan)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor HP pelanggan kosong.'
            ], 400);
        }

        $today = date('Y-m-d');
        $status_jatuh_tempo = ($invoice->jatuh_tempo < $today) ? 'Lewat Jatuh Tempo' : 'Belum Jatuh Tempo';

        SendBroadcastTagihanJob::dispatch(
            $invoice->no_faktur,
            $invoice->nama_pelanggan,
            $invoice->kode_pelanggan,
            $invoice->no_hp_pelanggan,
            $invoice->tanggal,
            $invoice->jatuh_tempo,
            $invoice->sisa_piutang,
            $status_jatuh_tempo
        );

        return response()->json([
            'success' => true,
            'message' => 'Broadcast tagihan berhasil dikirim ke antrian.'
        ]);
    }
}

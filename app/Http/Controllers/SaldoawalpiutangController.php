<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailsaldoawalpiutangpelanggan;
use App\Models\Historibayarpenjualan;
use App\Models\Penjualan;
use App\Models\Retur;
use App\Models\Saldoawalpiutangpelanggan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SaldoawalpiutangController extends Controller
{
    public function index(Request $request)
    {
        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalpiutangpelanggan::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan', 'desc');
        $saldo_awal = $query->get();
        return view('marketing.saldoawalpiutang.index', compact('list_bulan', 'start_year', 'saldo_awal', 'nama_bulan'));
    }

    public function create()
    {
        $list_bulan = config('global.list_bulan');
        $start_year = config('global.start_year');
        return view('marketing.saldoawalpiutang.create', compact('list_bulan', 'start_year'));
    }

    public function store(Request $request)
    {
        $bulan = $request->bulan;
        $bln = $bulan < 10 ? "0" . $bulan : $bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . $bln . "-01";
        $data_saldo = json_decode($request->data_saldo);
        $kode_saldo_awal = "SAPI" . $bln . substr($tahun, 2, 2);

        $cektutuplaporan = cektutupLaporan($tanggal, "marketing");
        if ($cektutuplaporan > 0) {
            return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
        } else if (empty($data_saldo)) {
            return Redirect::back()->with(messageError('Silahkan Get Saldo Terlebih Dahulu !'));
        }

        DB::beginTransaction();
        try {
            // Cek Saldo Bulan Berikutnya
            $bulanberikutnya = getbulandantahunberikutnya($bulan, $tahun, "bulan");
            $tahunberikutnya = getbulandantahunberikutnya($bulan, $tahun, "tahun");
            $ceksaldobulanberikutnya = Saldoawalpiutangpelanggan::where('bulan', $bulanberikutnya)->where('tahun', $tahunberikutnya)->count();

            if ($ceksaldobulanberikutnya > 0) {
                return Redirect::back()->with(messageError('Tidak Bisa Update Saldo, Dikarenakan Saldo Berikutnya sudah di Set'));
            }

            // Hapus saldo lama jika ada
            Saldoawalpiutangpelanggan::where('kode_saldo_awal', $kode_saldo_awal)->delete();

            // Simpan Master Saldo Awal
            Saldoawalpiutangpelanggan::create([
                'kode_saldo_awal' => $kode_saldo_awal,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal' => $tanggal
            ]);

            // Simpan Detail
            $detail_saldo = [];
            $timestamp = Carbon::now();
            foreach ($data_saldo as $d) {
                $detail_saldo[] = [
                    'kode_saldo_awal' => $kode_saldo_awal,
                    'no_faktur' => $d->no_faktur,
                    'jumlah' => !empty($d->jumlah) ? $d->jumlah : 0,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp
                ];
            }

            if (!empty($detail_saldo)) {
                $chunks = array_chunk($detail_saldo, 100);
                foreach ($chunks as $chunk) {
                    Detailsaldoawalpiutangpelanggan::insert($chunk);
                }
            }

            DB::commit();
            return redirect(route('sapiutang.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('sapiutang.index'))->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalpiutangpelanggan::where('kode_saldo_awal', $kode_saldo_awal)->first();
        $detail = Detailsaldoawalpiutangpelanggan::where('marketing_saldoawal_piutang_detail.kode_saldo_awal', $kode_saldo_awal)
            ->leftJoin('marketing_penjualan', 'marketing_saldoawal_piutang_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->leftJoin('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->select('marketing_saldoawal_piutang_detail.*', 'marketing_penjualan.tanggal', 'pelanggan.nama_pelanggan', 'marketing_penjualan.kode_pelanggan')
            ->get();
        $nama_bulan = config('global.nama_bulan');
        return view('marketing.saldoawalpiutang.show', compact('saldo_awal', 'nama_bulan', 'detail'));
    }

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $saldo_awal = Saldoawalpiutangpelanggan::where('kode_saldo_awal', $kode_saldo_awal)->first();
        try {
            $cektutuplaporan = cektutupLaporan($saldo_awal->tanggal, "marketing");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Saldoawalpiutangpelanggan::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getdetailsaldo(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $tanggal = $tahun . "-" . ($bulan < 10 ? "0" . $bulan : $bulan) . "-01";

        // Cari Saldo Awal Terakhir sebelum tanggal ini
        $last_saldo_awal = Saldoawalpiutangpelanggan::where('tanggal', '<', $tanggal)
            ->orderBy('tanggal', 'desc')
            ->first();

        if (!$last_saldo_awal) {
            // Jika tidak ada saldo awal sama sekali, maka ambil semua faktur kredit yang belum lunas
            // Namun biasanya sistem selalu punya saldo awal.
            // Kita asumsikan minimal ada satu saldo awal. Jika tidak ada, kita ambil dari awal transaksi.
            $last_date = '2020-01-01'; // Default start date
            $query_base = DB::table('marketing_penjualan')
                ->select('no_faktur', DB::raw('0 as jumlah_awal'))
                ->where('jenis_transaksi', 'K')
                ->where('status_batal', 0)
                ->where('tanggal', '<', $tanggal);
        } else {
            $last_date = $last_saldo_awal->tanggal;
            $query_base = DB::table('marketing_saldoawal_piutang_detail')
                ->select('no_faktur', 'jumlah as jumlah_awal')
                ->where('kode_saldo_awal', $last_saldo_awal->kode_saldo_awal);
        }

        // Penjualan setelah saldo awal terakhir sampai sebelum tanggal target
        $query_penjualan = DB::table('marketing_penjualan')
            ->select('no_faktur', DB::raw('((SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - potongan_istimewa - penyesuaian + ppn) as total_faktur'))
            ->where('jenis_transaksi', 'K')
            ->where('status_batal', 0)
            ->where('tanggal', '>=', $last_date)
            ->where('tanggal', '<', $tanggal);

        // Pembayaran setelah saldo awal terakhir sampai sebelum tanggal target
        $query_pembayaran = DB::table('marketing_penjualan_historibayar')
            ->select('no_faktur', DB::raw('SUM(jumlah) as total_bayar'))
            ->where('tanggal', '>=', $last_date)
            ->where('tanggal', '<', $tanggal)
            ->groupBy('no_faktur');

        // Retur (PF) setelah saldo awal terakhir sampai sebelum tanggal target
        $query_retur = DB::table('marketing_retur')
            ->join('marketing_retur_detail', 'marketing_retur.no_retur', '=', 'marketing_retur_detail.no_retur')
            ->select('no_faktur', DB::raw('SUM(subtotal) as total_retur'))
            ->where('jenis_retur', 'PF')
            ->where('marketing_retur.tanggal', '>=', $last_date)
            ->where('marketing_retur.tanggal', '<', $tanggal)
            ->groupBy('no_faktur');

        // Gabungkan semuanya
        $piutang = DB::table('marketing_penjualan')
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->leftJoinSub($query_base, 'base', 'marketing_penjualan.no_faktur', '=', 'base.no_faktur')
            ->leftJoinSub($query_penjualan, 'penjualan', 'marketing_penjualan.no_faktur', '=', 'penjualan.no_faktur')
            ->leftJoinSub($query_pembayaran, 'pembayaran', 'marketing_penjualan.no_faktur', '=', 'pembayaran.no_faktur')
            ->leftJoinSub($query_retur, 'retur', 'marketing_penjualan.no_faktur', '=', 'retur.no_faktur')
            ->select(
                'marketing_penjualan.no_faktur',
                'marketing_penjualan.tanggal',
                'marketing_penjualan.kode_pelanggan',
                'pelanggan.nama_pelanggan',
                DB::raw('
                    (IFNULL(base.jumlah_awal, 0) + 
                     IFNULL(penjualan.total_faktur, 0) - 
                     IFNULL(pembayaran.total_bayar, 0) - 
                     IFNULL(retur.total_retur, 0)) as saldo_akhir
                ')
            )
            ->where('marketing_penjualan.jenis_transaksi', 'K')
            ->where('marketing_penjualan.status_batal', 0)
            ->where('marketing_penjualan.tanggal', '<', $tanggal)
            ->having('saldo_akhir', '>', 0)
            ->orderBy('marketing_penjualan.tanggal')
            ->orderBy('marketing_penjualan.no_faktur')
            ->get();

        return view('marketing.saldoawalpiutang.getdetailsaldo', compact('piutang'));
    }
}

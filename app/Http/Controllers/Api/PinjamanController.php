<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{
    public function index(Request $request)
    {
        $nik = $request->user()->nik;

        // 1. PJP (Pinjaman Jangka Panjang)
        $pjp = DB::table('keuangan_pjp')
            ->select(
                'no_pinjaman',
                'tanggal',
                'jumlah_pinjaman',
                'angsuran',
                'jumlah_angsuran',
                'status',
                DB::raw("'PJP' as tipe_pinjaman"),
                DB::raw("(SELECT SUM(jumlah) FROM keuangan_pjp_historibayar WHERE no_pinjaman = keuangan_pjp.no_pinjaman) as total_bayar")
            )
            ->where('nik', $nik)
            ->get();

        // 2. Kasbon
        $kasbon = DB::table('keuangan_kasbon')
            ->select(
                'no_kasbon as no_pinjaman',
                'tanggal',
                'jumlah as jumlah_pinjaman',
                DB::raw('0 as angsuran'),
                DB::raw('0 as jumlah_angsuran'),
                'status',
                DB::raw("'KASBON' as tipe_pinjaman"),
                DB::raw("(SELECT SUM(jumlah) FROM keuangan_kasbon_historibayar WHERE no_kasbon = keuangan_kasbon.no_kasbon) as total_bayar")
            )
            ->where('nik', $nik)
            ->get();

        // 3. Piutang Karyawan
        $piutang = DB::table('keuangan_piutangkaryawan')
            ->select(
                'no_pinjaman',
                'tanggal',
                'jumlah as jumlah_pinjaman',
                DB::raw('0 as angsuran'),
                DB::raw('0 as jumlah_angsuran'),
                'status',
                DB::raw("'PIUTANG' as tipe_pinjaman"),
                DB::raw("(SELECT SUM(jumlah) FROM keuangan_piutangkaryawan_historibayar WHERE no_pinjaman = keuangan_piutangkaryawan.no_pinjaman) as total_bayar")
            )
            ->where('nik', $nik)
            ->get();

        $all_loans = $pjp->concat($kasbon)->concat($piutang)->map(function ($loan) {
            $loan->total_bayar = $loan->total_bayar ?? 0;
            $loan->sisa_pinjaman = $loan->jumlah_pinjaman - $loan->total_bayar;
            return $loan;
        })->sortByDesc('tanggal')->values();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Pinjaman',
            'data' => $all_loans,
            'summary' => [
                'total_sisa' => $all_loans->sum('sisa_pinjaman')
            ]
        ]);
    }

    public function show(Request $request, $tipe, $no_pinjaman)
    {
        $history = [];
        $loan_info = null;

        if ($tipe === 'PJP') {
            $loan_info = DB::table('keuangan_pjp')->where('no_pinjaman', $no_pinjaman)->first();
            $history = DB::table('keuangan_pjp_historibayar')
                ->where('no_pinjaman', $no_pinjaman)
                ->orderBy('tanggal', 'desc')
                ->get();
        } else if ($tipe === 'KASBON') {
            $loan_info = DB::table('keuangan_kasbon')->where('no_kasbon', $no_pinjaman)->first();
            $history = DB::table('keuangan_kasbon_historibayar')
                ->where('no_kasbon', $no_pinjaman)
                ->orderBy('tanggal', 'desc')
                ->get();
        } else if ($tipe === 'PIUTANG') {
            $loan_info = DB::table('keuangan_piutangkaryawan')->where('no_pinjaman', $no_pinjaman)->first();
            $history = DB::table('keuangan_piutangkaryawan_historibayar')
                ->where('no_pinjaman', $no_pinjaman)
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Pinjaman',
            'loan' => $loan_info,
            'history' => $history
        ]);
    }

    public function getSimulationRules(Request $request)
    {
        $nik = $request->user()->nik;
        $karyawancontroller = new \App\Http\Controllers\KaryawanController();
        $getkaryawan = $karyawancontroller->getkaryawan(\Illuminate\Support\Facades\Crypt::encrypt($nik))->getContent();
        $karyawan = json_decode($getkaryawan, true);
        $datakaryawan = $karyawan['data'];

        $jumlahbulankerja = calculateMonths($datakaryawan['tanggal_masuk'], date('Y-m-d'));
        $sp_pusat = ['SP2', 'SP3'];
        $sp_cabang = ['SP1', 'SP2', 'SP3'];

        $minimal_bayar = 75 / 100 * $datakaryawan['total_pinjaman'];
        $persentase_bayar = !empty($datakaryawan['total_pinjaman']) ?  ROUND($datakaryawan['total_pembayaran'] / $datakaryawan['total_pinjaman'] * 100) : 0;
        
        if ($datakaryawan['status_karyawan'] == 'T') {
            $tenor_max = 20;
        } else {
            $tenor_max = calculateMonths(date('Y-m-d'), $datakaryawan['akhir_kontrak'] ?? date('Y-m-d'));
            $tenor_max = $tenor_max > 0 ? $tenor_max : 0;
            // Limit tenor max to 20 even for contract if it exceeds
            if ($tenor_max > 20) $tenor_max = 20;
        }

        $masakerja = hitungMasakerja($datakaryawan['tanggal_masuk'], date('Y-m-d'));
        $jmlkali_jmk = hitungJmk($masakerja['tahun']);
        
        if ($masakerja['tahun'] < 2) {
            $jmk = $jmlkali_jmk * ($datakaryawan['gaji_pokok'] ?? 0);
        } else {
            $jmk = $jmlkali_jmk * ($datakaryawan['gapok_tunjangan'] ?? 0);
        }

        $sisa_jmk = $jmk - ($datakaryawan['total_jmk_dibayar'] ?? 0);
        $angsuran_max = ROUND(40 / 100 * ($datakaryawan['gapok_tunjangan'] ?? 0));
        $plafon = $angsuran_max * $tenor_max;

        $plafon_max = $plafon < $sisa_jmk ? $plafon : $sisa_jmk;

        // Check Eligibility
        $is_eligible = true;
        $messages = [];

        if ($datakaryawan['status_karyawan'] == 'O') {
            $is_eligible = false;
            $messages[] = 'Status karyawan Outsourcing tidak dapat mengajukan PJP.';
        }

        if ($jumlahbulankerja < 15) {
            $is_eligible = false;
            $messages[] = 'Masa kerja minimal 15 bulan (saat ini ' . $jumlahbulankerja . ' bulan).';
        }

        if ($tenor_max <= 0 && $datakaryawan['status_karyawan'] != 'T') {
            $is_eligible = false;
            $messages[] = 'Kontrak karyawan sudah berakhir atau tidak terdata.';
        }

        if ($datakaryawan['total_pembayaran'] < $minimal_bayar) {
            $is_eligible = false;
            $messages[] = 'Sisa pinjaman sebelumnya masih di atas 25%.';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'tenor_max' => (int)$tenor_max,
                'angsuran_max' => (int)$angsuran_max,
                'plafon_max' => (int)$plafon_max,
                'is_eligible' => $is_eligible,
                'messages' => $messages,
                'employee_info' => [
                    'nama' => $datakaryawan['nama_karyawan'],
                    'status' => $datakaryawan['statuskaryawan'],
                    'masakerja' => $masakerja['tahun'] . ' Thn ' . $masakerja['bulan'] . ' Bln',
                ]
            ]
        ]);
    }
}

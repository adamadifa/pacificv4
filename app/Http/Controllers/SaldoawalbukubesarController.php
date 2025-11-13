<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\Detailpembelian;
use App\Models\Detailpenjualan;
use App\Models\Detailretur;
use App\Models\Detailsaldoawalbukubesar;
use App\Models\Historibayarpenjualan;
use App\Models\Jurnalkoreksi;
use App\Models\Jurnalumum;
use App\Models\Kaskecil;
use App\Models\Ledger;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\Saldoawalbukubesar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SaldoawalbukubesarController extends Controller
{
    public function index(Request $request)
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        $query = Saldoawalbukubesar::query();
        if ($request->has('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->has('tahun')) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->orderBy('bulan', 'asc');
        $data['saldoawalbukubesar'] = $query->get();
        return view('accounting.saldoawalbukubesar.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['cek_saldo_awal'] = Saldoawalbukubesar::count();
        $data['coa'] = Coa::orderby('kode_akun', 'asc')
            ->whereNotIn('kode_akun', ['1', '0-0000'])
            ->get();
        return view('accounting.saldoawalbukubesar.create', $data);
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['saldoawalbukubesar'] = Saldoawalbukubesar::where('kode_saldo_awal', $kode_saldo_awal)->first();
        $data['detailsaldoawalbukubesar'] = Detailsaldoawalbukubesar::join('coa', 'bukubesar_saldoawal_detail.kode_akun', '=', 'coa.kode_akun')->where('bukubesar_saldoawal_detail.kode_saldo_awal', $kode_saldo_awal)->get();
        return view('accounting.saldoawalbukubesar.show', $data);
    }

    public function edit($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['saldoawalbukubesar'] = Saldoawalbukubesar::where('kode_saldo_awal', $kode_saldo_awal)->first();
        $data['detailsaldoawalbukubesar'] = Detailsaldoawalbukubesar::join('coa', 'bukubesar_saldoawal_detail.kode_akun', '=', 'coa.kode_akun')->where('bukubesar_saldoawal_detail.kode_saldo_awal', $kode_saldo_awal)->get();
        $data['coa'] = Coa::orderby('kode_akun', 'asc')
            ->whereNotIn('kode_akun', ['1', '0-0000'])
            ->get();
        return view('accounting.saldoawalbukubesar.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        DB::beginTransaction();

        $kode_saldo_awal = "SA" . $request->bulan . $request->tahun;
        try {
            Saldoawalbukubesar::create([
                'kode_saldo_awal' => $kode_saldo_awal,
                'tanggal' => $request->tahun . "-" . $request->bulan . "-01",
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);

            $kode_akun = $request->kode_akun;
            $jumlah = $request->jumlah;

            foreach ($kode_akun as $key => $value) {
                if ($jumlah[$key] != 0) {
                    Detailsaldoawalbukubesar::create([
                        'kode_saldo_awal' => $kode_saldo_awal,
                        'kode_akun' => $value,
                        'jumlah' => toNumber($jumlah[$key]),
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('saldoawalbukubesar.index')->with(messageSuccess('Data berhasil disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }


    public function getsaldo(Request $request)
    {
        $bulan_dipilih = $request->bulan;
        $tahun_dipilih = $request->tahun;
        $nama_bulan = config('global.nama_bulan');

        // Mengatur agar $bulan dan $tahun menjadi bulan sebelumnya
        $bulan_sebelumnya = $bulan_dipilih;
        $tahun_sebelumnya = $tahun_dipilih;

        if ($bulan_dipilih == 1 || $bulan_dipilih == "01") {
            // Jika bulan Januari maka mundur ke Desember tahun sebelumnya
            $bulan_sebelumnya = 12;
            $tahun_sebelumnya = $tahun_dipilih - 1;
        } else {
            $bulan_sebelumnya = (int)$bulan_dipilih - 1;
            // Pastikan format tetap dua digit
            $bulan_sebelumnya = str_pad($bulan_sebelumnya, 2, "0", STR_PAD_LEFT);
        }

        // Cek apakah saldo bulan sebelumnya sudah ada
        $cek_saldo_bulan_sebelumnya = Saldoawalbukubesar::where('bulan', $bulan_sebelumnya)
            ->where('tahun', $tahun_sebelumnya)
            ->count();

        if ($cek_saldo_bulan_sebelumnya == 0) {
            $nama_bulan_sebelumnya = $nama_bulan[$bulan_sebelumnya * 1];
            return response()->json([
                'success' => false,
                'message' => "Saldo Awal Bulan $nama_bulan_sebelumnya $tahun_sebelumnya belum dibuat. Silakan buat saldo awal bulan sebelumnya terlebih dahulu."
            ], 400);
        }

        $bulan = $bulan_sebelumnya;
        $tahun = $tahun_sebelumnya;
        $start_date = $tahun . "-" . $bulan . "-01";

        $dari = $tahun . "-" . $bulan . "-01";
        $sampai = date('Y-m-t', strtotime($dari));


        $saldoawal = Detailsaldoawalbukubesar::query();

        $saldoawal->join('bukubesar_saldoawal', 'bukubesar_saldoawal.kode_saldo_awal', '=', 'bukubesar_saldoawal_detail.kode_saldo_awal');
        $saldoawal->join('coa', 'bukubesar_saldoawal_detail.kode_akun', '=', 'coa.kode_akun');
        $saldoawal->select(
            'bukubesar_saldoawal_detail.kode_akun',
            'coa.jenis_akun',
            'nama_akun',

            // Set tanggal 1 pada bulan yang dipilih sebagai default tanggal
            DB::raw("CONCAT('$tahun-$bulan-01') as tanggal"),
            'bukubesar_saldoawal_detail.kode_saldo_awal as no_bukti',
            DB::raw("'SALDO AWAL' AS sumber"),
            DB::raw("'Saldo Awal' as keterangan"),
            // 'bukubesar_saldoawal_detail.jumlah as jml_kredit',


            DB::raw('IF(coa.jenis_akun ="1",bukubesar_saldoawal_detail.jumlah,0) as jml_kredit'),
            DB::raw('IF(coa.jenis_akun !="1" || coa.jenis_akun IS NULL,bukubesar_saldoawal_detail.jumlah,0) as jml_debet'),
            DB::raw('0 as urutan')
        );
        $saldoawal->where('bukubesar_saldoawal.bulan', $bulan);
        $saldoawal->where('bukubesar_saldoawal.tahun', $tahun);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $saldoawal->whereBetween('bukubesar_saldoawal_detail.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $saldoawal->orderBy('bukubesar_saldoawal_detail.kode_akun');


        // ->get()->toArray();
        // Mengubah $saldo_awal_ledger menjadi koleksi
        $saldoawalCollection = collect($saldoawal);
        // dd($saldoawalCollection);
        //Ledger BANK
        $ledger = Ledger::query();
        $ledger->select(
            'bank.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_ledger.tanggal',
            'keuangan_ledger.no_bukti',
            DB::raw('CONCAT_WS(" - ", bank.nama_bank, bank.no_rekening) AS sumber'),
            'keuangan_ledger.keterangan',
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_debet'),
            DB::raw('IF(coa.jenis_akun="1" AND debet_kredit="D",1,2) as urutan')
        );
        $ledger->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
        $ledger->join('coa', 'bank.kode_akun', '=', 'coa.kode_akun');


        $ledger->whereBetween('keuangan_ledger.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $ledger->whereBetween('bank.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $ledger->orderBy('bank.kode_akun');
        $ledger->orderBy('tanggal');
        $ledger->orderBy('keuangan_ledger.no_bukti');


        $ledger_transaksi = Ledger::query();
        $ledger_transaksi->select(
            'keuangan_ledger.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_ledger.tanggal',
            'keuangan_ledger.no_bukti',
            DB::raw('CONCAT_WS(" - ", bank.nama_bank, bank.no_rekening) AS sumber'),
            'keuangan_ledger.keterangan',
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_debet'),
            DB::raw('IF((coa.jenis_akun = "1" AND debet_kredit = "K") OR ((coa.jenis_akun = "1" OR coa.jenis_akun IS NULL) AND debet_kredit = "D"), 1, 2) as urutan')
        );
        $ledger_transaksi->whereBetween('keuangan_ledger.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $ledger_transaksi->whereBetween('keuangan_ledger.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $ledger_transaksi->join('coa', 'keuangan_ledger.kode_akun', '=', 'coa.kode_akun');
        $ledger_transaksi->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
        $ledger_transaksi->orderBy('keuangan_ledger.kode_akun');
        $ledger_transaksi->orderBy('keuangan_ledger.tanggal');
        $ledger_transaksi->orderBy('keuangan_ledger.no_bukti');


        //Pembelian

        $pembelian = Detailpembelian::query();
        $pembelian->select(
            'pembelian_detail.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'pembelian.tanggal',
            'pembelian.no_bukti',
            DB::raw("'PEMBELIAN' AS sumber"),
            DB::raw('IF(pembelian_detail.kode_transaksi="PNJ",pembelian_detail.keterangan_penjualan,CONCAT(pembelian_barang.nama_barang, " - ", COALESCE(pembelian_detail.keterangan, ""))) as keterangan'),
            DB::raw('IF(pembelian_detail.kode_transaksi="PNJ",pembelian_detail.jumlah * harga + penyesuaian,0) as jml_kredit'),
            DB::raw('IF(pembelian_detail.kode_transaksi="PMB",pembelian_detail.jumlah * harga + penyesuaian,0) as jml_debet'),
            DB::raw('IF(pembelian_detail.kode_transaksi="PMB",2,1) as urutan')
        );
        $pembelian->join('pembelian', 'pembelian_detail.no_bukti', '=', 'pembelian.no_bukti');
        $pembelian->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang');
        $pembelian->join('coa', 'pembelian_detail.kode_akun', '=', 'coa.kode_akun');
        $pembelian->whereBetween('pembelian.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $pembelian->whereBetween('pembelian_detail.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $pembelian->orderBy('pembelian_detail.kode_akun');
        $pembelian->orderBy('pembelian.tanggal');
        $pembelian->orderBy('pembelian.no_bukti');


        //JURNAL UMUM

        $jurnalumum = Jurnalumum::query();
        $jurnalumum->select(
            'accounting_jurnalumum.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'accounting_jurnalumum.tanggal',
            'accounting_jurnalumum.kode_ju as no_bukti',
            DB::raw("'JURNAL UMUM' AS sumber"),
            'accounting_jurnalumum.keterangan',
            DB::raw('IF(accounting_jurnalumum.debet_kredit="K",accounting_jurnalumum.jumlah,0) as jml_kredit'),
            DB::raw('IF(accounting_jurnalumum.debet_kredit="D",accounting_jurnalumum.jumlah,0) as jml_debet'),
            DB::raw('IF(accounting_jurnalumum.debet_kredit="D",2,1) as urutan')
        );
        $jurnalumum->whereBetween('accounting_jurnalumum.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $jurnalumum->whereBetween('accounting_jurnalumum.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $jurnalumum->join('coa', 'accounting_jurnalumum.kode_akun', '=', 'coa.kode_akun');

        $jurnalumum->orderBy('accounting_jurnalumum.kode_akun');
        $jurnalumum->orderBy('accounting_jurnalumum.tanggal');
        $jurnalumum->orderBy('accounting_jurnalumum.kode_ju');



        //JURNAL Koreksi

        $jurnalkoreksi = Jurnalkoreksi::query();
        $jurnalkoreksi->select(
            'pembelian_jurnalkoreksi.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'pembelian_jurnalkoreksi.tanggal',
            'pembelian_jurnalkoreksi.no_bukti',
            DB::raw("'JURNAL KOREKSI' AS sumber"),
            'pembelian_jurnalkoreksi.keterangan',
            DB::raw('IF(pembelian_jurnalkoreksi.debet_kredit="K",pembelian_jurnalkoreksi.jumlah*harga,0) as jml_kredit'),
            DB::raw('IF(pembelian_jurnalkoreksi.debet_kredit="D",pembelian_jurnalkoreksi.jumlah*harga,0) as jml_debet'),
            DB::raw('IF(pembelian_jurnalkoreksi.debet_kredit="D",2,1) as urutan')
        );
        $jurnalkoreksi->whereBetween('pembelian_jurnalkoreksi.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $jurnalkoreksi->whereBetween('pembelian_jurnalkoreksi.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $jurnalkoreksi->join('coa', 'pembelian_jurnalkoreksi.kode_akun', '=', 'coa.kode_akun');

        $jurnalkoreksi->orderBy('pembelian_jurnalkoreksi.kode_akun');
        $jurnalkoreksi->orderBy('pembelian_jurnalkoreksi.tanggal');
        $jurnalkoreksi->orderBy('pembelian_jurnalkoreksi.no_bukti');



        //    dd($jurnalumum->get());
        $coa_kas_kecil = Coa::where('kode_transaksi', 'KKL');
        $coa_piutangcabang = Coa::where('kode_transaksi', 'PCB');

        //Kas Kecil
        $kaskecil = Kaskecil::query();
        $kaskecil->select(
            'coa_kas_kecil.kode_akun',
            'coa_kas_kecil.jenis_akun',
            'nama_akun',
            'keuangan_kaskecil.tanggal',
            'keuangan_kaskecil.no_bukti',
            DB::raw("CONCAT('KAS KECIL ', keuangan_kaskecil.kode_cabang) AS sumber"),
            'keuangan_kaskecil.keterangan',
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",2,1) as urutan')
        );
        $kaskecil->leftJoinSub($coa_kas_kecil, 'coa_kas_kecil', function ($join) {
            $join->on('keuangan_kaskecil.kode_cabang', '=', 'coa_kas_kecil.kode_cabang_coa');
        });
        $kaskecil->where(function ($query) {
            $query->where('keuangan_kaskecil.keterangan', '!=', 'Penerimaan Kas Kecil')
                ->orWhere('keuangan_kaskecil.kode_cabang', '=', 'PST');
        });







        $kaskecil->whereBetween('keuangan_kaskecil.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kaskecil->whereBetween('coa_kas_kecil.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kaskecil->orderBy('coa_kas_kecil.kode_akun');
        $kaskecil->orderBy('keuangan_kaskecil.tanggal');
        $kaskecil->orderBy('keuangan_kaskecil.no_bukti');


        //dd($kaskecil->get());

        $kaskecil_transaksi = Kaskecil::query();
        $kaskecil_transaksi->select(
            'keuangan_kaskecil.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_kaskecil.tanggal',
            'keuangan_kaskecil.no_bukti',
            DB::raw("CONCAT('KAS KECIL ', keuangan_kaskecil.kode_cabang) AS sumber"),
            'keuangan_kaskecil.keterangan',
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",1,2) as urutan')
        );
        $kaskecil_transaksi->whereBetween('keuangan_kaskecil.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kaskecil_transaksi->whereBetween('keuangan_kaskecil.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kaskecil_transaksi->where('keuangan_kaskecil.keterangan', '!=', 'Penerimaan Kas Kecil');
        $kaskecil_transaksi->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.kode_akun');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.tanggal');
        $kaskecil_transaksi->orderBy('keuangan_kaskecil.no_bukti');

        //Kas Bank Perantara
        $kasbankperantara = Kaskecil::query();
        $kasbankperantara->select(
            'keuangan_kaskecil.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'keuangan_kaskecil.tanggal',
            'keuangan_kaskecil.no_bukti',
            DB::raw("'KAS KECIL' AS sumber"),
            'keuangan_kaskecil.keterangan',
            DB::raw('IF(debet_kredit="K",jumlah,0) as jml_kredit'),
            DB::raw('IF(debet_kredit="D",jumlah,0) as jml_debet'),
            DB::raw('IF(debet_kredit="D",1,2) as urutan')
        );
        $kasbankperantara->whereBetween('keuangan_kaskecil.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kasbankperantara->whereBetween('keuangan_kaskecil.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kasbankperantara->where('keuangan_kaskecil.kode_akun', '1-1104');
        $kasbankperantara->join('coa', 'keuangan_kaskecil.kode_akun', '=', 'coa.kode_akun');
        $kasbankperantara->orderBy('keuangan_kaskecil.kode_akun');
        $kasbankperantara->orderBy('keuangan_kaskecil.tanggal');
        $kasbankperantara->orderBy('keuangan_kaskecil.no_bukti');



        //Piutang dari Kas Besar Penjualan
        $piutangcabang = Historibayarpenjualan::query();
        $piutangcabang->select(
            'coa_piutangcabang.kode_akun',
            'coa_piutangcabang.jenis_akun',
            'nama_akun',
            'marketing_penjualan_historibayar.tanggal',
            'marketing_penjualan_historibayar.no_bukti',
            DB::raw("'KAS BESAR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_penjualan_historibayar.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            'marketing_penjualan_historibayar.jumlah as jml_debet',
            DB::raw('1 as urutan')
        );
        $piutangcabang->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $piutangcabang->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $piutangcabang->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $piutangcabang->leftJoinSub($coa_piutangcabang, 'coa_piutangcabang', function ($join) {
            $join->on('salesman.kode_cabang', '=', 'coa_piutangcabang.kode_cabang_coa');
        });
        $piutangcabang->whereBetween('marketing_penjualan_historibayar.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $piutangcabang->whereBetween('coa_piutangcabang.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $piutangcabang->where('marketing_penjualan_historibayar.voucher', 0);
        $piutangcabang->where('marketing_penjualan.status_batal', 0);
        $piutangcabang->orderBy('coa_piutangcabang.kode_akun');
        $piutangcabang->orderBy('marketing_penjualan_historibayar.tanggal');
        $piutangcabang->orderBy('marketing_penjualan_historibayar.no_bukti');


        //Penjualan Produk
        $penjualan_produk = Detailpenjualan::query();
        $penjualan_produk->select(
            'produk.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan Produk ',produk_harga.kode_produk, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('subtotal as jml_kredit'),
            DB::raw('0 as jml_debet'),
            DB::raw('1 as urutan')
        );
        $penjualan_produk->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $penjualan_produk->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $penjualan_produk->join('coa', 'produk.kode_akun', '=', 'coa.kode_akun');
        $penjualan_produk->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $penjualan_produk->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $penjualan_produk->whereBetween('marketing_penjualan.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $penjualan_produk->whereBetween('produk.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $penjualan_produk->where('marketing_penjualan.status_batal', 0);
        $penjualan_produk->orderBy('marketing_penjualan.tanggal');
        $penjualan_produk->orderBy('marketing_penjualan.no_faktur');




        //Putang Datang 1-1401

        //Retur Penjualan
        $returpenjualan = Detailretur::query();
        $returpenjualan->select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as jml_retur'));
        $returpenjualan->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $returpenjualan->where('jenis_retur', 'PF');
        $returpenjualan->whereBetween('marketing_retur.tanggal', [$start_date, $sampai]);
        $returpenjualan->groupBy('marketing_retur.no_faktur');

        $detailpenjualan = Detailpenjualan::query();
        $detailpenjualan->select('marketing_penjualan.no_faktur', DB::raw('SUM(subtotal) as jml_bruto_penjualan'));
        $detailpenjualan->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $detailpenjualan->whereBetween('marketing_penjualan.tanggal', [$start_date, $sampai]);
        $detailpenjualan->where('status_batal', 0);
        $detailpenjualan->groupBy('marketing_penjualan.no_faktur');

        $penjualannetto = Penjualan::query();
        $penjualannetto->select(
            'marketing_penjualan.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur as no_bukti',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan ',pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            DB::raw('(IFNULL(jml_bruto_penjualan,0) - IFNULL(potongan,0) - IFNULL(potongan_istimewa,0) - IFNULL(penyesuaian,0) - IFNULL(jml_retur,0)) as jml_debet'),
            DB::raw('1 as urutan')
        );
        $penjualannetto->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $penjualannetto->join('coa', 'marketing_penjualan.kode_akun', '=', 'coa.kode_akun');
        $penjualannetto->leftJoinSub($returpenjualan, 'returpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'returpenjualan.no_faktur');
        });
        $penjualannetto->leftJoinSub($detailpenjualan, 'detailpenjualan', function ($join) {
            $join->on('marketing_penjualan.no_faktur', '=', 'detailpenjualan.no_faktur');
        });
        $penjualannetto->where('marketing_penjualan.status_batal', 0);
        $penjualannetto->whereBetween('marketing_penjualan.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $penjualannetto->whereBetween('marketing_penjualan.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $penjualannetto->orderBy('marketing_penjualan.kode_akun');
        $penjualannetto->orderBy('marketing_penjualan.tanggal');



        //Piutang Datang

        //Kas Besar
        $kasbesarpiutangdagang = Historibayarpenjualan::query();
        $kasbesarpiutangdagang->select(
            'marketing_penjualan_historibayar.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan_historibayar.tanggal',
            'marketing_penjualan_historibayar.no_bukti',
            DB::raw("'KAS BESAR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_penjualan_historibayar.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            'marketing_penjualan_historibayar.jumlah as jml_kredit',
            DB::raw('0 as jml_debet'),
            DB::raw('2 as urutan')
        );
        $kasbesarpiutangdagang->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $kasbesarpiutangdagang->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $kasbesarpiutangdagang->join('coa', 'marketing_penjualan_historibayar.kode_akun', '=', 'coa.kode_akun');
        $kasbesarpiutangdagang->whereBetween('marketing_penjualan_historibayar.tanggal', [$start_date, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $kasbesarpiutangdagang->whereBetween('marketing_penjualan_historibayar.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $kasbesarpiutangdagang->where('voucher', 0);
        $kasbesarpiutangdagang->orderBy('marketing_penjualan_historibayar.kode_akun');
        $kasbesarpiutangdagang->orderBy('marketing_penjualan_historibayar.tanggal');
        $kasbesarpiutangdagang->orderBy('marketing_penjualan_historibayar.no_bukti');



        //Retur
        $returpenjualanpiutangdagang = Detailretur::query();
        $returpenjualanpiutangdagang->select(
            'marketing_retur.kode_akun_piutang_dagang',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_retur.tanggal',
            DB::raw("marketing_retur.no_retur as no_bukti"),
            DB::raw("'RETUR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_retur.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('SUM(marketing_retur_detail.subtotal) as jml_kredit'),
            DB::raw('0 as jml_debet'),
            DB::raw('2 as urutan')
        );
        $returpenjualanpiutangdagang->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $returpenjualanpiutangdagang->join('coa', 'marketing_retur.kode_akun_piutang_dagang', '=', 'coa.kode_akun');
        $returpenjualanpiutangdagang->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $returpenjualanpiutangdagang->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $returpenjualanpiutangdagang->where('jenis_retur', 'PF');
        $returpenjualanpiutangdagang->whereBetween('marketing_retur.tanggal', [$start_date, $sampai]);
        $returpenjualanpiutangdagang->where('marketing_penjualan.status_batal', 0);
        $returpenjualanpiutangdagang->where('marketing_penjualan.tanggal', '<', $start_date);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $returpenjualanpiutangdagang->whereBetween('marketing_retur.kode_akun_piutang_dagang', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $returpenjualanpiutangdagang->groupBy('marketing_retur.kode_akun_piutang_dagang', 'coa.jenis_akun', 'nama_akun', 'marketing_retur.tanggal', 'marketing_retur.no_retur', 'marketing_retur.no_faktur', 'pelanggan.nama_pelanggan');
        $returpenjualanpiutangdagang->orderBy('marketing_retur.tanggal');
        $returpenjualanpiutangdagang->orderBy('marketing_retur.no_retur');


        $retur_penjualan = Detailretur::query();
        $retur_penjualan->select(
            'marketing_retur.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_retur.tanggal',
            DB::raw("marketing_retur.no_retur as no_bukti"),
            DB::raw("'RETUR PENJUALAN' AS sumber"),
            DB::raw("CONCAT(marketing_retur.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            'marketing_retur_detail.subtotal as jml_debet',
            DB::raw('1 as urutan')
        );

        $retur_penjualan->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        $retur_penjualan->join('coa', 'marketing_retur.kode_akun', '=', 'coa.kode_akun');
        $retur_penjualan->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $retur_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $retur_penjualan->whereBetween('marketing_retur.tanggal', [$request->dari, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $retur_penjualan->whereBetween('marketing_retur.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $retur_penjualan->where('marketing_retur.jenis_retur', 'PF');
        $retur_penjualan->orderBy('marketing_retur.tanggal');
        $retur_penjualan->orderBy('marketing_retur.no_retur');



        $potongan_penjualan = Penjualan::query();
        $potongan_penjualan->select(
            'marketing_penjualan.kode_akun_potongan',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur as no_bukti',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan ',marketing_penjualan.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            DB::raw('IFNULL(potongan,0) + IFNULL(potongan_istimewa,0) as jml_debet'),
            DB::raw('1 as urutan')
        );
        $potongan_penjualan->join('coa', 'marketing_penjualan.kode_akun_potongan', '=', 'coa.kode_akun');
        $potongan_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $potongan_penjualan->whereBetween('marketing_penjualan.tanggal', [$request->dari, $sampai]);
        $potongan_penjualan->where('marketing_penjualan.status_batal', 0);
        $potongan_penjualan->orderBy('marketing_penjualan.tanggal');
        $potongan_penjualan->orderBy('marketing_penjualan.no_faktur');
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $potongan_penjualan->whereBetween('marketing_penjualan.kode_akun_potongan', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $potongan_penjualan->orderBy('marketing_penjualan.tanggal');
        $potongan_penjualan->orderBy('marketing_penjualan.no_faktur');



        $penyesuaian_penjualan = Penjualan::query();
        $penyesuaian_penjualan->select(
            'marketing_penjualan.kode_akun_penyesuaian',
            'coa.jenis_akun',
            'nama_akun',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.no_faktur as no_bukti',
            DB::raw("'PENJUALAN' AS sumber"),
            DB::raw("CONCAT(' Penjualan ',marketing_penjualan.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
            DB::raw('0 as jml_kredit'),
            DB::raw('IFNULL(penyesuaian,0) as jml_debet'),
            DB::raw('1 as urutan')
        );
        $penyesuaian_penjualan->join('coa', 'marketing_penjualan.kode_akun_penyesuaian', '=', 'coa.kode_akun');
        $penyesuaian_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $penyesuaian_penjualan->whereBetween('marketing_penjualan.tanggal', [$request->dari, $sampai]);
        $penyesuaian_penjualan->where('marketing_penjualan.status_batal', 0);
        $penyesuaian_penjualan->orderBy('marketing_penjualan.tanggal');
        $penyesuaian_penjualan->orderBy('marketing_penjualan.no_faktur');
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $penyesuaian_penjualan->whereBetween('marketing_penjualan.kode_akun_penyesuaian', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }

        $penyesuaian_penjualan->orderBy('marketing_penjualan.tanggal');
        $penyesuaian_penjualan->orderBy('marketing_penjualan.no_faktur');



        //dd($penyesuaian_penjualan->get());


        //dd($potongan_penjualan->get());
        // if ($request->kode_akun_dari == '4-2100' || $request->kode_akun_sampai == '4-2100') {
        //     $retur_penjualan = Detailretur::query();
        //     $retur_penjualan->select(
        //         DB::raw("'4-2100' as kode_akun"),
        //         DB::raw("'Retur Penjualan' as nama_akun"),
        //         'marketing_retur.tanggal',
        //         DB::raw("marketing_retur.no_retur as no_bukti"),
        //         DB::raw("'RETUR PENJUALAN' AS sumber"),
        //         DB::raw("CONCAT(marketing_retur.no_faktur, ' - ', pelanggan.nama_pelanggan) as keterangan"),
        //         DB::raw('0 as jml_kredit'),
        //         'marketing_retur_detail.subtotal as jml_debet',
        //         DB::raw('1 as urutan')
        //     );

        //     $retur_penjualan->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur');
        //     $retur_penjualan->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        //     $retur_penjualan->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        //     $retur_penjualan->whereBetween('marketing_retur.tanggal', [$request->dari, $sampai]);
        //     $retur_penjualan->where('marketing_retur.jenis_retur', 'PF');
        //     $retur_penjualan->orderBy('marketing_retur.tanggal');
        //     $retur_penjualan->orderBy('marketing_retur.no_retur');
        // } else {
        //     // Jika tidak ada retur_penjualan, buat query kosong agar tidak error pada unionAll
        //     $retur_penjualan = Detailretur::query()->select(
        //         DB::raw("'4-2100' as kode_akun"),
        //         DB::raw("'Retur Penjualan' as nama_akun"),
        //         DB::raw("NULL as tanggal"),
        //         DB::raw("NULL as no_bukti"),
        //         DB::raw("'RETUR PENJUALAN' AS sumber"),
        //         DB::raw("NULL as keterangan"),
        //         DB::raw('0 as jml_kredit'),
        //         DB::raw('0 as jml_debet'),
        //         DB::raw('1 as urutan')
        //     )->whereRaw('0 = 1'); // Query kosong
        // }

        // Melakukan sum debet dan kredit dari data union sebelum tanggal $request->dari, group by kode_akun
        // $mutasi_transaksi = $ledger->unionAll($kaskecil)
        //     ->unionAll($ledger_transaksi)
        //     ->unionAll($piutangcabang)
        //     ->unionAll($pembelian)
        //     ->unionAll($jurnalumum)
        //     ->unionAll($jurnalkoreksi)
        //     ->unionAll($penjualan_produk);



        // Contoh penggunaan: $total_mutasi_per_akun adalah collection, akses per kode_akun



        $hutangdagangdanlainnya = Pembelian::query();
        $hutangdagangdanlainnya->select(
            'pembelian.kode_akun',
            'coa.jenis_akun',
            'nama_akun',
            'pembelian.tanggal',
            'pembelian.no_bukti',
            DB::raw("'PEMBELIAN' AS sumber"),
            DB::raw("CONCAT(' Pembelian ',pembelian.no_bukti, ' - ', supplier.nama_supplier) as keterangan"),
            DB::raw('detailpembelian.subtotal as jml_kredit'),
            DB::raw('0 as jml_debet'),
            DB::raw('1 as urutan')
        );

        $hutangdagangdanlainnya->join('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier');
        $hutangdagangdanlainnya->join('coa', 'pembelian.kode_akun', '=', 'coa.kode_akun');
        $hutangdagangdanlainnya->leftJoin(
            DB::raw('(
                SELECT no_bukti, SUM( IF ( kode_transaksi = "PMB", ( ( jumlah * harga ) + penyesuaian ), 0 ) ) - SUM( IF ( kode_transaksi = "PNJ", ( jumlah * harga ), 0 ) ) as subtotal
                FROM pembelian_detail
                GROUP BY no_bukti
            ) detailpembelian'),
            function ($join) {
                $join->on('pembelian.no_bukti', '=', 'detailpembelian.no_bukti');
            }
        );
        $hutangdagangdanlainnya->whereBetween('pembelian.tanggal', [$request->dari, $sampai]);
        if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
            $hutangdagangdanlainnya->whereBetween('pembelian.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
        }
        $hutangdagangdanlainnya->orderBy('pembelian.tanggal');
        $hutangdagangdanlainnya->orderBy('pembelian.no_bukti');
        $hutangdagangdanlainnya->orderBy('urutan');

        //dd($hutangdagangdanlainnya->get());
        $data['dari'] = $request->dari;
        $data['sampai'] = $sampai;
        $data['saldoawalCollection'] = $saldoawalCollection;

        $union_data = $ledger->unionAll($saldoawal)
            ->unionAll($kaskecil)
            ->unionAll($kaskecil_transaksi)
            ->unionAll($kasbankperantara)
            ->unionAll($ledger_transaksi)
            ->unionAll($piutangcabang)
            ->unionAll($pembelian)
            ->unionAll($jurnalumum)
            ->unionAll($jurnalkoreksi)
            ->unionAll($penjualan_produk)
            ->unionAll($penjualannetto)
            ->unionAll($kasbesarpiutangdagang)
            ->unionAll($returpenjualanpiutangdagang)
            ->unionAll($retur_penjualan)
            ->unionAll($potongan_penjualan)
            ->unionAll($penyesuaian_penjualan)
            ->unionAll($hutangdagangdanlainnya);


        //Labarugi
        $kode_laba_rugi = array('4,5,6,7,8,9');
        $akun_jangan_ditampilkan = ['0-0000', '1', '2'];
        // Ambil hasil union sebagai subquery, lalu lakukan SUM group by kode_akun

        $rekapakunlabarugi = DB::query()->fromSub($union_data, 'rekap')
            ->selectRaw('kode_akun, nama_akun,
                    SUM(IF(jenis_akun = 1, jml_kredit - jml_debet, jml_debet - jml_kredit)) as saldo_akhir')
            ->whereRaw('LEFT(kode_akun,1) IN (' . implode(',', $kode_laba_rugi) . ')')
            ->groupBy('kode_akun', 'nama_akun')
            ->orderBy('kode_akun');

        $labarugi = Coa::leftJoinSub($rekapakunlabarugi, 'rekapakun', function ($join) {
            $join->on('coa.kode_akun', '=', 'rekapakun.kode_akun');
        })
            ->select('coa.kode_akun', 'coa.nama_akun', 'coa.level', 'coa.sub_akun', 'rekapakun.saldo_akhir')
            ->whereRaw('LEFT(coa.kode_akun,1) IN (' . implode(',', $kode_laba_rugi) . ')')
            ->whereNotIn('coa.kode_akun', $akun_jangan_ditampilkan)
            ->where(function ($query) {
                // Hanya tampilkan saldo_akhir yang tidak null,
                // atau jika null hanya untuk level 0 dan 1
                $query->whereNotNull('rekapakun.saldo_akhir')
                    ->orWhere(function ($q) {
                        $q->whereNull('rekapakun.saldo_akhir')
                            ->whereIn('coa.level', [0, 1, 2]);
                    });
            })
            ->get();
        $kode_akun_pendapatan = 4;
        $kode_akun_pokok_penjualan = 5;
        $kode_akun_pendapatanlain = 8;
        $kode_akun_biayalain = 9;

        $kode_akun_biaya_penjualan = '6-1';
        $kode_akun_biaya_adm = '6-2';

        $subtotal_akun_pendapatan = 0;
        $subtotal_akun_pokok_penjualan = 0;
        $subtotal_akun_pendapatanlain = 0;
        $subtotal_akun_biayalain = 0;
        $subtotal_akun_biaya_penjualan = 0;
        $subtotal_akun_biaya_adm = 0;
        foreach ($labarugi as $index => $d) {

            $kode_akun_minus = [
                '4-2101',
                '4-2201',
                '4-2202',
                '5-1202',
                '5-3200',
                '5-3400',
                '5-3800',
                '5-1203',
            ];
            // Hitung indentasi berdasarkan level (misal: 20px per level)
            // $indent = ($d->level ?? 0) * 20;
            if (in_array($d->kode_akun, $kode_akun_minus)) {
                $saldo_akhir = $d->saldo_akhir * -1;
                $test = 'minus';
            } else {
                $saldo_akhir = $d->saldo_akhir;
                $test = 'plus';
            }



            if (substr($d->kode_akun, 0, 1) == $kode_akun_pendapatan) {
                $subtotal_akun_pendapatan += $saldo_akhir;
            }

            if (substr($d->kode_akun, 0, 1) == $kode_akun_pokok_penjualan) {
                $subtotal_akun_pokok_penjualan += $saldo_akhir;
            }

            if (substr($d->kode_akun, 0, 1) == $kode_akun_pendapatanlain) {
                $subtotal_akun_pendapatanlain += $saldo_akhir;
            }

            if (substr($d->kode_akun, 0, 1) == $kode_akun_biayalain) {
                $subtotal_akun_biayalain += $saldo_akhir;
            }

            if (substr($d->kode_akun, 0, 3) == $kode_akun_biaya_penjualan) {
                $subtotal_akun_biaya_penjualan += $saldo_akhir;
            }

            if (substr($d->kode_akun, 0, 3) == $kode_akun_biaya_adm) {
                $subtotal_akun_biaya_adm += $saldo_akhir;
            }
        }

        $gross_profit = $subtotal_akun_pendapatan - $subtotal_akun_pokok_penjualan;
        $biaya_operasional = $subtotal_akun_biaya_adm + $subtotal_akun_biaya_penjualan;
        $operating_profit = $gross_profit - $biaya_operasional;
        $net_profit_loss = $operating_profit + $subtotal_akun_pendapatanlain - $subtotal_akun_biayalain;
        // echo "Pendapatan: " . $subtotal_akun_pendapatan . "<br>";
        // echo "Pokok Penjualan: " . $subtotal_akun_pokok_penjualan . "<br>";
        // echo "Gross Profit: " . $gross_profit . "<br>";
        // echo "Biaya Operasional: " . $biaya_operasional . "<br>";
        // echo "Operating Profit: " . $operating_profit . "<br>";
        // echo "Pendapatan Lain: " . $subtotal_akun_pendapatanlain . "<br>";
        // echo "Biaya Lain: " . $subtotal_akun_biayalain . "<br>";
        // echo "Net Profit Loss: " . $net_profit_loss . "<br>";
        // die;

        $data['net_profit_loss'] = $net_profit_loss;
        //Neraca
        $neraca = array('1,2,3');
        $akun_jangan_ditampilkan = ['0-0000', '1', '2'];
        // Ambil hasil union sebagai subquery, lalu lakukan SUM group by kode_akun

        $rekapakun = DB::query()->fromSub($union_data, 'rekap')
            ->selectRaw('kode_akun, nama_akun,
                    SUM(IF(jenis_akun = 1, jml_kredit - jml_debet, jml_debet - jml_kredit)) as saldo_akhir')
            ->whereRaw('LEFT(kode_akun,1) IN (' . implode(',', $neraca) . ')')
            ->groupBy('kode_akun', 'nama_akun')
            ->orderBy('kode_akun');

        $data['neraca'] = Coa::leftJoinSub($rekapakun, 'rekapakun', function ($join) {
            $join->on('coa.kode_akun', '=', 'rekapakun.kode_akun');
        })
            ->select('coa.kode_akun', 'coa.nama_akun', 'coa.level', 'coa.sub_akun', 'rekapakun.saldo_akhir')
            ->whereRaw('LEFT(coa.kode_akun,1) IN (' . implode(',', $neraca) . ')')
            ->whereNotIn('coa.kode_akun', $akun_jangan_ditampilkan)
            ->where(function ($query) {
                // Hanya tampilkan saldo_akhir yang tidak null,
                // atau jika null hanya untuk level 0 dan 1
                $query->whereNotNull('rekapakun.saldo_akhir')
                    ->orWhere(function ($q) {
                        $q->whereNull('rekapakun.saldo_akhir')
                            ->whereIn('coa.level', [0, 1, 2]);
                    });
            })
            ->get();

        return view('accounting.saldoawalbukubesar.getsaldo', $data);
    }

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        DB::beginTransaction();
        try {
            // Hapus detail terlebih dahulu
            Detailsaldoawalbukubesar::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            // Hapus saldo awal
            Saldoawalbukubesar::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            DB::commit();
            return redirect()->back()->with(messageSuccess('Data berhasil dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }
}

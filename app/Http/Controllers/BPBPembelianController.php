<?php

namespace App\Http\Controllers;

use App\Models\DetailBPB;
use App\Models\Barangpembelian;
use App\Models\BPB;
use App\Models\Cabang;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class BPBPembelianController extends Controller
{
    public function index(Request $request)
    {
        $cabang = Auth::user()->kode_cabang;
        $id = Auth::user()->id;
        $dept = Auth::user()->kode_dept;

        $cabangList = DB::table('cabang')
            ->orderBy('nama_cabang')
            ->get();

        $deptList = DB::table('hrd_departemen')
            ->orderBy('nama_dept')
            ->get();
        $bpb = DB::table('bpb_pembelian as bpb')
            ->select(
                'bpb.*',
                'hrd_departemen.nama_dept',
                'users.name as nama_user',
                'u.name as nama_approve_user',
                'cabang.nama_cabang',

                // TOTAL BPB
                DB::raw('
            (SELECT SUM(jumlah)
             FROM bpb_pembelian_detail
             WHERE bpb_pembelian_detail.no_bpb = bpb.no_bpb
            ) as total_bpb
        '),

                // TOTAL SERAH TERIMA (dari pembelian)
                DB::raw('
            (SELECT SUM(pd.jumlah)
             FROM pembelian p
             JOIN pembelian_detail pd
               ON pd.no_bukti = p.no_bukti
             WHERE p.no_bpb = bpb.no_bpb
            ) as total_serah_terima
        ')
            )
            ->leftJoin('hrd_departemen', 'bpb.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->leftJoin('users', 'bpb.id_user', '=', 'users.id')
            ->leftJoin('users as u', 'bpb.approve_user', '=', 'u.id')
            ->leftJoin('cabang', 'bpb.kode_cabang', '=', 'cabang.kode_cabang')

            // Hak akses
            ->when(!in_array($id, ['54', '29', '1']), function ($q) use ($cabang, $dept) {
                $q->where('bpb.kode_cabang', $cabang)
                    ->where('bpb.kode_dept', $dept);
            })

            // Filter tanggal
            ->when($request->filled('dari') && $request->filled('sampai'), function ($q) use ($request) {
                $q->whereBetween('bpb.tanggal', [$request->dari, $request->sampai]);
            })

            // Filter no BPB
            ->when($request->filled('no_bpb_search'), function ($q) use ($request) {
                $q->where('bpb.no_bpb', $request->no_bpb_search);
            })

            // 🔥 STATUS
            ->when($request->status == 'selesai', function ($q) {
                $q->havingRaw('IFNULL(total_serah_terima,0) >= IFNULL(total_bpb,0)');
            })

            ->when($request->status == 'proses', function ($q) {
                $q->havingRaw('IFNULL(total_serah_terima,0) < IFNULL(total_bpb,0)');
            })

            // Filter cabang
            ->when($request->filled('kode_cabang'), function ($q) use ($request) {
                $q->where('bpb.kode_cabang', $request->kode_cabang);
            })

            // Filter departemen
            ->when($request->filled('kode_dept'), function ($q) use ($request) {
                $q->where('bpb.kode_dept', $request->kode_dept);
            })

            ->orderBy('bpb.tanggal', 'desc')
            ->orderBy('bpb.created_at', 'desc')
            ->simplePaginate(20)
            ->appends($request->all());

        return view('pembelian.bpb.index', compact('bpb', 'cabangList', 'deptList'));
    }

    public function storeBukti($no_bpb, Request $request)
    {
        $no_bpb = Crypt::decrypt($no_bpb);

        DB::table('bpb_pembelian')
            ->where('no_bpb', $no_bpb)
            ->update([
                'no_bukti' => $request->no_bukti
            ]);

        return response()->json([
            'success' => true,
            'message' => 'No Bukti berhasil disimpan'
        ]);
    }

    public function cetak($id)
    {
        $no_bpb = Crypt::decrypt($id);
        $bpb = DB::table('bpb_pembelian')
            ->leftJoin('supplier', 'bpb_pembelian.kode_supplier', '=', 'supplier.kode_supplier')
            ->where('no_bpb', $no_bpb)
            ->first();

        $items = DB::table('bpb_pembelian_detail as d')
            ->leftJoin('pembelian_barang as bp', 'bp.kode_barang', '=', 'd.kode_barang')
            ->leftJoin('pembelian_barang_kategori as pbk', 'bp.kode_kategori', '=', 'pbk.kode_kategori')
            ->leftJoin('bpb_pembelian as h', 'd.no_bpb', '=', 'h.no_bpb')
            ->where('h.no_bpb', $no_bpb)
            ->select(
                'd.*',
                'bp.nama_barang',
                'bp.kode_kategori',
                'bp.satuan',
                'pbk.nama_kategori',
                'h.no_bpb',
                'h.tanggal'
            )
            ->get();

        return view('pembelian.bpb.cetak', compact('bpb', 'items'));
    }

    public function create()
    {

        $data['cabang'] = Cabang::all();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['kategori'] = DB::table('pembelian_barang_kategori')
            ->orderBy('nama_kategori')
            ->get();
        $data['supplier'] = DB::table('supplier')
            ->orderBy('nama_supplier')
            ->get();
        $data['barang'] = DB::table('pembelian_barang as bp')
            ->join('pembelian_barang_kategori as pbk', 'pbk.kode_kategori', '=', 'bp.kode_kategori')
            ->join('bpb_detail as bd', 'bd.kode_barang', '=', 'bp.kode_barang')
            ->join('bpb', function ($join) {
                $join->on('bpb.no_bpb', '=', 'bd.no_bpb')
                    ->where('bpb.approve_gudang', '1');
            })

            ->select(
                'bp.kode_barang',
                'bp.nama_barang',
                'bp.satuan',
                'bp.kode_kategori',
                'bp.kode_group',
                'pbk.nama_kategori',
                'bd.keterangan',

                DB::raw('SUM(bd.jumlah) as total_bpb'),

                DB::raw('
                    COALESCE((
                        SELECT SUM(gd.jumlah)
                        FROM gudang_logistik_barang_keluar_detail gd
                        JOIN gudang_logistik_barang_keluar g
                            ON g.no_bukti = gd.no_bukti
                        WHERE g.no_ref = bd.no_bpb
                        AND gd.kode_barang = bp.kode_barang
                    ),0) as total_keluar
                '),
                DB::raw('
                    COALESCE((
                        SELECT SUM(bpd.jumlah - COALESCE(bpd.jumlah_diterima,0))
                        FROM bpb_pembelian_detail bpd
                        JOIN bpb_pembelian bpbh
                        WHERE bpd.kode_barang = bp.kode_barang
                        AND bpd.jumlah_diterima IS NULL
                    ),0) as total_bpb_pembelian
                '),

                // 🔥 SISA FINAL
                DB::raw('
                (
                    SUM(bd.jumlah)
                    - COALESCE((
                        SELECT SUM(gd.jumlah)
                        FROM gudang_logistik_barang_keluar_detail gd
                        JOIN gudang_logistik_barang_keluar g
                            ON g.no_bukti = gd.no_bukti
                        WHERE g.no_ref = bd.no_bpb
                        AND gd.kode_barang = bp.kode_barang
                    ),0)
                    - COALESCE((
                        SELECT SUM(bpd.jumlah - COALESCE(bpd.jumlah_diterima,0))
                        FROM bpb_pembelian_detail bpd
                        JOIN bpb_pembelian bpbh
                        WHERE bpd.kode_barang = bp.kode_barang
                        AND bpd.jumlah_diterima IS NULL
                    ),0)
                ) as sisa
            ')
            )

            ->groupBy(
                'bp.kode_barang',
                'bp.kode_kategori',
                'bp.kode_group',
                'pbk.nama_kategori',
                'bp.nama_barang',
                'bp.satuan',
                'bd.no_bpb',
                'bd.keterangan'
            )

            ->having('sisa', '>', 0)
            ->get();

        return view('pembelian.bpb.create', $data);
    }

    private function generateNoBPB()
    {
        $bulan = date('m');
        $tahun = date('Y');

        // Cari nomor terakhir bulan ini
        $last = DB::table('bpb_pembelian')->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('no_bpb', 'desc')
            ->first();

        if ($last) {
            // Ambil urutan dari format: BPB-0001/11/2025
            $explode = explode("/", $last->no_bpb);
            $running = (int) str_replace("BPB-", "", $explode[0]);
            $next = $running + 1;
        } else {
            $next = 1;
        }

        // Format 4 digit
        $no_urut = str_pad($next, 4, "0", STR_PAD_LEFT);

        return "BPB-" . $no_urut . "/" . $bulan . "/" . $tahun;
    }

    public function store(Request $request)
    {
        $kode_barang = $request->kode_barang;
        $qty = $request->jml;
        $keterangan = $request->ket;

        DB::beginTransaction();
        try {

            if (empty($kode_barang)) {
                return Redirect::back()->with(messageError('Detail Barang Kosong!'));
            }

            // Generate otomatis
            $no_bpb = $this->generateNoBPB();

            // ============================
            // INSERT HEADER BPB
            // ============================
            DB::table('bpb_pembelian')->insert([
                'no_bpb' => $no_bpb,
                'kode_dept' => auth()->user()->kode_dept,
                'tujuan' => 'GDL',
                'kode_cabang' => auth()->user()->kode_cabang,
                'tanggal' => $request->tanggal,
                'id_user' => auth()->user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ============================
            // INSERT DETAIL
            // ============================

            $detail = [];
            $timestamp = now();

            for ($i = 0; $i < count($kode_barang); $i++) {

                $detail[] = [
                    'no_bpb' => $no_bpb,
                    'kode_barang' => $kode_barang[$i],
                    'jumlah' => toNumber($qty[$i]),
                    'keterangan' => $keterangan[$i],
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            $chunks = array_chunk($detail, 5);
            foreach ($chunks as $chunk) {
                DB::table('bpb_pembelian_detail')->insert($chunk);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Disimpan !');

        } catch (\Exception $e) {

            DB::rollBack();
            dd($e);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function edit($no_bpb)
    {
        $no_bpb = Crypt::decrypt($no_bpb);
        $data['bpb'] = DB::table('bpb_pembelian')->where('no_bpb', $no_bpb)->first();
        $data['detail'] = DB::table('bpb_pembelian_detail')->join('pembelian_barang', 'bpb_pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bpb', $no_bpb)->get();
        $data['barang'] = DB::table('pembelian_barang')->where('kode_group', 'GDL')->get();
        return view('pembelian.bpb.edit', $data);
    }

    public function update($no_bpb, Request $request)
    {
        $no_bpb = Crypt::decrypt($no_bpb);
        $kode_barang = $request->kode_barang;
        $qty = $request->jml;
        $keterangan = $request->ket;

        DB::beginTransaction();
        try {

            if (empty($kode_barang)) {
                return Redirect::back()->with(messageError('Data Detail Produk Masih Kosong !'));
            }

            // Cek duplikasi no BPB
            $cek_barang_masuk = DB::table('bpb_pembelian')->where('no_bpb', $request->no_bpb)
                ->where('no_bpb', '!=', $no_bpb)
                ->count();
            if ($cek_barang_masuk > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }

            // Hapus detail lama
            DB::table('bpb_pembelian_detail')->where('no_bpb', $no_bpb)->delete();

            // Update header
            DB::table('bpb_pembelian')->where('no_bpb', $no_bpb)->update([
                'no_bpb' => $request->no_bpb,
                'tanggal' => $request->tanggal,
            ]);

            // Simpan detail (format disamakan dengan store)
            $detail = [];
            $timestamp = now();

            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail[] = [
                    'no_bpb' => $request->no_bpb,
                    'kode_barang' => $kode_barang[$i],
                    'jumlah' => toNumber($qty[$i]),
                    'keterangan' => $keterangan[$i],
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }

            // Chunk insert
            $chunks = array_chunk($detail, 5);
            foreach ($chunks as $chunk) {
                DB::table('bpb_pembelian_detail')->insert($chunk);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data Berhasil Di Update !');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function storeapprove($kode_bpb, Request $request)
    {
        $kode_bpb = Crypt::decrypt($kode_bpb);
        DB::beginTransaction();
        try {
            if ($request->approve == '1') {
                $updated = DB::table('bpb_pembelian')
                    ->where('no_bpb', $kode_bpb)
                    ->where('approve_head_dept', '1')
                    ->update([
                        'approve_gudang' => '1',
                        'tgl_gudang' => now(),
                        'updated_at' => now()
                    ]);
            } else {
                $updated = DB::table('bpb_pembelian')
                    ->where('no_bpb', $kode_bpb)
                    ->where('approve_head_dept', '0')
                    ->update([
                        'approve_head_dept' => '1',
                        'approve_user' => Auth::user()->id,
                        'tgl_head_dept' => now(),
                        'updated_at' => now()
                    ]);
            }


            DB::commit();

            if ($updated === 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'BPB sudah di-approve sebelumnya'
                ], 409);
            }

            return response()->json([
                'status' => true,
                'message' => 'BPB berhasil di-approve'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($no_bpb)
    {
        $no_bpb = Crypt::decrypt($no_bpb);
        $bpb = DB::table('bpb_pembelian')->where('no_bpb', $no_bpb)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($bpb->tanggal, "pembelian");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            //Hapus Surat Jalan
            DB::table('bpb_pembelian')->where('no_bpb', $no_bpb)->delete();
            DB::table('bpb_pembelian_detail')->where('no_bpb', $no_bpb)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function show($no_bpb)
    {
        $no_bpb = Crypt::decrypt($no_bpb);
        $data['supplier'] = DB::table('supplier')
            ->orderBy('nama_supplier')
            ->get();
        // HEADER BPB
        $data['bpb'] = DB::table('bpb_pembelian')->where('bpb_pembelian.no_bpb', $no_bpb)
            ->select(
                'bpb_pembelian.*',
                'hrd_departemen.nama_dept',
                'users.name as nama_user',
                'cabang.nama_cabang'
            )
            ->leftJoin('hrd_departemen', 'bpb_pembelian.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->leftJoin('users', 'bpb_pembelian.id_user', '=', 'users.id')
            ->leftJoin('cabang', 'bpb_pembelian.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        // DETAIL BPB
        $data['detail'] = DB::table('bpb_pembelian_detail')->where('bpb_pembelian_detail.no_bpb', $no_bpb)
            ->leftJoin('pembelian_barang', 'bpb_pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->select(
                'bpb_pembelian_detail.*',
                'pembelian_barang.nama_barang',
                'pembelian_barang.satuan'
            )
            ->get();

        $data['serahTerima'] = DB::table('gudang_logistik_barang_masuk')->where('gudang_logistik_barang_masuk.no_bpb', $no_bpb)->orderBy('tanggal')->get();

        // SERAH TERIMA DETAIL (GROUP BY NO SURAT)
        $data['historyDetail'] = DB::table('gudang_logistik_barang_masuk_detail')
            ->join('gudang_logistik_barang_masuk', 'gudang_logistik_barang_masuk.no_bukti', '=', 'gudang_logistik_barang_masuk_detail.no_bukti')
            ->join('pembelian_barang', 'pembelian_barang.kode_barang', '=', 'gudang_logistik_barang_masuk_detail.kode_barang')
            ->whereIn('gudang_logistik_barang_masuk_detail.no_bukti', $data['serahTerima']->pluck('no_bukti'))
            ->select(
                'gudang_logistik_barang_masuk.no_bukti',
                'gudang_logistik_barang_masuk.tanggal',
                'gudang_logistik_barang_masuk_detail.kode_barang',
                'pembelian_barang.nama_barang',
                'pembelian_barang.satuan',
                'gudang_logistik_barang_masuk_detail.jumlah',
                'gudang_logistik_barang_masuk_detail.keterangan',
            )
            ->orderBy('gudang_logistik_barang_masuk.tanggal')
            ->get()
            ->groupBy('no_bukti');

        // TOTAL SUDAH DISERAHKAN PER BARANG
        $data['diserahkanTotal'] = DB::table('gudang_logistik_barang_masuk_detail')
            ->join('gudang_logistik_barang_masuk', 'gudang_logistik_barang_masuk.no_bukti', '=', 'gudang_logistik_barang_masuk_detail.no_bukti')
            ->where('gudang_logistik_barang_masuk.no_bpb', $no_bpb)
            ->select('kode_barang', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kode_barang')
            ->pluck('total', 'kode_barang');
        return view('pembelian.bpb.show', $data);
    }
    private function generateNoSerahTerima()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $dept = "G";

        // Konversi bulan ke romawi
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        $bulanRomawi = $romawi[(int) $bulan];

        $last = DB::table('pembelian')->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('no_bukti', 'desc')
            ->first();

        if ($last) {
            // Format lama: 036/KB/V/2025
            $explode = explode('/', $last->no_bukti);
            $running = (int) $explode[0];
            $next = $running + 1;
        } else {
            $next = 1;
        }

        // Format 3 digit
        $noUrut = str_pad($next, 3, '0', STR_PAD_LEFT);

        return $dept . '/' . $noUrut . '/' . $bulanRomawi . '/' . $tahun;
    }


    public function serahterimabpbstore(Request $request)
    {
        DB::beginTransaction();

        try {

            // ✅ VALIDASI
            $request->validate([
                'no_ref' => 'required',
                'tanggal_diserahkan' => 'required',
                'kode_supplier' => 'required'
            ]);

            $no_bpb = $request->no_ref;

            // 1. Header BPB
            $bpb = DB::table('bpb_pembelian')
                ->where('no_bpb', $no_bpb)
                ->first();

            if (!$bpb) {
                throw new \Exception('Data BPB tidak ditemukan');
            }

            // 2. Detail BPB
            $detail_bpb = DB::table('bpb_pembelian_detail')
                ->where('no_bpb', $no_bpb)
                ->get()
                ->keyBy('kode_barang');

            if ($detail_bpb->isEmpty()) {
                throw new \Exception('Detail BPB kosong');
            }

            // 3. Qty yang sudah diserahkan sebelumnya
            $sudah = DB::table('pembelian_detail')
                ->select('kode_barang', DB::raw('SUM(jumlah) as total'))
                ->where('kode_transaksi', $no_bpb)
                ->groupBy('kode_barang')
                ->pluck('total', 'kode_barang');

            // 4. Generate nomor bukti
            $no_bukti = $this->generateNoSerahTerima();

            // =========================
            // ✅ INSERT PEMBELIAN (HEADER)
            // =========================
            DB::table('pembelian')->insert([
                'no_bukti' => $no_bukti,
                'tanggal' => $request->tanggal_diserahkan,
                'kode_supplier' => $request->kode_supplier,
                'kode_asal_pengajuan' => 'GDL',
                'jenis_transaksi' => 'T',
                'jatuh_tempo' => $request->tanggal_diserahkan,
                'ppn' => 0,
                'kategori_transaksi' => '',
                'kode_akun' => '2-1300',
                'id_user' => auth()->user()->id,
                'no_bpb' => $no_bpb,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // =========================
            // ✅ INSERT GUDANG LOGISTIK (HEADER)
            // =========================
            DB::table('gudang_logistik_barang_masuk')->insert([
                'no_bukti' => $no_bukti,
                'tanggal' => $request->tanggal_diserahkan,
                'no_bpb' => $no_bpb,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $detail_insert = [];
            $detail_gudang = [];
            $total_pembelian = 0;

            // =========================
            // 🔁 LOOP BARANG
            // =========================
            foreach ($request->diserahkan as $kode_barang => $qty) {

                $qty = floatval($qty);
                if ($qty <= 0)
                    continue;

                if (!isset($detail_bpb[$kode_barang]))
                    continue;

                $qty_awal = $detail_bpb[$kode_barang]->jumlah;
                $qty_sudah = $sudah[$kode_barang] ?? 0;
                $sisa = $qty_awal - $qty_sudah;

                // 🚨 VALIDASI SISA
                if ($qty > $sisa) {
                    throw new \Exception("Qty $kode_barang melebihi sisa ($sisa)");
                }

                $harga = $detail_bpb[$kode_barang]->harga ?? 0;
                $subtotal = $qty * $harga;
                $total_pembelian += $subtotal;

                // =========================
                // DETAIL PEMBELIAN
                // =========================
                $detail_insert[] = [
                    'no_bukti' => $no_bukti,
                    'kode_barang' => $kode_barang,
                    'jumlah' => $qty,
                    'harga' => $harga,
                    'penyesuaian' => 0,
                    'kode_akun' => null,
                    'keterangan' => $detail_bpb[$kode_barang]->keterangan,
                    'keterangan_penjualan' => null,
                    'kode_transaksi' => 'PMB',
                    'konversi_gram' => 0,
                    'kode_cabang' => $bpb->kode_cabang,
                    'kode_cr' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // =========================
                // DETAIL GUDANG LOGISTIK
                // =========================
                $detail_gudang[] = [
                    'no_bukti' => $no_bukti,
                    'kode_barang' => $kode_barang,
                    'keterangan' => $detail_bpb[$kode_barang]->keterangan,
                    'jumlah' => $qty,
                    'harga' => 0,
                    'penyesuaian' => 0,
                    'kode_akun' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }


            if (empty($detail_insert)) {
                throw new \Exception('Tidak ada qty yang diserahkan');
            }

            // =========================
            // ✅ INSERT DETAIL
            // =========================
            DB::table('pembelian_detail')->insert($detail_insert);
            DB::table('gudang_logistik_barang_masuk_detail')->insert($detail_gudang);

            DB::commit();

            return back()->with('success', 'Serah terima berhasil & masuk ke gudang logistik');

        } catch (\Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }


    public function updateSerahTerima(Request $request)
    {
        $request->validate([
            'no_bukti' => 'required'
        ]);

        // ✅ DATA DETAIL
        $dataDetail = [];

        if (!is_null($request->jumlah)) {
            $dataDetail['jumlah'] = $request->jumlah;
        }

        if (!is_null($request->keterangan)) {
            $dataDetail['keterangan'] = $request->keterangan;
        }

        // ✅ UPDATE DETAIL
        if (!empty($dataDetail) && !empty($request->kode_barang)) {
            DB::table('pembelian_detail')
                ->where('no_bukti', $request->no_bukti)
                ->where('kode_barang', $request->kode_barang)
                ->update($dataDetail);
        }

        // ✅ DATA HEADER
        if ($request->diterima == 1) {
            DB::table('pembelian')
                ->where('no_bukti', $request->no_bukti) // ⛔ FIX INI
                ->update([
                    'diterima' => 1
                ]);
        }

        return response()->json(['status' => 'success']);
    }
    public function deleteSerahTerima(Request $request)
    {
        DB::table('pembelian_detail')
            ->where('no_bukti', $request->no_bukti)
            ->where('kode_barang', $request->kode_barang)
            ->delete();

        return response()->json(['success' => true]);
    }


}

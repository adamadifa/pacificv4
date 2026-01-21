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

class BPBController extends Controller
{
    public function index(Request $request)
    {
        $cabang = Auth::user()->kode_cabang;
        $id = Auth::user()->id;
        $dept = Auth::user()->kode_dept;

        $bpb = BPB::query()
            ->select(
                'bpb.*',
                'hrd_departemen.nama_dept',
                'users.name as nama_user',
                'u.name as nama_approve_user',
                'cabang.nama_cabang',

                // TOTAL BPB
                DB::raw('
                (SELECT SUM(jumlah)
                 FROM bpb_detail
                 WHERE bpb_detail.no_bpb = bpb.no_bpb
                ) as total_bpb
            '),

                // TOTAL SERAH TERIMA
                DB::raw('
                (SELECT SUM(std.jumlah)
                 FROM gudang_logistik_barang_keluar st
                 JOIN gudang_logistik_barang_keluar_detail std
                   ON std.no_bukti = st.no_bukti
                 WHERE st.no_ref = bpb.no_bpb
                ) as total_serah_terima
            ')
            )
            ->leftJoin('hrd_departemen', 'bpb.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->leftJoin('users', 'bpb.id_user', '=', 'users.id')
            ->leftJoin('users as u', 'bpb.approve_user', '=', 'u.id')
            ->leftJoin('cabang', 'bpb.kode_cabang', '=', 'cabang.kode_cabang')

            // Hak akses
            ->when(!in_array($id, ['67', '29', '1']), function ($q) use ($cabang, $dept) {
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

            // 🔥 FILTER STATUS
            ->when($request->status == 'selesai', function ($q) {
                $q->havingRaw('total_serah_terima = total_bpb');
            })

            ->when($request->status == 'proses', function ($q) {
                $q->havingRaw('(total_serah_terima < total_bpb OR total_serah_terima IS NULL)');
            })

            ->orderBy('bpb.tanggal', 'desc')
            ->orderBy('bpb.created_at', 'desc')
            ->simplePaginate(20)
            ->appends($request->all());

        return view('gudanglogistik.bpb.index', compact('bpb'));
    }


    public function create()
    {

        $data['cabang'] = Cabang::all();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['barang'] = Barangpembelian::where('kode_group', 'GDL')->get();
        return view('gudanglogistik.bpb.create', $data);
    }

    private function generateNoBPB()
    {
        $bulan = date('m');
        $tahun = date('Y');

        // Cari nomor terakhir bulan ini
        $last = BPB::whereMonth('tanggal', $bulan)
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
            $bpb = BPB::create([
                'no_bpb' => $no_bpb,
                'kode_dept' => auth()->user()->kode_dept,
                'tujuan' => 'GDL',
                'kode_cabang' => auth()->user()->kode_cabang,
                'tanggal' => $request->tanggal,
                'id_user' => auth()->user()->id,
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
                DetailBPB::insert($chunk);
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
        $data['bpb'] = BPB::where('no_bpb', $no_bpb)->first();
        $data['detail'] = DetailBPB::join('pembelian_barang', 'bpb_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bpb', $no_bpb)->get();
        $data['barang'] = Barangpembelian::where('kode_group', 'GDL')->get();
        return view('gudanglogistik.bpb.edit', $data);
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
            $cek_barang_masuk = BPB::where('no_bpb', $request->no_bpb)
                ->where('no_bpb', '!=', $no_bpb)
                ->count();
            if ($cek_barang_masuk > 0) {
                return Redirect::back()->with(messageError('Data Sudah Ada !'));
            }

            // Hapus detail lama
            DetailBPB::where('no_bpb', $no_bpb)->delete();

            // Update header
            BPB::where('no_bpb', $no_bpb)->update([
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
                DetailBPB::insert($chunk);
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
                $updated = DB::table('bpb')
                    ->where('no_bpb', $kode_bpb)
                    ->where('approve_head_dept', '1')
                    ->update([
                        'approve_gudang' => '1',
                        'tgl_gudang' => now(),
                        'updated_at' => now()
                    ]);
            } else {
                $updated = DB::table('bpb')
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
        $bpb = BPB::where('no_bpb', $no_bpb)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($bpb->tanggal, "gudanglogistik");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            BPB::where('no_bpb', $no_bpb)->delete();
            DB::table('bpb_detail')->where('no_bpb', $no_bpb)->delete();
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

        // HEADER BPB
        $data['bpb'] = BPB::where('bpb.no_bpb', $no_bpb)
            ->select(
                'bpb.*',
                'hrd_departemen.nama_dept',
                'users.name as nama_user',
                'cabang.nama_cabang'
            )
            ->leftJoin('hrd_departemen', 'bpb.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->leftJoin('users', 'bpb.id_user', '=', 'users.id')
            ->leftJoin('cabang', 'bpb.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();

        // DETAIL BPB
        $data['detail'] = DetailBPB::where('bpb_detail.no_bpb', $no_bpb)
            ->leftJoin('pembelian_barang', 'bpb_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->select(
                'bpb_detail.*',
                'pembelian_barang.nama_barang',
                'pembelian_barang.satuan'
            )
            ->get();



        $data['serahTerima'] = DB::table('gudang_logistik_barang_keluar')->where('no_ref', $no_bpb)->orderBy('tanggal')->get();

        // SERAH TERIMA DETAIL (GROUP BY NO SURAT)
        $data['historyDetail'] = DB::table('gudang_logistik_barang_keluar_detail')
            ->join('gudang_logistik_barang_keluar', 'gudang_logistik_barang_keluar.no_bukti', '=', 'gudang_logistik_barang_keluar_detail.no_bukti')
            ->join('pembelian_barang', 'pembelian_barang.kode_barang', '=', 'gudang_logistik_barang_keluar_detail.kode_barang')
            ->whereIn('gudang_logistik_barang_keluar_detail.no_bukti', $data['serahTerima']->pluck('no_bukti'))
            ->select(
                'gudang_logistik_barang_keluar.no_bukti',
                'gudang_logistik_barang_keluar.tanggal',
                'gudang_logistik_barang_keluar_detail.kode_barang',
                'pembelian_barang.nama_barang',
                'pembelian_barang.satuan',
                'gudang_logistik_barang_keluar_detail.jumlah',
                'gudang_logistik_barang_keluar_detail.keterangan',
            )
            ->orderBy('gudang_logistik_barang_keluar.tanggal')
            ->get()
            ->groupBy('no_bukti');

        // TOTAL SUDAH DISERAHKAN PER BARANG
        $data['diserahkanTotal'] = DB::table('gudang_logistik_barang_keluar_detail')
            ->join('gudang_logistik_barang_keluar', 'gudang_logistik_barang_keluar.no_bukti', '=', 'gudang_logistik_barang_keluar_detail.no_bukti')
            ->where('gudang_logistik_barang_keluar.no_ref', $no_bpb)
            ->select('kode_barang', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kode_barang')
            ->pluck('total', 'kode_barang');
        return view('gudanglogistik.bpb.show', $data);
    }
    private function generateNoSerahTerima()
    {
        $bulan = date('m');
        $tahun = date('Y');
        $dept = "KB";

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

        $last = DB::table('gudang_logistik_barang_keluar')->whereMonth('tanggal', $bulan)
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

        return $noUrut . '/' . $dept . '/' . $bulanRomawi . '/' . $tahun;
    }


    public function serahterimabpbstore(Request $request)
    {
        DB::beginTransaction();

        try {

            $no_bukti = $this->generateNoSerahTerima();
            $header = [
                'no_bukti' => $no_bukti,
                'kode_jenis_pengeluaran' => $request->kode_dept,
                'no_ref' => $request->no_ref, // no_bpb
                'kode_cabang' => $request->kode_cabang == 'PST' ? '' : $request->kode_cabang,
                'tanggal' => $request->tanggal_diserahkan,
                'id_user' => auth()->user()->id,
                'diterima' => 0,
                'created_at' => now(),
            ];

            DB::table('gudang_logistik_barang_keluar')->insert($header);

            // LOOP DETAIL
            foreach ($request->diserahkan as $kode_barang => $qty) {

                if ($qty === null || $qty == "" || $qty == 0) {
                    continue; // skip kosong
                }

                DB::table('gudang_logistik_barang_keluar_detail')->insert([
                    'no_bukti' => $no_bukti,
                    'kode_barang' => $kode_barang,
                    'jumlah' => $qty,
                    'keterangan' => $request->keterangan[$kode_barang] ?? null,
                    'created_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Serah terima BPB berhasil disimpan!');

        } catch (\Exception $e) {

            DB::rollback();
            return redirect()->back()->with('warning', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }


    public function updateSerahTerima(Request $request)
    {
        $data = [];
        if ($request->jumlah !== null) {
            $data['jumlah'] = $request->jumlah;
        }
        if ($request->keterangan !== null) {
            $data['keterangan'] = $request->keterangan;
        }

        if ($request->diterima !== null) {
            $data['diterima'] = $request->diterima;
            $data['id_user_diterima'] = Auth::user()->id;
            DB::table('gudang_logistik_barang_keluar')
                ->where('no_bukti', $request->no_bukti)
                ->update($data);
        }

        DB::table('gudang_logistik_barang_keluar_detail')
            ->where('no_bukti', $request->no_bukti)
            ->where('kode_barang', $request->kode_barang)
            ->update($data);

        return response()->json(['status' => 'success']);
    }
    public function deleteSerahTerima(Request $request)
    {
        DB::table('gudang_logistik_barang_keluar_detail')
            ->where('no_bukti', $request->no_bukti)
            ->where('kode_barang', $request->kode_barang)
            ->delete();

        return response()->json(['success' => true]);
    }


}

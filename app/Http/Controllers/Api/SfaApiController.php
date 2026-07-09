<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Harga;
use App\Models\Penjualan;
use App\Models\Detailpenjualan;
use App\Models\Checkinpenjualan;
use App\Models\Ajuanlimitkredit;
use App\Models\Historibayarpenjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class SfaApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah.'
            ], 401);
        }

        // Generate Sanctum Token
        $token = $user->createToken('sfa_auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email ?? '',
                'kode_cabang' => $user->kode_cabang ?? '',
                'kode_dept' => $user->kode_dept ?? '',
            ]
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        
        $bulan = $request->query('bulan', date('m'));
        $tahun = $request->query('tahun', date('Y'));

        $target = 500.0; // Box / Dus
        
        $realisasi = DB::table('marketing_penjualan')
            ->join('marketing_penjualan_detail', 'marketing_penjualan.no_faktur', '=', 'marketing_penjualan_detail.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->where('marketing_penjualan.kode_salesman', $user->kode_salesman)
            ->whereMonth('marketing_penjualan.tanggal', $bulan)
            ->whereYear('marketing_penjualan.tanggal', $tahun)
            ->sum(DB::raw('marketing_penjualan_detail.jumlah / 10')); // Dummy convert to dus

        // Penjualan Bulan Berjalan
        $penjualan = DB::table('marketing_penjualan')
            ->where('kode_salesman', $user->kode_salesman)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status_batal', 0)
            ->sum(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) - potongan - penyesuaian - potongan_istimewa + ppn'));

        // Pembayaran Bulan Berjalan
        $pembayaran = DB::table('marketing_penjualan_historibayar')
            ->join('marketing_penjualan', 'marketing_penjualan.no_faktur', '=', 'marketing_penjualan_historibayar.no_faktur')
            ->where('marketing_penjualan_historibayar.kode_salesman', $user->kode_salesman)
            ->whereMonth('marketing_penjualan_historibayar.tanggal', $bulan)
            ->whereYear('marketing_penjualan_historibayar.tanggal', $tahun)
            ->where('marketing_penjualan.status_batal', 0)
            ->sum('marketing_penjualan_historibayar.jumlah');

        // Jumlah Transaksi Bulan Berjalan
        $jmltransaksi = DB::table('marketing_penjualan')
            ->where('kode_salesman', $user->kode_salesman)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status_batal', 0)
            ->count();

        // Target Pencapaian Produk Detail
        $start_date = $tahun . "-" . $bulan . "-01";
        $end_date = date('Y-m-t', strtotime($start_date));
        $target_detail = DB::table('marketing_komisi_target_detail')
            ->select(
                'marketing_komisi_target_detail.kode_produk',
                'nama_produk',
                'isi_pcs_dus',
                'jumlah',
                'detailpenjualan.realisasi'
            )
            ->join('produk', 'marketing_komisi_target_detail.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_komisi_target', 'marketing_komisi_target_detail.kode_target', '=', 'marketing_komisi_target.kode_target')
            ->leftJoin(
                DB::raw("(
                    SELECT
                        produk_harga.kode_produk,
                        SUM(jumlah) as realisasi
                    FROM
                        marketing_penjualan_detail
                    INNER JOIN produk_harga ON marketing_penjualan_detail.kode_harga = produk_harga.kode_harga
                    INNER JOIN marketing_penjualan ON marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur
                    WHERE tanggal BETWEEN '$start_date' AND '$end_date' AND kode_salesman = '$user->kode_salesman' AND status_promosi = '0'
                    GROUP BY produk_harga.kode_produk
                ) detailpenjualan"),
                function ($join) {
                    $join->on('marketing_komisi_target_detail.kode_produk', '=', 'detailpenjualan.kode_produk');
                }
            )
            ->where('kode_salesman', $user->kode_salesman)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get()
            ->map(function($item) {
                return [
                    'kode_produk' => $item->kode_produk,
                    'nama_produk' => $item->nama_produk,
                    'isi_pcs_dus' => (int)$item->isi_pcs_dus,
                    'target_jumlah' => (double)$item->jumlah,
                    'realisasi_pcs' => (double)($item->realisasi ?? 0),
                ];
            });

        // Histori Kunjungan
        $tanggal = $request->query('tanggal', date('Y-m-d'));
        $kunjungan = DB::table('marketing_penjualan_checkin')
            ->join('pelanggan', 'marketing_penjualan_checkin.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->select(
                'marketing_penjualan_checkin.kode_pelanggan',
                'pelanggan.nama_pelanggan',
                'marketing_penjualan_checkin.checkin_time',
                'marketing_penjualan_checkin.checkout_time',
                'marketing_penjualan_checkin.tanggal',
                'marketing_penjualan_checkin.latitude',
                'marketing_penjualan_checkin.longitude'
            )
            ->where('marketing_penjualan_checkin.kode_salesman', $user->kode_salesman)
            ->where('marketing_penjualan_checkin.tanggal', $tanggal)
            ->orderBy('marketing_penjualan_checkin.checkin_time', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'kode_pelanggan' => $item->kode_pelanggan,
                    'nama_pelanggan' => $item->nama_pelanggan,
                    'checkin_time' => $item->checkin_time,
                    'checkout_time' => $item->checkout_time,
                    'tanggal' => $item->tanggal,
                    'latitude' => (double)$item->latitude,
                    'longitude' => (double)$item->longitude,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'target' => $target,
                'realisasi' => round($realisasi, 2),
                'cabang' => $user->kode_cabang,
                'salesman' => $user->name,
                'penjualan' => (double)$penjualan,
                'pembayaran' => (double)$pembayaran,
                'jmltransaksi' => (int)$jmltransaksi,
                'target_detail' => $target_detail,
                'kunjungan_detail' => $kunjungan,
            ]
        ]);
    }

    public function pelanggan(Request $request)
    {
        $user = Auth::user();
        
        $pelanggan = Pelanggan::where('kode_salesman', $user->kode_salesman)
            ->where('status_aktif_pelanggan', 1)
            ->get()
            ->map(function($item) {
                $saldo_voucher_program = \DB::table('marketing_program_pencairan_detail')
                    ->join('marketing_program_pencairan', 'marketing_program_pencairan_detail.kode_pencairan', '=', 'marketing_program_pencairan.kode_pencairan')
                    ->select(\DB::raw("SUM(diskon_kumulatif - diskon_reguler) as jml_voucher"))
                    ->where('kode_pelanggan', $item->kode_pelanggan)
                    ->where('metode_pembayaran', 'VC')
                    ->where('status', '1')
                    ->first();

                $tanggal_mulai = date('Y-m-d', strtotime("2025-01-01"));
                $diskonprogram = \DB::table('marketing_penjualan_historibayar')
                    ->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur')
                    ->select(\DB::raw("SUM(jumlah) as jml_voucher"))
                    ->where('marketing_penjualan.kode_pelanggan', $item->kode_pelanggan)
                    ->where('jenis_voucher', 2)
                    ->where('voucher_reward', 1)
                    ->where('marketing_penjualan_historibayar.tanggal', '>=', $tanggal_mulai)
                    ->first();

                $saldo_voucher = ($saldo_voucher_program->jml_voucher ?? 0) - ($diskonprogram->jml_voucher ?? 0);

                return [
                    'kode_pelanggan' => $item->kode_pelanggan,
                    'nama_pelanggan' => $item->nama_pelanggan,
                    'alamat_pelanggan' => $item->alamat_pelanggan,
                    'no_hp' => $item->no_hp_pelanggan ?? '',
                    'kode_cabang' => $item->kode_cabang,
                    'latitude' => $item->latitude ?? -6.2000,
                    'longitude' => $item->longitude ?? 106.8166,
                    'limit_kredit' => $item->limit_pelanggan ?? 0,
                    'foto' => $item->foto ?? '',
                    'encrypted_kode_pelanggan' => Crypt::encrypt($item->kode_pelanggan),
                    'saldo_voucher' => $saldo_voucher,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $pelanggan
        ]);
    }

    public function produk(Request $request)
    {
        $kode_pelanggan = $request->query('kode_pelanggan');
        if (!$kode_pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter kode_pelanggan wajib disertakan.'
            ], 400);
        }

        $hargaModel = new Harga();
        $harga = $hargaModel->getHargabypelanggan($kode_pelanggan);

        $produk = $harga->map(function($item) {
            return [
                'kode_harga' => $item->kode_harga,
                'kode_produk' => $item->kode_produk,
                'nama_produk' => $item->nama_produk,
                'satuan' => $item->satuan ?? 'Dus',
                'isi_pcs_dus' => $item->isi_pcs_dus ?? 1,
                'isi_pcs_pack' => $item->isi_pcs_pack ?? 0,
                'harga_dus' => $item->harga_dus ?? 0.0,
                'harga_pack' => $item->harga_pack ?? 0.0,
                'harga_pcs' => $item->harga_pcs ?? 0.0,
                'kode_kategori_diskon' => $item->kode_kategori_diskon,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $produk
        ]);
    }

    public function penjualan(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'kode_pelanggan' => 'required|string',
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|string|in:T,K',
            'total_bayar' => 'required|numeric',
            'items' => 'required|array',
            'items.*.kode_produk' => 'required|string',
            'items.*.qty_dus' => 'required|integer',
            'items.*.qty_pack' => 'required|integer',
            'items.*.qty_pcs' => 'required|integer',
            'items.*.status_promosi' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $salesman = DB::table('salesman')
                ->join('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
                ->where('kode_salesman', $user->kode_salesman)
                ->first();

            if (!$salesman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Salesman data not found.'
                ], 400);
            }

            $tahun = date('y', strtotime($request->tanggal));
            $thn = date('Y', strtotime($request->tanggal));
            $start_date = "2024-03-01";

            if ($user->hasRole('salesman') && $salesman->kode_kategori_salesman == "TO") {
                $kode_cabang = $user->kode_cabang;
                $tgltrans = explode("-", $request->tanggal);
                $bulantrans = $tgltrans[1];
                $tahuntrans = $tgltrans[0];
                $cekpenjualan = Penjualan::join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                    ->where('salesman.kode_cabang', $kode_cabang)
                    ->whereRaw('MONTH(tanggal)="' . $bulantrans . '"')
                    ->whereRaw('YEAR(tanggal)="' . $tahuntrans . '"')
                    ->whereRaw('MID(no_faktur,4,2)="PR"')
                    ->orderBy('no_faktur', 'desc')
                    ->first();
                $last_no_faktur = $cekpenjualan != null ? $cekpenjualan->no_faktur : '';
                $no_faktur = buatkode($last_no_faktur, $kode_cabang . "PR" . $bulantrans . substr($tahuntrans, 2, 2), 4);
            } else {
                if ($request->tanggal >= '2024-03-01' && $salesman->kode_cabang != "PST") {
                    $lastransaksi = Penjualan::join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
                        ->where('tanggal', '>=', $start_date)
                        ->whereRaw('MID(no_faktur,6,1)="' . $salesman->kode_sales . '"')
                        ->where('salesman.kode_cabang', $salesman->kode_cabang)
                        ->whereRaw('YEAR(tanggal)="' . $thn . '"')
                        ->whereRaw('LEFT(no_faktur,3)="' . $salesman->kode_pt . '"')
                        ->orderBy('no_faktur', 'desc')
                        ->first();
                    $last_no_faktur = $lastransaksi != NULL ? $lastransaksi->no_faktur : "";
                    $no_faktur = buatkode($last_no_faktur, $salesman->kode_pt . $tahun . $salesman->kode_sales, 6);
                } else {
                    $kode_cabang = $user->kode_cabang;
                    $last_faktur = Penjualan::whereraw('left(no_faktur,6)="' . $kode_cabang . $tahun . '"')
                        ->orderby('no_faktur', 'desc')
                        ->first();
                    $no_bukti_count = $last_faktur ? (int)substr($last_faktur->no_faktur, -4) + 1 : 1;
                    $no_faktur = $kode_cabang . $tahun . str_pad($no_bukti_count, 4, '0', STR_PAD_LEFT);
                }
            }

            // Generate No Bukti for Historibayarpenjualan
            $lastbayar = Historibayarpenjualan::whereRaw('LEFT(no_bukti,6) = "' . $salesman->kode_cabang . date('y') . '-"')
                ->orderBy("no_bukti", "desc")
                ->first();
            $last_no_bukti = $lastbayar != null ? $lastbayar->no_bukti : '';
            $no_bukti = buatkode($last_no_bukti, $salesman->kode_cabang . date('y') . "-", 6);

            $pelanggan = DB::table('pelanggan')->where('kode_pelanggan', $request->kode_pelanggan)->first();
            if (!$pelanggan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelanggan data not found.'
                ], 400);
            }

            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periode Laporan Sudah Ditutup'
                ], 400);
            }

            $ljt = !empty($pelanggan->ljt) ? $pelanggan->ljt : 14;
            $jatuh_tempo = date("Y-m-d", strtotime("+$ljt day", strtotime($request->tanggal)));

            $potongan_aida = (double)($request->potongan_aida ?? 0);
            $potongan_swan = (double)($request->potongan_swan ?? 0);
            $potongan_stick = (double)($request->potongan_stick ?? 0);
            $potongan_sambal = (double)($request->potongan_sambal ?? 0);
            $total_potongan = $potongan_aida + $potongan_swan + $potongan_stick + $potongan_sambal;

            $jenis_transaksi = $request->jenis_transaksi;
            $jenis_bayar = $jenis_transaksi == "K" ? "TP" : $request->jenis_bayar;
            $titipan = $jenis_transaksi == "T" ? 0 : (double)($request->titipan ?? 0);
            $voucher = $jenis_transaksi == "K" ? 0 : (double)($request->voucher ?? 0);

            $total_bruto = 0;
            $detail = [];
            foreach ($request->items as $item) {
                $harga = DB::table('produk_harga')
                    ->select('produk_harga.*', 'produk.isi_pcs_pack', 'produk.isi_pcs_dus')
                    ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
                    ->where('produk_harga.kode_produk', $item['kode_produk'])
                    ->where(function($q) use ($request, $user) {
                        $q->where('kode_pelanggan', $request->kode_pelanggan)
                          ->orWhere('kode_cabang', $user->kode_cabang);
                    })
                    ->first();

                if ($harga) {
                    $qty_dus = (int)$item['qty_dus'];
                    $qty_pack = (int)$item['qty_pack'];
                    $qty_pcs = (int)$item['qty_pcs'];
                    $status_promosi = (isset($item['status_promosi']) && $item['status_promosi'] == 1) ? 1 : 0;

                    $harga_dus = $status_promosi ? 0 : (double)$harga->harga_dus;
                    $harga_pack = $status_promosi ? 0 : (double)$harga->harga_pack;
                    $harga_pcs = $status_promosi ? 0 : (double)$harga->harga_pcs;

                    $subtotal = ($qty_dus * $harga_dus) + ($qty_pack * $harga_pack) + ($qty_pcs * $harga_pcs);
                    $total_bruto += $subtotal;

                    $detail[] = [
                        'no_faktur' => $no_faktur,
                        'kode_harga' => $harga->role_harga ?? $harga->kode_harga,
                        'jumlah' => ($qty_dus * $harga->isi_pcs_dus) + ($qty_pack * $harga->isi_pcs_pack) + $qty_pcs,
                        'harga_dus' => $harga_dus,
                        'harga_pack' => $harga_pack,
                        'harga_pcs' => $harga_pcs,
                        'subtotal' => $subtotal,
                        'status_promosi' => $status_promosi
                    ];
                }
            }

            $total_netto = $total_bruto - $total_potongan;

            $penjualan_model = new Penjualan();
            $sisa_piutang = $penjualan_model->getPiutangpelanggan($request->kode_pelanggan);

            $faktur_kredit = $penjualan_model->getFakturkredit($request->kode_pelanggan);
            $max_faktur = $faktur_kredit['jml_faktur'];
            $unpaid_faktur = $faktur_kredit['unpaid_faktur'];
            $siklus_pembayaran = $faktur_kredit['siklus_pembayaran'];

            $total_piutang = $sisa_piutang + $total_netto;

            if ($jenis_transaksi == 'K' && $total_piutang > $pelanggan->limit_pelanggan && $siklus_pembayaran === '0') {
                return response()->json([
                    'success' => false,
                    'message' => 'Melebihi Limit, Silahkan Ajukan Penambahan Limit'
                ], 400);
            } else if ($jenis_transaksi == 'K' && $total_netto > $pelanggan->limit_pelanggan && $siklus_pembayaran == '1') {
                return response()->json([
                    'success' => false,
                    'message' => 'Melebihi Limit, Silahkan Ajukan Penambahan Limit'
                ], 400);
            } else if ($unpaid_faktur > $max_faktur && $siklus_pembayaran === '0') {
                return response()->json([
                    'success' => false,
                    'message' => 'Melebihi Jumlah Faktur Kredit'
                ], 400);
            }

            $penjualan = Penjualan::create([
                'no_faktur' => $no_faktur,
                'tanggal' => $request->tanggal,
                'kode_pelanggan' => $request->kode_pelanggan,
                'kode_salesman' => $user->kode_salesman,
                'keterangan' => $request->keterangan,
                'status_sampel' => 0,
                'status_pajak_faktur' => $request->status_pajak_faktur ?? 0,

                'potongan_aida' => $potongan_aida,
                'potongan_swan' => $potongan_swan,
                'potongan_stick' => $potongan_stick,
                'potongan_sambal' => $potongan_sambal,
                'potongan' => $total_potongan,

                'potis_aida' => 0,
                'potis_swan' => 0,
                'potis_stick' => 0,
                'potongan_istimewa' => 0,

                'peny_aida' => 0,
                'peny_swan' => 0,
                'peny_stick' => 0,
                'penyesuaian' => 0,

                'jenis_transaksi' => $jenis_transaksi,
                'jenis_bayar' => $jenis_bayar,

                'jatuh_tempo' => $jatuh_tempo,
                'routing' => $pelanggan->hari,
                'id_user' => $user->id,
                'status' => 0,
            ]);

            Detailpenjualan::insert($detail);

            // Jika Transaksi Tunai
            if ($jenis_transaksi == "T") {
                if ($jenis_bayar == "TN") {
                    Historibayarpenjualan::create([
                        'no_bukti' => $no_bukti,
                        'no_faktur' => $no_faktur,
                        'tanggal' => $request->tanggal,
                        'jenis_bayar' => $jenis_bayar,
                        'jumlah' => $total_netto - $voucher,
                        'kode_salesman' => $user->kode_salesman,
                        'id_user' => $user->id
                    ]);
                }

                // Jika Ada Voucher
                if (!empty($voucher)) {
                    Historibayarpenjualan::create([
                        'no_bukti' => $jenis_bayar == 'TR' ? $no_bukti : buatkode($no_bukti, $salesman->kode_cabang . date('y') . "-", 6),
                        'no_faktur' => $no_faktur,
                        'tanggal' => $request->tanggal,
                        'jenis_bayar' => $jenis_bayar,
                        'jumlah' => $voucher,
                        'voucher' => 1,
                        'jenis_voucher' => 2,
                        'voucher_reward' => 1,
                        'kode_salesman' => $user->kode_salesman,
                        'id_user' => $user->id
                    ]);
                }
            } else {
                if (!empty($titipan)) {
                    Historibayarpenjualan::create([
                        'no_bukti' => $no_bukti,
                        'no_faktur' => $no_faktur,
                        'tanggal' => $request->tanggal,
                        'jenis_bayar' => 'TP',
                        'jumlah' => $titipan,
                        'kode_salesman' => $user->kode_salesman,
                        'id_user' => $user->id
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Penjualan berhasil disimpan.',
                'no_faktur' => $no_faktur,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function historiPenjualan(Request $request, $kode_pelanggan)
    {
        $perPage = 10;
        $noFaktur = $request->query('no_faktur');
        $status = $request->query('status');

        $data = Penjualan::select(
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.jenis_transaksi',
            'potongan',
            'potongan_istimewa',
            'penyesuaian',
            'ppn',
            'status',
            'status_batal'
        )
            ->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) as total_bruto'))
            ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
            ->when($noFaktur, function ($query) use ($noFaktur) {
                $query->where('marketing_penjualan.no_faktur', 'like', '%' . $noFaktur . '%');
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('marketing_penjualan.status', $status);
            })
            ->orderBy('marketing_penjualan.tanggal', 'desc')
            ->orderBy('marketing_penjualan.no_faktur', 'desc')
            ->paginate($perPage);

        $datapenjualan = collect($data->items())->map(function($d) {
            $total_bruto = $d->total_bruto ?? 0;
            $potongan = $d->potongan ?? 0;
            $potongan_istimewa = $d->potongan_istimewa ?? 0;
            $penyesuaian = $d->penyesuaian ?? 0;
            $ppn = $d->ppn ?? 0;
            $total_netto = $total_bruto - $potongan - $potongan_istimewa - $penyesuaian + $ppn;

            return [
                'no_faktur' => $d->no_faktur,
                'tanggal' => $d->tanggal,
                'jenis_transaksi' => $d->jenis_transaksi,
                'status' => $d->status,
                'status_batal' => $d->status_batal,
                'total_netto' => $total_netto,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $datapenjualan,
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'has_more' => $data->hasMorePages()
        ]);
    }

    public function historiRetur(Request $request, $kode_pelanggan)
    {
        $perPage = 10;
        $noFaktur = $request->query('no_faktur');

        $data = DB::table('marketing_retur')
            ->select(
                'marketing_retur.no_retur',
                'marketing_retur.tanggal',
                'marketing_retur.no_faktur',
                'marketing_penjualan.kode_pelanggan',
                'jenis_retur'
            )
            ->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail WHERE no_retur = marketing_retur.no_retur) as total_retur'))
            ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
            ->when($noFaktur, function ($query) use ($noFaktur) {
                $query->where('marketing_penjualan.no_faktur', 'like', '%' . $noFaktur . '%');
            })
            ->orderBy('marketing_retur.tanggal', 'desc')
            ->orderBy('marketing_retur.no_retur', 'desc')
            ->paginate($perPage);

        $dataretur = collect($data->items())->map(function($d) {
            return [
                'no_retur' => $d->no_retur,
                'no_faktur' => $d->no_faktur,
                'tanggal' => $d->tanggal,
                'jenis_retur' => $d->jenis_retur,
                'total_retur' => (double)($d->total_retur ?? 0.0),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $dataretur,
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'has_more' => $data->hasMorePages()
        ]);
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'kode_pelanggan' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $pelanggan = Pelanggan::where('kode_pelanggan', $request->kode_pelanggan)->firstOrFail();
            
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = $request->kode_pelanggan . '.' . $file->getClientOriginalExtension();
                
                // Save to storage/pelanggan
                $file->storeAs('public/pelanggan', $filename);
                
                $pelanggan->update([
                    'foto' => $filename,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'status_lokasi' => 1
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Foto pelanggan berhasil diperbarui.',
                    'foto' => $filename
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function detailPenjualan(Request $request)
    {
        $noFaktur = $request->query('no_faktur');
        if (!$noFaktur) {
            return response()->json(['success' => false, 'message' => 'No faktur tidak ditemukan.'], 400);
        }

        $pnj = new Penjualan();
        $penjualan = $pnj->getFaktur($noFaktur);

        if (!$penjualan) {
            return response()->json(['success' => false, 'message' => 'Faktur tidak ditemukan.'], 404);
        }

        $detail = $pnj->getDetailpenjualan($noFaktur)->map(function($d) {
            $jumlah = (int)$d->jumlah;
            $isi_pcs_dus = (int)$d->isi_pcs_dus;
            $isi_pcs_pack = (int)$d->isi_pcs_pack;

            $qty_dus = 0;
            $qty_pack = 0;
            $qty_pcs = 0;

            if ($isi_pcs_dus > 0) {
                $qty_dus = floor($jumlah / $isi_pcs_dus);
                $sisa = $jumlah % $isi_pcs_dus;
            } else {
                $sisa = $jumlah;
            }

            if ($isi_pcs_pack > 0) {
                $qty_pack = floor($sisa / $isi_pcs_pack);
                $qty_pcs = $sisa % $isi_pcs_pack;
            } else {
                $qty_pcs = $sisa;
            }

            return [
                'kode_harga' => $d->kode_harga,
                'kode_produk' => $d->kode_produk,
                'nama_produk' => $d->nama_produk,
                'harga_dus' => (double)$d->harga_dus,
                'harga_pack' => (double)$d->harga_pack,
                'harga_pcs' => (double)$d->harga_pcs,
                'qty_dus' => $qty_dus,
                'qty_pack' => $qty_pack,
                'qty_pcs' => $qty_pcs,
                'subtotal' => (double)$d->subtotal,
            ];
        });

        $retur = DB::table('marketing_retur_detail')
            ->select(
                'tanggal',
                'marketing_retur_detail.*',
                'jenis_retur',
                'produk_harga.kode_produk',
                'nama_produk',
                'subtotal'
            )
            ->join('produk_harga', 'marketing_retur_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->where('no_faktur', $noFaktur)
            ->get()
            ->map(function($r) {
                return [
                    'no_retur' => $r->no_retur,
                    'tanggal' => $r->tanggal,
                    'kode_produk' => $r->kode_produk,
                    'nama_produk' => $r->nama_produk,
                    'qty_dus' => (int)$r->qty_dus,
                    'qty_pack' => (int)$r->qty_pack,
                    'qty_pcs' => (int)$r->qty_pcs,
                    'subtotal' => (double)$r->subtotal,
                    'jenis_retur' => $r->jenis_retur,
                ];
            });

        $historibayar = DB::table('marketing_penjualan_historibayar')
            ->select(
                'marketing_penjualan_historibayar.*',
                'nama_salesman',
                'marketing_penjualan_historibayar_giro.kode_giro',
                'no_giro',
                'giro_to_cash',
                'nama_voucher'
            )
            ->leftJoin('jenis_voucher', 'marketing_penjualan_historibayar.jenis_voucher', '=', 'jenis_voucher.id')
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
            ->leftJoin('marketing_penjualan_giro', 'marketing_penjualan_historibayar_giro.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_historibayar.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('no_faktur', $noFaktur)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($h) {
                return [
                    'no_bukti' => $h->no_bukti,
                    'tanggal' => $h->tanggal,
                    'jumlah' => (double)$h->jumlah,
                    'cara_bayar' => $h->jenis_bayar,
                    'nama_salesman' => $h->nama_salesman,
                    'no_giro' => $h->no_giro,
                    'nama_voucher' => $h->nama_voucher,
                ];
            });

        $total_bruto = (double)$penjualan->total_bruto;
        $potongan = (double)$penjualan->potongan;
        $potongan_istimewa = (double)$penjualan->potongan_istimewa;
        $penyesuaian = (double)$penjualan->penyesuaian;
        $ppn = (double)$penjualan->ppn;
        $total_netto = $total_bruto - $potongan - $potongan_istimewa - $penyesuaian + $ppn;
        $total_retur = (double)($penjualan->total_retur ?? 0.0);
        $total_bayar = (double)($penjualan->total_bayar ?? 0.0);
        $sisa_piutang = $total_netto - $total_retur - $total_bayar;

        return response()->json([
            'success' => true,
            'data' => [
                'no_faktur' => $penjualan->no_faktur,
                'tanggal' => $penjualan->tanggal,
                'jenis_transaksi' => $penjualan->jenis_transaksi,
                'ljt' => (int)$penjualan->ljt,
                'status' => $penjualan->status,
                'status_batal' => $penjualan->status_batal,
                'signature' => $penjualan->signature,
                'nama_pelanggan' => $penjualan->nama_pelanggan,
                'nama_salesman' => $penjualan->nama_salesman,
                'total_bruto' => $total_bruto,
                'potongan' => $potongan,
                'potongan_istimewa' => $potongan_istimewa,
                'penyesuaian' => $penyesuaian,
                'ppn' => $ppn,
                'total_netto' => $total_netto,
                'total_retur' => $total_retur,
                'total_bayar' => $total_bayar,
                'sisa_piutang' => $sisa_piutang > 0 ? $sisa_piutang : 0.0,
                'detail' => $detail,
                'retur' => $retur,
                'historibayar' => $historibayar
            ]
        ]);
    }

    public function uploadSignature(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required|string',
            'image' => 'required|string', // raw base64 string
        ]);

        try {
            $penjualan = Penjualan::where('no_faktur', $request->no_faktur)->firstOrFail();
            $fileName = str_replace('/', '_', $request->no_faktur) . '.png';
            $folderPath = "public/signature/";
            $file = $folderPath . $fileName;

            $image_base64 = base64_decode($request->image);

            if (Storage::exists($file)) {
                Storage::delete($file);
            }
            Storage::put($file, $image_base64);

            $penjualan->update([
                'signature' => $fileName
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tanda tangan berhasil disimpan.',
                'signature' => $fileName
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function diskon()
    {
        $diskon = \DB::table('produk_diskon')->orderBy('kode_kategori_diskon')->get();
        return response()->json([
            'success' => true,
            'data' => $diskon
        ]);
    }
}

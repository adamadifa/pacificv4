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
use Illuminate\Support\Facades\DB;

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
        
        $bulan = date('m');
        $tahun = date('Y');

        $target = 500.0; // Box / Dus
        
        $realisasi = DB::table('marketing_penjualan')
            ->join('marketing_penjualan_detail', 'marketing_penjualan.no_faktur', '=', 'marketing_penjualan_detail.no_faktur')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->where('marketing_penjualan.kode_salesman', $user->kode_salesman)
            ->whereMonth('marketing_penjualan.tanggal', $bulan)
            ->whereYear('marketing_penjualan.tanggal', $tahun)
            ->sum(DB::raw('marketing_penjualan_detail.jumlah / 10')); // Dummy convert to dus

        return response()->json([
            'success' => true,
            'data' => [
                'target' => $target,
                'realisasi' => round($realisasi, 2),
                'cabang' => $user->kode_cabang,
                'salesman' => $user->name,
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
                return [
                    'kode_pelanggan' => $item->kode_pelanggan,
                    'nama_pelanggan' => $item->nama_pelanggan,
                    'alamat_pelanggan' => $item->alamat_pelanggan,
                    'no_hp' => $item->no_hp_pelanggan ?? '',
                    'kode_cabang' => $item->kode_cabang,
                    'latitude' => $item->latitude ?? -6.2000,
                    'longitude' => $item->longitude ?? 106.8166,
                    'limit_kredit' => $item->limit_pelanggan ?? 0,
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
                'kode_produk' => $item->kode_produk,
                'nama_produk' => $item->nama_produk,
                'satuan' => $item->satuan ?? 'Dus',
                'isi_pcs_dus' => $item->isi_pcs_dus ?? 1,
                'harga_dus' => $item->harga_dus ?? 0.0,
                'harga_pcs' => $item->harga_pcs ?? 0.0,
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
            'items.*.qty_pcs' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $kode_cabang = $user->kode_cabang;
            $today = date('ymd');
            $last_faktur = Penjualan::whereraw('left(no_faktur,6)="' . $kode_cabang . $today . '"')
                ->orderby('no_faktur', 'desc')
                ->first();
            
            // Generate nomor faktur
            $no_bukti_count = $last_faktur ? (int)substr($last_faktur->no_faktur, -4) + 1 : 1;
            $no_faktur = $kode_cabang . $today . str_pad($no_bukti_count, 4, '0', STR_PAD_LEFT);

            $penjualan = Penjualan::create([
                'no_faktur' => $no_faktur,
                'tanggal' => $request->tanggal,
                'kode_pelanggan' => $request->kode_pelanggan,
                'kode_salesman' => $user->kode_salesman,
                'jenis_transaksi' => $request->jenis_transaksi,
                'total_bayar' => $request->total_bayar,
                'status' => 0,
            ]);

            foreach ($request->items as $item) {
                $harga = DB::table('produk_harga')
                    ->where('kode_produk', $item['kode_produk'])
                    ->where(function($q) use ($request, $user) {
                        $q->where('kode_pelanggan', $request->kode_pelanggan)
                          ->orWhere('kode_cabang', $user->kode_cabang);
                    })
                    ->first();

                if ($harga) {
                    Detailpenjualan::create([
                        'no_faktur' => $no_faktur,
                        'kode_harga' => $harga->role_harga ?? $harga->kode_harga,
                        'jumlah' => ($item['qty_dus'] * $harga->isi_pcs_dus) + $item['qty_pcs'],
                        'subtotal' => ($item['qty_dus'] * $harga->harga_dus) + ($item['qty_pcs'] * $harga->harga_pcs),
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
}

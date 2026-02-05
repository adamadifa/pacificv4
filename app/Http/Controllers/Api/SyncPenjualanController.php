<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Detailpenjualan;
use App\Models\Historibayarpenjualan;
use App\Models\Salesman;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SyncPenjualanController extends Controller
{
    /**
     * Sync single penjualan (POST /api/sync/penjualan)
     */
    public function sync(Request $request)
    {
        // ... (Implement exact same logic as Sync single, but we focus on Batch for now)
        // For now, I will implement batchStore which is the priority. 
        // Single sync might be redundant if we switch to batch fully, but for completeness or legacy it might be needed.
        // Given the prompt asks for "Sync Batch", I will focus on batchStore.
        
        return $this->batchStore(new Request(['data' => [$request->all()]]));
    }

    /**
     * Sync batch penjualan (POST /api/sync/penjualan/batch)
     */
    public function batchStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|array|min:1',
            'data.*.no_faktur' => 'required|string|max:13',
            'data.*.tanggal' => 'required|date',
            'data.*.kode_pelanggan' => 'required|string|max:13',
            'data.*.kode_salesman' => 'required|string|max:7',
            'data.*.jenis_transaksi' => 'required|string|max:1',
            'data.*.jenis_bayar' => 'required|string|max:2',
            'data.*.status' => 'required|string|max:1',
            // Add other validations as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $results = [];
        $successCount = 0;
        $failedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($request->data as $item) {
                try {
                    // Logic to sync single Item
                    $no_faktur = $item['no_faktur'];
                    
                    // 1. Check or Create Penjualan
                    $penjualan = Penjualan::find($no_faktur);
                    $action = 'created';

                    $penjualanData = [
                        'no_faktur' => $no_faktur,
                        'tanggal' => $item['tanggal'],
                        'kode_pelanggan' => $item['kode_pelanggan'],
                        'kode_salesman' => $item['kode_salesman'],
                        'kode_akun' => $item['kode_akun'] ?? '1-1401',
                        'kode_akun_potongan' => $item['kode_akun_potongan'] ?? '4-2201',
                        'kode_akun_penyesuaian' => $item['kode_akun_penyesuaian'] ?? '4-2202',
                        'potongan_aida' => $item['potongan_aida'] ?? 0,
                        'potongan_swan' => $item['potongan_swan'] ?? 0,
                        'potongan_stick' => $item['potongan_stick'] ?? 0,
                        'potongan_sp' => $item['potongan_sp'] ?? 0,
                        'potongan_sambal' => $item['potongan_sambal'] ?? 0,
                        'potongan' => $item['potongan'] ?? 0,
                        'potis_aida' => $item['potis_aida'] ?? 0,
                        'potis_swan' => $item['potis_swan'] ?? 0,
                        'potis_stick' => $item['potis_stick'] ?? 0,
                        'potongan_istimewa' => $item['potongan_istimewa'] ?? 0,
                        'peny_aida' => $item['peny_aida'] ?? 0,
                        'peny_swan' => $item['peny_swan'] ?? 0,
                        'peny_stick' => $item['peny_stick'] ?? 0,
                        'penyesuaian' => $item['penyesuaian'] ?? 0,
                        'ppn' => $item['ppn'] ?? 0,
                        'jenis_transaksi' => $item['jenis_transaksi'],
                        'jenis_bayar' => $item['jenis_bayar'],
                        'jatuh_tempo' => $item['jatuh_tempo'] ?? null,
                        'status' => $item['status'],
                        'routing' => $item['routing'] ?? null,
                        'signature' => $item['signature'] ?? null,
                        'tanggal_pelunasan' => $item['tanggal_pelunasan'] ?? null,
                        'print' => $item['print'] ?? 0,
                        'id_user' => $item['id_user'] ?? null, 
                        'keterangan' => $item['keterangan'] ?? null,
                        'status_batal' => $item['status_batal'] ?? '0',
                        'lock_print' => $item['lock_print'] ?? '0',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    if ($penjualan) {
                        $penjualan->update($penjualanData);
                        $action = 'updated';
                    } else {
                        Penjualan::create($penjualanData);
                    }

                    // 2. Handle Details
                    if (isset($item['detail']) && is_array($item['detail'])) {
                        // Delete existing details to avoid duplication/mismatch? 
                        // Usually safer to delete and re-insert for full sync
                        Detailpenjualan::where('no_faktur', $no_faktur)->delete();

                        foreach ($item['detail'] as $detail) {
                            Detailpenjualan::create([
                                'no_faktur' => $no_faktur,
                                'kode_harga' => $detail['kode_harga'],
                                'harga_dus' => $detail['harga_dus'],
                                'harga_pack' => $detail['harga_pack'],
                                'harga_pcs' => $detail['harga_pcs'],
                                'jumlah' => $detail['jumlah'],
                                'subtotal' => $detail['subtotal'],
                                'status_promosi' => $detail['status_promosi'] ?? '0'
                            ]);
                        }
                    }

                    // 3. Handle Histori Bayar
                    if (isset($item['historibayar']) && is_array($item['historibayar'])) {
                         // Check duplications or sync strategy. 
                         // User request in previous conversation: "check if payment record already exists (using no_bukti) before inserting it"
                         // So I should not just delete all.
                         
                         foreach($item['historibayar'] as $bayar) {
                             $existsBayar = Historibayarpenjualan::where('no_bukti', $bayar['no_bukti'])->first();
                             $bayarData = [
                                 'no_bukti' => $bayar['no_bukti'],
                                 'no_faktur' => $no_faktur,
                                 'tanggal' => $bayar['tanggal'],
                                 'kode_salesman' => $bayar['kode_salesman'] ?? $item['kode_salesman'],
                                 'jenis_bayar' => $bayar['jenis_bayar'],
                                 'jumlah' => $bayar['jumlah'],
                                 'voucher' => $bayar['voucher'] ?? '0',
                                 'jenis_voucher' => $bayar['jenis_voucher'] ?? '0',
                                 'kode_lhp' => $bayar['kode_lhp'] ?? null,
                                 'kode_akun' => $bayar['kode_akun'] ?? '1-1401',
                                 'keterangan' => $bayar['keterangan'] ?? null,
                                 'id_user' => $bayar['id_user'] ?? $item['id_user'] ?? null
                             ];

                             if($existsBayar) {
                                 $existsBayar->update($bayarData);
                             } else {
                                 Historibayarpenjualan::create($bayarData);
                             }
                         }
                    }

                    $results[] = [
                        'no_faktur' => $no_faktur,
                        'status' => 'success',
                        'message' => 'Berhasil disync'
                    ];
                    $successCount++;

                } catch (\Exception $e) {
                    $results[] = [
                        'no_faktur' => $item['no_faktur'] ?? 'UNKNOWN',
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ];
                    $failedCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Sync batch selesai. Sukses: {$successCount}, Gagal: {$failedCount}",
                'summary' => [
                    'total' => count($request->data),
                    'success' => $successCount,
                    'failed' => $failedCount
                ],
                'results' => $results
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Sync batch penjualan error: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync batch data penjualan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

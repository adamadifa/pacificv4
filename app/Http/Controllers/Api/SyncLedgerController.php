<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ledger;
use App\Models\Bank;
use App\Models\Coa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SyncLedgerController extends Controller
{
    /**
     * Sync single ledger (POST /api/sync/ledger)
     */
    public function sync(Request $request)
    {
        return $this->batchStore(new Request(['data' => [$request->all()]]));
    }

    /**
     * Sync batch ledger (POST /api/sync/ledger/batch)
     */
    public function batchStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|array|min:1',
            'data.*.no_bukti' => 'required|string|max:12',
            'data.*.tanggal' => 'required|date',
            'data.*.keterangan' => 'required|string|max:255',
            'data.*.jumlah' => 'required|integer',
            'data.*.debet_kredit' => 'required|in:D,K',
            'data.*.kode_bank' => 'required|string|max:5',
            'data.*.kode_akun' => 'required|string|max:6',
            'data.*.pelanggan' => 'nullable|string|max:255',
            'data.*.kode_peruntukan' => 'nullable|string|max:2',
            'data.*.keterangan_peruntukan' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek duplikasi no_bukti dalam request
        $noBuktiList = array_column($request->data, 'no_bukti');
        $duplicates = array_diff_assoc($noBuktiList, array_unique($noBuktiList));

        if (!empty($duplicates)) {
             return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: Terdapat duplikasi No Bukti dalam request',
                'errors' => [
                    'duplicate_no_bukti' => array_values(array_unique($duplicates))
                ]
            ], 422);
        }

        $results = [];
        $successCount = 0;
        $failedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($request->data as $item) {
                try {
                    // Validasi foreign key simple (optional)
                    // if (!Bank::where('kode_bank', $item['kode_bank'])->exists()) throw new \Exception("Kode bank tidak ditemukan");
                    // if (!Coa::where('kode_akun', $item['kode_akun'])->exists()) throw new \Exception("Kode akun tidak ditemukan");
                    
                    $existing = Ledger::find($item['no_bukti']);
                    $action = 'created';

                    $ledgerData = [
                        'no_bukti' => $item['no_bukti'],
                        'tanggal' => $item['tanggal'],
                        'pelanggan' => $item['pelanggan'] ?? null,
                        'kode_bank' => $item['kode_bank'],
                        'kode_akun' => $item['kode_akun'],
                        'keterangan' => $item['keterangan'],
                        'jumlah' => $item['jumlah'],
                        'debet_kredit' => $item['debet_kredit'],
                        'kode_peruntukan' => $item['kode_peruntukan'] ?? null,
                        'keterangan_peruntukan' => $item['keterangan_peruntukan'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    if ($existing) {
                        $existing->update($ledgerData);
                        $action = 'updated';
                    } else {
                        // Force Create with primary key
                        $ledger = new Ledger();
                        $ledger->no_bukti = $item['no_bukti'];
                        $ledger->fill($ledgerData);
                        $ledger->save();
                    }

                    $results[] = [
                        'no_bukti' => $item['no_bukti'],
                        'status' => 'success',
                        'message' => $action == 'created' ? 'Berhasil disync' : 'Berhasil diupdate',
                        'action' => $action
                    ];
                    $successCount++;

                } catch (\Exception $e) {
                    $results[] = [
                        'no_bukti' => $item['no_bukti'],
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
            Log::error("Sync batch ledger error: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync batch data ledger',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

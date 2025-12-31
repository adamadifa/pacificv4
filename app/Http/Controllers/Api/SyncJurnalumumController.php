<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jurnalumum;
use App\Models\Coa;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SyncJurnalumumController extends Controller
{
    /**
     * Sync single jurnal umum (POST /api/sync/jurnalumum)
     */
    public function sync(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_ju' => 'required|string|max:9',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|integer',
            'debet_kredit' => 'required|in:D,K',
            'kode_akun' => 'required|string|max:6',
            'kode_dept' => 'required|string|max:3',
            'kode_peruntukan' => 'required|string|max:2',
            'id_user' => 'required|integer',
            'kode_cabang' => 'nullable|string|max:3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Validasi foreign key
            $coa = Coa::find($request->kode_akun);
            if (!$coa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => [
                        'kode_akun' => ['Kode akun tidak ditemukan']
                    ]
                ], 422);
            }

            $dept = Departemen::find($request->kode_dept);
            if (!$dept) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => [
                        'kode_dept' => ['Kode departemen tidak ditemukan']
                    ]
                ], 422);
            }

            // Cek apakah kode_ju sudah ada
            $existing = Jurnalumum::find($request->kode_ju);
            $action = 'created';

            if ($existing) {
                // Update data yang ada
                $existing->update([
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                    'jumlah' => $request->jumlah,
                    'debet_kredit' => $request->debet_kredit,
                    'kode_akun' => $request->kode_akun,
                    'kode_dept' => $request->kode_dept,
                    'kode_pruntukan' => $request->kode_peruntukan,
                    'id_user' => $request->id_user,
                    'kode_cabang' => $request->kode_cabang
                ]);
                $action = 'updated';
            } else {
                // Insert data baru
                Jurnalumum::create([
                    'kode_ju' => $request->kode_ju,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                    'jumlah' => $request->jumlah,
                    'debet_kredit' => $request->debet_kredit,
                    'kode_akun' => $request->kode_akun,
                    'kode_dept' => $request->kode_dept,
                    'kode_pruntukan' => $request->kode_peruntukan,
                    'id_user' => $request->id_user,
                    'kode_cabang' => $request->kode_cabang
                ]);
            }

            DB::commit();

            $message = $action == 'created' 
                ? 'Data jurnal umum berhasil disync' 
                : 'Data jurnal umum berhasil diupdate';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'kode_ju' => $request->kode_ju,
                    'action' => $action,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]
            ], $action == 'created' ? 201 : 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Sync jurnal umum error: {$e->getMessage()}", [
                'kode_ju' => $request->kode_ju,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync data jurnal umum',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete single jurnal umum (DELETE /api/sync/jurnalumum)
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_ju' => 'required|string|max:9'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $jurnalumum = Jurnalumum::find($request->kode_ju);

            if (!$jurnalumum) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode JU tidak ditemukan',
                    'kode_ju' => $request->kode_ju
                ], 404);
            }

            $jurnalumum->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data jurnal umum berhasil dihapus',
                'data' => [
                    'kode_ju' => $request->kode_ju,
                    'deleted_at' => now()->format('Y-m-d H:i:s')
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error("Delete jurnal umum error: {$e->getMessage()}", [
                'kode_ju' => $request->kode_ju,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data jurnal umum',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check kode_ju (duplikasi) (POST /api/sync/jurnalumum/check)
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_ju' => 'required|string|max:9'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $exists = Jurnalumum::where('kode_ju', $request->kode_ju)->exists();

        return response()->json([
            'success' => true,
            'exists' => $exists,
            'kode_ju' => $request->kode_ju
        ], 200);
    }

    /**
     * Sync batch jurnal umum (POST /api/sync/jurnalumum/batch)
     */
    public function syncBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|array|min:1',
            'data.*.kode_ju' => 'required|string|max:9',
            'data.*.tanggal' => 'required|date',
            'data.*.keterangan' => 'required|string|max:255',
            'data.*.jumlah' => 'required|integer',
            'data.*.debet_kredit' => 'required|in:D,K',
            'data.*.kode_akun' => 'required|string|max:6',
            'data.*.kode_dept' => 'required|string|max:3',
            'data.*.kode_peruntukan' => 'required|string|max:2',
            'data.*.id_user' => 'required|integer',
            'data.*.kode_cabang' => 'nullable|string|max:3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek duplikasi kode_ju dalam request
        $kodeJuList = array_column($request->data, 'kode_ju');
        $duplicates = array_diff_assoc($kodeJuList, array_unique($kodeJuList));

        if (!empty($duplicates)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: Terdapat duplikasi Kode JU dalam request',
                'errors' => [
                    'duplicate_kode_ju' => array_values(array_unique($duplicates))
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
                    // Validasi foreign key
                    $coa = Coa::find($item['kode_akun']);
                    if (!$coa) {
                        $results[] = [
                            'kode_ju' => $item['kode_ju'],
                            'status' => 'failed',
                            'message' => 'Kode akun tidak ditemukan'
                        ];
                        $failedCount++;
                        continue;
                    }

                    $dept = Departemen::find($item['kode_dept']);
                    if (!$dept) {
                        $results[] = [
                            'kode_ju' => $item['kode_ju'],
                            'status' => 'failed',
                            'message' => 'Kode departemen tidak ditemukan'
                        ];
                        $failedCount++;
                        continue;
                    }

                    // Cek apakah kode_ju sudah ada
                    $existing = Jurnalumum::find($item['kode_ju']);
                    $action = 'created';

                    if ($existing) {
                        $existing->update([
                            'tanggal' => $item['tanggal'],
                            'keterangan' => $item['keterangan'],
                            'jumlah' => $item['jumlah'],
                            'debet_kredit' => $item['debet_kredit'],
                            'kode_akun' => $item['kode_akun'],
                            'kode_dept' => $item['kode_dept'],
                            'kode_pruntukan' => $item['kode_peruntukan'],
                            'id_user' => $item['id_user'],
                            'kode_cabang' => $item['kode_cabang'] ?? null
                        ]);
                        $action = 'updated';
                    } else {
                        Jurnalumum::create([
                            'kode_ju' => $item['kode_ju'],
                            'tanggal' => $item['tanggal'],
                            'keterangan' => $item['keterangan'],
                            'jumlah' => $item['jumlah'],
                            'debet_kredit' => $item['debet_kredit'],
                            'kode_akun' => $item['kode_akun'],
                            'kode_dept' => $item['kode_dept'],
                            'kode_pruntukan' => $item['kode_peruntukan'],
                            'id_user' => $item['id_user'],
                            'kode_cabang' => $item['kode_cabang'] ?? null
                        ]);
                    }

                    $results[] = [
                        'kode_ju' => $item['kode_ju'],
                        'status' => 'success',
                        'message' => $action == 'created' ? 'Berhasil disync' : 'Berhasil diupdate',
                        'action' => $action
                    ];
                    $successCount++;

                } catch (\Exception $e) {
                    $results[] = [
                        'kode_ju' => $item['kode_ju'],
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
            Log::error("Sync batch jurnal umum error: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal sync batch data jurnal umum',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete batch jurnal umum (DELETE /api/sync/jurnalumum/batch)
     */
    public function deleteBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_ju' => 'required|array|min:1',
            'kode_ju.*' => 'required|string|max:9'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek duplikasi kode_ju dalam request
        $duplicates = array_diff_assoc($request->kode_ju, array_unique($request->kode_ju));

        if (!empty($duplicates)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: Terdapat duplikasi Kode JU dalam request',
                'errors' => [
                    'duplicate_kode_ju' => array_values(array_unique($duplicates))
                ]
            ], 422);
        }

        $results = [];
        $successCount = 0;
        $failedCount = 0;

        try {
            foreach ($request->kode_ju as $kodeJu) {
                try {
                    $jurnalumum = Jurnalumum::find($kodeJu);

                    if (!$jurnalumum) {
                        $results[] = [
                            'kode_ju' => $kodeJu,
                            'status' => 'failed',
                            'message' => 'Kode JU tidak ditemukan'
                        ];
                        $failedCount++;
                        continue;
                    }

                    $jurnalumum->delete();

                    $results[] = [
                        'kode_ju' => $kodeJu,
                        'status' => 'success',
                        'message' => 'Berhasil dihapus'
                    ];
                    $successCount++;

                } catch (\Exception $e) {
                    $results[] = [
                        'kode_ju' => $kodeJu,
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ];
                    $failedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Hapus batch selesai. Sukses: {$successCount}, Gagal: {$failedCount}",
                'summary' => [
                    'total' => count($request->kode_ju),
                    'success' => $successCount,
                    'failed' => $failedCount
                ],
                'results' => $results
            ], 200);

        } catch (\Exception $e) {
            Log::error("Delete batch jurnal umum error: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus batch data jurnal umum',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}


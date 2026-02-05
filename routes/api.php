<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::apiResource('/slipgaji', App\Http\Controllers\Api\SlipgajiController::class);
// Route::get('/slipgaji/{bulan}/{tahun}/{nik}', [App\Http\Controllers\Api\SlipgajiController::class, 'show']);
// Route::get('/api/slipgaji', [App\Http\Controllers\Api\SlipgajiController::class, 'index']);

Route::apiResource('/slipgaji', App\Http\Controllers\Api\SlipgajiController::class);
Route::get('/slipgaji/{bulangaji}/{tahungaji}/{nik}', [App\Http\Controllers\Api\SlipgajiController::class, 'show']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Sync Jurnal Umum
Route::prefix('sync')->group(function () {
    Route::post('/jurnalumum', [App\Http\Controllers\Api\SyncJurnalumumController::class, 'sync']);
    Route::delete('/jurnalumum', [App\Http\Controllers\Api\SyncJurnalumumController::class, 'delete']);
    Route::post('/jurnalumum/check', [App\Http\Controllers\Api\SyncJurnalumumController::class, 'check']);
    Route::post('/jurnalumum/batch', [App\Http\Controllers\Api\SyncJurnalumumController::class, 'syncBatch']);
    Route::delete('/jurnalumum/batch', [App\Http\Controllers\Api\SyncJurnalumumController::class, 'deleteBatch']);

    // API Sync Penjualan
    Route::post('/penjualan/batch', [App\Http\Controllers\Api\SyncPenjualanController::class, 'batchStore']);

    // API Sync Kas Kecil
    Route::post('/kaskecil/batch', [App\Http\Controllers\Api\SyncKaskecilController::class, 'batchStore']);
});

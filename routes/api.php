<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdmsController;

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

// API Employee Auth
Route::get('/karyawan/{filename}', function ($filename) {
    $path = public_path('storage/karyawan/' . $filename);
    if (!file_exists($path)) {
        return response()->json(['message' => 'Image not found.'], 404);
    }
    return response()->file($path);
});

Route::post('/login-karyawan', [App\Http\Controllers\Api\Auth\EmployeeAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/me-karyawan', [App\Http\Controllers\Api\Auth\EmployeeAuthController::class, 'me']);
    Route::get('/slipgaji-months', [App\Http\Controllers\Api\SlipgajiController::class, 'getMonths']);
    Route::get('/slipgaji/{bulangaji}/{tahungaji}/{nik}', [App\Http\Controllers\Api\SlipgajiController::class, 'show']);
    Route::get('/pinjaman', [App\Http\Controllers\Api\PinjamanController::class, 'index']);
    Route::get('/pinjaman/{type}/{id}', [App\Http\Controllers\Api\PinjamanController::class, 'show']);
    Route::get('/pinjaman-simulation-rules', [App\Http\Controllers\Api\PinjamanController::class, 'getSimulationRules']);
    Route::get('/izin', [App\Http\Controllers\Api\IzinController::class, 'index']);
    Route::get('/izin/form-data', [App\Http\Controllers\Api\IzinController::class, 'getFormData']);
    Route::post('/izin/store', [App\Http\Controllers\Api\IzinController::class, 'store']);
    Route::delete('/izin/{type}/{id}', [App\Http\Controllers\Api\IzinController::class, 'destroy']);
    Route::post('/logout-karyawan', [App\Http\Controllers\Api\Auth\EmployeeAuthController::class, 'logout']);
    Route::get('/attendance-history', [\App\Http\Controllers\Api\AttendanceController::class, 'getHistory']);
    Route::get('/attendance-summary', [\App\Http\Controllers\Api\AttendanceController::class, 'getSummary']);
    Route::get('/attendance-today', [\App\Http\Controllers\Api\AttendanceController::class, 'getAttendanceToday']);
    Route::post('/attendance-store', [\App\Http\Controllers\Api\AttendanceController::class, 'store']);

    // Push Notification Subscriptions
    Route::post('/push-subscribe', [\App\Http\Controllers\Api\PushSubscriptionController::class, 'subscribe']);
    Route::post('/push-unsubscribe', [\App\Http\Controllers\Api\PushSubscriptionController::class, 'unsubscribe']);
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

    // API Sync Ledger
    Route::post('/ledger/batch', [App\Http\Controllers\Api\SyncLedgerController::class, 'batchStore']);
});

// ADMS Machine Interface
Route::any('/adms/capture', [AdmsController::class, 'capture']);
Route::any('/adms/receive', [AdmsController::class, 'receiveX100c']);

<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$customer_code = 'TSM-00467';

// Get list of invoices for this customer between 2026-01-01 and 2026-06-30
$invoices = DB::table('marketing_penjualan')
    ->where('kode_pelanggan', $customer_code)
    ->whereBetween('tanggal', ['2026-01-01', '2026-06-30'])
    ->get();

echo "Total invoices: " . $invoices->count() . "\n";
foreach ($invoices as $inv) {
    $age = $inv->tanggal_pelunasan ? (strtotime($inv->tanggal_pelunasan) - strtotime($inv->tanggal)) / 86400 : (time() - strtotime($inv->tanggal)) / 86400;
    echo "Faktur: {$inv->no_faktur} | Tanggal: {$inv->tanggal} | Status: {$inv->status} | Pelunasan: {$inv->tanggal_pelunasan} | Jenis: {$inv->jenis_transaksi} | Age: " . round($age, 1) . " days\n";
}

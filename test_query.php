<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Penjualan;

$start_date = '2020-01-01';
$end_date = '2026-05-31';
$search = 'budi'; // example

echo "=== WITHOUT FORCE INDEX ===\n";
$query = Penjualan::query();
$query->select('marketing_penjualan.*', 'pelanggan.nama_pelanggan');
$query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
$query->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date]);
$query->where('nama_pelanggan', 'like', '%' . $search . '%');
$query->orderBy('marketing_penjualan.tanggal', 'desc');
$query->orderBy('marketing_penjualan.no_faktur', 'desc');
$query->limit(15);
$explain1 = DB::select("EXPLAIN " . $query->toSql(), $query->getBindings());
print_r($explain1);

echo "\n=== WITH FORCE INDEX ===\n";
$query2 = DB::table('marketing_penjualan')->fromRaw('marketing_penjualan FORCE INDEX (idx_penjualan_tanggal_nofaktur)');
$query2->select('marketing_penjualan.*', 'pelanggan.nama_pelanggan');
$query2->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
$query2->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date]);
$query2->where('nama_pelanggan', 'like', '%' . $search . '%');
$query2->orderBy('marketing_penjualan.tanggal', 'desc');
$query2->orderBy('marketing_penjualan.no_faktur', 'desc');
$query2->limit(15);
$explain2 = DB::select("EXPLAIN " . $query2->toSql(), $query2->getBindings());
print_r($explain2);

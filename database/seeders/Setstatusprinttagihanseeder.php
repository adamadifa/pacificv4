<?php

namespace Database\Seeders;

use App\Models\Historibayarpenjualan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Setstatusprinttagihanseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Historibayarpenjualan::where('tanggal', '<=', date('Y-m-d'))->update([
            'print_tagihan' => 1
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlasankoreksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Alasankoreksi::insert([
            [
                'alasan' => 'Perjalanan Dinas Luar Kota',
                'status_denda' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'alasan' => 'Error System',
                'status_denda' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'alasan' => 'Lupa Absen',
                'status_denda' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'alasan' => 'Lainnya',
                'status_denda' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

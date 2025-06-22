<?php

namespace Database\Seeders;

use App\Models\Kategorijmk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Kategorijmkseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategorijmk = [
            ['kode_kategori' => 'JMK01', 'nama_kategori' => 'Resign'],
            ['kode_kategori' => 'JMK02', 'nama_kategori' => 'Pensiun'],
            ['kode_kategori' => 'JMK03', 'nama_kategori' => 'Meninggal Dunia'],
            ['kode_kategori' => 'JMK04', 'nama_kategori' => 'PHK Pelanggaran'],
        ];

        Kategorijmk::insert($kategorijmk);
    }
}

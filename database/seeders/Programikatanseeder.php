<?php

namespace Database\Seeders;

use App\Models\Programikatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Programikatanseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Programikatan::create([
            'kode_program' => 'PRIK001',
            'nama_program' => 'BBDP',
            'produk' => '["BB", "DEP"]',
            'keterangan' => 'Program BBDP',
        ]);

        Programikatan::create([
            'kode_program' => 'PRIK002',
            'nama_program' => 'SP8',
            'produk' => '["SP8"]',
            'keterangan' => 'Program SP8',
        ]);

        Programikatan::create([
            'kode_program' => 'PRIK003',
            'nama_program' => 'AIDA',
            'produk' => '["AB", "AR", "AS"]',
            'keterangan' => 'Program AIDA',
        ]);
    }
}

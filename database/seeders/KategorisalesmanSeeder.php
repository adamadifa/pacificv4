<?php

namespace Database\Seeders;

use App\Models\Kategorisalesman;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategorisalesmanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategorisalesman::create([
            'kode_kategori_salesman' => 'TO',
            'nama_kategori_salesman' => 'Taking Order'
        ]);

        Kategorisalesman::create([
            'kode_kategori_salesman' => 'CV',
            'nama_kategori_salesman' => 'Canvaser'
        ]);

        Kategorisalesman::create([
            'kode_kategori_salesman' => 'NM',
            'nama_kategori_salesman' => 'Normal'
        ]);

        Kategorisalesman::create([
            'kode_kategori_salesman' => 'MT',
            'nama_kategori_salesman' => 'Motoris'
        ]);

        Kategorisalesman::create([
            'kode_kategori_salesman' => 'RT',
            'nama_kategori_salesman' => 'Retail'
        ]);
    }
}

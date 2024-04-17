<?php

namespace Database\Seeders;

use App\Models\Kategoribarangpembelian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Kategoribarangpembelianseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategoribarangpembelian::create([
            'kode_kategori' => 'B001',
            'nama_kategori' => 'BAHAN',
            'kode_group' => 'GDB'
        ]);
        Kategoribarangpembelian::create([
            'kode_kategori' => 'B002',
            'nama_kategori' => 'KEMASAN',
            'kode_group' => 'GDB'
        ]);

        Kategoribarangpembelian::create([
            'kode_kategori' => 'K001',
            'nama_kategori' => 'SUKU CADANG',
            'kode_group' => 'GDL'
        ]);

        Kategoribarangpembelian::create([
            'kode_kategori' => 'K002',
            'nama_kategori' => 'LAINNYA',
            'kode_group' => 'GDL'
        ]);

        Kategoribarangpembelian::create([
            'kode_kategori' => 'K003',
            'nama_kategori' => 'OPSIH',
            'kode_group' => 'GDL'
        ]);

        Kategoribarangpembelian::create([
            'kode_kategori' => 'K004',
            'nama_kategori' => 'APD',
            'kode_group' => 'GDL'
        ]);

        Kategoribarangpembelian::create([
            'kode_kategori' => 'K005',
            'nama_kategori' => 'ATK',
            'kode_group' => 'GDL'
        ]);

        Kategoribarangpembelian::create([
            'kode_kategori' => 'K006',
            'nama_kategori' => 'CETAKAN',
            'kode_group' => 'GDL'
        ]);

        Kategoribarangpembelian::create([
            'kode_kategori' => 'K007',
            'nama_kategori' => 'OBAT OBATAN',
            'kode_group' => 'GDL'
        ]);

        Kategoribarangpembelian::create([
            'kode_kategori' => 'K008',
            'nama_kategori' => 'EXTRA FOOD',
            'kode_group' => 'GDL'
        ]);

        Kategoribarangpembelian::create([
            'kode_kategori' => 'K009',
            'nama_kategori' => 'SUKU CADANG LAINNYA',
            'kode_group' => 'GDL'
        ]);

        Kategoribarangpembelian::create([
            'kode_kategori' => 'K010',
            'nama_kategori' => 'LAINNYA',
            'kode_group' => 'GAF'
        ]);
    }
}

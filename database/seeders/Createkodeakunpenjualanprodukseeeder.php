<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Createkodeakunpenjualanprodukseeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kode_akun' => '4-1202',
                'nama_akun' => 'Penjualan AIDA BESAR 500 GR',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
            [
                'kode_akun' => '4-1205',
                'nama_akun' => 'Penjualan AIDA RENTENG 25 GR',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
            [
                'kode_akun' => '4-1203',
                'nama_akun' => 'Penjualan AIDA SEDANG 250 GR',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
            [
                'kode_akun' => '4-1102',
                'nama_akun' => 'Penjualan SAUS BAWANG BALL',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
            [
                'kode_akun' => '4-1114',
                'nama_akun' => 'Penjualan SAUS BP 500 GR',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
            [
                'kode_akun' => '4-1501',
                'nama_akun' => 'Penjualan BUMBU TABUR',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
            [
                'kode_akun' => '4-1107',
                'nama_akun' => 'Penjualan SAUS EXTRA PEDAS',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
            [
                'kode_akun' => '4-1113',
                'nama_akun' => 'Penjualan PREMIUM POUCH 1000 GR',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
            [
                'kode_akun' => '4-1109',
                'nama_akun' => 'Penjualan SAMBAL CABE 200',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
            [
                'kode_akun' => '4-1108',
                'nama_akun' => 'Penjualan SAUS PREMIUM',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
            [
                'kode_akun' => '4-1112',
                'nama_akun' => 'Penjualan SAUS PREMIUM 500',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
            [
                'kode_akun' => '4-1110',
                'nama_akun' => 'Penjualan SAUS STICK PREMIUM',
                'sub_akun' => '4-1000',
                'level' => 2,
                'jenis_akun' => 1,
                'kode_kategori' => 'C00'
            ],
        ];

        foreach ($data as $item) {
            // Asumsi model COA bernama Coa dan fieldnya kode_akun, nama_akun
            if (!\App\Models\Coa::where('kode_akun', $item['kode_akun'])->exists()) {
                \App\Models\Coa::create([
                    'kode_akun' => $item['kode_akun'],
                    'nama_akun' => $item['nama_akun'],
                ]);
            }
        }
    }
}

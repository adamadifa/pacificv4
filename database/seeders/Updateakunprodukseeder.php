<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Updateakunprodukseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Produk::where('kode_produk', 'AB')->update(['kode_akun' => '4-1202']);
        \App\Models\Produk::where('kode_produk', 'AR')->update(['kode_akun' => '4-1205']);
        \App\Models\Produk::where('kode_produk', 'AS')->update(['kode_akun' => '4-1203']);
        \App\Models\Produk::where('kode_produk', 'BB')->update(['kode_akun' => '4-1102']);
        \App\Models\Produk::where('kode_produk', 'BP500')->update(['kode_akun' => '4-1114']);
        \App\Models\Produk::where('kode_produk', 'BR20')->update(['kode_akun' => '4-1501']);
        \App\Models\Produk::where('kode_produk', 'DEP')->update(['kode_akun' => '4-1107']);
        \App\Models\Produk::where('kode_produk', 'P1000')->update(['kode_akun' => '4-1113']);
        \App\Models\Produk::where('kode_produk', 'PP500')->update(['kode_akun' => '4-1116']);
        \App\Models\Produk::where('kode_produk', 'SC')->update(['kode_akun' => '4-1109']);
        \App\Models\Produk::where('kode_produk', 'SP')->update(['kode_akun' => '4-1108']);
        \App\Models\Produk::where('kode_produk', 'SP500')->update(['kode_akun' => '4-1112']);
        \App\Models\Produk::where('kode_produk', 'SP8')->update(['kode_akun' => '4-1110']);
        \App\Models\Produk::where('kode_produk', 'SS500')->update(['kode_akun' => '4-1115']);
    }
}

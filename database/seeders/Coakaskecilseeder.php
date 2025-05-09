<?php

namespace Database\Seeders;

use App\Models\Coakeaskecil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Coakaskecilseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //1-1102	Kas Kecil Bandung
        // 1-1103	Kas Kecil Bogor
        // 1-1111	Kas Kecil Pusat
        // 1-1112	Kas Kecil Tasik
        // 1-1113	Kas Kecil Sukabumi
        // 1-1114	Kas Kecil Pwt
        // 1-1115	Kas Kecil Tegal
        // 1-1116	Kas Kecil Surabaya
        // 1-1117	Kas Kecil Semarang
        // 1-1118	Kas Kecil Klaten
        // 1-1119	Kas Kecil Garut
        // 1-1120	Kas Kecil Purwakarta
        // 1-1121	Kas Kecil Banten
        // 1-1122	Kas Kecil Bekasi
        // 1-1123	Kas Kecil Tangerang
        if (!Coakeaskecil::where('kode_akun', '1-1102')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1102',
                'kode_cabang' => 'BDG'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1103')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1103',
                'kode_cabang' => 'BGR'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1111')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1111',
                'kode_cabang' => 'PST'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1112')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1112',
                'kode_cabang' => 'TSM'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1113')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1113',
                'kode_cabang' => 'SKB'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1114')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1114',
                'kode_cabang' => 'PWT'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1115')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1115',
                'kode_cabang' => 'TGL'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1116')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1116',
                'kode_cabang' => 'SBY'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1117')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1117',
                'kode_cabang' => 'SMR'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1118')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1118',
                'kode_cabang' => 'KLT'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1119')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1119',
                'kode_cabang' => 'GRT'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1120')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1120',
                'kode_cabang' => 'PWK'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1121')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1121',
                'kode_cabang' => 'BTN'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1122')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1122',
                'kode_cabang' => 'BKI'
            ]);
        }
        if (!Coakeaskecil::where('kode_akun', '1-1123')->exists()) {
            Coakeaskecil::create([
                'kode_akun' => '1-1123',
                'kode_cabang' => 'TGR'
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Updatecoapiutangcabang extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update kode_transaksi menjadi PCB dan kode_cabang sesuai data
        $coaData = [
            ['kode_akun' => '1-1495', 'kode_cabang' => 'TGR'],
            ['kode_akun' => '1-1468', 'kode_cabang' => 'TSM'],
            ['kode_akun' => '1-1405', 'kode_cabang' => 'TGL'],
            ['kode_akun' => '1-1486', 'kode_cabang' => 'SBY'],
            ['kode_akun' => '1-1407', 'kode_cabang' => 'SKB'],
            ['kode_akun' => '1-1492', 'kode_cabang' => 'PWK'],
            ['kode_akun' => '1-1404', 'kode_cabang' => 'PWT'],
            ['kode_akun' => '1-1488', 'kode_cabang' => 'SMR'],
            ['kode_akun' => '1-1402', 'kode_cabang' => 'BDG'],
            ['kode_akun' => '1-1408', 'kode_cabang' => 'BGR'],
            ['kode_akun' => '1-1490', 'kode_cabang' => 'KLT'],
            ['kode_akun' => '1-1493', 'kode_cabang' => 'BTN'],
            ['kode_akun' => '1-1494', 'kode_cabang' => 'BKI'],
            ['kode_akun' => '1-1487', 'kode_cabang' => 'GRT'],
        ];

        foreach ($coaData as $data) {
            \Illuminate\Support\Facades\DB::table('coa')
                ->where('kode_akun', $data['kode_akun'])
                ->update([
                    'kode_transaksi' => 'PCB',
                    'kode_cabang' => $data['kode_cabang']
                ]);
        }
    }
}

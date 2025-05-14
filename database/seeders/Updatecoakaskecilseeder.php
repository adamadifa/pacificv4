<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Updatecoakaskecilseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update kode_transaksi menjadi KKL dan kode_cabang sesuai data
        $coaData = [
            ['kode_akun' => '1-1102', 'kode_cabang' => 'BDG'],
            ['kode_akun' => '1-1121', 'kode_cabang' => 'BTN'],
            ['kode_akun' => '1-1122', 'kode_cabang' => 'BKI'],
            ['kode_akun' => '1-1103', 'kode_cabang' => 'BGR'],
            ['kode_akun' => '1-1119', 'kode_cabang' => 'GRT'],
            ['kode_akun' => '1-1118', 'kode_cabang' => 'KLT'],
            ['kode_akun' => '1-1120', 'kode_cabang' => 'PWK'],
            ['kode_akun' => '1-1111', 'kode_cabang' => 'PST'],
            ['kode_akun' => '1-1114', 'kode_cabang' => 'PWT'],
            ['kode_akun' => '1-1117', 'kode_cabang' => 'SMR'],
            ['kode_akun' => '1-1113', 'kode_cabang' => 'SKB'],
            ['kode_akun' => '1-1116', 'kode_cabang' => 'SBY'],
            ['kode_akun' => '1-1123', 'kode_cabang' => 'TGR'],
            ['kode_akun' => '1-1112', 'kode_cabang' => 'TSM'],
            ['kode_akun' => '1-1115', 'kode_cabang' => 'TGL'],
        ];

        foreach ($coaData as $data) {
            DB::table('coa')
                ->where('kode_akun', $data['kode_akun'])
                ->update([
                    'kode_transaksi' => 'KKL',
                    'kode_cabang' => $data['kode_cabang']
                ]);
        }
    }
}

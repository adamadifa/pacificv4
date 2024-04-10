<?php

namespace Database\Seeders;

use App\Models\Tujuanangkutan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Tujuanangkutanseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tujuanangkutan::create([
            'kode_tujuan' => 'BDG',
            'tujuan' => 'BANDUNG',
            'tarif' => 1050000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'PWT',
            'tujuan' => 'PURWOKERTO',
            'tarif' => 1225000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'BGR',
            'tujuan' => 'BOGOR',
            'tarif' => 1575000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'BTN',
            'tujuan' => 'BANTEN',
            'tarif' => 2500000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'SKB',
            'tujuan' => 'SUKABUMI',
            'tarif' => 1575000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'TGL',
            'tujuan' => 'TEGAL',
            'tarif' => 1700000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'PKL',
            'tujuan' => 'PEKALONGAN',
            'tarif' => 1900000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'SCD',
            'tujuan' => 'SURABAYA COL DESEL',
            'tarif' => 2500000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'STR',
            'tujuan' => 'SURABAYA TORONTON',
            'tarif' => 4750000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'CRB',
            'tujuan' => 'CIREBON',
            'tarif' => 1300000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'GRT',
            'tujuan' => 'GARUT',
            'tarif' => 800000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'DMK',
            'tujuan' => 'DEMAK',
            'tarif' => 2700000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'AMB',
            'tujuan' => 'AMBON',
            'tarif' => 2500000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'TGR',
            'tujuan' => 'TANGERANG',
            'tarif' => 1875000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'KLT',
            'tujuan' => 'KLATEN',
            'tarif' => 2700000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'KLP',
            'tujuan' => 'KALIPUCANG',
            'tarif' => 1000000
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'TSM',
            'tujuan' => 'TASIKMALAYA',
            'tarif' => 0
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'PWK',
            'tujuan' => 'PURWAKARTA',
            'tarif' => 0
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'PML',
            'tujuan' => 'PEMALANG',
            'tarif' => 0
        ]);

        Tujuanangkutan::create([
            'kode_tujuan' => 'BKI',
            'tujuan' => 'BEKASI',
            'tarif' => 0
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Angkutan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Angkutanseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Angkutan::create([
            'kode_angkutan' => 'A001',
            'nama_angkutan' => 'ANGKUTAN KS',
            'keterangan' => 'KS'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A002',
            'nama_angkutan' => 'ANGKUTAN KAWAN SWAKA',
            'keterangan' => 'KWN SUAKA'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A003',
            'nama_angkutan' => 'ANGKUTAN AS',
            'keterangan' => 'AS'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A004',
            'nama_angkutan' => 'ANGKUTAN SD',
            'keterangan' => 'SD'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A005',
            'nama_angkutan' => 'ANGKUTAN WAWAN',
            'keterangan' => 'WAWAN'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A006',
            'nama_angkutan' => 'ANGKUTAN RTP',
            'keterangan' => 'RTP'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A007',
            'nama_angkutan' => 'ANGKUTAN KWN GOBRAS',
            'keterangan' => 'KWN GOBRAS'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A008',
            'nama_angkutan' => 'ANGKUTAN LH',
            'keterangan' => 'LH'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A009',
            'nama_angkutan' => 'ANGKUTAN TSN',
            'keterangan' => 'TSN'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A010',
            'nama_angkutan' => 'ANGKUTAN MANDIRI',
            'keterangan' => 'MANDIRI'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A011',
            'nama_angkutan' => 'ANGKUTAN GS',
            'keterangan' => 'GS'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A012',
            'nama_angkutan' => 'ANGKUTAN CV TRESNO',
            'keterangan' => 'CV TRESNO'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A013',
            'nama_angkutan' => 'ANGKUTAN MSA',
            'keterangan' => 'MSA'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A014',
            'nama_angkutan' => 'ANGKUTAN MITRA KOMANDO',
            'keterangan' => 'MITRA KOMANDO'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A015',
            'nama_angkutan' => 'ANGKUTAN ARP MANDIRI',
            'keterangan' => 'ARP MANDIRI'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A016',
            'nama_angkutan' => 'ANGKUTAN CAHAYA BARU',
            'keterangan' => 'CAHAYA BARU'
        ]);

        Angkutan::create([
            'kode_angkutan' => 'A017',
            'nama_angkutan' => 'LAHANG JAYA ABADI',
            'keterangan' => 'LAHANG JAYA ABADI'
        ]);
    }
}

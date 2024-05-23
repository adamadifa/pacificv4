<?php

namespace Database\Seeders;

use App\Models\Jenisvoucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Jenisvoucherseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jenisvoucher::create([
            'id' => 1,
            'nama_voucher' => 'Penghapusan Piutang'
        ]);

        Jenisvoucher::create([
            'id' => 2,
            'nama_voucher' => 'Diskon Program'
        ]);

        Jenisvoucher::create([
            'id' => 3,
            'nama_voucher' => 'Penyelesaian Piutang Oleh Salesman'
        ]);

        Jenisvoucher::create([
            'id' => 4,
            'nama_voucher' => 'Pengalihan Piutang Dgng Jd Piutang Kary'
        ]);

        Jenisvoucher::create([
            'id' => 6,
            'nama_voucher' => 'Saus Premium TP 5-1'
        ]);

        Jenisvoucher::create([
            'id' => 5,
            'nama_voucher' => 'Lainnya'
        ]);

        Jenisvoucher::create([
            'id' => 7,
            'nama_voucher' => 'PPN KPBPB'
        ]);

        Jenisvoucher::create([
            'id' => 8,
            'nama_voucher' => 'PPN WAPU'
        ]);

        Jenisvoucher::create([
            'id' => 9,
            'nama_voucher' => 'PPH PASAL 22'
        ]);
    }
}

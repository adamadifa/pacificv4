<?php

namespace Database\Seeders;

use App\Models\Izinkoreksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Updatestatusizinkoreksiseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Izinkoreksi::where('status', 1)->update([
            'head' => 1,
            'hrd' => 1
        ]);

        Izinkoreksi::where('direktur', 1)->update([
            'forward_to_direktur' => 1
        ]);
    }
}

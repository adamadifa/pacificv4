<?php

namespace Database\Seeders;

use App\Models\Izinpulang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Updatestatusizinpulangseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Izinpulang::where('status', 1)->update([
            'head' => 1,
            'hrd' => 1
        ]);

        Izinpulang::where('direktur', 1)->update([
            'forward_to_direktur' => 1
        ]);
    }
}

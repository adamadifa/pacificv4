<?php

namespace Database\Seeders;

use App\Models\Izinkeluar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Updatestatusizinkeluarseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Izinkeluar::where('status', 1)->update([
            'head' => 1,
            'hrd' => 1
        ]);

        Izinkeluar::where('direktur', 1)->update([
            'forward_to_direktur' => 1
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Izinabsen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Updatestatusizinabsenseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Izinabsen::where('status', 1)->update([
            'head' => 1,
            'hrd' => 1
        ]);

        Izinabsen::where('direktur', 1)->update([
            'forward_to_direktur' => 1
        ]);
    }
}

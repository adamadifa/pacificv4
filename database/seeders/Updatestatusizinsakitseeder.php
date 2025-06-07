<?php

namespace Database\Seeders;

use App\Models\Izinsakit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Updatestatusizinsakitseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Izinsakit::where('status', 1)->update([
            'head' => 1,
            'hrd' => 1
        ]);

        Izinsakit::where('direktur', 1)->update([
            'forward_to_direktur' => 1
        ]);
    }
}

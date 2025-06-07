<?php

namespace Database\Seeders;

use App\Models\Izincuti;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Updatestatusizincutiseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Izincuti::where('status', 1)->update([
            'head' => 1,
            'hrd' => 1
        ]);

        Izincuti::where('direktur', 1)->update([
            'forward_to_direktur' => 1
        ]);
    }
}

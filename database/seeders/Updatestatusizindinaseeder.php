<?php

namespace Database\Seeders;

use App\Models\Izindinas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Updatestatusizindinaseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Izindinas::where('status', 1)->update([
            'head' => 1,
            'hrd' => 1
        ]);

        Izindinas::where('direktur', 1)->update([
            'forward_to_direktur' => 1
        ]);
    }
}

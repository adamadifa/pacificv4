<?php

namespace Database\Seeders;

use App\Models\Izinterlambat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Updatestatusizinterlambat extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Izinterlambat::where('status', 1)->update([
            'head' => 1,
            'hrd' => 1
        ]);

        Izinterlambat::where('direktur', 1)->update([
            'forward_to_direktur' => 1
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MktRewardProgram2026;
use App\Models\MktRewardProgramDetail2026;

class MktRewardProgram2026Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kode_program = 'PRIK004';
        
        // Ensure idempotency: Check if program reward already exists
        $program = MktRewardProgram2026::where('kode_program', $kode_program)->first();

        if (!$program) {
            $program = MktRewardProgram2026::create([
                'kode_program' => $kode_program,
                'keterangan' => 'Reward Scheme for PRIK004'
            ]);
        }

        // Clear existing details to ensure clean state if re-running (or just check if empty)
        // For idempotency with data updates, simpler to delete old details and re-insert, 
        // OR check unique constraint. Here I'll delete existing details for this program to ensure the latest data is applied.
        MktRewardProgramDetail2026::where('reward_id', $program->id)->delete();

        $details = [
            [
                'qty_dari' => 5,
                'qty_sampai' => 29,
                'reward_minus' => 500,
                'reward_tidak_minus' => 0,
                'reward_ach_target' => 1000
            ],
            [
                'qty_dari' => 30,
                'qty_sampai' => 49,
                'reward_minus' => 500,
                'reward_tidak_minus' => 750,
                'reward_ach_target' => 1250
            ],
            [
                'qty_dari' => 50,
                'qty_sampai' => 99,
                'reward_minus' => 500,
                'reward_tidak_minus' => 750,
                'reward_ach_target' => 1500
            ],
            [
                'qty_dari' => 100,
                'qty_sampai' => 299,
                'reward_minus' => 500,
                'reward_tidak_minus' => 0,
                'reward_ach_target' => 1750
            ],
            [
                'qty_dari' => 300,
                'qty_sampai' => 499,
                'reward_minus' => 0,
                'reward_tidak_minus' => 1000,
                'reward_ach_target' => 2000
            ],
            [
                'qty_dari' => 500,
                'qty_sampai' => 999999, // Representing > 500
                'reward_minus' => 0,
                'reward_tidak_minus' => 1000,
                'reward_ach_target' => 2250
            ],
        ];

        foreach ($details as $detail) {
            MktRewardProgramDetail2026::create(array_merge(['reward_id' => $program->id], $detail));
        }
    }
}

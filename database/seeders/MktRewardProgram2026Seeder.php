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
        $programs = [
            'PRIK004' => [
                'keterangan' => 'Reward Scheme for PRIK004',
                'details' => [
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
                        'qty_sampai' => 999999,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 1000,
                        'reward_ach_target' => 2250
                    ],
                ]
            ],
            'PRIK002' => [
                'keterangan' => 'Reward Scheme for PRIK002',
                'details' => [
                    [
                        'qty_dari' => 5,
                        'qty_sampai' => 29,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 3000,
                        'reward_ach_target' => 3500
                    ],
                    [
                        'qty_dari' => 30,
                        'qty_sampai' => 49,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 3000,
                        'reward_ach_target' => 4000
                    ],
                    [
                        'qty_dari' => 50,
                        'qty_sampai' => 99,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 3000,
                        'reward_ach_target' => 4500
                    ],
                    [
                        'qty_dari' => 100,
                        'qty_sampai' => 299,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 3000,
                        'reward_ach_target' => 5000
                    ],
                    [
                        'qty_dari' => 300,
                        'qty_sampai' => 499,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 3000,
                        'reward_ach_target' => 5500
                    ],
                    [
                        'qty_dari' => 500,
                        'qty_sampai' => 999999,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 3000,
                        'reward_ach_target' => 6000
                    ],
                ]
            ],
            'PRIK005' => [
                'keterangan' => 'Reward Scheme for PRIK005',
                'details' => [
                    [
                        'qty_dari' => 5,
                        'qty_sampai' => 29,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 750,
                        'reward_ach_target' => 1000
                    ],
                    [
                        'qty_dari' => 30,
                        'qty_sampai' => 49,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 750,
                        'reward_ach_target' => 1250
                    ],
                    [
                        'qty_dari' => 50,
                        'qty_sampai' => 99,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 750,
                        'reward_ach_target' => 1500
                    ],
                    [
                        'qty_dari' => 100,
                        'qty_sampai' => 299,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 1000,
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
                        'qty_sampai' => 999999,
                        'reward_minus' => 0,
                        'reward_tidak_minus' => 1000,
                        'reward_ach_target' => 2250,
                        'is_flat' => 0
                    ],
                ]
            ],
            'PRIK003' => [
                'keterangan' => 'Reward Scheme for PRIK003',
                'details' => [
                    [
                        'qty_dari' => 10,
                        'qty_sampai' => 29,
                        'reward_minus' => 2000,
                        'reward_tidak_minus' => 4000,
                        'reward_ach_target' => 6000,
                        'is_flat' => 0
                    ],
                    [
                        'qty_dari' => 30,
                        'qty_sampai' => 49,
                        'reward_minus' => 2000,
                        'reward_tidak_minus' => 4000,
                        'reward_ach_target' => 6000,
                        'is_flat' => 0
                    ],
                    [
                        'qty_dari' => 50,
                        'qty_sampai' => 99,
                        'reward_minus' => 2000,
                        'reward_tidak_minus' => 4000,
                        'reward_ach_target' => 6000,
                        'is_flat' => 0
                    ],
                    [
                        'qty_dari' => 100,
                        'qty_sampai' => 199,
                        'reward_minus' => 2000,
                        'reward_tidak_minus' => 4000,
                        'reward_ach_target' => 6000,
                        'is_flat' => 0
                    ],
                    [
                        'qty_dari' => 200,
                        'qty_sampai' => 299,
                        'reward_minus' => 400000,
                        'reward_tidak_minus' => 800000,
                        'reward_ach_target' => 1200000,
                        'is_flat' => 1
                    ],
                    [
                        'qty_dari' => 300,
                        'qty_sampai' => 499,
                        'reward_minus' => 400000,
                        'reward_tidak_minus' => 800000,
                        'reward_ach_target' => 1200000,
                        'is_flat' => 1
                    ],
                    [
                        'qty_dari' => 500,
                        'qty_sampai' => 999999,
                        'reward_minus' => 400000,
                        'reward_tidak_minus' => 800000,
                        'reward_ach_target' => 1200000,
                        'is_flat' => 1
                    ],
                ]
            ]
        ];

        foreach ($programs as $kode_program => $data) {
            $program = MktRewardProgram2026::where('kode_program', $kode_program)->first();

            if (!$program) {
                $program = MktRewardProgram2026::create([
                    'kode_program' => $kode_program,
                    'keterangan' => $data['keterangan']
                ]);
            }

            // Remove existing details to ensure clean slate and correct data
            MktRewardProgramDetail2026::where('reward_id', $program->id)->delete();

            foreach ($data['details'] as $detail) {
                MktRewardProgramDetail2026::create(array_merge(['reward_id' => $program->id], $detail));
            }
        }
    }
}

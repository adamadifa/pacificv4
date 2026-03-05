<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AjuanLimitConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('marketing_ajuan_limitkredit_config_approval')->truncate();

        $configs = [
            [
                'min_limit' => 0,
                'max_limit' => 5000000,
                'roles' => json_encode(['sales marketing manager']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'min_limit' => 5000001,
                'max_limit' => 10000000,
                'roles' => json_encode(['sales marketing manager', 'regional sales manager']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'min_limit' => 10000001,
                'max_limit' => 15000000,
                'roles' => json_encode(['sales marketing manager', 'regional sales manager', 'gm marketing']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'min_limit' => 15000001,
                'max_limit' => 999999999,
                'roles' => json_encode(['sales marketing manager', 'regional sales manager', 'gm marketing', 'direktur']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('marketing_ajuan_limitkredit_config_approval')->insert($configs);
    }
}

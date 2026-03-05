<?php

namespace Database\Seeders;

use App\Models\LemburApprovalConfig;
use Illuminate\Database\Seeder;

class LemburApprovalConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            [
                'kode_dept' => 'GAF',
                'kode_cabang' => 'PST',
                'roles' => ['manager general affair', 'gm operasional', 'asst. manager hrd', 'direktur']
            ],
            [
                'kode_dept' => 'MTC',
                'kode_cabang' => 'PST',
                'roles' => ['manager maintenance', 'gm operasional', 'asst. manager hrd', 'direktur']
            ],
            [
                'kode_dept' => 'PRD',
                'kode_cabang' => 'PST',
                'roles' => ['manager produksi', 'gm operasional', 'asst. manager hrd', 'direktur']
            ],
            [
                'kode_dept' => 'PDQ',
                'kode_cabang' => 'PST',
                'roles' => ['gm operasional', 'asst. manager hrd', 'direktur']
            ],
            [
                'kode_dept' => 'GDG',
                'kode_cabang' => 'PST',
                'roles' => ['manager gudang', 'gm operasional', 'asst. manager hrd', 'direktur']
            ],
            [
                'kode_dept' => 'HRD',
                'kode_cabang' => 'PST',
                'roles' => ['gm operasional', 'asst. manager hrd', 'direktur']
            ],
            [
                'kode_dept' => 'AKT',
                'kode_cabang' => 'PST',
                'roles' => ['asst. manager hrd', 'direktur']
            ],
            [
                'kode_dept' => 'MKT',
                'kode_cabang' => 'PST',
                'roles' => ['sales marketing manager', 'asst. manager hrd', 'direktur']
            ],
        ];

        foreach ($configs as $config) {
            LemburApprovalConfig::updateOrCreate(
                [
                    'kode_dept' => $config['kode_dept'],
                    'kode_cabang' => $config['kode_cabang']
                ],
                [
                    'roles' => $config['roles']
                ]
            );
        }
    }
}

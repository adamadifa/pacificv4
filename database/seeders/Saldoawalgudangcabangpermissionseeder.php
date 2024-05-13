<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class Saldoawalgudangcabangpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Saldo Awal Gudang Cabang'
        ]);

        Permission::create([
            'name' => 'sagudangcabang.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sagudangcabang.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sagudangcabang.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sagudangcabang.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sagudangcabang.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'sagudangcabang.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sagudangcabang.delete',
            'id_permission_group' => $permissiongroup->id
        ]);
    }
}

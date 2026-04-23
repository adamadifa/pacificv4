<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SaldoawalpiutangPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'sapiutang.index',
            'sapiutang.create',
            'sapiutang.store',
            'sapiutang.show',
            'sapiutang.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ], [
                'id_permission_group' => 73
            ]);
        }

        $role = Role::findByName('super admin', 'web');
        if ($role) {
            $role->givePermissionTo($permissions);
        }
    }
}

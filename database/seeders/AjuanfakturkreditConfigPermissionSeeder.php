<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AjuanfakturkreditConfigPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group = \App\Models\Permission_group::where('name', 'Ajuan Faktur Kredit')->first();
        $permissions = [
            'ajuanfaktur.config',
            'ajuanfaktur.edit',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::updateOrCreate([
                'name' => $permissionName
            ], [
                'name' => $permissionName,
                'guard_name' => 'web',
                'id_permission_group' => $group->id
            ]);

            $superAdmin = Role::where('name', 'super admin')->first();
            if ($superAdmin) {
                $superAdmin->givePermissionTo($permission);
            }
        }
    }
}

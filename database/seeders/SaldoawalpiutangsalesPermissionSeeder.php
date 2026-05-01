<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SaldoawalpiutangsalesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'sapiutangsales.index',
            'sapiutangsales.create',
            'sapiutangsales.show',
            'sapiutangsales.delete',
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

        // Optional: Add to other roles if needed, e.g. admin marketing
        $role_marketing = Role::where('name', 'admin marketing')->where('guard_name', 'web')->first();
        if ($role_marketing) {
            $role_marketing->givePermissionTo($permissions);
        }
    }
}

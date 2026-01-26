<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MonitoringProgramIkatan2026PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::firstOrCreate([
            'name' => 'Program Ikatan 2026'
        ]);

        $permissions = [
            'programikatan2026.monitoring',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'id_permission_group' => $permissiongroup->id
            ]);
        }

        $roles = [
            'super admin',
            'operation manager',
            'regional sales manager',
            'gm marketing',
            'direktur',
            'sales marketing manager'
        ];

        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($permissions);
            }
        }
    }
}

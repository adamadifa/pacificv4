<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProgramIkatan2026PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::firstOrCreate([
            'name' => 'Program Ikatan 2026'
        ]);

        $permissions = [
            'programikatan2026.index',
            'programikatan2026.create',
            'programikatan2026.store',
            'programikatan2026.edit',
            'programikatan2026.update',
            'programikatan2026.delete',
            'programikatan2026.show',
            'programikatan2026.approve',
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
            'direktur'
        ];

        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($permissions);
            }
        }
    }
}

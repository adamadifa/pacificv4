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
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
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
            Permission::create([
                'name' => $permission,
                'id_permission_group' => $permissiongroup->id
            ]);
        }

        $createdPermissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1; // Super Admin
        $role = Role::findById($roleID);
        $role->givePermissionTo($createdPermissions);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProgrammarketingPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Program Marketing'
        ]);

        Permission::create([
            'name' => 'programmarketing.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'programmarketing.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'programmarketing.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'programmarketing.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'programmarketing.update',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'programmarketing.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

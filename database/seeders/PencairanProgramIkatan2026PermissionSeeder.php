<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PencairanProgramIkatan2026PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Pencairan Program Ikatan 2026'
        ]);

        Permission::create([
            'name' => 'pencairanprogramikatan2026.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pencairanprogramikatan2026.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pencairanprogramikatan2026.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pencairanprogramikatan2026.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pencairanprogramikatan2026.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'pencairanprogramikatan2026.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pencairanprogramikatan2026.upload',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pencairanprogramikatan2026.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

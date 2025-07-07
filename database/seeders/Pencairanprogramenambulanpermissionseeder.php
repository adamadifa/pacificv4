<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Pencairanprogramenambulanpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Pencairan Program Enambulan'
        ]);

        Permission::create([
            'name' => 'pencairanprogramenambulan.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pencairanprogramenambulan.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pencairanprogramenambulan.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pencairanprogramenambulan.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pencairanprogramenambulan.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'pencairanprogramenambulan.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pencairanprogramenambulan.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

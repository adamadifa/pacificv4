<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Kesepakatanbersamapermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Kesepakatan Bersama'
        ]);

        Permission::create([
            'name' => 'kb.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kb.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kb.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kb.update',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kb.delete',
            'id_permission_group' => $permissiongroup->id
        ]);


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

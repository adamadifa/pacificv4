<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Kasbonpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissiongroup = Permission_group::create([
            'name' => 'Kasbon'
        ]);

        Permission::create([
            'name' => 'kasbon.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kasbon.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kasbon.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kasbon.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kasbon.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'kasbon.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kasbon.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Transitinpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Transit IN'
        ]);

        Permission::create([
            'name' => 'transitin.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'transitin.create',
            'id_permission_group' => $permissiongroup->id
        ]);



        Permission::create([
            'name' => 'transitin.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'transitin.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'transitin.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

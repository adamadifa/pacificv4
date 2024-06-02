<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Returpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Retur'
        ]);

        Permission::create([
            'name' => 'retur.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'retur.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'retur.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'retur.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'retur.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'retur.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'retur.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

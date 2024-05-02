<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Driverhelperpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Driver Helper'
        ]);

        Permission::create([
            'name' => 'driverhelper.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'driverhelper.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'driverhelper.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'driverhelper.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'driverhelper.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'driverhelper.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'driverhelper.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

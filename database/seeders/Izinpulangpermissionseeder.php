<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Izinpulangpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Izin Pulang'
        ]);

        Permission::create([
            'name' => 'izinpulang.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinpulang.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinpulang.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinpulang.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinpulang.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'izinpulang.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinpulang.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinpulang.approve',
            'id_permission_group' => $permissiongroup->id
        ]);


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

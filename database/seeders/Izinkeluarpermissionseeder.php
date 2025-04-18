<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Izinkeluarpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissiongroup = Permission_group::create([
            'name' => 'Izin Keluar'
        ]);

        Permission::create([
            'name' => 'izinkeluar.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkeluar.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkeluar.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkeluar.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkeluar.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'izinkeluar.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkeluar.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkeluar.approve',
            'id_permission_group' => $permissiongroup->id
        ]);


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

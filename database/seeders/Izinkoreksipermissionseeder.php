<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Izinkoreksipermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissiongroup = Permission_group::create([
            'name' => 'Izin Koreksi'
        ]);

        Permission::create([
            'name' => 'izinkoreksi.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkoreksi.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkoreksi.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkoreksi.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkoreksi.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'izinkoreksi.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkoreksi.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izinkoreksi.approve',
            'id_permission_group' => $permissiongroup->id
        ]);


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

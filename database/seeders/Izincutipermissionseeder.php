<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Izincutipermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissiongroup = Permission_group::create([
            'name' => 'Izin Cuti'
        ]);

        Permission::create([
            'name' => 'izincuti.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izincuti.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izincuti.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izincuti.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izincuti.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'izincuti.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izincuti.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'izincuti.approve',
            'id_permission_group' => $permissiongroup->id
        ]);


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

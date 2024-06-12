<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Setorantransferpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Setoran Transfer'
        ]);

        Permission::create([
            'name' => 'setorantransfer.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'setorantransfer.create',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'setorantransfer.store',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'setorantransfer.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'setorantransfer.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

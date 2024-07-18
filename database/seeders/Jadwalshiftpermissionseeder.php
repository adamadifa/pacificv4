<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Jadwalshiftpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissiongroup = Permission_group::create([
            'name' => 'Jadwal Shift'
        ]);

        Permission::create([
            'name' => 'jadwalshift.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'jadwalshift.create',
            'id_permission_group' => $permissiongroup->id
        ]);



        Permission::create([
            'name' => 'jadwalshift.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'jadwalshift.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'jadwalshift.update',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'jadwalshift.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

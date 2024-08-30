<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Klaimkaskecilpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Klaim Kas Kecil'
        ]);

        Permission::create([
            'name' => 'klaimkaskecil.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'klaimkaskecil.create',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'klaimkaskecil.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'klaimkaskecil.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'klaimkaskecil.proses',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'klaimkaskecil.approve',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

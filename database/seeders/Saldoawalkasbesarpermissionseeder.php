<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Saldoawalkasbesarpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Saldo Awal Kas Besar'
        ]);

        Permission::create([
            'name' => 'sakasbesar.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sakasbesar.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sakasbesar.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sakasbesar.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sakasbesar.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'sakasbesar.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sakasbesar.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

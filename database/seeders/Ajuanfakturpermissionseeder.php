<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Ajuanfakturpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Ajuan Faktur Kredit'
        ]);

        Permission::create([
            'name' => 'ajuanfaktur.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuanfaktur.create',
            'id_permission_group' => $permissiongroup->id
        ]);



        Permission::create([
            'name' => 'ajuanfaktur.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuanfaktur.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'ajuanfaktur.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuanfaktur.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuanfaktur.approve',
            'id_permission_group' => $permissiongroup->id
        ]);


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

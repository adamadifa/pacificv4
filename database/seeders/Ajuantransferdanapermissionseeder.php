<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Ajuantransferdanapermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Ajuan Transfer Dana'
        ]);

        Permission::create([
            'name' => 'ajuantransfer.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuantransfer.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuantransfer.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuantransfer.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuantransfer.update',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'ajuantransfer.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuantransfer.approve',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuantransfer.proses',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

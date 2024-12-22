<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission_group;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Ajuanprogramkumulatifpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Ajuan Program Kumulatif'
        ]);

        Permission::create([
            'name' => 'ajuankumulatif.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuankumulatif.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuankumulatif.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuankumulatif.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuankumulatif.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'ajuankumulatif.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ajuankumulatif.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

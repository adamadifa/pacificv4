<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Coacabangpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'COA Cabang'
        ]);

        Permission::create([
            'name' => 'coacabang.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'coacabang.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'coacabang.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'coacabang.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'coacabang.update',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'coacabang.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Mutasibankpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Mutasi Bank'
        ]);

        Permission::create([
            'name' => 'mutasibank.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'mutasibank.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'mutasibank.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'mutasibank.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'mutasibank.update',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'mutasibank.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

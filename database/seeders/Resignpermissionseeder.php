<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Resignpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Resign'
        ]);

        Permission::create([
            'name' => 'resign.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'resign.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'resign.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'resign.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'resign.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'resign.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'resign.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

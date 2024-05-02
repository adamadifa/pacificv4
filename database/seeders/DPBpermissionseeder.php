<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DPBpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'DPB'
        ]);

        Permission::create([
            'name' => 'dpb.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'dpb.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'dpb.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'dpb.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'dpb.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'dpb.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'dpb.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

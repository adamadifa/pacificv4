<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Repackpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Repack Gudang Cabang'
        ]);

        Permission::create([
            'name' => 'repackcbg.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'repackcbg.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'repackcbg.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'repackcbg.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'repackcbg.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'repackcbg.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'repackcbg.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

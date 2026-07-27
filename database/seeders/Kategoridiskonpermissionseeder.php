<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Kategoridiskonpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::firstOrCreate([
            'name' => 'kategoridiskon'
        ]);

        Permission::firstOrCreate([
            'name' => 'kategoridiskon.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::firstOrCreate([
            'name' => 'kategoridiskon.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::firstOrCreate([
            'name' => 'kategoridiskon.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::firstOrCreate([
            'name' => 'kategoridiskon.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::firstOrCreate([
            'name' => 'kategoridiskon.update',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::firstOrCreate([
            'name' => 'kategoridiskon.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::firstOrCreate([
            'name' => 'kategoridiskon.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1; // Super admin
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

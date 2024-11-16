<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Penyesuaianupahpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Penyesuaian Upah'
        ]);

        Permission::create([
            'name' => 'penyupah.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'penyupah.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'penyupah.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'penyupah.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'penyupah.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'penyupah.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'penyupah.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Suratjalanangkutanpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Surat Jalan Angkutan'
        ]);

        Permission::create([
            'name' => 'suratjalanangkutan.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalanangkutan.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalanangkutan.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalanangkutan.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalanangkutan.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'suratjalanangkutan.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalanangkutan.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'suratjalanangkutan.approve',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

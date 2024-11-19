<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Monitoringprogrampermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Monitoring Program'
        ]);

        Permission::create([
            'name' => 'monitoringprogram.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'monitoringprogram.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'monitoringprogram.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'monitoringprogram.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'monitoringprogram.update',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'monitoringprogram.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

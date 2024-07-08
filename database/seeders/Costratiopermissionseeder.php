<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Costratiopermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Cost Ratio'
        ]);

        Permission::create([
            'name' => 'costratio.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'costratio.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'costratio.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'costratio.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'costratio.update',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'costratio.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

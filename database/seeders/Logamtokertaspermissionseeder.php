<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Logamtokertaspermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Logam to Kertas'
        ]);

        Permission::create([
            'name' => 'logamtokertas.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'logamtokertas.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'logamtokertas.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'logamtokertas.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'logamtokertas.update',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'logamtokertas.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

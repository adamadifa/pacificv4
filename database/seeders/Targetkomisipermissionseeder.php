<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Targetkomisipermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Target Komisi'
        ]);

        Permission::create([
            'name' => 'targetkomisi.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'targetkomisi.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'targetkomisi.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'targetkomisi.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'targetkomisi.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'targetkomisi.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'targetkomisi.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'targetkomisi.approve',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

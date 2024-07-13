<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Badstokgapermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Bad Stok GA'
        ]);

        Permission::create([
            'name' => 'badstokga.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'badstokga.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'badstokga.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'badstokga.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'badstokga.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'badstokga.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'badstokga.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Kontrabonpembelianpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Kontrabon Pembelian'
        ]);

        Permission::create([
            'name' => 'kontrabonpmb.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kontrabonpmb.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kontrabonpmb.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kontrabonpmb.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kontrabonpmb.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'kontrabonpmb.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kontrabonpmb.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kontrabonpmb.approve',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kontrabonpmb.proses',
            'id_permission_group' => $permissiongroup->id
        ]);




        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

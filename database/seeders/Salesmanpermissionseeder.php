<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Salesmanpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'SFA'
        ]);

        Permission::create([
            'name' => 'sfa.pelanggan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sfa.penjualan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sfa.retur',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sfa.limitkredit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sfa.ajuanfaktur',
            'id_permission_group' => $permissiongroup->id
        ]);




        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 20;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

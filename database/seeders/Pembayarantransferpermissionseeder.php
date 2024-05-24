<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Pembayarantransferpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Pembayaran Transfer'
        ]);

        Permission::create([
            'name' => 'pembayarantransfer.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarantransfer.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarantransfer.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarantransfer.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarantransfer.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'pembayarantransfer.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarantransfer.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

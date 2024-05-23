<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Pembayarangiropermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Pembayaran Giro'
        ]);

        Permission::create([
            'name' => 'pembayarangiro.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarangiro.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarangiro.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarangiro.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarangiro.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'pembayarangiro.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarangiro.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

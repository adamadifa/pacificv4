<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Pembayarankasbonpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Pembayaran Kasbon'
        ]);

        Permission::create([
            'name' => 'pembayarankasbon.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarankasbon.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarankasbon.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarankasbon.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarankasbon.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'pembayarankasbon.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pembayarankasbon.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

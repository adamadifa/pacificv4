<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Barangkeluargudanglogistikpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Barang Keluar Gudang Logistik'
        ]);

        Permission::create([
            'name' => 'barangkeluargl.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangkeluargl.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangkeluargl.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangkeluargl.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangkeluargl.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'barangkeluargl.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangkeluargl.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

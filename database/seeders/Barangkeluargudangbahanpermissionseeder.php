<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\PermissionGroup;

class Barangkeluargudangbahanpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Barang Keluar Gudang Bahan'
        ]);

        Permission::create([
            'name' => 'barangkeluargb.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangkeluargb.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangkeluargb.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangkeluargb.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangkeluargb.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'barangkeluargb.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangkeluargb.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

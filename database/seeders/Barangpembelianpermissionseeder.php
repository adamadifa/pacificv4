<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Barangpembelianpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Barang Pembelian'
        ]);

        Permission::create([
            'name' => 'barangpembelian.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangpembelian.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangpembelian.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangpembelian.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangpembelian.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'barangpembelian.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangpembelian.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

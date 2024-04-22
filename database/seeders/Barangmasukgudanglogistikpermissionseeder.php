<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Barangmasukgudanglogistikpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Barang Masuk Gudang Logistik'
        ]);

        Permission::create([
            'name' => 'barangmasukgl.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangmasukgl.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangmasukgl.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangmasukgl.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangmasukgl.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'barangmasukgl.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangmasukgl.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

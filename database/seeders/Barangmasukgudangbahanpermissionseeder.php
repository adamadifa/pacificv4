<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Barangmasukgudangbahanpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Barang Masuk Gudang Bahan'
        ]);

        Permission::create([
            'name' => 'barangmasukgb.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangmasukgb.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangmasukgb.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangmasukgb.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangmasukgb.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'barangmasukgb.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'barangmasukgb.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

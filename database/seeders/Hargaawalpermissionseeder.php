<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Hargaawalpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Harga Awal HPP'
        ]);

        Permission::create([
            'name' => 'hargaawalhpp.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'hargaawalhpp.create',
            'id_permission_group' => $permissiongroup->id
        ]);



        Permission::create([
            'name' => 'hargaawalhpp.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'hargaawalhpp.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'hargaawalhpp.update',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'hargaawalhpp.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

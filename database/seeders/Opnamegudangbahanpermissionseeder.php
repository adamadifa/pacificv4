<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Opnamegudangbahanpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Opname Gudang Bahan'
        ]);

        Permission::create([
            'name' => 'opgudangbahan.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'opgudangbahan.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'opgudangbahan.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'opgudangbahan.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'opgudangbahan.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'opgudangbahan.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'opgudangbahan.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

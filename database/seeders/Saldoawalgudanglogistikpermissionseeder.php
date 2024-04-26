<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Saldoawalgudanglogistikpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Saldo Awal Gudang Logistik'
        ]);

        Permission::create([
            'name' => 'sagudanglogistik.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sagudanglogistik.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sagudanglogistik.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sagudanglogistik.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sagudanglogistik.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'sagudanglogistik.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'sagudanglogistik.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

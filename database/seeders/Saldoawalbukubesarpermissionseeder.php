<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Saldoawalbukubesarpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Saldo Awal Buku Besar'
        ]);

        Permission::create([
            'name' => 'saldoawalbukubesar.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'saldoawalbukubesar.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'saldoawalbukubesar.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'saldoawalbukubesar.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'saldoawalbukubesar.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'saldoawalbukubesar.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'saldoawalbukubesar.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

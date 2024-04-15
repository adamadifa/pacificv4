<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Rejectgudangjadipermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Reject Gudang Jadi'
        ]);

        Permission::create([
            'name' => 'rejectgudangjadi.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'rejectgudangjadi.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'rejectgudangjadi.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'rejectgudangjadi.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'rejectgudangjadi.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'rejectgudangjadi.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'rejectgudangjadi.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

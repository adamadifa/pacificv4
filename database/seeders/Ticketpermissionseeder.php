<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Ticketpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'ticket'
        ]);

        Permission::create([
            'name' => 'ticket.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ticket.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ticket.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ticket.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ticket.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'ticket.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ticket.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'ticket.approve',
            'id_permission_group' => $permissiongroup->id
        ]);


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

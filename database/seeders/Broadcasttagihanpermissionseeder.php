<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Broadcasttagihanpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Broadcast Tagihan'
        ]);

        Permission::create([
            'name' => 'broadcasttagihan.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'broadcasttagihan.send',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        
        // Asosiasikan ke role super admin (ID 1 atau name 'super admin')
        $role = Role::where('name', 'super admin')->first();
        if ($role) {
            $role->givePermissionTo($permissions);
        }
    }
}

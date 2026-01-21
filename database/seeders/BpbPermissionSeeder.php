<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BpbPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::firstOrCreate([
            'name' => 'BPB'
        ]);

        $permissions = [
            'bpb.index',
            'bpb.create',
            'bpb.edit',
            'bpb.update',
            'bpb.store',
            'bpb.delete',
            'bpb.show',
            'bpb.storeapprove',
            'bpb.serahterimabpbstore',
            'bpb.updateSerahTerima',
            'bpb.deleteSerahTerima'
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'id_permission_group' => $permissiongroup->id
            ]);
        }

        $allPermissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        
        // Assign to Super Admin (Role ID 1 usually)
        $roleID = 1;
        $role = Role::findById($roleID);
        if ($role) {
            $role->givePermissionTo($allPermissions);
        }
    }
}

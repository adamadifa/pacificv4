<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RealtimetrackingPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group = Permission_group::where('name', 'Realtime Tracking')->first();
        if (!$group) {
            $group = Permission_group::create(['name' => 'Realtime Tracking']);
        }

        $permission = Permission::updateOrCreate(
            ['name' => 'tracking.index'],
            [
                'name' => 'tracking.index',
                'id_permission_group' => $group->id,
                'guard_name' => 'web'
            ]
        );

        // Assign permission to super admin (Role ID 1)
        $role = Role::findById(1);
        if ($role) {
            $role->givePermissionTo($permission);
        }
    }
}

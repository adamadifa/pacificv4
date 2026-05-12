<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Permission_group;

class TrackingtruckPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $group = Permission_group::where('name', 'Surat Jalan')->first();
        if (!$group) {
             $group = Permission_group::create(['name' => 'Surat Jalan']);
        }

        Permission::updateOrCreate(['name' => 'trackingtruck.index'], [
            'name' => 'trackingtruck.index',
            'id_permission_group' => $group->id,
            'guard_name' => 'web'
        ]);

        $role = Role::findByName('super admin');
        $role->givePermissionTo('trackingtruck.index');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission_group;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AlasankoreksiPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group = Permission_group::where('name', 'Izin Koreksi')->first();
        $permissions = [
            'alasankoreksi.index',
            'alasankoreksi.create',
            'alasankoreksi.store',
            'alasankoreksi.edit',
            'alasankoreksi.update',
            'alasankoreksi.delete',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::updateOrCreate([
                'name' => $permissionName
            ], [
                'name' => $permissionName,
                'guard_name' => 'web',
                'id_permission_group' => $group->id
            ]);

            // Assign to Super Admin
            $superAdmin = Role::where('name', 'super admin')->first();
            if ($superAdmin) {
                $superAdmin->givePermissionTo($permission);
            }
        }
    }
}

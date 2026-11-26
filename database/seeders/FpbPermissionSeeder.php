<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Permission_group;

class FpbPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $group = Permission_group::firstOrCreate(['name' => 'FPB']);

        $permissions = [
            'fpb.index',
            'fpb.create',
            'fpb.store',
            'fpb.edit',
            'fpb.update',
            'fpb.delete',
        ];

        $superAdmin = Role::where('name', 'super admin')->first();

        foreach ($permissions as $permName) {
            $permission = Permission::firstOrCreate(
                ['name' => $permName],
                ['id_permission_group' => $group->id]
            );

            if ($superAdmin && !$superAdmin->hasPermissionTo($permission)) {
                $superAdmin->givePermissionTo($permission);
            }
        }
    }
}

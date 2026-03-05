<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AjuanLimitConfigPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $group = Permission_group::where('name', 'Ajuan Limit Kredit')->first();
        $permissions = [
            'ajuanlimit.config',
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

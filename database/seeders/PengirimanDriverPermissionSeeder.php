<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Permission_group;

class PengirimanDriverPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $group = Permission_group::firstOrCreate(['name' => 'Pengiriman Driver']);

        $permissions = [
            'pengirimandriver.index',
            'pengirimandriver.create',
            'pengirimandriver.store',
            'pengirimandriver.edit',
            'pengirimandriver.update',
            'pengirimandriver.delete',
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

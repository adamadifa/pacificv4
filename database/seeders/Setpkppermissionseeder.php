<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Setpkppermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::where('name', 'Penjualan')->first();

        if ($permissiongroup) {
            $permission = Permission::updateOrCreate(
                ['name' => 'penjualan.setpkp'],
                ['id_permission_group' => $permissiongroup->id]
            );

            $role = Role::findById(1);
            if ($role) {
                $role->givePermissionTo($permission);
            }
        }
    }
}

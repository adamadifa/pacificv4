<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Aruskaspermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::where('name', 'Laporan Accounting')->first();
        if (!$permissiongroup) {
            $permissiongroup = Permission_group::create([
                'name' => 'Laporan Accounting'
            ]);
        }

        $permission = Permission::firstOrCreate([
            'name' => 'akt.aruskas',
        ], [
            'id_permission_group' => $permissiongroup->id
        ]);

        if (empty($permission->id_permission_group)) {
            $permission->update(['id_permission_group' => $permissiongroup->id]);
        }

        // Berikan permission hanya ke super admin dan gm administrasi
        $roles = [
            'super admin',
            'gm administrasi',
        ];

        $roleIds = Role::whereIn('name', $roles)->pluck('id');
        $permission->syncRoles($roleIds);
    }
}


<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Dokumenopnamepermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::firstOrCreate([
            'name' => 'Tujuan Angkutan'
        ]);

        $permission = Permission::firstOrCreate([
            'name' => 'worksheetom.dokumenopname',
            'id_permission_group' => $permissiongroup->id
        ]);

        $roleAdmin = Role::where('name', 'super admin')->first();
        if ($roleAdmin) {
            $roleAdmin->givePermissionTo($permission);
        }

        $roleOM = Role::where('name', 'operation manager')->first();
        if ($roleOM) {
            $roleOM->givePermissionTo($permission);
        }
    }
}

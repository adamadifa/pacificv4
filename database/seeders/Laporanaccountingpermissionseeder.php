<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Laporanaccountingpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Laporan Accounting'
        ]);

        $permissions = [
            'akt.rekapbj',
            'akt.rekappersediaan',
            'akt.costratio',
            'akt.jurnalumum',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'id_permission_group' => $permissiongroup->id
            ]);
        }



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Worksheetompermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Tujuan Angkutan'
        ]);

        Permission::create([
            'name' => 'worksheetom.oman',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.komisisalesman',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.insentifom',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.komisidriverhelper',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.costratio',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.visitpelanggan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.monitoringretur',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.monitoringprogram',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.kebutuhancabang',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.produkexpired',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.evaluasisharing',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.bbm',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'worksheetom.ratiobs',
            'id_permission_group' => $permissiongroup->id
        ]);


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

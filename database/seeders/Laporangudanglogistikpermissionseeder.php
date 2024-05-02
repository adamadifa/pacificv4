<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Laporangudanglogistikpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Laporan Gudang Logistik'
        ]);

        Permission::create([
            'name' => 'gl.barangmasuk',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gl.barangkeluar',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gl.persediaan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gl.persediaanaopname',
            'id_permission_group' => $permissiongroup->id
        ]);




        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

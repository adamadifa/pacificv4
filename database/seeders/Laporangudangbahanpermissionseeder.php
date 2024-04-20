<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Laporangudangbahanpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Laporan Gudang Bahan'
        ]);

        Permission::create([
            'name' => 'gb.barangmasuk',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gb.barangkeluar',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gb.persediaan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gb.rekappersediaan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gb.kartugudang',
            'id_permission_group' => $permissiongroup->id
        ]);


        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

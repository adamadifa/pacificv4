<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Laporangudangcabangpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Laporan Gudang Cabang'
        ]);

        Permission::create([
            'name' => 'gc.goodstok',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gc.badstok',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gc.rekappersediaan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gc.mutasidpb',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'gc.monitoringretur',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'gc.rekonsiliasibj',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

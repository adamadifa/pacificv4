<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Laporanpembelianpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissiongroup = Permission_group::create([
            'name' => 'Laporan Pembelian'
        ]);

        Permission::create([
            'name' => 'pb.pembelian',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pb.rekapsupplier',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'pb.rekappembelian',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pb.kartuhutang',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pb.auh',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pb.bahankemasan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pb.rekapbahankemasan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pb.jurnalkoreksi',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pb.rekapakun',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'pb.rekapkontrabon',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

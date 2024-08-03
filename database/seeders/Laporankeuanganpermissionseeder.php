<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Laporankeuanganpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $permissiongroup = Permission_group::create([
            'name' => 'Laporan Keuangan'
        ]);

        Permission::create([
            'name' => 'keu.kaskecil',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.ledger',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.saldokasbesar',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.lpu',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.penjualan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.uanglogam',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.rekapbg',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.pinjaman',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.kartupinjaman',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.kasbon',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.kartukasbon',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.piutangkaryawan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.kartupiutangkaryawan',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'keu.rekapkartupiutang',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

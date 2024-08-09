<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Laporanpenjualanpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Laporan Marketing'
        ]);

        $permissions = [
            'mkt.penjualan',
            'mkt.kasbesar',
            'mkt.retur',
            'mkt.kartupiutang',
            'mkt.aup',
            'mkt.lebihsatufaktur',
            'mkt.dppp',
            'mkt.dpp',
            'mkt.omsetpelanggan',
            'mkt.rekappelanggan',
            'mkt.rekappenjualan',
            'mkt.rekapkendaraan',
            'mkt.harganet',
            'mkt.tandaterimafaktur',
            'mkt.rekapwilayah',
            'mkt.effectivecall',
            'mkt.analisatransaksi',
            'mkt.tunaitransfer',
            'mkt.lhp',
            'mkt.routingsalesman',
            'mkt.salesperfomance',
            'mkt.persentasesfa',
            'mkt.smmactivity',
            'mkt.rsmactivity',
            'mkt.komisisalesman',
            'mkt.komisidriverhelper'
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

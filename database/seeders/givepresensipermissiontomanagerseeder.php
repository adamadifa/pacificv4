<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class givepresensipermissiontomanagerseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'presensi.index',
            'presensi.koreksi',

            'izinabsen.index',
            'izinabsen.create',
            'izinabsen.edit',
            'izinabsen.store',
            'izinabsen.update',
            'izinabsen.show',
            'izinabsen.delete',
            'izinabsen.approve',

            'izincuti.index',
            'izincuti.create',
            'izincuti.edit',
            'izincuti.store',
            'izincuti.update',
            'izincuti.show',
            'izincuti.delete',
            'izincuti.approve',

            'izinsakit.index',
            'izinsakit.create',
            'izinsakit.edit',
            'izinsakit.store',
            'izinsakit.update',
            'izinsakit.show',
            'izinsakit.delete',
            'izinsakit.approve',

            'izinkeluar.index',
            'izinkeluar.create',
            'izinkeluar.edit',
            'izinkeluar.store',
            'izinkeluar.update',
            'izinkeluar.show',
            'izinkeluar.delete',
            'izinkeluar.approve',

            'izinterlambat.index',
            'izinterlambat.create',
            'izinterlambat.edit',
            'izinterlambat.store',
            'izinterlambat.update',
            'izinterlambat.show',
            'izinterlambat.delete',
            'izinterlambat.approve',

            'izinpulang.index',
            'izinpulang.create',
            'izinpulang.edit',
            'izinpulang.store',
            'izinpulang.update',
            'izinpulang.show',
            'izinpulang.delete',
            'izinpulang.approve',

            'izindinas.index',
            'izindinas.create',
            'izindinas.edit',
            'izindinas.store',
            'izindinas.update',
            'izindinas.show',
            'izindinas.delete',
            'izindinas.approve',

            'izinkoreksi.index',
            'izinkoreksi.create',
            'izinkoreksi.edit',
            'izinkoreksi.store',
            'izinkoreksi.update',
            'izinkoreksi.show',
            'izinkoreksi.delete',
            'izinkoreksi.approve',

        ];

        $roles = [
            'regional sales manager',
            'operation manager',
            'manager keuangan',
            'manager general affair',
            'manager pembelian',
            'sales marketing manager',
            'asst. manager hrd',
            'regional operation manager',
            'manager produksi',
            'manager gudang',
            'manager maintenance',
            'manager hrd',
            'manager audit',
            'gm marketing',
            'gm administrasi',
            'gm operasional',
            'direktur'
        ];
        // Daftar role yang akan diberikan permission


        foreach ($roles as $roleName) {
            // Membuat role jika belum ada
            $role = Role::firstOrCreate(['name' => $roleName]);

            // Memberikan permission ke setiap role
            $role->givePermissionTo($permissions);
        }
    }
}

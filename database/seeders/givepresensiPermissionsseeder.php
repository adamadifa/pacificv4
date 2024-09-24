<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class givepresensiPermissionsseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat permission
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

            'izincuti.index',
            'izincuti.create',
            'izincuti.edit',
            'izincuti.store',
            'izincuti.update',
            'izincuti.show',
            'izincuti.delete',

            'izinsakit.index',
            'izinsakit.create',
            'izinsakit.edit',
            'izinsakit.store',
            'izinsakit.update',
            'izinsakit.show',
            'izinsakit.delete',

            'izinkeluar.index',
            'izinkeluar.create',
            'izinkeluar.edit',
            'izinkeluar.store',
            'izinkeluar.update',
            'izinkeluar.show',
            'izinkeluar.delete',

            'izinterlambat.index',
            'izinterlambat.create',
            'izinterlambat.edit',
            'izinterlambat.store',
            'izinterlambat.update',
            'izinterlambat.show',
            'izinterlambat.delete',

            'izinpulang.index',
            'izinpulang.create',
            'izinpulang.edit',
            'izinpulang.store',
            'izinpulang.update',
            'izinpulang.show',
            'izinpulang.delete',

            'izindinas.index',
            'izindinas.create',
            'izindinas.edit',
            'izindinas.store',
            'izindinas.update',
            'izindinas.show',
            'izindinas.delete',

            'izinkoreksi.index',
            'izinkoreksi.create',
            'izinkoreksi.edit',
            'izinkoreksi.store',
            'izinkoreksi.update',
            'izinkoreksi.show',
            'izinkoreksi.delete',

        ];


        $userIds = [
            3
        ];

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            // Memberikan permission langsung ke setiap user
            $user->givePermissionTo($permissions);
        }
    }
}

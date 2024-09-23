<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class givehariliburpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'harilibur.index',
            'harilibur.create',
            'harilibur.edit',
            'harilibur.update',
            'harilibur.store',
            'harilibur.delete',
            'harilibur.setharilibur',


        ];


        $userIds = [
            4,
            6,
            12,
            13,
            17,
            23,
            26,
            30,
            33,
            35,
            38,
            39,
            43,
            47,
            49,
            52,
            53,
            54,
            61,
            65,
            69,
            73,
            74,
            84,
            86,
            87,
            89,
            90,
            92,
            106,
            114,
            115,
            164,
            176,
            177,
            178,
            191,
            192,
            203,
            213,
            218,
            219,
            220,
            222,
            223,
            225,
            226,
            232,
            240,
        ];

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            // Memberikan permission langsung ke setiap user
            $user->givePermissionTo($permissions);
        }
    }
}

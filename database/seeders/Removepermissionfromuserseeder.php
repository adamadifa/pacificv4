<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Removepermissionfromuserseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role-role target
        $roles = [
            'operation manager',
            'sales marketing manager',
            'regional operation manager',
            'regional sales manager',
            'gm administrasi',
            'gm operasional',
            'manager keuangan',
            'manager pembelian',
            'manager gudang',
            'manager general affair',
            'manager audit',
            'manager produksi',
            'manager maintenance',
            'gm marketing',
            'direktur'
        ];

        // Permission yang ingin dihapus dari user (langsung)
        $permissionsToRemove = [
            'izinabsen.create',
            'izinpulang.create',
            'izincuti.create',
            'izinkeluar.create',
            'izinsakit.create',
            'izinterlambat.create',
            'izindinas.create',
            'izinkoreksi.create',
        ];

        foreach ($roles as $roleName) {
            $users = User::role($roleName)->get();

            foreach ($users as $user) {
                foreach ($permissionsToRemove as $permissionName) {
                    $permission = Permission::where('name', $permissionName)->first();

                    if ($permission && $user->hasDirectPermission($permission)) {
                        $user->revokePermissionTo($permission);
                        $this->command->info("Revoked '$permissionName' from user '{$user->name}' (Role: $roleName)");
                    }
                }
            }
        }
    }
}

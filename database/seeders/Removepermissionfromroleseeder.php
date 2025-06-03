<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Removepermissionfromroleseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar role yang akan dihapus permission-nya
        $roles = [
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

        // Daftar permission yang akan dihapus dari role
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
            $role = Role::where('name', $roleName)->first();

            if ($role) {
                foreach ($permissionsToRemove as $permissionName) {
                    $permission = Permission::where('name', $permissionName)->first();

                    if ($permission) {
                        $role->revokePermissionTo($permission);
                        // Optional: log to console
                        $this->command->info("Revoked '$permissionName' from '$roleName'");
                    }
                }
            } else {
                $this->command->warn("Role '$roleName' not found.");
            }
        }
    }
}

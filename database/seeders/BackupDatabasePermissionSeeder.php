<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Permission_group;

class BackupDatabasePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat atau ambil permission group untuk Utilities
        $utilitiesGroup = Permission_group::firstOrCreate([
            'name' => 'Utilities'
        ], [
            'name' => 'Utilities'
        ]);

        // Buat permission untuk backup database
        $permission = Permission::firstOrCreate([
            'name' => 'backup.database'
        ], [
            'name' => 'backup.database',
            'id_permission_group' => $utilitiesGroup->id
        ]);

        // Berikan permission ini ke role super admin dan gm administrasi
        $superAdminRole = Role::where('name', 'super admin')->first();
        $gmAdminRole = Role::where('name', 'gm administrasi')->first();

        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permission);
        }

        if ($gmAdminRole) {
            $gmAdminRole->givePermissionTo($permission);
        }

        $this->command->info('Permission group Utilities berhasil dibuat.');
        $this->command->info('Permission backup.database berhasil dibuat dan diberikan ke role yang sesuai.');
    }
}

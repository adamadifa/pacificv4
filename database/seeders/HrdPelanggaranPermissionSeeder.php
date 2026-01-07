<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HrdPelanggaranPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari Permission Group Laporan HRD
        $group = Permission_group::where('name', 'Laporan HRD')->first();

        if (!$group) {
             $this->command->error('Permission group "Laporan HRD" tidak ditemukan. Pastikan grup ini sudah ada.');
             // Fallback atau create jika perlu, tapi user bilang "masukan ke grup laporan hrd", asumsi sudah ada.
             // Kita create saja biar aman jika belum ada.
             $group = Permission_group::create(['name' => 'Laporan HRD']);
        }

        // Buat Permission
        $permission = Permission::firstOrCreate([
            'name' => 'hrd.pelanggaran'
        ], [
            'name' => 'hrd.pelanggaran',
            'guard_name' => 'web',
            'id_permission_group' => $group->id
        ]);
        
        // Pastikan id_permission_group terupdate jika permission sudah ada sebelumnya
        if ($permission->id_permission_group != $group->id) {
            $permission->id_permission_group = $group->id;
            $permission->save();
        }

        // Assign ke Super Admin
        $roleSuperAdmin = Role::where('name', 'super admin')->first();
        if ($roleSuperAdmin) {
            $roleSuperAdmin->givePermissionTo($permission);
        }
        
        // Assign ke Manager HRD (Optional, good practice)
        $roleManagerHrd = Role::where('name', 'manager hrd')->first();
        if ($roleManagerHrd) {
            $roleManagerHrd->givePermissionTo($permission);
        }

        $this->command->info('Permission "hrd.pelanggaran" berhasil dibuat dan dimasukkan ke grup "Laporan HRD".');
    }
}

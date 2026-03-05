<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LemburApprovalConfigPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari atau buat Permission Group "Lembur"
        $group = Permission_group::firstOrCreate([
            'name' => 'Lembur'
        ]);

        // Daftar permission yang akan dibuat
        $permissions = [
            'lembur.config.index',
            'lembur.config.create',
            'lembur.config.edit',
            'lembur.config.delete',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate([
                'name' => $permissionName
            ], [
                'name' => $permissionName,
                'guard_name' => 'web',
                'id_permission_group' => $group->id
            ]);

            // Pastikan id_permission_group terupdate jika permission sudah ada
            if ($permission->id_permission_group != $group->id) {
                $permission->id_permission_group = $group->id;
                $permission->save();
            }

            // Assign ke Super Admin
            $roleSuperAdmin = Role::where('name', 'super admin')->first();
            if ($roleSuperAdmin) {
                $roleSuperAdmin->givePermissionTo($permission);
            }

            // Assign ke Asst. Manager HRD
            $roleAmh = Role::where('name', 'asst. manager hrd')->first();
            if ($roleAmh) {
                $roleAmh->givePermissionTo($permission);
            }
        }

        $this->command->info('Permissions for Overtime Config Approval successfully created and assigned.');
    }
}

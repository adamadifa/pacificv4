<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Pencairanikatanuploadseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = Permission::firstorCreate([
            'name' => 'pencairanprogramikt.upload',
            'id_permission_group' => 149
        ]);

        // $permissions = Permission::where('id_permission_group', 149)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permission);
    }
}

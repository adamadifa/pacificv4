<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Sfapermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = Permission::create([
            'name' => 'sfa.trackingsalesman',
            'id_permission_group' => 131
        ]);


        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permission);
    }
}

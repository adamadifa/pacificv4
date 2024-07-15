<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Jasamasakerjapermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Jasa Masa Kerja'
        ]);

        Permission::create([
            'name' => 'jasamasakerja.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'jasamasakerja.create',
            'id_permission_group' => $permissiongroup->id
        ]);



        Permission::create([
            'name' => 'jasamasakerja.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'jasamasakerja.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'jasamasakerja.update',
            'id_permission_group' => $permissiongroup->id
        ]);


        Permission::create([
            'name' => 'jasamasakerja.delete',
            'id_permission_group' => $permissiongroup->id
        ]);



        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

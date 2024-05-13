<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Kirimpusatpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Kirim Pusat'
        ]);

        Permission::create([
            'name' => 'kirimpusat.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kirimpusat.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kirimpusat.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kirimpusat.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kirimpusat.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'kirimpusat.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'kirimpusat.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

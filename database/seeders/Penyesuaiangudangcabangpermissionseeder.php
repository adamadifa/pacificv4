<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Penyesuaiangudangcabangpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Penyesuaian Gudang Cabang'
        ]);

        Permission::create([
            'name' => 'penygudangcbg.index',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'penygudangcbg.create',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'penygudangcbg.edit',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'penygudangcbg.store',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'penygudangcbg.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'penygudangcbg.show',
            'id_permission_group' => $permissiongroup->id
        ]);

        Permission::create([
            'name' => 'penygudangcbg.delete',
            'id_permission_group' => $permissiongroup->id
        ]);

        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

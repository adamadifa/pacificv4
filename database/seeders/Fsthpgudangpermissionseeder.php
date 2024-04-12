<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Fsthpgudangpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::where('name', 'FSTHP')->first();

        Permission::create([
            'name' => 'fsthpgudang.index',
            'id_permission_group' => $permissiongroup->id
        ]);






        $permissions = Permission::where('name', 'fsthpgudang.index')->get();
        $roleID = 1;
        $role = Role::findById($roleID);
        $role->givePermissionTo($permissions);
    }
}

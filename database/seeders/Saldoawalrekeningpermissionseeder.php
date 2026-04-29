<?php
 
namespace Database\Seeders;
 
use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
 
class Saldoawalrekeningpermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissiongroup = Permission_group::create([
            'name' => 'Saldo Awal Rekening'
        ]);
 
        Permission::create([
            'name' => 'sarekening.index',
            'id_permission_group' => $permissiongroup->id
        ]);
 
        Permission::create([
            'name' => 'sarekening.create',
            'id_permission_group' => $permissiongroup->id
        ]);
 
        Permission::create([
            'name' => 'sarekening.edit',
            'id_permission_group' => $permissiongroup->id
        ]);
 
        Permission::create([
            'name' => 'sarekening.store',
            'id_permission_group' => $permissiongroup->id
        ]);
 
        Permission::create([
            'name' => 'sarekening.update',
            'id_permission_group' => $permissiongroup->id
        ]);
        Permission::create([
            'name' => 'sarekening.show',
            'id_permission_group' => $permissiongroup->id
        ]);
 
        Permission::create([
            'name' => 'sarekening.delete',
            'id_permission_group' => $permissiongroup->id
        ]);
 
        $permissions = Permission::where('id_permission_group', $permissiongroup->id)->get();
        $roleID = 1; // Assuming 1 is Super Admin
        $role = Role::findById($roleID);
        if ($role) {
            $role->givePermissionTo($permissions);
        }
    }
}

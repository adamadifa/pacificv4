<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class Copypermissionseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role asal dan tujuan
        $roleSourceName = 'manager audit'; // role sumber
        $roleTargetName = 'audit'; // role target

        // Cari role berdasarkan nama
        $roleSource = Role::where('name', $roleSourceName)->first();
        $roleTarget = Role::where('name', $roleTargetName)->first();

        // Cek apakah role ditemukan
        if ($roleSource && $roleTarget) {
            // Dapatkan semua permission dari role sumber
            $permissions = $roleSource->permissions;

            // Berikan semua permission ke role target
            $roleTarget->syncPermissions($permissions);

            echo "Permissions copied from {$roleSourceName} to {$roleTargetName} successfully!";
        } else {
            echo "Role source or target not found.";
        }
    }
}

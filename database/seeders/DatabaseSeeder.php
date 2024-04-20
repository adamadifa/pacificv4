<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            KategorisalesmanSeeder::class,
            Suratjalanpermissionseeder::class,
            Tujuanangkutanseeder::class,
            Tujuanangkutanpermissionseeder::class,
            Angkutanseeder::class,
            Angkutanpermissionseeder::class,
            Fsthpgudangpermissionseeder::class,
            Repackgudangjadipermissionseeder::class,
            Rejectgudangjadipermissionseeder::class,
            Lainnyagudangjadipermissionseeder::class,
            Saldoawalmutasigudangjadipermissionseeder::class,
            Suratjalanangkutanpermissionseeder::class,
            Laporangudangjadipermissionseeder::class,
            Barangmasukgudangbahanpermissionseeder::class,
            Kategoribarangpembelianseeder::class,
            Saldoawalgudangbahanpermissionseeder::class,
            Opnamegudangbahanpermissionseeder::class,
            Laporangudangbahanpermissionseeder::class,
        ]);
    }
}

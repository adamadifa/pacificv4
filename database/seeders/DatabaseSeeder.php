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
            Barangmasukgudanglogistikpermissionseeder::class,
            Saldoawalhargagudangbahanpermissionseeder::class,
            Barangkeluargudanglogistikpermissionseeder::class,
            Saldoawalgudanglogistikpermissionseeder::class,
            Opnamegudanglogistikpermissionseeder::class,
            Laporangudanglogistikpermissionseeder::class,
            DPBpermissionseeder::class,
            Driverhelperpermissionseeder::class,
            Mutasidpbpermissionseeder::class,
            Transitinpermissionseeder::class,
            Rejectpermissionseeder::class,
            Repackpermissionseeder::class,
            Kirimpusatpermissionseeder::class,
            Penyesuaiangudangcabangpermissionseeder::class,
            Saldoawalgudangcabangpermissionseeder::class,
            Laporangudangcabangpermissionseeder::class,
            Targetkomisipermissionseeder::class,
            Ratiodriverhelperpermissionseeder::class,
            Pembayaranpenjualanpermissionseeder::class,
            Jenisvoucherseeder::class,
            Pembayarangiropermissionseeder::class,
            Pembayarantransferpermissionseeder::class,
            Returpermissionseeder::class,
            Ajuanlimitkreditpermissionseeder::class,
            Ajuanfakturpermissionseeder::class,
            Setoranpenjualanpermissionseeder::class,
            Setorantransferpermissionseeder::class,
            Setorangiropermissionseeder::class,
            Setoranpusatpermissionseeder::class,
            Logamtokertaspermissionseeder::class,
            Saldoawalkasbesarpermissionseeder::class,
            Ajuantransferdanapermissionseeder::class,
            Kaskecilpermissionseeder::class,
            Ledgerpermissionseeder::class,
            Saldoawalledgerpermissionseeder::class,
            Mutasibankpermissionseeder::class
        ]);
    }
}

<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class Globalprovider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Guard $auth): void
    {
        view()->composer('*', function ($view) use ($auth) {
            $roles_show_cabang = ['super admin', 'general manager 3', 'manager keuangan', 'direktur', 'regional sales manager'];
            $start_periode = '2023-01-01';
            $end_periode = date('Y') . '-12-31';
            $namabulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $gudang_jadi = [
                'sagudangjadi.index',
                'suratjalan.index',
                'fsthpgudang.index',
                'repackgudangjadi.index',
                'lainnyagudangjadi.index',
            ];
            $laporan_gudang_jadi = [
                'gj.persediaan',
                'gj.rekappersediaan',
                'gj.rekaphasilproduksi',
                'gj.rekappengeluaran',
                'gj.realisasikiriman',
                'gj.realisasioman',
                'gj.angkutan'
            ];

            $all_gudang_jadi = array_merge($gudang_jadi, $laporan_gudang_jadi);
            $shareddata = [
                'roles_show_cabang' => $roles_show_cabang,
                'start_periode' => $start_periode,
                'end_periode' => $end_periode,
                'namabulan' => $namabulan,
                'gudang_jadi' => $gudang_jadi,
                'laporan_gudang_jadi' => $laporan_gudang_jadi,
                'all_gudang_jadi' => $all_gudang_jadi
            ];
            View::share($shareddata);
        });
    }
}

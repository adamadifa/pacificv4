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
            $shareddata = [
                'roles_show_cabang' => $roles_show_cabang,
                'start_periode' => $start_periode,
                'end_periode' => $end_periode,
            ];
            View::share($shareddata);
        });
    }
}

<?php

namespace App\Http\Controllers;

use App\Charts\HasilproduksiChart;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::findorfail(auth()->user()->id);
        if ($user->hasRole(['super admin'])) {
            return $this->marketing();
        } else {
            return 0;
        }
    }

    public function marketing()
    {
        return view('dashboard.marketing');
    }


    public function produksi(HasilproduksiChart $chart)
    {
        $data['start_year'] = config('global.start_year');
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan_singkat'] = config('global.nama_bulan_singkat');
        $data['chart'] = $chart->build(2024);

        return view('dashboard.produksi', $data);
    }
}

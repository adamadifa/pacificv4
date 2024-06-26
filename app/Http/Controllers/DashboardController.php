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
            return $this->marketing();
        }
    }

    public function marketing()
    {
        return view('dashboard.marketing');
    }


    public function produksi()
    {
        $data['start_year'] = config('global.start_year');
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan_singkat'] = config('global.nama_bulan_singkat');
        return view('dashboard.produksi', $data);
    }
}

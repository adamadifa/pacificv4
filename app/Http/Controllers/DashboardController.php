<?php

namespace App\Http\Controllers;

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


    public function produksi()
    {
        $start_year = config('global.start_year');
        $list_bulan = config('global.list_bulan');
        return view('dashboard.produksi', compact('start_year', 'list_bulan'));
    }
}

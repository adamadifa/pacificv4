<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OmancabangController extends Controller
{
    public function index()
    {
        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        return view('marketing.omancabang.index', compact('list_bulan', 'nama_bulan', 'start_year'));
    }
}

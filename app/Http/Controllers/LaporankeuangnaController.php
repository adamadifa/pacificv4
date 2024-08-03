<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporankeuangnaController extends Controller
{
    public function index()
    {
        return view('keuangan.laporan.index');
    }
}

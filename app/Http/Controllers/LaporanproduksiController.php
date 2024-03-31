<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanproduksiController extends Controller
{
    public function index()
    {
        return view('produksi.laporan.index');
    }
}

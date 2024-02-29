<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class SaldoawalmutasiproduksiController extends Controller
{
    public function index()
    {

        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        return view('produksi.saldoawalmutasiproduksi.index', compact('nama_bulan', 'start_year'));
    }
}

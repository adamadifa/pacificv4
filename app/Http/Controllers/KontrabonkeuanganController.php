<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KontrabonkeuanganController extends Controller
{
    public function pembelian(Request $request)
    {
        return view('keuangan.kontrabon.pembelian');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangmasukproduksiController extends Controller
{
    public function index()
    {
        return view('produksi.barangmasuk.index');
    }
}

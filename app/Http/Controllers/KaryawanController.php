<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        return view('datamaster.karyawan.index', compact('cabang'));
    }

    public function create()
    {
        return view('datamaster.karyawan.create');
    }
}

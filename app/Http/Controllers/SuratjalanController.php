<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SuratjalanController extends Controller
{
    public function index()
    {
    }

    public function create($no_permintaan)
    {
        $no_permintaan = Crypt::decrypt($no_permintaan);
        return view('gudangjadi.suratjalan.create');
    }
}

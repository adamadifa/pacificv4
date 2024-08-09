<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class LaporanpenjualanController extends Controller
{
    public function index()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('marketing.laporan.index', $data);
    }
}

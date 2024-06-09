<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class SetoranpenjualanController extends Controller
{
    public function index(Request $request)
    {

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('keuangan.kasbesar.setoranpenjualan.index', $data);
    }
}

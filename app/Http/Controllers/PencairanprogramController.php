<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class PencairanprogramController extends Controller
{
    public function index()
    {
        return view('worksheetom.pencairanprogram.index');
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;

        return view('worksheetom.pencairanprogram.create', $data);
    }
}

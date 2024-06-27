<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Kasbon;
use Illuminate\Http\Request;

class KasbonController extends Controller
{
    public function index(Request $request)
    {

        $kb = new Kasbon();
        $kasbon = $kb->getKasbon(request: $request)->paginate(15);
        $kasbon->appends(request()->all());
        $data['kasbon'] = $kasbon;


        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.kasbon.index', $data);
    }


    public function create()
    {
        return view('keuangan.kasbon.create');
    }
}

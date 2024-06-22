<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Pjp;
use Illuminate\Http\Request;

class PjpController extends Controller
{
    public function index(Request $request)
    {

        $pj = new Pjp();
        $pjp = $pj->getPjp(request: $request)->paginate(15);
        $pjp->appends(request()->all());
        $data['pjp'] = $pjp;

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('keuangan.pjp.index', $data);
    }


    public function create()
    {
        return view('keuangan.pjp.create');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Setoranpusat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SetoranpusatController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $sp = new Setoranpusat();
        $data['setoran_pusat'] = $sp->getSetoranpusat(request: $request)->get();

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;

        return view('keuangan.kasbesar.setoranpusat.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('keuangan.kasbesar.setoranpusat.create', $data);
    }
}

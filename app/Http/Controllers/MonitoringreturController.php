<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Retur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class MonitoringreturController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }
        $rtr = new Retur();
        $retur = $rtr->getRetur($request, $no_retur = "")->cursorPaginate();
        $retur->appends(request()->all());
        $data['retur'] = $retur;


        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('worksheetom.monitoringretur.index', $data);
    }


    public function create($no_retur)
    {
        $no_retur = Crypt::decrypt($no_retur);
        $rtr = new Retur();
        $retur = $rtr->getRetur($request = null, $no_retur)->first();
        $data['retur'] = $retur;
        $data['detail'] = $rtr->getDetailretur($no_retur);
        return view('worksheetom.monitoringretur.create', $data);
    }
}

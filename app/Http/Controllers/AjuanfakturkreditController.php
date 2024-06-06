<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class AjuanfakturkreditController extends Controller
{
    public function index(Request $request)
    {

        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $roles_approve_ajuanfakturkredit = config('global.roles_aprove_ajuanfakturkredit');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $data['roles_approve_ajuanfakturkredit'] = $roles_approve_ajuanfakturkredit;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('marketing.ajuanfaktur.index', $data);
    }
}

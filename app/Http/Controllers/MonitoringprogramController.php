<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Programikatan;
use Illuminate\Http\Request;

class MonitoringprogramController extends Controller
{
    public function index()
    {
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('worksheetom.monitoringprogram.index', $data);
    }
}

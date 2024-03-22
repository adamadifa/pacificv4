<?php

namespace App\Http\Controllers;

use App\Models\Omancabang;
use Illuminate\Http\Request;

class OmancabangController extends Controller
{
    public function index(Request $request)
    {
        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');

        $query = Omancabang::query();
        if (!empty($request->bulan_search)) {
            $query->where('bulan', $request->bulan_search);
        }

        if (!empty($request->tahun_search)) {
            $query->where('tahun', $request->tahun_search);
        } else {
            $query->where('tahun', date('Y'));
        }


        $query->orderBy('bulan');
        $oman_cabang = $query->paginate(15);
        $oman_cabang->appends(request()->all());
        return view('marketing.omancabang.index', compact(
            'list_bulan',
            'nama_bulan',
            'start_year',
            'oman_cabang'
        ));
    }
}

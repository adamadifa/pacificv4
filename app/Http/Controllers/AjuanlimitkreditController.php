<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DateTime;

class AjuanlimitkreditController extends Controller
{
    public function index()
    {
        return view('marketing.ajuanlimit.index');
    }

    public function create()
    {

        return view('marketing.ajuanlimit.create');
    }


    //AJAX REQUEST
    public function gettopupTerakhir(Request $request)
    {
        $tgl1 = new DateTime($request->tanggal);
        $tgl2 = new DateTime(date('Y-m-d'));
        $lama_topup = $tgl2->diff($tgl1)->days + 1;

        // tahun
        $y = $tgl2->diff($tgl1)->y;

        // bulan
        $m = $tgl2->diff($tgl1)->m;

        // hari
        $d = $tgl2->diff($tgl1)->d;

        $usia_topup = $y . " tahun " . $m . " bulan " . $d . " hari";

        $data = [
            'lama_topup' => $lama_topup,
            'usia_topup' => $usia_topup
        ];
        return response()->json([
            'success' => true,
            'message' => 'Detail Pelanggan',
            'data'    => $data
        ]);
    }
}

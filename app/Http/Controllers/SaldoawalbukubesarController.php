<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\Saldoawalbukubesar;

use Illuminate\Http\Request;

class SaldoawalbukubesarController extends Controller
{
    public function index(Request $request)
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        return view('accounting.saldoawalbukubesar.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['cek_saldo_awal'] = Saldoawalbukubesar::count();
        $data['coa'] = Coa::orderby('kode_akun', 'asc')
            ->whereNotIn('kode_akun', ['1', '0-0000'])
            ->get();
        return view('accounting.saldoawalbukubesar.create', $data);
    }
}

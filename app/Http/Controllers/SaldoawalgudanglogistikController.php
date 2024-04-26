<?php

namespace App\Http\Controllers;

use App\Models\Saldoawalgudanglogistik;
use Illuminate\Http\Request;

class SaldoawalgudanglogistikController extends Controller
{
    public function index(Request $request)
    {

        $list_bulan = config('global.list_bulan');
        $nama_bulan = config('global.nama_bulan');
        $start_year = config('global.start_year');
        $query = Saldoawalgudanglogistik::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->orderBy('tahun', 'desc');
        $query->orderBy('bulan');
        $saldo_awal = $query->get();
        return view('gudanglogistik.saldoawal.index', compact('list_bulan', 'start_year', 'saldo_awal', 'nama_bulan'));
    }
}

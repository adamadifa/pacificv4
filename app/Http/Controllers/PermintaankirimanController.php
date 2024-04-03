<?php

namespace App\Http\Controllers;

use App\Models\Permintaankiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PermintaankirimanController extends Controller
{
    public function index(Request $request)
    {
        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $query = Permintaankiriman::query();
        $query->select('marketing_permintaan_kiriman.*', 'nama_salesman', 'no_mutasi', 'no_dok', 'gudang_jadi_mutasi.tanggal as tanggal_surat_jalan', 'status_surat_jalan');
        $query->leftJoin('salesman', 'marketing_permintaan_kiriman.kode_salesman', '=', 'salesman.kode_salesman');
        $query->leftJoin('gudang_jadi_mutasi', 'marketing_permintaan_kiriman.no_permintaan', '=', 'gudang_jadi_mutasi.no_permintaan');
        $query->orderBy('status', 'asc');
        $query->orderBy('tanggal', 'desc');
        $query->orderBy('marketing_permintaan_kiriman.no_permintaan', 'desc');
        $pk = $query->paginate(15);
        $pk->appends(request()->all());
        return view('marketing.permintaankiriman.index', compact('pk'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Permintaankiriman;
use Illuminate\Http\Request;

class PermintaankirimanController extends Controller
{
    public function index(Request $request)
    {
        $query = Permintaankiriman::query();
        $query->select('marketing_permintaan_kiriman.*', 'nama_salesman');
        $query->leftJoin('salesman', 'marketing_permintaan_kiriman.kode_salesman', '=', 'salesman.kode_salesman');
        $query->orderBy('status', 'asc');
        $query->orderBy('tanggal', 'desc');
        $query->orderBy('marketing_permintaan_kiriman.no_permintaan', 'desc');
        $pk = $query->paginate(15);
        $pk->appends(request()->all());
        return view('marketing.permintaankiriman.index', compact('pk'));
    }
}

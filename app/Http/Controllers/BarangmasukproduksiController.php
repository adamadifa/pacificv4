<?php

namespace App\Http\Controllers;

use App\Models\Barangmasukproduksi;
use Illuminate\Http\Request;

class BarangmasukproduksiController extends Controller
{
    public function index(Request $request)
    {

        $query = Barangmasukproduksi::query();
        $query->orderBy('tanggal', 'desc');
        $query->orderBy('created_at', 'desc');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }

        $barangmasuk = $query->simplePaginate(20);
        $barangmasuk->appends(request()->all());

        $asal_barang = config('produksi.asal_barang_produksi');
        return view('produksi.barangmasuk.index', compact('barangmasuk', 'asal_barang'));
    }
}

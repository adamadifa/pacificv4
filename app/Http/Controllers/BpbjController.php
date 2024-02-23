<?php

namespace App\Http\Controllers;

use App\Models\Mutasiproduksi;
use Illuminate\Http\Request;

class BpbjController extends Controller
{
    public function index(Request $request)
    {
        $query = Mutasiproduksi::query();
        $query->orderBy('tanggal_mutasi', 'desc');
        $query->orderBy('created_at', 'desc');
        $bpbj = $query->paginate(15);
        $bpbj->appends(request()->all());
        return view('produksi.bpbj.index', compact('bpbj'));
    }
}

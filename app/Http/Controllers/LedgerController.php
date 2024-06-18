<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Coa;
use App\Models\Ledger;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index(Request $request)
    {

        $lg = new Ledger();
        $data['ledger'] = $lg->getLedger(request: $request)->get();
        $data['bank'] = Bank::orderBy('nama_bank')->get();
        return view('keuangan.ledger.index', $data);
    }

    public function create(Request $request)
    {
        $data['bank'] = Bank::orderBy('nama_bank')->get();
        $data['coa'] = Coa::orderby('kode_akun')->get();
        return view('keuangan.ledger.create', $data);
    }
}

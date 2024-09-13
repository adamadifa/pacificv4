<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Detailvalidasiretur;
use App\Models\Retur;
use App\Models\Validasiitemretur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class MonitoringreturController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }
        $rtr = new Retur();
        $retur = $rtr->getRetur($request, $no_retur = "")->cursorPaginate();
        $retur->appends(request()->all());
        $data['retur'] = $retur;


        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('worksheetom.monitoringretur.index', $data);
    }


    public function create($no_retur)
    {
        $no_retur = Crypt::decrypt($no_retur);
        $rtr = new Retur();
        $retur = $rtr->getRetur($request = null, $no_retur)->first();
        $data['retur'] = $retur;
        $data['detail'] = $rtr->getDetailretur($no_retur);

        $data['validasi_item'] = Validasiitemretur::orderBy('kode_item')->get();
        $validasi_cek = Detailvalidasiretur::select('kode_item')->where('no_retur', $no_retur)->get();
        $kode_item_cek = [];
        foreach ($validasi_cek as $d) {
            $kode_item_cek[] = $d->kode_item;
        }

        $data['kode_item_cek'] = $kode_item_cek;
        return view('worksheetom.monitoringretur.create', $data);
    }

    public function store(Request $request, $no_retur)
    {
        $no_retur = Crypt::decrypt($no_retur);
        $request->validate([
            'kode_item' => 'required',
        ]);

        try {
            for ($i = 0; $i < count($request->kode_item); $i++) {
                Detailvalidasiretur::create([
                    'no_retur' => $no_retur,
                    'kode_item' => $request->kode_item[$i],
                ]);
            }

            return Redirect::back()->with(messageSuccess('Data Berhasil Tersimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

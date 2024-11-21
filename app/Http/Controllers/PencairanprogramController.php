<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Pencairanprogram;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PencairanprogramController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
        } else {
            $kode_cabang = $request->kode_cabang;
        }
        $query = Pencairanprogram::query();
        if (!empty($kode_cabang)) {
            $query->where('kode_cabang', $kode_cabang);
        }

        $pencairanprogram = $query->get();

        $data['pencairanprogram'] = $pencairanprogram;

        return view('worksheetom.pencairanprogram.index', $data);
    }

    public function create()
    {
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('worksheetom.pencairanprogram.create', $data);
    }

    public function store(Request $request)
    {
        //Kode Pencairan Program PCBDG240001
        $request->validate([
            'tanggal' => 'required',
            'kode_cabang' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'kode_jenis_program' => 'required',
            'keterangan' => 'required'
        ]);
        $lastpencairan = Pencairanprogram::select('kode_pencairan')->orderBy('kode_pencairan', 'desc')
            ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($request->tanggal)) . '"')
            ->where('kode_cabang', $request->kode_cabang)
            ->first();
        $last_kode_pencairan = $lastpencairan != null ? $lastpencairan->kode_pencairan : '';
        $kode_pencairan = buatkode($last_kode_pencairan, "PC" . $request->kode_cabang . date('y', strtotime($request->tanggal)), 4);

        try {
            //code...
            Pencairanprogram::create([
                'kode_pencairan' => $kode_pencairan,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $request->kode_cabang,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'kode_jenis_program' => $request->kode_jenis_program,
                'keterangan' => $request->keterangan
            ]);
            return Redirect::back()->with(messageSuccess("Data Berhasil Disimpan"));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

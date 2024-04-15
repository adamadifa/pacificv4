<?php

namespace App\Http\Controllers;

use App\Models\Angkutan;
use App\Models\Suratjalanangkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SuratjalanangkutanController extends Controller
{
    public function index(Request $request)
    {

        $start_year = config('global.start_year');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');


        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }
        $query = Suratjalanangkutan::query();
        $query->select('gudang_jadi_angkutan_suratjalan.no_dok', 'tanggal', 'tujuan', 'nama_angkutan', 'no_polisi', 'gudang_jadi_angkutan_suratjalan.tarif', 'tepung', 'bs');
        $query->join('gudang_jadi_mutasi', 'gudang_jadi_angkutan_suratjalan.no_dok', '=', 'gudang_jadi_mutasi.no_dok');
        $query->join('angkutan', 'gudang_jadi_angkutan_suratjalan.kode_angkutan', '=', 'angkutan.kode_angkutan');
        $query->join('angkutan_tujuan', 'gudang_jadi_angkutan_suratjalan.kode_tujuan', '=', 'angkutan_tujuan.kode_tujuan');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('gudang_jadi_mutasi.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('gudang_jadi_mutasi.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_dok_search)) {
            $query->where('gudang_jadi_angkutan_suratjalan.no_dok', $request->no_dok_search);
        }

        if (!empty($request->kode_angkutan_search)) {
            $query->where('gudang_jadi_angkutan_suratjalan.kode_angkutan', $request->kode_angkutan_search);
        }
        $suratjalanangkutan = $query->paginate(15);
        $suratjalanangkutan->appends(request()->all());
        $data['suratjalanangkutan'] = $suratjalanangkutan;
        $data['angkutan'] = Angkutan::orderBy('kode_angkutan')->get();
        return view('gudangjadi.suratjalanangkutan.index', $data);
    }
}

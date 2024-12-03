<?php

namespace App\Http\Controllers;

use App\Models\Tutuplaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class TutuplaporanController extends Controller
{

    public function index(Request $request)
    {
        $query = Tutuplaporan::query();
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        } else {
            $query->where('bulan', date('m'));
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }
        $query->orderBy('tutup_laporan.created_at');
        $data['tutup_laporan'] = $query->get();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('utilities.tutuplaporan.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('utilities.tutuplaporan.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
            'tanggal' => 'required',
            'jenis_laporan' => 'required',
        ]);

        try {
            $tutuplap = Tutuplaporan::where('bulan', $request->bulan)->where('tahun', $request->tahun)->orderBy('kode_tutup_laporan', 'desc')->first();
            $lastkode = $tutuplap != null ? $tutuplap->kode_tutup_laporan : '';
            $kode_tutup_laporan = buatkode($lastkode, $request->tahun . $request->bulan, 2);
            $cek = Tutuplaporan::where('jenis_laporan', $request->jenis_laporan)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->first();
            if (!empty($cek)) {
                return Redirect::back()->with(messageError('Data Sudah Ada'));
            }
            Tutuplaporan::create([
                'kode_tutup_laporan' => $kode_tutup_laporan,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'tanggal' => $request->tanggal,
                'jenis_laporan' => $request->jenis_laporan,
                'status' => 1
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function lockunlock($kode_tutup_laporan)
    {
        $kode_tutup_laporan = Crypt::decrypt($kode_tutup_laporan);
        try {
            $tutup_laporan = Tutuplaporan::where('kode_tutup_laporan', $kode_tutup_laporan)->first();
            if ($tutup_laporan->status == 1) {
                $tutup_laporan->update([
                    'status' => 0
                ]);
                return Redirect::back()->with(messageSuccess('Laporan Dibuka'));
            } else {
                $tutup_laporan->update([
                    'status' => 1
                ]);
                return Redirect::back()->with(messageSuccess('Laporan Ditutup'));
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cektutuplaporan(Request $request)
    {
        $tanggal = explode("-", $request->tanggal);
        $bulan = $tanggal[1];
        $tahun = $tanggal[0];
        $cek = Tutuplaporan::where('jenis_laporan', $request->jenis_laporan)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('status', 1)
            ->count();
        return $cek;
    }
}

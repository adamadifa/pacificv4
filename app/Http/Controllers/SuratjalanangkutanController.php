<?php

namespace App\Http\Controllers;

use App\Models\Angkutan;
use App\Models\Suratjalanangkutan;
use App\Models\Tujuanangkutan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
        $query->select(
            'gudang_jadi_angkutan_suratjalan.no_dok',
            'gudang_jadi_mutasi.tanggal',
            'tujuan',
            'nama_angkutan',
            'no_polisi',
            'gudang_jadi_angkutan_suratjalan.tarif',
            'tepung',
            'bs',
            'gudang_jadi_angkutan_kontrabon.tanggal as tanggal_kontrabon',
            'keuangan_ledger.tanggal as tanggal_ledger',
            'ledgerhutang.tanggal as tanggal_ledger_hutang',
        );
        $query->join('gudang_jadi_mutasi', 'gudang_jadi_angkutan_suratjalan.no_dok', '=', 'gudang_jadi_mutasi.no_dok');
        $query->join('angkutan', 'gudang_jadi_angkutan_suratjalan.kode_angkutan', '=', 'angkutan.kode_angkutan');
        $query->join('angkutan_tujuan', 'gudang_jadi_angkutan_suratjalan.kode_tujuan', '=', 'angkutan_tujuan.kode_tujuan');
        $query->leftJoin('gudang_jadi_angkutan_kontrabon_detail', 'gudang_jadi_angkutan_kontrabon_detail.no_dok', '=', 'gudang_jadi_angkutan_suratjalan.no_dok');
        $query->leftJoin('gudang_jadi_angkutan_kontrabon', 'gudang_jadi_angkutan_kontrabon_detail.no_kontrabon', '=', 'gudang_jadi_angkutan_kontrabon.no_kontrabon');
        $query->leftJoin('keuangan_ledger_kontrabonangkutan', 'gudang_jadi_angkutan_kontrabon.no_kontrabon', '=', 'keuangan_ledger_kontrabonangkutan.no_kontrabon');
        $query->leftJoin('keuangan_ledger', 'keuangan_ledger_kontrabonangkutan.no_bukti', '=', 'keuangan_ledger.no_bukti');
        $query->leftJoin('keuangan_ledger_kontrabonangkutan_hutang', 'gudang_jadi_angkutan_kontrabon.no_kontrabon', '=', 'keuangan_ledger_kontrabonangkutan_hutang.no_kontrabon');
        $query->leftJoin('keuangan_ledger as ledgerhutang', 'keuangan_ledger_kontrabonangkutan_hutang.no_bukti', '=', 'ledgerhutang.no_bukti');
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
        $query->orderBy('gudang_jadi_mutasi.tanggal', 'desc');
        $suratjalanangkutan = $query->paginate(15);
        $suratjalanangkutan->appends(request()->all());
        $data['suratjalanangkutan'] = $suratjalanangkutan;
        $data['angkutan'] = Angkutan::orderBy('kode_angkutan')->get();
        return view('gudangjadi.suratjalanangkutan.index', $data);
    }

    public function edit($no_dok)
    {
        $no_dok = Crypt::decrypt($no_dok);
        $data['suratjalanangkutan'] = Suratjalanangkutan::where('no_dok', $no_dok)->first();
        $data['tujuan'] = Tujuanangkutan::orderBy('kode_tujuan')->get();
        $data['angkutan'] = Angkutan::orderBy('kode_angkutan')->get();
        return view('gudangjadi.suratjalanangkutan.edit', $data);
    }

    public function update(Request $request, $no_dok)
    {
        $no_dok = Crypt::decrypt($no_dok);


        $request->validate([
            'no_dok' => 'required',
            'kode_tujuan' => 'required',
            'no_polisi' => 'required',
            'tarif' => 'required',
            'kode_angkutan' => 'required',
        ]);

        try {
            Suratjalanangkutan::where('no_dok', $no_dok)->update([
                'no_dok' => $request->no_dok,
                'kode_tujuan' => $request->kode_tujuan,
                'no_polisi' => $request->no_polisi,
                'tarif' => empty($request->tarif) ? 0 : toNumber($request->tarif),
                'tepung' => empty($request->tepung) ? 0 : toNumber($request->tepung),
                'bs' => empty($request->bs) ? 0 : toNumber($request->bs),
                'kode_angkutan' => $request->kode_angkutan,
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function getsuratjalanbyangkutan($kode_angkutan)
    {
        $suratjalan = Suratjalanangkutan::select('gudang_jadi_angkutan_suratjalan.*', 'tujuan', 'gudang_jadi_mutasi.tanggal')
            ->join('gudang_jadi_mutasi', 'gudang_jadi_angkutan_suratjalan.no_dok', '=', 'gudang_jadi_mutasi.no_dok')
            ->join('angkutan_tujuan', 'gudang_jadi_angkutan_suratjalan.kode_tujuan', '=', 'angkutan_tujuan.kode_tujuan')
            ->leftJoin('gudang_jadi_angkutan_kontrabon_detail', 'gudang_jadi_angkutan_kontrabon_detail.no_dok', '=', 'gudang_jadi_angkutan_suratjalan.no_dok')
            ->where('kode_angkutan', $kode_angkutan)->orderBy('gudang_jadi_mutasi.tanggal')
            ->whereNull('gudang_jadi_angkutan_kontrabon_detail.no_kontrabon')
            ->get();
        echo "<option value=''>No. Dokumen | Tanggal | Tujuan | Tarif</option>";
        foreach ($suratjalan as $d) {
            echo "<option tanggal='$d->tanggal' tujuan='$d->tujuan' tarif='$d->tarif' value='$d->no_dok'>" . $d->no_dok . " | " . formatIndo($d->tanggal) . " | " . $d->tujuan . " | " . formatAngka($d->tarif) . "</option>";
        }
    }
}

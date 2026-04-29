<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Saldoawalrekening;
use App\Models\Saldoawalrekeningdetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use App\Exports\SaldoawalrekeningTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class SaldoawalrekeningController extends Controller
{
    public function index(Request $request)
    {
        $query = Saldoawalrekening::query();
        if (!empty($request->tanggal)) {
            $query->where('tanggal', $request->tanggal);
        }
        $query->orderBy('tanggal', 'desc');
        $data['saldo_awal'] = $query->paginate(15);
        $data['saldo_awal']->appends($request->all());

        return view('keuangan.rekening_saldoawal.index', $data);
    }

    public function create()
    {
        $data['bank'] = Bank::orderBy('kode_bank')->get();
        return view('keuangan.rekening_saldoawal.create', $data);
    }

    public function downloadtemplate()
    {
        return Excel::download(new SaldoawalrekeningTemplateExport, 'template_saldo_awal_rekening.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        DB::beginTransaction();
        try {
            $kode_saldo_awal = "SAREK" . date('dmY', strtotime($request->tanggal));
            
            // Cek apakah sudah ada untuk tanggal ini
            $cek = Saldoawalrekening::where('kode_saldo_awal', $kode_saldo_awal)->count();
            if ($cek > 0) {
                return Redirect::back()->with(messageError('Saldo Awal untuk tanggal tersebut sudah ada'));
            }

            // Simpan Master
            Saldoawalrekening::create([
                'kode_saldo_awal' => $kode_saldo_awal,
                'tanggal' => $request->tanggal,
            ]);

            // Load Excel
            $data = Excel::toArray([], $request->file('file'));
            $rows = $data[0];

            // Skip Header (row index 0)
            for ($i = 1; $i < count($rows); $i++) {
                $kode_bank = $rows[$i][0];
                $jumlah = $rows[$i][2];

                if (!empty($kode_bank) && !empty($jumlah) && $jumlah > 0) {
                    Saldoawalrekeningdetail::create([
                        'kode_saldo_awal' => $kode_saldo_awal,
                        'kode_bank' => $kode_bank,
                        'jumlah' => $jumlah,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('sarekening.index')->with(messageSuccess('Data Berhasil Diimport'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $kode_saldo_awal = "SAREK" . date('dmY', strtotime($request->tanggal));
            
            // Cek apakah sudah ada untuk tanggal ini
            $cek = Saldoawalrekening::where('kode_saldo_awal', $kode_saldo_awal)->count();
            if ($cek > 0) {
                return Redirect::back()->with(messageError('Saldo Awal untuk tanggal tersebut sudah ada'));
            }

            // Simpan Master
            Saldoawalrekening::create([
                'kode_saldo_awal' => $kode_saldo_awal,
                'tanggal' => $request->tanggal,
            ]);

            // Simpan Detail
            $kode_bank = $request->kode_bank;
            $jumlah = $request->jumlah;

            for ($i = 0; $i < count($kode_bank); $i++) {
                $jml = toNumber($jumlah[$i]);
                if ($jml > 0) {
                    Saldoawalrekeningdetail::create([
                        'kode_saldo_awal' => $kode_saldo_awal,
                        'kode_bank' => $kode_bank[$i],
                        'jumlah' => $jml,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('sarekening.index')->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $data['saldo_awal'] = Saldoawalrekening::with('details.bank')->where('kode_saldo_awal', $kode_saldo_awal)->firstOrFail();
        return view('keuangan.rekening_saldoawal.show', $data);
    }

    public function edit($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $data['saldo_awal'] = Saldoawalrekening::where('kode_saldo_awal', $kode_saldo_awal)->firstOrFail();
        $data['details'] = Saldoawalrekeningdetail::where('kode_saldo_awal', $kode_saldo_awal)->get()->pluck('jumlah', 'kode_bank')->toArray();
        $data['bank'] = Bank::orderBy('kode_bank')->get();
        return view('keuangan.rekening_saldoawal.edit', $data);
    }

    public function update(Request $request, $kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $saldo_awal = Saldoawalrekening::where('kode_saldo_awal', $kode_saldo_awal)->firstOrFail();
            $saldo_awal->update([
                'tanggal' => $request->tanggal,
            ]);

            // Hapus detail lama dan simpan yang baru
            Saldoawalrekeningdetail::where('kode_saldo_awal', $kode_saldo_awal)->delete();

            $kode_bank = $request->kode_bank;
            $jumlah = $request->jumlah;

            for ($i = 0; $i < count($kode_bank); $i++) {
                $jml = toNumber($jumlah[$i]);
                if ($jml > 0) {
                    Saldoawalrekeningdetail::create([
                        'kode_saldo_awal' => $kode_saldo_awal,
                        'kode_bank' => $kode_bank[$i],
                        'jumlah' => $jml,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('sarekening.index')->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        DB::beginTransaction();
        try {
            Saldoawalrekening::where('kode_saldo_awal', $kode_saldo_awal)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

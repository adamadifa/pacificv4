<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Coa;
use App\Models\Kategoritransaksimutasikeuangan;
use App\Models\Mutasikeuangan;
use App\Models\Saldoawalledger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MutasikeuanganController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $mk = new Mutasikeuangan();
        $data['mutasikeuangan'] = $mk->getMutasi(request: $request)->get();
        $bnk = new Bank();
        $data['bank'] = $bnk->getBank()->get();

        $bulan = !empty($request->dari) ? date('m', strtotime($request->dari)) : '';
        $tahun = !empty($request->dari) ? date('Y', strtotime($request->dari)) : '';

        if ($user->hasRole('staff keuangan 2')) {
            $data['saldo_awal']  = Saldoawalledger::where('bulan', $bulan)->where('tahun', $tahun)->where('kode_bank', 'BK070')->first();
        } else {

            $data['saldo_awal']  = Saldoawalledger::where('bulan', $bulan)->where('tahun', $tahun)->where('kode_bank', $request->kode_bank_search)->first();
        }
        $start_date = $tahun . "-" . $bulan . "-01";
        if (!empty($request->dari && !empty($request->sampai))) {

            if ($user->hasRole('staff keuangan 2')) {
                $data['mutasi']  = Mutasikeuangan::select(
                    DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as kredit"),
                    DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as debet")
                )

                    ->where('tanggal', '>=', $start_date)
                    ->where('tanggal', '<', $request->dari)
                    ->where('kode_bank', 'BK070')
                    ->first();
            } else {
                $data['mutasi']  = Mutasikeuangan::select(
                    DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as kredit"),
                    DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as debet")
                )

                    ->where('tanggal', '>=', $start_date)
                    ->where('tanggal', '<', $request->dari)
                    ->where('kode_bank', $request->kode_bank_search)
                    ->first();
            }
        } else {
            $data['mutasi'] = null;
        }



        return view('keuangan.mutasikeuangan.index', $data);
    }

    public function create()
    {
        $bnk = new Bank();
        $data['bank'] = $bnk->getBank()->get();
        $data['coa'] = Coa::orderby('kode_akun')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['kategori'] = Kategoritransaksimutasikeuangan::orderBy('kode_kategori')->get();
        return view('keuangan.mutasikeuangan.create', $data);
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            Mutasikeuangan::create([
                'tanggal' => $request->tanggal,
                'no_bukti' => $request->no_bukti,
                'kode_bank' => $request->kode_bank,
                'keterangan' => $request->keterangan,
                'jumlah' => toNumber($request->jumlah),
                'debet_kredit' => $request->debet_kredit,
                'kode_kategori' => $request->kode_kategori,
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        try {
            $mutasikeuangan = Mutasikeuangan::findorfail($id);
            $mutasikeuangan->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $mutasi = Mutasikeuangan::findorfail($id);
        $data['mutasikeuangan'] = $mutasi;
        $data['bank'] = Bank::orderBy('nama_bank')->get();
        $data['coa'] = Coa::orderby('kode_akun')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['kategori'] = Kategoritransaksimutasikeuangan::orderBy('kode_kategori')->get();
        return view('keuangan.mutasikeuangan.edit', $data);
    }

    public function update($id, Request $request)
    {
        $id = Crypt::decrypt($id);
        try {
            $mutasi = Mutasikeuangan::findorfail($id);
            $mutasi->tanggal = $request->tanggal;
            $mutasi->no_bukti = $request->no_bukti;
            $mutasi->keterangan = $request->keterangan;
            $mutasi->jumlah = toNumber($request->jumlah);
            $mutasi->debet_kredit = $request->debet_kredit;
            $mutasi->kode_kategori = $request->kode_kategori;
            $mutasi->save();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_bank, $dari, $sampai)
    {
        $bulan = date('m', strtotime(date('Y-m-d')));
        $tahun = date('Y', strtotime(date('Y-m-d')));
        $kode_bank = $kode_bank != "all" ? Crypt::decrypt($kode_bank) : '';
        $data['bank'] = Bank::where('kode_bank', $kode_bank)->first();
        $data['saldo_awal']  = Saldoawalledger::where('bulan', $bulan)->where('tahun', $tahun)->where('kode_bank', $kode_bank)->first();

        $start_date = $tahun . "-" . $bulan . "-01";
        $data['rekap']  = Mutasikeuangan::select(
            DB::raw("SUM(IF(debet_kredit='K',jumlah,0))as rekap_kredit"),
            DB::raw("SUM(IF(debet_kredit='D',jumlah,0))as rekap_debet"),
        )

            ->whereBetween('tanggal', [$dari, $sampai])
            ->when(!empty($kode_bank), function ($query) use ($kode_bank) {
                $query->where('kode_bank', $kode_bank);
            })
            // ->when($request->dari && $request->sampai, function ($query) use ($request) {
            //     $query->where('tanggal', '>=', $request->dari)
            //         ->where('tanggal', '<=', $request->sampai);
            // }, function ($query) {
            //     $query->where('tanggal', date('Y-m-d'));
            // })
            // ->groupBy('kode_bank')
            ->first();

        $qmutasi = Mutasikeuangan::query();
        if (!empty($dari) && !empty($sampai)) {
            $qmutasi->whereBetween('tanggal', [$dari, $sampai]);
        } else {
            $qmutasi->where('tanggal', '>=', $start_date)->where('tanggal', '<=', date('Y-m-d'));
        }


        $data['mutasi']  = $qmutasi->get();

        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        return view('keuangan.mutasikeuangan.show', $data);
    }


    public function showmutasikategori(Request $request)
    {
        $data['mutasi'] = Mutasikeuangan::where('kode_kategori', $request->kode_kategori)->where('tanggal', $request->tanggal)
            ->join('bank', 'keuangan_mutasi.kode_bank', '=', 'bank.kode_bank')
            ->get();
        return view('keuangan.mutasikeuangan.showmutasikategori', $data);
    }
}

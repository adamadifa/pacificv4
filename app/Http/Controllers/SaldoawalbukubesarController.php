<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\Detailsaldoawalbukubesar;
use App\Models\Saldoawalbukubesar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SaldoawalbukubesarController extends Controller
{
    public function index(Request $request)
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
       $query = Saldoawalbukubesar::query();
       if($request->has('bulan')){
           $query->where('bulan', $request->bulan);
       }
       if($request->has('tahun')){
           $query->where('tahun', $request->tahun);
       }else{
           $query->where('tahun', date('Y'));
       }
       $query->orderBy('bulan', 'asc');
       $data['saldoawalbukubesar'] = $query->get();
        return view('accounting.saldoawalbukubesar.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['cek_saldo_awal'] = Saldoawalbukubesar::count();
        $data['coa'] = Coa::orderby('kode_akun', 'asc')
            ->whereNotIn('kode_akun', ['1', '0-0000'])
            ->get();
        return view('accounting.saldoawalbukubesar.create', $data);
    }

    public function edit($kode_saldo_awal)
    {
        $kode_saldo_awal = Crypt::decrypt($kode_saldo_awal);
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['saldoawalbukubesar'] = Saldoawalbukubesar::where('kode_saldo_awal', $kode_saldo_awal)->first();
        $data['coa'] = Coa::orderby('kode_akun', 'asc')
            ->whereNotIn('kode_akun', ['1', '0-0000'])
            ->get();
        return view('accounting.saldoawalbukubesar.edit', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        DB::beginTransaction();

        $kode_saldo_awal = "SA" . $request->bulan . $request->tahun;
        try {
           Saldoawalbukubesar::create([
               'kode_saldo_awal' => $kode_saldo_awal,
               'tanggal' => $request->tahun . "-" . $request->bulan . "-01",
               'bulan' => $request->bulan,
               'tahun' => $request->tahun,
           ]);

           $kode_akun = $request->kode_akun;
           $jumlah = $request->jumlah;

           foreach ($kode_akun as $key => $value) {
               Detailsaldoawalbukubesar::create([
                   'kode_saldo_awal' => $kode_saldo_awal,
                   'kode_akun' => $value,
                   'jumlah' => toNumber($jumlah[$key]),
               ]);
           }
           DB::commit();
           return redirect()->route('saldoawalbukubesar.index')->with(messageSuccess('Data berhasil disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }
}

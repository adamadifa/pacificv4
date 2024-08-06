<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Giro;
use App\Models\Ledgergiro;
use App\Models\Ledgersetoranpusat;
use App\Models\Setoranpusat;
use App\Models\Setoranpusatgiro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SetorangiroController extends Controller
{
    public function index(Request $request)
    {

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $gr = new Giro();
        $giro = $gr->getGiro(request: $request);
        $giro = $giro->paginate(15);
        $giro->appends(request()->all());
        $data['giro'] = $giro;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;


        return view('keuangan.kasbesar.setorangiro.index', $data);
    }


    public function create($kode_giro)
    {
        $kode_giro = Crypt::decrypt($kode_giro);
        $gr = new Giro();
        $data['giro'] = $gr->getGiro(kode_giro: $kode_giro)->first();
        return view('keuangan.kasbesar.setorangiro.create', $data);
    }


    public function store($kode_giro, Request $request)
    {

        $request->validate([
            'tanggal' => 'required'
        ]);
        $kode_giro = Crypt::decrypt($kode_giro);
        $gr = new Giro();
        $giro = $gr->getGiro(kode_giro: $kode_giro)->first();

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            //Generate Kode setoran
            $lastsetoranpusat = Setoranpusat::select('kode_setoran')
                ->whereRaw('LEFT(kode_setoran,4)="SB' . date('y') . '"')
                ->orderBy('kode_setoran', 'desc')
                ->first();
            $last_kode_setoran = $lastsetoranpusat != null ?  $lastsetoranpusat->kode_setoran : '';
            $kode_setoran   = buatkode($last_kode_setoran, 'SB' . date('y'), 5);

            Setoranpusat::create([
                'kode_setoran' => $kode_setoran,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $giro->kode_cabang,
                'setoran_giro' => $giro->total,
                'keterangan' => 'SETOR GIRO PELANGGAN ' . $giro->nama_pelanggan,
                'status' => $giro->status,
                'omset_bulan' => $giro->omset_bulan,
                'omset_tahun' => $giro->omset_tahun
            ]);

            Setoranpusatgiro::create([
                'kode_setoran' => $kode_setoran,
                'kode_giro' => $kode_giro
            ]);

            //Cek Ledger Transfer
            $ledgergiro = Ledgergiro::where('kode_giro', $kode_giro)->first();
            if ($ledgergiro) {
                Ledgersetoranpusat::create([
                    'no_bukti' => $ledgergiro->no_bukti,
                    'kode_setoran' => $kode_setoran
                ]);
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_setoran)
    {
        $kode_setoran = Crypt::decrypt($kode_setoran);
        $setoranpusat = Setoranpusat::where('kode_setoran', $kode_setoran)->first();

        if (!$setoranpusat) {
            return Redirect::back()->with(messageError('Data Setoran Penjualan tidak ditemukan'));
        }

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($setoranpusat->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            Setoranpusat::where('kode_setoran', $kode_setoran)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

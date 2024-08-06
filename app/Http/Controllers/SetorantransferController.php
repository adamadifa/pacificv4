<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Giro;
use App\Models\Ledgersetoranpusat;
use App\Models\Ledgertransfer;
use App\Models\Setoranpusat;
use App\Models\Setoranpusattransfer;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SetorantransferController extends Controller
{
    public function index(Request $request)
    {

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $trf = new Transfer();
        $transfer = $trf->getTransfer(request: $request);
        $transfer = $transfer->paginate(15);
        $transfer->appends(request()->all());
        $data['transfer'] = $transfer;

        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;


        return view('keuangan.kasbesar.setorantransfer.index', $data);
    }

    public function create($kode_transfer)
    {
        $kode_transfer = Crypt::decrypt($kode_transfer);
        $trf = new Transfer();
        $data['transfer'] = $trf->getTransfer(kode_transfer: $kode_transfer)->first();
        return view('keuangan.kasbesar.setorantransfer.create', $data);
    }


    public function store($kode_transfer, Request $request)
    {

        $request->validate([
            'tanggal' => 'required'
        ]);
        $kode_transfer = Crypt::decrypt($kode_transfer);
        $trf = new Transfer();
        $transfer = $trf->getTransfer(kode_transfer: $kode_transfer)->first();

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
                'kode_cabang' => $transfer->kode_cabang,
                'setoran_transfer' => $transfer->total,
                'keterangan' => 'SETOR TRANSFER PELANGGAN ' . $transfer->nama_pelanggan,
                'status' => $transfer->status,
                'omset_bulan' => $transfer->omset_bulan,
                'omset_tahun' => $transfer->omset_tahun
            ]);

            Setoranpusattransfer::create([
                'kode_setoran' => $kode_setoran,
                'kode_transfer' => $kode_transfer
            ]);

            //Cek Ledger Transfer
            $ledgertransfer = Ledgertransfer::where('kode_transfer', $kode_transfer)->first();
            if ($ledgertransfer) {
                Ledgersetoranpusat::create([
                    'no_bukti' => $ledgertransfer->no_bukti,
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

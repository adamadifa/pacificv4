<?php

namespace App\Http\Controllers;

use App\Models\Detailtransfer;
use App\Models\Penjualan;
use App\Models\Salesman;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PembayarantransferController extends Controller
{
    public function create($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $penjualan = Penjualan::where('no_faktur', $no_faktur)
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['salesman'] =  Salesman::where('kode_cabang', $penjualan->kode_cabang)
            ->where('status_aktif_salesman', '1')
            ->where('nama_salesman', '!=', '-')
            ->get();
        $data['no_faktur'] = $no_faktur;
        return view('marketing.pembayarantransfer.create', $data);
    }


    public function store(Request $request, $no_faktur)
    {

        $request->validate([
            'tanggal' => 'required',
            'jumlah' => 'required',
            'kode_salesman' => 'required',
            'bank_pengirim' => 'required',
        ]);
        $no_faktur = Crypt::decrypt($no_faktur);
        $tahun = date('Y', strtotime($request->tanggal));
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $penjualan = Penjualan::where('no_faktur', $no_faktur)->first();
            $lastransfer = Transfer::select('kode_transfer')
                ->whereRaw('YEAR(tanggal)="' . $tahun . '"')
                ->orderBy("kode_transfer", "desc")
                ->first();

            $last_kode_transfer = $lastransfer != null ? $lastransfer->kode_transfer : '';
            $kode_transfer  = buatkode($last_kode_transfer, "TR" . $tahun, 4);
            Transfer::create([
                'kode_transfer' => $kode_transfer,
                'kode_pelanggan' => $penjualan->kode_pelanggan,
                'tanggal' => $request->tanggal,
                'kode_salesman' => $request->kode_salesman,
                'bank_pengirim' => $request->bank_pengirim,
                'jatuh_tempo' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'status' => 0,
            ]);

            Detailtransfer::create([
                'kode_transfer' => $kode_transfer,
                'no_faktur' => $no_faktur,
                'jumlah' => toNumber($request->jumlah)
            ]);


            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($no_faktur, $kode_transfer)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_transfer = Crypt::decrypt($kode_transfer);
        $penjualan = Penjualan::where('no_faktur', $no_faktur)
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['salesman'] =  Salesman::where('kode_cabang', $penjualan->kode_cabang)
            ->where('status_aktif_salesman', '1')
            ->where('nama_salesman', '!=', '-')
            ->get();

        $data['transfer'] = Detailtransfer::select(
            'marketing_penjualan_transfer_detail.kode_transfer',
            'marketing_penjualan_transfer.tanggal',
            'bank_pengirim',
            'kode_salesman',
            'marketing_penjualan_transfer_detail.*',
            'jatuh_tempo',
            'status',
            'tanggal_ditolak',
            'keterangan',
        )
            ->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer')
            ->where('marketing_penjualan_transfer_detail.no_faktur', $no_faktur)
            ->where('marketing_penjualan_transfer_detail.kode_transfer', $kode_transfer)
            ->first();
        $data['no_faktur'] = $no_faktur;
        $data['kode_transfer'] = $kode_transfer;
        return view('marketing.pembayarantransfer.edit', $data);
    }

    public function update(Request $request, $no_faktur, $kode_transfer)
    {

        $request->validate([
            'tanggal' => 'required',
            'jumlah' => 'required',
            'kode_salesman' => 'required',
            'bank_pengirim' => 'required',
        ]);
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_transfer = Crypt::decrypt($kode_transfer);
        DB::beginTransaction();
        try {

            $transfer = Transfer::where('kode_transfer', $kode_transfer)->first();
            $cektutuplaporantransfer = cektutupLaporan($transfer->tanggal, "penjualan");
            if ($cektutuplaporantransfer > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            $penjualan = Penjualan::where('no_faktur', $no_faktur)->first();
            Transfer::where('kode_transfer', $kode_transfer)->update([
                'kode_pelanggan' => $penjualan->kode_pelanggan,
                'tanggal' => $request->tanggal,
                'kode_salesman' => $request->kode_salesman,
                'bank_pengirim' => $request->bank_pengirim,
                'jatuh_tempo' => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);

            Detailtransfer::where('kode_transfer', $kode_transfer)->where('no_faktur', $no_faktur)->update([
                'jumlah' => toNumber($request->jumlah)
            ]);


            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($no_faktur, $kode_transfer)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_transfer = Crypt::decrypt($kode_transfer);
        $transfer = Transfer::where('kode_transfer', $kode_transfer)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($transfer->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Detailtransfer::where('no_faktur', $no_faktur)->where('kode_transfer', $kode_transfer)->delete();
            $cekdetailtransfer = Detailtransfer::where('kode_transfer', $kode_transfer)->count();
            if (empty($cekdetailtransfer)) {
                Transfer::where('kode_transfer', $kode_transfer)->delete();
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

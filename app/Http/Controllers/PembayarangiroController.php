<?php

namespace App\Http\Controllers;

use App\Models\Detailgiro;
use App\Models\Giro;
use App\Models\Historibayarpenjualangiro;
use App\Models\Penjualan;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PembayarangiroController extends Controller
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
        return view('marketing.pembayarangiro.create', $data);
    }


    public function store(Request $request, $no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $tahun = date('Y', strtotime($request->tanggal));
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $penjualan = Penjualan::where('no_faktur', $no_faktur)->first();
            $lastgiro = Giro::select('kode_giro')
                ->whereRaw('YEAR(tanggal)="' . $tahun . '"')
                ->orderBy("kode_giro", "desc")
                ->first();

            $last_kode_giro = $lastgiro != null ? $lastgiro->kode_giro : '';
            $kode_giro  = buatkode($last_kode_giro, "GR" . $tahun, 4);
            Giro::create([
                'kode_giro' => $kode_giro,
                'kode_pelanggan' => $penjualan->kode_pelanggan,
                'tanggal' => $request->tanggal,
                'no_giro' => $request->no_giro,
                'kode_salesman' => $request->kode_salesman,
                'bank_pengirim' => $request->bank_pengirim,
                'jatuh_tempo' => $request->jatuh_tempo,
                'keterangan' => $request->keterangan,
                'status' => 0,
            ]);

            Detailgiro::create([
                'kode_giro' => $kode_giro,
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


    public function edit($no_faktur, $kode_giro)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_giro = Crypt::decrypt($kode_giro);
        $penjualan = Penjualan::where('no_faktur', $no_faktur)
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['salesman'] =  Salesman::where('kode_cabang', $penjualan->kode_cabang)
            ->where('status_aktif_salesman', '1')
            ->where('nama_salesman', '!=', '-')
            ->get();

        $data['giro'] = Detailgiro::select(
            'no_giro',
            'marketing_penjualan_giro.tanggal',
            'bank_pengirim',
            'kode_salesman',
            'marketing_penjualan_giro_detail.*',
            'jatuh_tempo',
            'status',
            'tanggal_ditolak',
            'keterangan',
        )
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->where('marketing_penjualan_giro_detail.no_faktur', $no_faktur)
            ->where('marketing_penjualan_giro_detail.kode_giro', $kode_giro)
            ->first();
        $data['no_faktur'] = $no_faktur;
        $data['kode_giro'] = $kode_giro;
        return view('marketing.pembayarangiro.edit', $data);
    }



    public function update(Request $request, $no_faktur, $kode_giro)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_giro = Crypt::decrypt($kode_giro);
        DB::beginTransaction();
        try {

            $giro = Giro::where('kode_giro', $kode_giro)->first();

            $cektutuplaporangiro = cektutupLaporan($giro->tanggal, "penjualan");
            if ($cektutuplaporangiro > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $penjualan = Penjualan::where('no_faktur', $no_faktur)->first();
            Giro::where('kode_giro', $kode_giro)->update([
                'kode_pelanggan' => $penjualan->kode_pelanggan,
                'tanggal' => $request->tanggal,
                'no_giro' => $request->no_giro,
                'kode_salesman' => $request->kode_salesman,
                'bank_pengirim' => $request->bank_pengirim,
                'jatuh_tempo' => $request->jatuh_tempo,
                'keterangan' => $request->keterangan,
            ]);

            Detailgiro::where('kode_giro', $kode_giro)->where('no_faktur', $no_faktur)->update([
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

    public function destroy($no_faktur, $kode_giro)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $kode_giro = Crypt::decrypt($kode_giro);
        $giro = Giro::where('kode_giro', $kode_giro)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($giro->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Detailgiro::where('no_faktur', $no_faktur)->where('kode_giro', $kode_giro)->delete();
            $cekdetailgiro = Detailgiro::where('kode_giro', $kode_giro)->count();
            if (empty($cekdetailgiro)) {
                Giro::where('kode_giro', $kode_giro)->delete();
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

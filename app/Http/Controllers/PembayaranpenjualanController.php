<?php

namespace App\Http\Controllers;

use App\Models\Detailgiro;
use App\Models\Detailpencairan;
use App\Models\Historibayarpenjualan;
use App\Models\Historibayarpenjualangiro;
use App\Models\Jenisvoucher;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Salesman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PembayaranpenjualanController extends Controller
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
        $data['jenis_voucher'] = Jenisvoucher::orderBy('id')->get();
        $data['giroditolak'] = Detailgiro::select('marketing_penjualan_giro_detail.kode_giro', 'no_giro')
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->leftJoin(
                DB::raw("(
                    SELECT
                        kode_giro as cek_pembayaran_giro
                    FROM
                        marketing_penjualan_historibayar_giro
                    GROUP BY kode_giro
                ) pembayaran_giro"),
                function ($join) {
                    $join->on('marketing_penjualan_giro.kode_giro', '=', 'pembayaran_giro.cek_pembayaran_giro');
                }
            )
            ->where('marketing_penjualan_giro_detail.no_faktur', $no_faktur)
            ->where('marketing_penjualan_giro.status', '2')
            ->whereNull('cek_pembayaran_giro')
            ->get();
        $data['no_faktur'] = $no_faktur;
        $saldo_voucher_program = Detailpencairan::where('kode_pelanggan', $penjualan->kode_pelanggan)
            ->join('marketing_program_pencairan', 'marketing_program_pencairan_detail.kode_pencairan', '=', 'marketing_program_pencairan.kode_pencairan')
            ->select(DB::raw("SUM(diskon_kumulatif - diskon_reguler) as jml_voucher"))
            ->where('metode_pembayaran', 'VC')
            ->where('status', '1')
            ->first();
        $tanggal_mulai = date('Y-m-d', strtotime("2025-01-01"));
        $diskonprogram = Historibayarpenjualan::join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->select(DB::raw("SUM(jumlah) as jml_voucher"))
            ->where('marketing_penjualan.kode_pelanggan', $penjualan->kode_pelanggan)
            ->where('jenis_voucher', 2)
            ->where('voucher_reward', 1)
            ->where('marketing_penjualan_historibayar.tanggal', '>=', $tanggal_mulai)
            ->first();
        $saldo_voucher = $saldo_voucher_program->jml_voucher - $diskonprogram->jml_voucher;
        $data['saldo_voucher'] = $saldo_voucher;
        // dd($data['saldo_voucher']);
        return view('marketing.pembayaranpenjualan.create', $data);
    }


    public function store(Request $request, $no_faktur)
    {

        $request->validate([
            'tanggal' => 'required',
            'jumlah' => 'required',
            'kode_salesman' => 'required'
        ]);
        $no_faktur = Crypt::decrypt($no_faktur);
        $penjualan = Penjualan::where('no_faktur', $no_faktur)
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();

        $pelanggan = Pelanggan::where('kode_pelanggan', $penjualan->kode_pelanggan)->first();
        $jenis_transaksi = $penjualan->jenis_transaksi;
        $jenis_bayar = $jenis_transaksi == 'T' ? 'TN' : 'TP';
        $kode_cabang = $pelanggan->kode_cabang;
        $tahun = date('y', strtotime($request->tanggal));
        if (isset($request->agreementvoucher)) {
            $voucher = $request->agreementvoucher;
            $jenis_voucher = $request->jenis_voucher;
            if ($jenis_voucher == '2') {
                $voucher_reward = 1;
            } else {
                $voucher_reward = 0;
            }
        } else {
            $voucher = 0;
            $jenis_voucher = 0;
            $voucher_reward = 0;
        }
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            $lasthistoribayar = Historibayarpenjualan::select('no_bukti')
                ->whereRaw('LEFT(no_bukti,6) = "' . $kode_cabang . $tahun . '-"')
                ->orderBy("no_bukti", "desc")
                ->first();

            $last_no_bukti = $lasthistoribayar != null ? $lasthistoribayar->no_bukti : '';
            $no_bukti  = buatkode($last_no_bukti, $kode_cabang . $tahun . "-", 6);
            Historibayarpenjualan::create([
                'no_bukti' => $no_bukti,
                'tanggal' => $request->tanggal,
                'no_faktur' => $no_faktur,
                'jenis_bayar' => $jenis_bayar,
                'jumlah' => toNumber($request->jumlah),
                'voucher' => $voucher,
                'jenis_voucher' => $jenis_voucher,
                'voucher_reward' => $voucher_reward,
                'kode_salesman' => $request->kode_salesman,
                'id_user' => auth()->user()->id
            ]);
            if (isset($request->agreementgiro)) {
                Historibayarpenjualangiro::create([
                    'no_bukti' => $no_bukti,
                    'kode_giro' => $request->kode_giro,
                    'giro_to_cash' => 1,
                ]);
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $historibayar = Historibayarpenjualan::where('marketing_penjualan_historibayar.no_bukti', $no_bukti)
            ->select('marketing_penjualan_historibayar.*', 'kode_giro', 'giro_to_cash')
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
            ->first();

        //dd($historibayar);
        $no_faktur = $historibayar->no_faktur;
        $penjualan = Penjualan::where('no_faktur', $no_faktur)
            ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
            ->first();
        $data['salesman'] =  Salesman::where('kode_cabang', $penjualan->kode_cabang)
            ->where('status_aktif_salesman', '1')
            ->where('nama_salesman', '!=', '-')
            ->get();
        $data['jenis_voucher'] = Jenisvoucher::orderBy('id')->get();
        $data['giroditolak'] = Detailgiro::select('marketing_penjualan_giro_detail.kode_giro', 'no_giro')
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->leftJoin(
                DB::raw("(
                    SELECT
                        kode_giro as cek_pembayaran_giro
                    FROM
                        marketing_penjualan_historibayar_giro
                    GROUP BY kode_giro
                ) pembayaran_giro"),
                function ($join) {
                    $join->on('marketing_penjualan_giro.kode_giro', '=', 'pembayaran_giro.cek_pembayaran_giro');
                }
            )
            ->where('marketing_penjualan_giro_detail.no_faktur', $no_faktur)
            ->where('marketing_penjualan_giro.status', '2')
            ->get();
        $data['historibayar'] = $historibayar;
        return view('marketing.pembayaranpenjualan.edit', $data);
    }


    public function update(Request $request, $no_bukti)
    {

        $request->validate([
            'tanggal' => 'required',
            'jumlah' => 'required',
            'kode_salesman' => 'required'
        ]);

        $no_bukti = Crypt::decrypt($no_bukti);
        $user = User::findorFail(auth()->user()->id);

        // $penjualan = Penjualan::where('no_faktur', $historibayar->no_faktur)
        //     ->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman')
        //     ->first();
        // $jenis_transaksi = $penjualan->jenis_transaksi;
        // $jenis_bayar = $jenis_transaksi == 'T' ? 'TN' : 'TP';
        if (isset($request->agreementvoucher)) {
            $voucher = $request->agreementvoucher;
            $jenis_voucher = $request->jenis_voucher;
        } else {
            $voucher = 0;
            $jenis_voucher = 0;
        }
        DB::beginTransaction();
        try {
            $historibayar = Historibayarpenjualan::where('marketing_penjualan_historibayar.no_bukti', $no_bukti)
                ->select('marketing_penjualan_historibayar.*', 'kode_giro', 'giro_to_cash')
                ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
                ->first();


            $today = Carbon::now();
            $historiBayarDate = Carbon::parse($historibayar->created_at);
            $diffInDays = $today->diffInDays($historiBayarDate);
            if ($diffInDays > 3 && !$user->hasRole('super admin')) {
                return Redirect::back()->with(messageError('Data tidak dapat di edit karena telah lebih dari 3 hari.'));
            }

            $cektutuplaporanpembayaran = cektutupLaporan($historibayar->tanggal, "penjualan");
            if ($cektutuplaporanpembayaran > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }
            $cektutuplaporan = cektutupLaporan($request->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            Historibayarpenjualan::where('no_bukti', $no_bukti)->update([
                'tanggal' => $request->tanggal,
                // 'jenis_bayar' => $jenis_bayar,
                'jumlah' => toNumber($request->jumlah),
                'voucher' => $voucher,
                'jenis_voucher' => $jenis_voucher,
                'voucher_reward' => $jenis_voucher == '2' ? 1 : 0,
                'kode_salesman' => $request->kode_salesman,
                'id_user' => auth()->user()->id
            ]);

            // dd($request->agreementgiro);
            if (isset($request->agreementgiro)) {

                // dd('jalankan ini');
                Historibayarpenjualangiro::where('no_bukti', $no_bukti)->delete();
                Historibayarpenjualangiro::create([
                    'no_bukti' => $no_bukti,
                    'kode_giro' => $request->kode_giro,
                    'giro_to_cash' => 1
                ]);
            } else {
                Historibayarpenjualangiro::where('no_bukti', $no_bukti)->delete();
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $historibayar = Historibayarpenjualan::where('no_bukti', $no_bukti)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($historibayar->tanggal, "penjualan");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            //Hapus Surat Jalan
            Historibayarpenjualan::where('no_bukti', $no_bukti)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}

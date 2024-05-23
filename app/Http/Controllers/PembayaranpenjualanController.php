<?php

namespace App\Http\Controllers;

use App\Models\Detailgiro;
use App\Models\Jenisvoucher;
use App\Models\Penjualan;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

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
        return view('marketing.pembayaranpenjualan.create', $data);
    }
}

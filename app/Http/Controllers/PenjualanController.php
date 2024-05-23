<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Checkinpenjualan;
use App\Models\Detailgiro;
use App\Models\Detailpenjualan;
use App\Models\Detailretur;
use App\Models\Detailtransfer;
use App\Models\Historibayarpenjualan;
use App\Models\Penjualan;
use App\Models\Retur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PenjualanController extends Controller
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
        $query = Penjualan::query();
        $query->select(
            'marketing_penjualan.*',
            'nama_pelanggan',
            'nama_salesman',
            'nama_cabang'
        );
        $query->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) as total_bruto'));
        $query->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
        WHERE no_faktur = marketing_penjualan.no_faktur AND jenis_retur="PF") as total_retur'));
        $query->addSelect(DB::raw('(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur) as total_bayar'));
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->leftJoin(
            DB::raw("(
                SELECT
                    marketing_penjualan.no_faktur,
                    IF( salesbaru IS NULL, marketing_penjualan.kode_salesman, salesbaru ) AS kode_salesman_baru,
                    IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS kode_cabang_baru
                FROM
                    marketing_penjualan
                INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                LEFT JOIN (
                SELECT
                    MAX(id) AS id,
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                GROUP BY
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru,
                    salesman.kode_cabang
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
            function ($join) {
                $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
            }
        );

        $query->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman');
        $query->join('cabang', 'pindahfaktur.kode_cabang_baru', '=', 'cabang.kode_cabang');

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('marketing_penjualan.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_faktur_search)) {
            $query->where('marketing_penjualan.no_faktur', $request->no_faktur_search);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('kode_cabang_baru', $request->kode_cabang_search);
        }

        if (!empty($request->kode_salesman_search)) {
            $query->where('kode_salesman_baru', $request->kode_salesman_search);
        }

        if (!empty($request->kode_pelanggan_search)) {
            $query->where('marketing_penjualan.kode_pelanggan', $request->kode_pelanggan_search);
        }


        if (!empty($request->nama_pelanggan_search)) {
            $query->WhereRaw("MATCH(nama_pelanggan) AGAINST('" . $request->nama_pelanggan_search .  "')");
        }

        $query->orderBy('marketing_penjualan.tanggal', 'desc');
        $penjualan = $query->cursorPaginate();
        $penjualan->appends(request()->all());
        $data['penjualan'] = $penjualan;
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        return view('marketing.penjualan.index', $data);
    }

    public function show($no_faktur)
    {
        $no_faktur = Crypt::decrypt($no_faktur);
        $data['kepemilikan'] = config('pelanggan.kepemilikan');
        $data['lama_berjualan'] = config('pelanggan.lama_berjualan');
        $data['status_outlet'] = config('pelanggan.status_outlet');
        $data['type_outlet'] = config('pelanggan.type_outlet');
        $data['cara_pembayaran'] = config('pelanggan.cara_pembayaran');
        $data['lama_langganan'] = config('pelanggan.lama_langganan');
        $data['jenis_bayar'] = config('penjualan.jenis_bayar');
        $penjualan = Penjualan::select(
            'marketing_penjualan.*',
            'nama_pelanggan',
            'pelanggan.foto',
            'pelanggan.alamat_pelanggan',
            'pelanggan.status_aktif_pelanggan',
            'pelanggan.nik',
            'pelanggan.no_kk',
            'pelanggan.tanggal_lahir',
            'pelanggan.alamat_toko',
            'pelanggan.hari',
            'pelanggan.no_hp_pelanggan',
            'pelanggan.kepemilikan',
            'pelanggan.lama_berjualan',
            'pelanggan.status_outlet',
            'pelanggan.type_outlet',
            'pelanggan.cara_pembayaran',
            'pelanggan.lama_langganan',
            'pelanggan.jaminan',
            'pelanggan.omset_toko',
            'pelanggan.limit_pelanggan',
            'pelanggan.latitude',
            'pelanggan.longitude',
            'wilayah.nama_wilayah',
            'nama_salesman',
            'nama_cabang',
        )
            ->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) as total_bruto'))
            ->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
        WHERE no_faktur = marketing_penjualan.no_faktur AND jenis_retur="PF") as total_retur'))
            ->addSelect(DB::raw('(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur) as total_bayar'))
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->leftJoin(
                DB::raw("(
                SELECT
                    marketing_penjualan.no_faktur,
                    IF( salesbaru IS NULL, marketing_penjualan.kode_salesman, salesbaru ) AS kode_salesman_baru,
                    IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS kode_cabang_baru
                FROM
                    marketing_penjualan
                INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                LEFT JOIN (
                SELECT
                    MAX(id) AS id,
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                GROUP BY
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru,
                    salesman.kode_cabang
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
                function ($join) {
                    $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
                }
            )

            ->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman')
            ->join('cabang', 'pindahfaktur.kode_cabang_baru', '=', 'cabang.kode_cabang')
            ->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah')
            ->where('marketing_penjualan.no_faktur', $no_faktur)->first();
        $data['penjualan'] = $penjualan;
        $data['detail'] = Detailpenjualan::select('marketing_penjualan_detail.*', 'produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'subtotal')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->where('no_faktur', $no_faktur)
            ->get();

        $data['retur'] = Detailretur::select(
            'tanggal',
            'marketing_retur_detail.*',
            'jenis_retur',
            'produk_harga.kode_produk',
            'nama_produk',
            'isi_pcs_dus',
            'isi_pcs_pack',
            'subtotal'
        )
            ->join('produk_harga', 'marketing_retur_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->where('no_faktur', $no_faktur)
            ->get();

        $data['historibayar'] = Historibayarpenjualan::select(
            'marketing_penjualan_historibayar.*',
            'nama_salesman',
            'marketing_penjualan_historibayar_giro.kode_giro',
            'no_giro',
            'giro_to_cash',
            'nama_voucher'
        )

            ->leftJoin('jenis_voucher', 'marketing_penjualan_historibayar.jenis_voucher', '=', 'jenis_voucher.id')
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_historibayar.no_bukti', '=', 'marketing_penjualan_historibayar_giro.no_bukti')
            ->leftJoin('marketing_penjualan_giro', 'marketing_penjualan_historibayar_giro.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_historibayar.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('no_faktur', $no_faktur)
            ->orderBy('created_at', 'desc')
            ->get();

        $data['giro'] = Detailgiro::select(
            'no_giro',
            'marketing_penjualan_giro.tanggal',
            'bank_pengirim',
            'marketing_penjualan_giro_detail.*',
            'jatuh_tempo',
            'status',
            'tanggal_ditolak',
            'keterangan',
            'marketing_penjualan_historibayar.tanggal as tanggal_diterima',
            'marketing_penjualan_historibayar_giro.no_bukti as no_bukti_giro',
            'nama_salesman'
        )
            ->join('marketing_penjualan_giro', 'marketing_penjualan_giro_detail.kode_giro', '=', 'marketing_penjualan_giro.kode_giro')
            ->join('salesman', 'marketing_penjualan_giro.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin('marketing_penjualan_historibayar_giro', 'marketing_penjualan_giro.kode_giro', '=', 'marketing_penjualan_historibayar_giro.kode_giro')
            ->leftJoin('marketing_penjualan_historibayar', 'marketing_penjualan_historibayar_giro.no_bukti', '=', 'marketing_penjualan_historibayar.no_bukti')
            ->where('marketing_penjualan_giro_detail.no_faktur', $no_faktur)
            ->get();

        $data['transfer'] = Detailtransfer::select(
            'marketing_penjualan_transfer_detail.*',
            'marketing_penjualan_transfer.tanggal',
            'bank_pengirim',
            'jatuh_tempo',
            'status',
            'tanggal_ditolak',
            'keterangan',
            'marketing_penjualan_historibayar.tanggal as tanggal_diterima',
            'nama_salesman'
        )
            ->join('marketing_penjualan_transfer', 'marketing_penjualan_transfer_detail.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer')
            ->join('salesman', 'marketing_penjualan_transfer.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin('marketing_penjualan_historibayar_transfer', 'marketing_penjualan_transfer.kode_transfer', '=', 'marketing_penjualan_historibayar_transfer.kode_transfer')
            ->leftJoin('marketing_penjualan_historibayar', 'marketing_penjualan_historibayar_transfer.no_bukti', '=', 'marketing_penjualan_historibayar.no_bukti')
            ->where('marketing_penjualan_transfer_detail.no_faktur', $no_faktur)
            ->get();

        //dd($data['detail']);
        $data['checkin'] = Checkinpenjualan::where('tanggal', $penjualan->tanggal)->where('kode_pelanggan', $penjualan->kode_pelanggan)->first();
        return view('marketing.penjualan.show', $data);
    }
}

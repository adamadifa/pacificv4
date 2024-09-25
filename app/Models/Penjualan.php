<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Penjualan extends Model
{
    use HasFactory;
    protected $table = "marketing_penjualan";
    protected $primaryKey = "no_faktur";
    protected $guarded = [];
    public $incrementing = false;

    function getFaktur($no_faktur)
    {

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
            'salesman.kode_kategori_salesman as pola_operasi',
            'nama_kategori_salesman',
            'salesman.kode_cabang',
            'pelanggan.kode_cabang_pkp',
            'nama_cabang',
            'telepon_cabang',
            'alamat_cabang',
            'nama_pt',
            'marketing_penjualan.signature',
            'pelanggan.ljt',
            'kode_visit'


        )
            ->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) as total_bruto'))
            ->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
        WHERE no_faktur = marketing_penjualan.no_faktur AND jenis_retur="PF") as total_retur'))
            ->addSelect(DB::raw('(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur) as total_bayar'))
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->leftJoin('worksheetom_visitpelanggan', 'marketing_penjualan.no_faktur', '=', 'worksheetom_visitpelanggan.no_faktur')
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
                    no_faktur,
                    marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                    salesman.kode_cabang AS cabangbaru
                FROM
                    marketing_penjualan_movefaktur
                    INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur)
                ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
            ) pindahfaktur"),
                function ($join) {
                    $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
                }
            )

            ->join('salesman', 'pindahfaktur.kode_salesman_baru', '=', 'salesman.kode_salesman')
            ->join('cabang', 'pindahfaktur.kode_cabang_baru', '=', 'cabang.kode_cabang')
            ->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah')
            ->join('salesman_kategori', 'salesman.kode_kategori_salesman', '=', 'salesman_kategori.kode_kategori_salesman')
            ->where('marketing_penjualan.no_faktur', $no_faktur)->first();

        return $penjualan;
    }


    function getDetailpenjualan($no_faktur)
    {
        $detail = Detailpenjualan::select('marketing_penjualan_detail.*', 'produk_harga.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'subtotal')
            ->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga')
            ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
            ->where('no_faktur', $no_faktur)
            ->get();
        return $detail;
    }



    function getFakturwithDetail($request, $kode_pelanggan = "")
    {
        $query = Penjualan::query();
        $query->select(
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'marketing_penjualan.potongan',
            'marketing_penjualan.potongan_istimewa',
            'marketing_penjualan.penyesuaian',
            'marketing_penjualan.ppn',
            'marketing_penjualan.jenis_transaksi',
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
            'salesman.kode_kategori_salesman as pola_operasi',
            'nama_kategori_salesman',
            'salesman.kode_cabang',
            'nama_cabang',
            'alamat_cabang',
            'nama_pt',
            'marketing_penjualan.signature',
            DB::raw("json_arrayagg(json_object( 'kode_harga', marketing_penjualan_detail.kode_harga,
            'kode_produk',produk_harga.kode_produk,
            'nama_produk',produk.nama_produk,
            'isi_pcs_dus',produk.isi_pcs_dus,
            'isi_pcs_pack',produk.isi_pcs_pack,
            'harga_dus',marketing_penjualan_detail.harga_dus,
            'subtotal',marketing_penjualan_detail.subtotal,
            'jumlah', marketing_penjualan_detail.jumlah)) AS `detail`"),
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
        $query->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('salesman_kategori', 'salesman.kode_kategori_salesman', '=', 'salesman_kategori.kode_kategori_salesman');
        $query->join('marketing_penjualan_detail', 'marketing_penjualan.no_faktur', '=', 'marketing_penjualan_detail.no_faktur');
        $query->join('produk_harga', 'marketing_penjualan_detail.kode_harga', '=', 'produk_harga.kode_harga');
        $query->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk');
        $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->kode_cabang)) {
            $query->where('kode_cabang_baru', $request->kode_cabang);
        }

        if (!empty($request->kode_salesman)) {
            $query->where('kode_salesman_baru', $request->kode_salesman);
        }
        $query->groupBy(
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.kode_pelanggan',
            'marketing_penjualan.potongan',
            'marketing_penjualan.potongan_istimewa',
            'marketing_penjualan.penyesuaian',
            'marketing_penjualan.ppn',
            'marketing_penjualan.jenis_transaksi',
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
            'salesman.kode_kategori_salesman',
            'nama_kategori_salesman',
            'salesman.kode_cabang',
            'nama_cabang',
            'alamat_cabang',
            'nama_pt',
            'total_bruto',
            'total_retur',
            'total_bayar'
        );
        $penjualan = $query->get();

        return $penjualan;
    }

    function getFakturbyPelanggan($request, $kode_pelanggan)
    {
        $query = Penjualan::query();

        $query->select(
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
            'salesman.kode_kategori_salesman as pola_operasi',
            'nama_kategori_salesman',
            'salesman.kode_cabang',
            'nama_cabang',
            'alamat_cabang',
            'nama_pt',
            'signature'
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
        $query->join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah');
        $query->join('salesman_kategori', 'salesman.kode_kategori_salesman', '=', 'salesman_kategori.kode_kategori_salesman');
        $query->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan);
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_penjualan.tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->no_faktur_search)) {
            $query->where('marketing_penjualan.no_faktur', $request->no_faktur_search);
        }
        $query->orderBy('marketing_penjualan.tanggal', 'desc');
        $query->orderBy('marketing_penjualan.no_faktur');
        $penjualan = $query;
        return $penjualan;
    }



    function getPenjualanpelangganbydate($start_date, $end_date, $kode_pelanggan)
    {
        $query = Pelanggan::query();

        $query->select(
            'pelanggan.kode_pelanggan',
            'total_potongan',
            'total_ppn'
        );
        $query->addSelect(DB::raw("(SELECT SUM(subtotal) FROM marketing_penjualan_detail
        INNER JOIN marketing_penjualan ON marketing_penjualan_detail.no_faktur = marketing_penjualan.no_faktur
        WHERE kode_pelanggan = pelanggan.kode_pelanggan  AND status_batal = 0 AND
        marketing_penjualan.tanggal BETWEEN '$start_date' AND '$end_date') as total_bruto"));

        $query->addSelect(DB::raw("(SELECT SUM(subtotal) FROM marketing_retur_detail
        INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
        INNER JOIN marketing_penjualan ON marketing_retur.no_faktur = marketing_penjualan.no_faktur
        WHERE kode_pelanggan = pelanggan.kode_pelanggan AND jenis_retur='PF' AND
        marketing_retur.tanggal BETWEEN '$start_date' AND '$end_date') as total_retur"));

        $query->addSelect(DB::raw("(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar
        INNER JOIN marketing_penjualan ON marketing_penjualan_historibayar.no_faktur = marketing_penjualan.no_faktur
        WHERE kode_pelanggan = pelanggan.kode_pelanggan AND marketing_penjualan_historibayar.tanggal BETWEEN '$start_date' AND '$end_date') as total_bayar"));

        $query->leftJoin(
            DB::raw("(
            SELECT kode_pelanggan,
            SUM(potongan + potongan_istimewa + penyesuaian)  as total_potongan,
            SUM(ppn) as total_ppn
            FROM marketing_penjualan
            WHERE kode_pelanggan = '$kode_pelanggan' AND tanggal BETWEEN '$start_date' AND '$end_date'
            AND status_batal = 0
            GROUP BY kode_pelanggan
        ) penjualan"),
            function ($join) {
                $join->on('pelanggan.kode_pelanggan', '=', 'penjualan.kode_pelanggan');
            }
        );
        $query->where('pelanggan.kode_pelanggan', $kode_pelanggan);
        $penjualan = $query;
        return $penjualan;
    }


    function getPiutangpelanggan($kode_pelanggan)
    {
        $hari_ini = date('Y-m-d');

        $sa = Saldoawalpiutangpelanggan::orderBy('tanggal', 'desc')->first();
        $start_date = $sa != null ? $sa->tanggal : date('Y') . "-01-01";
        $kode_saldo_awal = $sa != null ? $sa->kode_saldo_awal : null;

        $saldo_awal = Detailsaldoawalpiutangpelanggan::select(
            'marketing_penjualan.kode_pelanggan',
            DB::raw('SUM(jumlah) as jumlah')
        )
            ->join('marketing_penjualan', 'marketing_saldoawal_piutang_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
            ->where('kode_saldo_awal', $kode_saldo_awal)
            ->where('status_batal', 0)
            ->groupBy('marketing_penjualan.kode_pelanggan')
            ->first();

        $saldo = $saldo_awal != null ? $saldo_awal->jumlah : 0;
        $end_date = date('Y-m-t', strtotime($hari_ini));

        $pj = new Penjualan();
        $penjualan = $pj->getPenjualanpelangganbydate($start_date, $end_date, $kode_pelanggan)->first();
        $sisa_piutang = $saldo + $penjualan->total_bruto -  $penjualan->total_potongan + $penjualan->total_ppn - $penjualan->total_retur - $penjualan->total_bayar;
        return $sisa_piutang;
    }


    function getFakturkredit($kode_pelanggan)
    {
        $ajuanfaktur = Pengajuanfaktur::where('kode_pelanggan', $kode_pelanggan)
            ->where('status', 1)
            ->orderBy('tanggal', 'desc')
            ->first();
        $jml_faktur = $ajuanfaktur != null ? $ajuanfaktur->jumlah_faktur : 1;
        $siklus_pembayaran = $ajuanfaktur != null ? $ajuanfaktur->siklus_pembayaran : 0;



        $unpaidSales = $this->getListfakturkredit($kode_pelanggan)->count();
        // $faktur_kredit = Penjualan::addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) as total_bruto'))
        //     ->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail
        //     INNER JOIN marketing_retur ON marketing_retur_detail.no_retur = marketing_retur.no_retur
        //     WHERE no_faktur = marketing_penjualan.no_faktur AND jenis_retur="PF") as total_retur'))

        //     ->addSelect(DB::raw('(SELECT SUM(jumlah) FROM marketing_penjualan_historibayar WHERE no_faktur = marketing_penjualan.no_faktur) as total_bayar'))
        //     ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
        //     ->where('marketing_penjualan.kode_pelanggan', $kode_pelanggan)
        //     ->where('jenis_transaksi', 'K')
        //     ->where('total_bruto', '>=', '1000000')
        //     ->count();

        $data = [
            'jml_faktur' => $jml_faktur,
            'siklus_pembayaran' => $siklus_pembayaran,
            'unpaid_faktur' => $unpaidSales
        ];

        return $data;
    }


    function getListfakturkredit($kode_pelanggan)
    {
        // Subquery untuk total penjualan bruto
        $subqueryTotalBruto = DB::table('marketing_penjualan_detail')
            ->select('marketing_penjualan_detail.no_faktur', DB::raw('SUM(subtotal) as total_bruto'))
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('kode_pelanggan', $kode_pelanggan)
            ->groupBy('no_faktur');

        // Subquery untuk total retur
        $subqueryTotalRetur = DB::table('marketing_retur_detail')
            ->select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as total_retur'))
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('kode_pelanggan', $kode_pelanggan)
            ->where('jenis_retur', 'PF')
            ->groupBy('no_faktur');

        // Subquery untuk total pembayaran
        $subqueryTotalPembayaran = DB::table('marketing_penjualan_historibayar')
            ->select('marketing_penjualan_historibayar.no_faktur', DB::raw('SUM(jumlah) as total_pembayaran'))
            ->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('kode_pelanggan', $kode_pelanggan)
            ->groupBy('no_faktur');

        $unpaidSales = Penjualan::select(
            'marketing_penjualan.no_faktur',
            'bruto.total_bruto',
            'retur.total_retur',
            'potongan',
            'potongan_istimewa',
            'penyesuaian',
            'ppn',
            'pembayaran.total_pembayaran'
        )
            ->selectRaw('COALESCE(bruto.total_bruto, 0) - COALESCE(retur.total_retur, 0) - COALESCE(potongan, 0) - COALESCE(potongan_istimewa, 0) - COALESCE(penyesuaian, 0) + COALESCE(ppn, 0) - COALESCE(pembayaran.total_pembayaran, 0) as sisa_piutang')
            ->leftJoinSub($subqueryTotalBruto, 'bruto', 'marketing_penjualan.no_faktur', '=', 'bruto.no_faktur')
            ->leftJoinSub($subqueryTotalRetur, 'retur', 'marketing_penjualan.no_faktur', '=', 'retur.no_faktur')
            ->leftJoinSub($subqueryTotalPembayaran, 'pembayaran', 'marketing_penjualan.no_faktur', '=', 'pembayaran.no_faktur')
            ->where('marketing_penjualan.status_batal', 0)
            ->where('kode_pelanggan', $kode_pelanggan)
            ->havingRaw('sisa_piutang > 0');
        return $unpaidSales;
    }


    function getpiutangFaktur($no_faktur)
    {
        // Subquery untuk total penjualan bruto
        $subqueryTotalBruto = DB::table('marketing_penjualan_detail')
            ->select('marketing_penjualan_detail.no_faktur', DB::raw('SUM(subtotal) as total_bruto'))
            ->join('marketing_penjualan', 'marketing_penjualan_detail.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('marketing_penjualan_detail.no_faktur', $no_faktur)
            ->groupBy('no_faktur');

        // Subquery untuk total retur
        $subqueryTotalRetur = DB::table('marketing_retur_detail')
            ->select('marketing_retur.no_faktur', DB::raw('SUM(subtotal) as total_retur'))
            ->join('marketing_retur', 'marketing_retur_detail.no_retur', '=', 'marketing_retur.no_retur')
            ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('marketing_retur.no_faktur', $no_faktur)
            ->where('jenis_retur', 'PF')
            ->groupBy('no_faktur');

        // Subquery untuk total pembayaran
        $subqueryTotalPembayaran = DB::table('marketing_penjualan_historibayar')
            ->select('marketing_penjualan_historibayar.no_faktur', DB::raw('SUM(jumlah) as total_pembayaran'))
            ->join('marketing_penjualan', 'marketing_penjualan_historibayar.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->where('marketing_penjualan_historibayar.no_faktur', $no_faktur)
            ->groupBy('no_faktur');

        $unpaidSales = Penjualan::select(
            'marketing_penjualan.no_faktur',
            'bruto.total_bruto',
            'retur.total_retur',
            'potongan',
            'potongan_istimewa',
            'penyesuaian',
            'ppn',
            'pembayaran.total_pembayaran'
        )
            ->selectRaw('COALESCE(bruto.total_bruto, 0) - COALESCE(retur.total_retur, 0) - COALESCE(potongan, 0) - COALESCE(potongan_istimewa, 0) - COALESCE(penyesuaian, 0) + COALESCE(ppn, 0) - COALESCE(pembayaran.total_pembayaran, 0) as sisa_piutang')
            ->leftJoinSub($subqueryTotalBruto, 'bruto', 'marketing_penjualan.no_faktur', '=', 'bruto.no_faktur')
            ->leftJoinSub($subqueryTotalRetur, 'retur', 'marketing_penjualan.no_faktur', '=', 'retur.no_faktur')
            ->leftJoinSub($subqueryTotalPembayaran, 'pembayaran', 'marketing_penjualan.no_faktur', '=', 'pembayaran.no_faktur')
            ->where('marketing_penjualan.no_faktur', $no_faktur)
            ->where('marketing_penjualan.status_batal', 0)
            ->havingRaw('sisa_piutang > 0');
        return $unpaidSales;
    }
}

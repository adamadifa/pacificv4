<?php

namespace App\Http\Controllers;

use App\Models\Barangmasukmaintenance;
use App\Models\Barangpembelian;
use App\Models\Detailbarangkeluarmaintenance;
use App\Models\Detailbarangmasukmaintenance;
use App\Models\Detailsaldoawalbahanbakar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanmaintenanceController extends Controller
{
    public function index()
    {
        $kode_barang = ['GA-002', 'GA-007', 'GA-588'];
        $data['barang'] = Barangpembelian::whereIn('kode_barang', $kode_barang)->get();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('maintenance.laporan.index', $data);
    }

    public function cetakbahanbakar(Request $request)
    {

        $dari = $request->tahun . "-" . $request->bulan . "-01";
        $sampai = date('Y-m-t', strtotime($dari));

        $data['saldo_awal'] = Detailsaldoawalbahanbakar::join('maintenance_saldoawal_bahanbakar', 'maintenance_saldoawal_bahanbakar_detail.kode_saldo_awal', 'maintenance_saldoawal_bahanbakar.kode_saldo_awal')
            ->select('jumlah', 'harga')
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->where('kode_barang', $request->kode_barang)
            ->first();





        $qpemasukan =  Detailbarangmasukmaintenance::query();
        $qpemasukan->select(
            'maintenance_barang_masuk.tanggal',
            DB::raw("SUM(IF(maintenance_barang_masuk.status = '1' , maintenance_barang_masuk_detail.jumlah ,0 )) AS qty_pembelian"),
            DB::raw("SUM(IF(maintenance_barang_masuk.status = '2' , maintenance_barang_masuk_detail.jumlah ,0 )) AS qty_lainnya"),
            DB::raw("SUM(IF(maintenance_barang_masuk.status = '2' , maintenance_barang_masuk_detail.harga ,0 )) AS harga_lainnya"),
            DB::raw("SUM(pembelian.harga) / COUNT(maintenance_barang_masuk.no_bukti) as harga_pembelian"),
            DB::raw("SUM(pembelian.penyesuaian) as penyesuaian"),
            DB::raw("SUM(0) as qty_keluar"),
            DB::raw("SUM(0) as qty_keluar_lainnya")
        );
        $qpemasukan->join('maintenance_barang_masuk', 'maintenance_barang_masuk_detail.no_bukti', '=', 'maintenance_barang_masuk.no_bukti');
        $qpemasukan->leftJoin(
            DB::raw("(
                    SELECT pembelian_detail.no_bukti,kode_barang,harga,penyesuaian FROM pembelian_detail
                    INNER JOIN pembelian ON pembelian_detail.no_bukti=pembelian.no_bukti
                    WHERE pembelian.tanggal BETWEEN '$dari' AND '$sampai'
                    GROUP BY pembelian_detail.no_bukti,kode_barang,harga,penyesuaian
                ) pembelian"),
            function ($join) {
                $join->on('maintenance_barang_masuk_detail.no_bukti', '=', 'pembelian.no_bukti');
                $join->on('maintenance_barang_masuk_detail.kode_barang', '=', 'pembelian.kode_barang');
            }
        );
        $qpemasukan->where('maintenance_barang_masuk_detail.kode_barang', $request->kode_barang);
        $qpemasukan->whereBetween('maintenance_barang_masuk.tanggal', [$dari, $sampai]);
        $qpemasukan->orderBy('maintenance_barang_masuk.tanggal');
        $qpemasukan->groupBy('maintenance_barang_masuk.tanggal');



        $qpengeluaran = Detailbarangkeluarmaintenance::query();
        $qpengeluaran->select(
            'maintenance_barang_keluar.tanggal',
            DB::raw("SUM(0) as qty_pembelian"),
            DB::raw("SUM(0) as qty_lainnya"),
            DB::raw("SUM(0) as harga_lainnya"),
            DB::raw("SUM(0) as harga_pembelian"),
            DB::raw("SUM(0) as penyesuaian"),
            DB::raw("SUM(IF(maintenance_barang_keluar.status = '1' , maintenance_barang_keluar_detail.jumlah ,0 )) AS qty_keluar"),
            DB::raw("SUM(IF(maintenance_barang_keluar.status = '2' , maintenance_barang_keluar_detail.jumlah ,0 )) AS qty_keluar_lainnya"),
        );
        $qpengeluaran->join('maintenance_barang_keluar', 'maintenance_barang_keluar_detail.no_bukti', '=', 'maintenance_barang_keluar.no_bukti');
        $qpengeluaran->where('maintenance_barang_keluar_detail.kode_barang', $request->kode_barang);
        $qpengeluaran->whereBetween('maintenance_barang_keluar.tanggal', [$dari, $sampai]);
        $qpengeluaran->orderBy('maintenance_barang_keluar.tanggal');
        $qpengeluaran->groupBy('maintenance_barang_keluar.tanggal');


        $qmutasi = $qpemasukan->unionAll($qpengeluaran)->get();
        $data['rekapbahanbakar'] = $qmutasi->groupBy('tanggal')
            ->map(function ($item) {
                return [
                    'tanggal' => $item->first()->tanggal,
                    'qty_pembelian' => $item->sum('qty_pembelian'),
                    'qty_lainnya' => $item->sum('qty_lainnya'),
                    'qty_keluar' => $item->sum('qty_keluar'),
                    'qty_keluar_lainnya' => $item->sum('qty_keluar_lainnya'),
                    'harga_pembelian' => $item->sum('harga_pembelian'),
                    'harga_lainnya' => $item->sum('harga_lainnya'),
                    'penyesuaian' => $item->sum('penyesuaian'),
                ];
            })
            ->sortBy('tanggal')
            ->values()
            ->all();

        $data['dari'] = $dari;
        $data['sampai'] = $sampai;
        if (isset($_POST['exportButton'])) {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "-SahabatEkspor.xls"
            header("Content-Disposition: attachment; filename=Rekap Bahan Bakar.xls");
        }


        return view('maintenance.laporan.rekapbahanbakar_cetak', $data);
    }
}

<?php

namespace App\Charts;

use App\Models\Detailmutasiproduksi;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class HasilproduksiChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($tahun): \ArielMejiaDev\LarapexCharts\LineChart
    {

        function myfunction($num)
        {
            return ($num * 1);
        }

        $nama_bulan_singkat = config('global.nama_bulan_singkat');
        $select_bulan = "";
        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = $nama_bulan_singkat[$i];
            $select_bulan .= "SUM(IF(MONTH(tanggal_mutasi)='$i' AND jenis_mutasi='BPBJ',jumlah,0)) as " . $nama_bulan_singkat[$i] . ",";
        }
        $rekap = Detailmutasiproduksi::selectRaw("
            $select_bulan
            kode_produk
        ")
            ->whereRaw("YEAR(tanggal_mutasi)='$tahun'")
            ->join("produksi_mutasi", "produksi_mutasi_detail.no_mutasi", "=", "produksi_mutasi.no_mutasi")
            ->groupBy("kode_produk")
            ->orderBy("kode_produk")
            ->get();

        foreach ($rekap as $d) {
            for ($i = 1; $i <= 12; $i++) {
                ${"jmlbln$d->kode_produk"}[] = $d->{$nama_bulan_singkat[$i]};
            }

            ${$d->kode_produk} = ${"jmlbln$d->kode_produk"};
        }
        //dd($AR);
        return $this->chart->lineChart()

            ->addData('AB', $AB)
            ->addData('AR', $AR)
            ->addData('AS', $AS)
            ->addData('BB', $BB)
            ->addData('BR20', $BR20)
            ->addData('DEP', $DEP)

            ->setHeight(300)
            ->setXAxis($bulan);
    }
}

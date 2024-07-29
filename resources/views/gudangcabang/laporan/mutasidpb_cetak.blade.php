<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Persediaan Mutasi DPB {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
    .freeze-table {
      height: auto;
      max-height: 795px;
      overflow: auto;
    }
  </style> --}}
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN BARANG PERSEDIAAN MUTASI DPB <br>
        </h4>
        <h4>CABANG {{ textUpperCase($cabang->nama_cabang) }}</h4>
        <h4>{{ textUpperCase($produk->nama_produk) }}</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">TANGGAL</th>
                        <th class="green" rowspan="2">PUSAT</th>
                        <th class="red" rowspan="2">PENGAMBILAN</th>
                        <th class="green" rowspan="2">PENGEMBALIAN</th>
                        <th class="red" colspan="3">REJECT</th>
                        <th class="green" rowspan="2">REPACK</th>
                        <th colspan="2">PENYESUAIAN</th>
                        <th rowspan="2">SALDO AKHIR</th>
                    </tr>
                    <tr>
                        <th class="red">REJECT PASAR</th>
                        <th class="red">REJECT MOBIL</th>
                        <th class="red">REJECT GUDANG</th>

                        <th class="green">MASUK</th>
                        <th class="red">KELUAR</th>
                    </tr>
                    <tr>
                        <th>SALDO AWAL</th>
                        <th colspan="9"></th>
                        <th class="right">{{ formatAngkaDesimal($saldo_awal) }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_pusat = 0;
                        $total_pengambilan = 0;
                        $total_pengembalian = 0;
                        $total_reject_pasar = 0;
                        $total_reject_mobil = 0;
                        $total_reject_gudang = 0;
                        $total_penyesuaian_in = 0;
                        $total_penyesuaian_out = 0;
                        $total_repack = 0;
                        $saldo_akhir_desimal = 0;
                        $saldo_akhir_jumlah = $saldo_awal_jumlah;
                    @endphp
                    @foreach ($mutasidpb as $d)
                        @php
                            $pusat = $d['pusat'] / $produk->isi_pcs_dus;
                            $jml_pengambilan = $d['jml_pengambilan'] / $produk->isi_pcs_dus;
                            $jml_pengembalian = $d['jml_pengembalian'] / $produk->isi_pcs_dus;
                            $reject_pasar = $d['reject_pasar'] / $produk->isi_pcs_dus;
                            $reject_mobil = $d['reject_mobil'] / $produk->isi_pcs_dus;
                            $reject_gudang = $d['reject_gudang'] / $produk->isi_pcs_dus;
                            $penyesuaian_in = $d['penyesuaian_in'] / $produk->isi_pcs_dus;
                            $penyesuaian_out = $d['penyesuaian_out'] / $produk->isi_pcs_dus;
                            $repack = $d['repack'] / $produk->isi_pcs_dus;

                            $jml_penerimaan = $d['pusat'] + $d['jml_pengembalian'] + $d['penyesuaian_in'] + $d['repack'];
                            $jml_pengeluaran =
                                $d['jml_pengambilan'] + $d['reject_pasar'] + $d['reject_gudang'] + $d['reject_mobil'] + $d['penyesuaian_out'];
                            $saldo_akhir_jumlah = $saldo_akhir_jumlah + $jml_penerimaan - $jml_pengeluaran;
                            $saldo_akhir_desimal = $saldo_akhir_jumlah / $produk->isi_pcs_dus;

                            $total_pusat += $d['pusat'];
                            $total_pengambilan += $d['jml_pengambilan'];
                            $total_pengembalian += $d['jml_pengembalian'];
                            $total_reject_pasar += $d['reject_pasar'];
                            $total_reject_mobil += $d['reject_mobil'];
                            $total_reject_gudang += $d['reject_gudang'];
                            $total_penyesuaian_in += $d['penyesuaian_in'];
                            $total_penyesuaian_out += $d['penyesuaian_out'];
                            $total_repack += $d['repack'];
                        @endphp
                        <tr>
                            <td>{{ DateToIndo($d['tanggal']) }}</td>
                            <td class="right">{{ formatAngkaDesimal($pusat) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jml_pengambilan) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jml_pengembalian) }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_pasar) }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_mobil) }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_gudang) }}</td>
                            <td class="right">{{ formatAngkaDesimal($repack) }}</td>
                            <td class="right">{{ formatAngkaDesimal($penyesuaian_in) }}</td>
                            <td class="right">{{ formatAngkaDesimal($penyesuaian_out) }}</td>
                            <td class="right">{{ formatAngkaDesimal($saldo_akhir_desimal) }}</td>
                        </tr>
                    @endforeach
                <tfoot>
                    @php
                        $total_pusat_desimal = $total_pusat / $produk->isi_pcs_dus;
                        $total_pengambilan_desimal = $total_pengambilan / $produk->isi_pcs_dus;
                        $total_pengembalian_desimal = $total_pengembalian / $produk->isi_pcs_dus;
                        $total_reject_pasar_desimal = $total_reject_pasar / $produk->isi_pcs_dus;
                        $total_reject_mobil_desimal = $total_reject_mobil / $produk->isi_pcs_dus;
                        $total_reject_gudang_desimal = $total_reject_gudang / $produk->isi_pcs_dus;
                        $total_penyesuaian_in_desimal = $total_penyesuaian_in / $produk->isi_pcs_dus;
                        $total_penyesuaian_out_desimal = $total_penyesuaian_out / $produk->isi_pcs_dus;
                        $total_repack_desimal = $total_repack / $produk->isi_pcs_dus;
                    @endphp
                    <tr>
                        <th>TOTAL</th>
                        <th class="right">{{ formatAngkaDesimal($total_pusat_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_pengambilan_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_pengembalian_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_reject_pasar_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_reject_mobil_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_reject_gudang_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_repack_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_penyesuaian_in_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_penyesuaian_out_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($saldo_akhir_desimal) }}</th>
                    </tr>
                </tfoot>
                </tbody>
            </table>
        </div>
    </div>
</body>

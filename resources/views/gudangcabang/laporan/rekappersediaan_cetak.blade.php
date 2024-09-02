<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Persediaan Gudang Bad Stok Gudang Cabang {{ date('Y-m-d H:i:s') }}</title>
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
    <style>
        .datatable3 th {
            font-size: 11px !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAPITULASI PERSEDIAAN GOOD STOK <br>
        </h4>
        <h4>CABANG {{ textUpperCase($cabang->nama_cabang) }}</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">KODE</th>
                        <th rowspan="2">PRODUK</th>
                        <th rowspan="2">SALDO AWAL</th>
                        <th colspan="6" class="green">PENERIMAAN</th>
                        <th colspan="8" class="red">PENGELUARAN</th>
                        <th rowspan="2">SALDO AKHIR</th>
                        <th colspan="3">SALDO AKHIR</th>
                    </tr>
                    <tr>
                        <th class="green">PUSAT</th>
                        <th class="green">TRANSIT IN</th>
                        <th class="green">RETUR</th>
                        <th class="green">LAIN LAIN</th>
                        <th class="green">REPACK</th>
                        <th class="green">PENYESUAIAN</th>
                        <th class="red">PENJUALAN</th>
                        <th class="red">PROMOSI</th>
                        <th class="red">REJECT PASAR</th>
                        <th class="red">REJECT MOBIL</th>
                        <th class="red">REJECT GUDANG</th>
                        <th class="red">TRANSIT OUT</th>
                        <th class="red">LAIN LAIN</th>
                        <th class="red">PENYESUAIAN</th>
                        <th>DUS</th>
                        <th>PACK</th>
                        <th>PCS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekapgs as $d)
                        @php
                            $saldo_awal = $d->saldo_awal / $d->isi_pcs_dus;
                            $pusat = $d->pusat / $d->isi_pcs_dus;
                            $transit_in = $d->transit_in / $d->isi_pcs_dus;
                            $retur = $d->retur / $d->isi_pcs_dus;
                            $jml_lainlain_in = $d->hutang_kirim + $d->pelunasan_ttr + $d->penyesuaian_bad;
                            $lainlain_in = $jml_lainlain_in / $d->isi_pcs_dus;
                            $repack = $d->repack / $d->isi_pcs_dus;
                            $penyesuaian_in = $d->penyesuaian_in / $d->isi_pcs_dus;
                            $jml_penerimaan = $d->pusat + $d->transit_in + $d->retur + $jml_lainlain_in + $d->repack + $d->penyesuaian_in;

                            $penjualan = $d->penjualan / $d->isi_pcs_dus;
                            $promosi = $d->promosi / $d->isi_pcs_dus;
                            $reject_pasar = $d->reject_pasar / $d->isi_pcs_dus;
                            $reject_mobil = $d->reject_mobil / $d->isi_pcs_dus;
                            $reject_gudang = $d->reject_gudang / $d->isi_pcs_dus;
                            $transit_out = $d->transit_out / $d->isi_pcs_dus;
                            $jml_lainlain_out = $d->pelunasan_hutangkirim + $d->ganti_barang + $d->ttr;
                            $lainlain_out = $jml_lainlain_out / $d->isi_pcs_dus;
                            $penyesuaian_out = $d->penyesuaian_out / $d->isi_pcs_dus;
                            $jml_pengeluaran =
                                $d->penjualan +
                                $d->promosi +
                                $d->reject_pasar +
                                $d->reject_mobil +
                                $d->reject_gudang +
                                $d->transit_out +
                                $jml_lainlain_out +
                                $d->penyesuaian_out;

                            $saldo_akhir_jumlah = $d->saldo_awal + $jml_penerimaan - $jml_pengeluaran;
                            $saldo_akhir_real = $saldo_akhir_jumlah;
                            $saldo_akhir_jumlah = $saldo_akhir_jumlah < 0 ? $saldo_akhir_jumlah * -1 : $saldo_akhir_jumlah;
                            $saldo_akhir_desimal = $saldo_akhir_real / $d->isi_pcs_dus;

                            $saldo_akhir_konversi = convertToduspackpcsv3($d->isi_pcs_dus, $d->isi_pcs_pack, $saldo_akhir_jumlah);
                            $saldo_akhir_dus = $saldo_akhir_konversi[0];
                            $saldo_akhir_pack = $saldo_akhir_konversi[1];
                            $saldo_akhir_pcs = $saldo_akhir_konversi[2];
                        @endphp
                        <tr>
                            <td>{{ $d->kode_produk }}</td>
                            <td>{{ textUpperCase($d->nama_produk) }}</td>
                            <td class="right">{{ formatAngkaDesimal($saldo_awal) }}</td>
                            <td class="right">{{ formatAngkaDesimal($pusat) }}</td>
                            <td class="right">{{ formatAngkaDesimal($transit_in) }}</td>
                            <td class="right">{{ formatAngkaDesimal($retur) }}</td>
                            <td class="right">{{ formatAngkaDesimal($lainlain_in) }}</td>
                            <td class="right">{{ formatAngkaDesimal($repack) }}</td>
                            <td class="right">{{ formatAngkaDesimal($penyesuaian_in) }}</td>

                            <td class="right">{{ formatAngkaDesimal($penjualan) }}</td>
                            <td class="right">{{ formatAngkaDesimal($promosi) }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_pasar) }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_mobil) }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_gudang) }}</td>
                            <td class="right">{{ formatAngkaDesimal($transit_out) }}</td>
                            <td class="right">{{ formatAngkaDesimal($lainlain_out) }}</td>
                            <td class="right">{{ formatAngkaDesimal($penyesuaian_out) }}</td>
                            <td class="right">{{ formatAngkaDesimal($saldo_akhir_desimal) }}</td>
                            <td class="right">{{ formatAngka($saldo_akhir_dus) }}</td>
                            <td class="right">{{ formatAngka($saldo_akhir_pack) }}</td>
                            <td class="right">{{ formatAngka($saldo_akhir_pcs) }}</td>
                        </tr>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="header">
        <h4 class="title">
            REKAPITULASI PERSEDIAAN BAD STOK <br>
        </h4>
        <h4>CABANG {{ textUpperCase($cabang->nama_cabang) }}</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2">KODE</th>
                    <th rowspan="2">PRODUK</th>
                    <th rowspan="2">SALDO AWAL</th>
                    <th colspan="4" class="green">PENERIMAAN</th>
                    <th colspan="3" class="red">PENGELUARAN</th>
                    <th rowspan="2">SALDO AKHIR</th>
                    <th colspan="3">SALDO AKHIR</th>
                </tr>
                <tr>
                    <th class="green">REJECT PASAR</th>
                    <th class="green">REJECT MOBIL</th>
                    <th class="green">REJECT GUDANG</th>
                    <th class="green">PENYESUAIAN</th>
                    <th class="red">KIRIM PUSAT</th>
                    <th class="red">REPACK</th>
                    <th class="red">PENYESUAIAN</th>
                    <th>DUS</th>
                    <th>PACK</th>
                    <th>PCS</th>
            </thead>
            <tbody>
                @foreach ($rekapbs as $d)
                    @php
                        $saldo_awal_bs = $d->saldo_awal / $d->isi_pcs_dus;
                        $reject_pasar_bs = $d->reject_pasar / $d->isi_pcs_dus;
                        $reject_mobil_bs = $d->reject_mobil / $d->isi_pcs_dus;
                        $reject_gudang_bs = $d->reject_gudang / $d->isi_pcs_dus;
                        $penyesuaian_in_bs = $d->penyesuaian_bad_in / $d->isi_pcs_dus;
                        $kirim_pusat_bs = $d->kirim_pusat / $d->isi_pcs_dus;
                        $repack_bs = $d->repack / $d->isi_pcs_dus;
                        $penyesuaian_out_bs = $d->penyesuaian_bad_out / $d->isi_pcs_dus;

                        $jml_penerimaan_bs = $d->reject_pasar + $d->reject_mobil + $d->reject_gudang + $d->penyesuaian_bad_in;
                        $jml_pengeluaran_bs = $d->kirim_pusat + $d->repack + $d->penyesuaian_bad_out;

                        $saldo_akhir_jumlah_bs = $d->saldo_awal + $jml_penerimaan_bs - $jml_pengeluaran_bs;
                        $saldo_akhir_real_bs = $saldo_akhir_jumlah_bs;
                        $saldo_akhir_jumlah_bs = $saldo_akhir_jumlah_bs < 0 ? $saldo_akhir_jumlah_bs * -1 : $saldo_akhir_jumlah_bs;
                        $saldo_akhir_desimal_bs = $saldo_akhir_real_bs / $d->isi_pcs_dus;

                        $saldo_akhir_konversi_bs = convertToduspackpcsv3($d->isi_pcs_dus, $d->isi_pcs_pack, $saldo_akhir_jumlah_bs);
                        $saldo_akhir_dus_bs = $saldo_akhir_konversi_bs[0];
                        $saldo_akhir_pack_bs = $saldo_akhir_konversi_bs[1];
                        $saldo_akhir_pcs_bs = $saldo_akhir_konversi_bs[2];
                    @endphp
                    <tr>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        <td class="right">{{ formatAngkaDesimal($saldo_awal_bs) }}</td>
                        <td class="right">{{ formatAngkaDesimal($reject_pasar_bs) }}</td>
                        <td class="right">{{ formatAngkaDesimal($reject_mobil_bs) }}</td>
                        <td class="right">{{ formatAngkaDesimal($reject_gudang_bs) }}</td>
                        <td class="right">{{ formatAngkaDesimal($penyesuaian_in_bs) }}</td>
                        <td class="right">{{ formatAngkaDesimal($kirim_pusat_bs) }}</td>
                        <td class="right">{{ formatAngkaDesimal($repack_bs) }}</td>
                        <td class="right">{{ formatAngkaDesimal($penyesuaian_out_bs) }}</td>
                        <td class="right">{{ formatAngkaDesimal($saldo_akhir_desimal_bs) }}</td>
                        <td class="right">{{ formatAngka($saldo_akhir_dus_bs) }}</td>
                        <td class="right">{{ formatAngka($saldo_akhir_pack_bs) }}</td>
                        <td class="right">{{ formatAngka($saldo_akhir_pcs_bs) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

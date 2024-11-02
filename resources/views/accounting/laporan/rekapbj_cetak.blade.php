<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap BJ {{ date('Y-m-d H:i:s') }}</title>
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
            REKAPITULASI PERSEDIAAN BARANG JADI <br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 300%">
                <thead>
                    <tr>
                        <th rowspan="3">NO</th>
                        <th rowspan="3">PRODUK</th>
                        <th colspan="{{ count($cabang) * 3 + 6 }}">CABANG</th>
                    </tr>
                    <tr>
                        @foreach ($cabang as $c)
                            <th colspan="3">{{ textupperCase($c->nama_cabang) }}</th>
                        @endforeach
                        <th colspan="3">GD PUSAT</th>
                        <th colspan="3">JUMLAH</th>
                    </tr>
                    <tr>
                        @foreach ($cabang as $c)
                            <th>QTY</th>
                            <th>HARGA</th>
                            <th>JUMLAH</th>
                        @endforeach
                        <th>QTY</th>
                        <th>HARGA</th>
                        <th>JUMLAH</th>

                        <th>QTY</th>
                        <th>HARGA</th>
                        <th>JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cabang as $c)
                        @php
                            ${"grandtotal_harga_$c->kode_cabang"} = 0;
                        @endphp
                    @endforeach
                    @php
                        $grandtotal_harga_gudang = 0;
                        $grandtotal_harga_saldoakhir = 0;
                    @endphp
                    @foreach ($rekapbj as $d)
                        @php
                            $harga_gudang =
                                $d->saldoawal_produksi + $d->produksi_bpbj != 0
                                    ? $d->saldoawal_produksi * $d->hargaawal_produksi +
                                        ($d->produksi_bpbj * $d->harga_hpp) / ($d->saldoawal_produksi + $d->produksi_bpbj)
                                    : 0;

                            $harga_kirim_cabang =
                                $d->saldoawal_gudangjadi + $d->gudangjadi_fsthp + $d->gudangjadi_repack + $d->gudangjadi_lainlain_in != 0
                                    ? ($d->saldoawal_gudangjadi * $d->hargaawal_gudang +
                                            $d->gudangjadi_fsthp * $harga_gudang +
                                            $d->gudangjadi_repack * $harga_gudang +
                                            $d->gudangjadi_lainlain_in * $harga_gudang) /
                                        ($d->saldoawal_gudangjadi + $d->gudangjadi_fsthp + $d->gudangjadi_repack + $d->gudangjadi_lainlain_in)
                                    : 0;

                            $saldoakhir_gudangpusat =
                                $d->saldoawal_gudangjadi +
                                ($d->gudangjadi_fsthp + $d->gudangjadi_repack + $d->gudangjadi_lainlain_in) -
                                ($d->gudangjadi_suratjalan + $d->gudangjadi_reject + $d->gudangjadi_lainlain_out);
                            $total_harga_gudang = $saldoakhir_gudangpusat * $harga_kirim_cabang;
                            $grandtotal_harga_gudang += $total_harga_gudang;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }} </td>

                            <td>{{ textupperCase($d->nama_produk) }}</td>
                            @php
                                $total_qty_allcabang = 0;
                                $total_harga_allcabang = 0;
                            @endphp
                            @foreach ($cabang as $c)
                                @php
                                    $saldoawal_cabang = $d->{"saldoawal_$c->kode_cabang"};
                                    $sisamutasi_cabang = $d->{"mutasi_$c->kode_cabang"};
                                    $saldoakhir_cabang = $saldoawal_cabang + $sisamutasi_cabang;
                                    $saldoakhir_cabang_desimal = $saldoakhir_cabang / $d->isi_pcs_dus;
                                    $total_qty_allcabang += ROUND($saldoakhir_cabang_desimal, 2);

                                    $qty_sa_cabang = ROUND($d->{"saldoawal_$c->kode_cabang"} / $d->isi_pcs_dus, 2);
                                    $qty_pusat_cabang = ROUND($d->{"pusat_$c->kode_cabang"} / $d->isi_pcs_dus, 2);
                                    $qty_transit_in_cabang = ROUND($d->{"transit_in_$c->kode_cabang"} / $d->isi_pcs_dus, 2);
                                    $qty_retur_cabang = ROUND($d->{"retur_$c->kode_cabang"} / $d->isi_pcs_dus, 2);
                                    $qty_lainlain_in_cabang = ROUND($d->{"hutang_kirim_$c->kode_cabang"} / $d->isi_pcs_dus, 2);
                                    $qty_repack_cabang = ROUND($d->{"repack_$c->kode_cabang"} / $d->isi_pcs_dus, 2);

                                    $total_qty_cabang =
                                        $qty_sa_cabang +
                                        $qty_pusat_cabang +
                                        $qty_transit_in_cabang +
                                        $qty_retur_cabang +
                                        $qty_lainlain_in_cabang +
                                        $qty_repack_cabang;
                                    $harga_cabang = !empty($harga_kirim_cabang) ? $harga_kirim_cabang : $d->{"hargaawal_$c->kode_cabang"};
                                    $harga_sa_cabang = $qty_sa_cabang * $d->{"hargaawal_$c->kode_cabang"};
                                    $harga_pusat_cabang = $qty_pusat_cabang = $qty_pusat_cabang * $harga_cabang;
                                    $harga_transit_in_cabang = $qty_transit_in_cabang * $harga_cabang;
                                    $harga_retur_cabang = $qty_retur_cabang * $harga_cabang;
                                    $harga_lainlain_cabang = $qty_lainlain_in_cabang * $harga_cabang;
                                    $harga_repack_cabang = $qty_repack_cabang * $harga_cabang;
                                    $total_harga_cabang =
                                        $harga_sa_cabang +
                                        $harga_pusat_cabang +
                                        $harga_transit_in_cabang +
                                        $harga_retur_cabang +
                                        $harga_lainlain_cabang +
                                        $harga_repack_cabang;

                                    $harga_akhir_cabang = !empty($total_qty_cabang) ? ROUND($total_harga_cabang / $total_qty_cabang, 9) : 0;
                                    $total_harga = ROUND($saldoakhir_cabang_desimal, 2) * ROUND($harga_akhir_cabang);

                                    $total_harga_allcabang += $total_harga;

                                    ${"grandtotal_harga_$c->kode_cabang"} += $total_harga;
                                @endphp
                                <td class="right">{{ formatAngkaDesimal($saldoakhir_cabang_desimal) }}</td>
                                <td class="right">{{ formatAngka($harga_akhir_cabang) }}</td>
                                <td class="right">{{ formatAngka($total_harga) }}</td>
                            @endforeach
                            <td class="right">{{ formatAngkaDesimal($saldoakhir_gudangpusat) }}</td>
                            <td class="right"> {{ formatAngka($harga_kirim_cabang) }}</td>
                            <td class="right"> {{ formatAngka($total_harga_gudang) }}</td>
                            <td class="right">{{ formatAngkaDesimal($total_qty_allcabang + $saldoakhir_gudangpusat) }}</td>
                            <td class="right">
                                @php
                                    $saldo_akhir_harga = !empty($total_qty_allcabang + $saldoakhir_gudangpusat)
                                        ? ($total_harga_allcabang + $total_harga_gudang) / ($total_qty_allcabang + $saldoakhir_gudangpusat)
                                        : 0;
                                @endphp
                                {{ formatAngka($saldo_akhir_harga) }}
                            </td>
                            <td class="right">
                                @php
                                    $total_harga_saldoakhir = ($total_qty_allcabang + $saldoakhir_gudangpusat) * $saldo_akhir_harga;
                                    $grandtotal_harga_saldoakhir += $total_harga_saldoakhir;
                                @endphp
                                {{ formatAngka($total_harga_saldoakhir) }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" style="font-size: 18px !important">TOTAL</th>
                        @foreach ($cabang as $c)
                            <th></th>
                            <th></th>
                            <th class="right" style="font-size: 18px !important">{{ formatAngka(${"grandtotal_harga_$c->kode_cabang"}) }}</th>
                        @endforeach
                        <th></th>
                        <th></th>
                        <th class="right" style="font-size: 18px !important">{{ formatAngka($grandtotal_harga_gudang) }}</th>
                        <th></th>
                        <th></th>
                        <th class="right" style="font-size: 18px !important">{{ formatAngka($grandtotal_harga_saldoakhir) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</body>

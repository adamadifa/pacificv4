<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Bahan Bakar {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP BAHAN BAKAR<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="3">TANGGAL</th>
                    <!-- <th rowspan="2" >BTB</th> -->
                    <th rowspan="2" colspan="3">SALDO AWAL</th>
                    <th colspan="6">MASUK</th>
                    <th colspan="6">KELUAR</th>
                    <th rowspan="2" colspan="3">SALDO AKHIR</th>
                </tr>
                <tr>
                    <th colspan="3">PEMBELIAN</th>
                    <th colspan="3">PENERIMAAN LAINNYA</th>
                    <th colspan="3">PEMAKAIAN</th>
                    <th colspan="3">PEMAKAIAN LAINNYA</th>
                </tr>
                <tr>
                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>

                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>

                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>

                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>

                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>

                    <th>QTY</th>
                    <th>HARGA</th>
                    <th>JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $qty_saldo_awal = $saldo_awal != null ? $saldo_awal->jumlah : 0;
                    $harga_saldo_awal = $qty_saldo_awal != null ? $saldo_awal->harga / $qty_saldo_awal : 0;
                    $total_qty_saldoawal = 0;
                    $total_qty_pembelian = 0;
                    $total_qty_lainnya = 0;
                    $total_qty_keluar = 0;
                    $total_qty_keluar_lainnya = 0;

                    $total_harga_pembelian = 0;
                    $total_harga_lainnya = 0;
                    $total_harga_keluar = 0;
                    $total_harga_keluar_lainnya = 0;
                @endphp
                @foreach ($rekapbahanbakar as $d)
                    @php
                        $jumlah_saldoawal = $qty_saldo_awal * $harga_saldo_awal;
                        $cek = $qty_saldo_awal . '*' . $harga_saldo_awal;
                        $jumlah_pembelian = $d['qty_pembelian'] * $d['harga_pembelian'] - $d['penyesuaian'];
                        $jumlah_lainnya = $d['qty_lainnya'] * $d['harga_lainnya'];
                        $harga_keluar =
                            ($jumlah_saldoawal + $jumlah_pembelian + $jumlah_lainnya) /
                            ($qty_saldo_awal + $d['qty_pembelian'] + $d['qty_lainnya']);

                        $total_qty_saldoawal += $qty_saldo_awal;
                        $total_qty_pembelian += $d['qty_pembelian'];
                        $total_qty_lainnya += $d['qty_lainnya'];
                        $total_qty_keluar += $d['qty_keluar'];
                        $total_qty_keluar_lainnya += $d['qty_keluar_lainnya'];
                    @endphp
                    <tr>
                        <td>{{ formatIndo($d['tanggal']) }}</td>
                        <td class="right">{{ formatAngkaDesimal($qty_saldo_awal) }}</td>
                        <td class="right">{{ formatAngkaDesimal($harga_saldo_awal) }}</td>
                        <td class="right">{{ formatAngkaDesimal($jumlah_saldoawal) }} </td>

                        <td class="right">{{ formatAngkaDesimal($d['qty_pembelian']) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d['harga_pembelian']) }}</td>
                        <td class="right">
                            @php
                                $total_harga_pembelian += $jumlah_pembelian;
                            @endphp
                            {{ formatAngkaDesimal($jumlah_pembelian) }}
                        </td>

                        <td class="right">{{ formatAngkaDesimal($d['qty_lainnya']) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d['harga_lainnya']) }}</td>
                        <td class="right">
                            @php
                                $total_harga_lainnya += $jumlah_lainnya;
                            @endphp
                            {{ formatAngkaDesimal($jumlah_lainnya) }}
                            {{-- {{ $d['qty_lainnya'] }} * {{ $d['harga_lainnya'] }} = {{ $jumlah_lainnya }} --}}
                        </td>

                        <td class="right">{{ formatAngkaDesimal($d['qty_keluar']) }}</td>
                        <td class="right">

                            {{ formatAngkaDesimal($harga_keluar) }}
                        </td>
                        <td class="right">
                            @php
                                $jumlah_keluar = $d['qty_keluar'] * $harga_keluar;
                                $total_harga_keluar += $jumlah_keluar;
                            @endphp
                            {{ formatAngkaDesimal($jumlah_keluar) }}


                        </td>

                        <td class="right">{{ formatAngkaDesimal($d['qty_keluar_lainnya']) }}</td>
                        <td class="right">
                            {{ !empty($d['qty_keluar_lainnya']) ? formatAngkaDesimal($harga_keluar) : '' }}</td>
                        <td class="right">
                            @php
                                $jumlah_keluar_lainnya = $d['qty_keluar_lainnya'] * $harga_keluar;
                                $total_harga_keluar_lainnya += $jumlah_keluar_lainnya;
                            @endphp
                            {{ formatAngkaDesimal($jumlah_keluar_lainnya) }}


                        </td>
                        <td class="right">
                            @php
                                $qty_saldo_akhir =
                                    $qty_saldo_awal +
                                    $d['qty_pembelian'] +
                                    $d['qty_lainnya'] -
                                    $d['qty_keluar'] -
                                    $d['qty_keluar_lainnya'];
                            @endphp
                            {{ formatAngkaDesimal($qty_saldo_akhir) }}
                        </td>
                        <td class="right">{{ formatAngkaDesimal($harga_keluar) }}</td>
                        <td class="right">
                            @php
                                $jumlah_saldo_akhir = $qty_saldo_akhir * $harga_keluar;
                            @endphp
                            {{ formatAngkaDesimal($jumlah_saldo_akhir) }}
                        </td>
                    </tr>
                    @php
                        $qty_saldo_awal = $qty_saldo_akhir;
                        $harga_saldo_awal = $harga_keluar;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">TOTAL</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_pembelian) }}</th>
                    <th></th>
                    <th class="right">{{ formatAngkaDesimal($total_harga_pembelian) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_lainnya) }}</th>
                    <th></th>
                    <th class="right">{{ formatAngkaDesimal($total_harga_lainnya) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_keluar) }}</th>
                    <th></th>
                    <th class="right">{{ formatAngkaDesimal($total_harga_keluar) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_qty_keluar_lainnya) }}</th>
                    <th></th>
                    <th class="right">{{ formatAngkaDesimal($total_harga_keluar_lainnya) }}</th>
                    <th colspan="3"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

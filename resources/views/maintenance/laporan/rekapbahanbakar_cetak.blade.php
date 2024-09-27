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
                    $harga_saldo_awal = $saldo_awal != null ? $saldo_awal->harga / $qty_saldo_awal : 0;
                    $jumlah_saldo_awal = $qty_saldo_awal * $harga_saldo_awal;
                @endphp
                @foreach ($rekapbahanbakar as $d)
                    <tr>
                        <td>{{ formatIndo($d['tanggal']) }}</td>
                        <td class="right">{{ formatAngkaDesimal($qty_saldo_awal) }}</td>
                        <td class="right">{{ formatAngkaDesimal($harga_saldo_awal) }}</td>
                        <td class="right">{{ formatAngkaDesimal($qty_saldo_awal * $harga_saldo_awal) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d['qty_pembelian']) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d['harga_pembelian']) }}</td>
                        <td class="right">
                            @php
                                $jumlah_pembelian = $d['qty_pembelian'] * $d['harga_pembelian'] - $d['penyesuaian'];
                            @endphp
                            {{ formatAngkaDesimal($jumlah_pembelian) }}
                        </td>
                        <td class="right">{{ formatAngkaDesimal($d['qty_lainnya']) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d['harga_lainnya']) }}</td>
                        <td class="right">
                            @php
                                $jumlah_lainnya = $d['qty_lainnya'] * $d['harga_lainnya'];
                            @endphp
                            {{ formatAngkaDesimal($jumlah_lainnya) }}
                        </td>
                        <td class="right">{{ formatAngkaDesimal($d['qty_keluar']) }}</td>
                        <td class="right">
                            @php
                                $harga_keluar = $harga_saldo_awal;
                            @endphp
                            {{ formatAngka($harga_keluar) }}
                            {{--
                            {{ formatAngkaDesimal($harga_keluar) }} --}}

                            {{-- ({{ $jumlah_saldo_awal }} + {{ $jumlah_pembelian }} +
                            {{ $jumlah_lainnya }})
                            /
                            ({{ $qty_saldo_awal }} + {{ $d['qty_pembelian'] }} +
                            {{ $d['qty_lainnya'] }})
                            =
                            {{ ($jumlah_saldo_awal + $jumlah_pembelian + $jumlah_lainnya) / ($qty_saldo_awal + $d['qty_pembelian'] + $d['qty_lainnya']) }}; --}}
                        </td>
                        <td class="right">
                            @php
                                $jumlah_keluar = $d['qty_keluar'] * $harga_keluar;
                            @endphp
                            {{ formatAngkaDesimal($jumlah_keluar) }}
                        </td>
                        <td class="right">{{ formatAngkaDesimal($d['qty_keluar_lainnya']) }}</td>
                        <td class="right">{{ !empty($d['qty_keluar_lainnya']) ? $harga_keluar : '' }}</td>
                        <td class="right">
                            @php
                                $jumlah_keluar_lainnya = $d['qty_keluar_lainnya'] * $harga_keluar;
                            @endphp
                            {{ formatAngkaDesimal($jumlah_keluar_lainnya) }}
                        </td>
                        <td class="right">
                            @php
                                $qty_saldo_akhir =
                                    $qty_saldo_awal + $d['qty_pembelian'] + $d['qty_lainnya'] - $d['qty_keluar'] - $d['qty_keluar_lainnya'];
                            @endphp
                            {{ formatAngkaDesimal($qty_saldo_akhir) }}
                        </td>
                        <td class="right">{{ formatAngka($harga_keluar) }}</td>
                        <td class="right">
                            @php
                                $jumlah_saldo_akhir = $qty_saldo_akhir * $harga_keluar;
                            @endphp
                            {{ formatAngkaDesimal($jumlah_saldo_akhir) }}
                        </td>
                    </tr>
                    @php
                        $qty_saldo_awal = $qty_saldo_akhir;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</body>

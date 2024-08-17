<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Analisa transaksi {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style> --}}

    <style>
        .text-red {
            background-color: red;
            color: white;
        }

        .bg-terimauang {
            background-color: #199291 !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            ANALISA TRANSAKSI <br>
        </h4>
        <h4>BULAN :{{ $namabulan[$bulan] }}</h4>
        <h4>TAHUN :{{ $tahun }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($salesman != null)
            <h4>
                {{ textUpperCase($salesman->nama_salesman) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width:130%">
                <thead bgcolor="#024a75" style="color:white; font-size:12;">
                    <tr bgcolor="#024a75" style="color:white; font-size:12;">
                        <th rowspan="3">No.</th>
                        <th rowspan="3" style="width: 7%">Nama Pelanggan</th>
                        <th colspan="5">Minggu Ke 1</th>
                        <th colspan="5">Minggu Ke 2</th>
                        <th colspan="5">Minggu Ke 3</th>
                        <th colspan="5">Minggu Ke 4</th>
                        <th rowspan="3">Total Penjualan</th>
                        <th rowspan="3">Total Pembayaran</th>
                        <th rowspan="3">Rata Rata Pembelian Produk</th>
                    </tr>
                    <tr>
                        <th colspan="2">JENIS TRANSAKSI</th>
                        <th colspan="3">JENIS PEMBAYARAN</th>
                        <th colspan="2">JENIS TRANSAKSI</th>
                        <th colspan="3">JENIS PEMBAYARAN</th>
                        <th colspan="2">JENIS TRANSAKSI</th>
                        <th colspan="3">JENIS PEMBAYARAN</th>
                        <th colspan="2">JENIS TRANSAKSI</th>
                        <th colspan="3">JENIS PEMBAYARAN</th>
                    </tr>
                    <tr>
                        <th>TUNAI</th>
                        <th>KREDIT</th>
                        <th>CASH</th>
                        <th>TRANSFER</th>
                        <th>GIRO</th>
                        <th>TUNAI</th>
                        <th>KREDIT</th>
                        <th>CASH</th>
                        <th>TRANSFER</th>
                        <th>GIRO</th>
                        <th>TUNAI</th>
                        <th>KREDIT</th>
                        <th>CASH</th>
                        <th>TRANSFER</th>
                        <th>GIRO</th>
                        <th>TUNAI</th>
                        <th>KREDIT</th>
                        <th>CASH</th>
                        <th>TRANSFER</th>
                        <th>GIRO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($analisatransaksi as $d)
                        @php
                            $cash_minggu_1 = $d['cash_minggu_1'] + $d['titipan_minggu_1'];
                            $cash_minggu_2 = $d['cash_minggu_2'] + $d['titipan_minggu_2'];
                            $cash_minggu_3 = $d['cash_minggu_3'] + $d['titipan_minggu_3'];
                            $cash_minggu_4 = $d['cash_minggu_4'] + $d['titipan_minggu_4'];
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d['nama_pelanggan'] }}</td>
                            <td class="right">{{ formatAngka($d['tunai_minggu_1']) }}</td>
                            <td class="right">{{ formatAngka($d['kredit_minggu_1']) }}</td>
                            <td class="right">{{ formatAngka($cash_minggu_1) }}</td>
                            <td class="right">{{ formatAngka($d['transfer_minggu_1']) }}</td>
                            <td class="right">{{ formatAngka($d['giro_minggu_1']) }}</td>

                            <td class="right">{{ formatAngka($d['tunai_minggu_2']) }}</td>
                            <td class="right">{{ formatAngka($d['kredit_minggu_2']) }}</td>
                            <td class="right">{{ formatAngka($cash_minggu_2) }}</td>
                            <td class="right">{{ formatAngka($d['transfer_minggu_2']) }}</td>
                            <td class="right">{{ formatAngka($d['giro_minggu_2']) }}</td>

                            <td class="right">{{ formatAngka($d['tunai_minggu_3']) }}</td>
                            <td class="right">{{ formatAngka($d['kredit_minggu_3']) }}</td>
                            <td class="right">{{ formatAngka($cash_minggu_3) }}</td>
                            <td class="right">{{ formatAngka($d['transfer_minggu_3']) }}</td>
                            <td class="right">{{ formatAngka($d['giro_minggu_3']) }}</td>

                            <td class="right">{{ formatAngka($d['tunai_minggu_4']) }}</td>
                            <td class="right">{{ formatAngka($d['kredit_minggu_4']) }}</td>
                            <td class="right">{{ formatAngka($cash_minggu_4) }}</td>
                            <td class="right">{{ formatAngka($d['transfer_minggu_4']) }}</td>
                            <td class="right">{{ formatAngka($d['giro_minggu_4']) }}</td>
                            <td class="right">{{ formatAngka($d['total_penjualan']) }}</td>
                            <td class="right">{{ formatAngka($d['total_pembayaran']) }}</td>
                            <td class="center">{{ ROUND(formatAngka($d['qty']) / 4) }}</td>



                            {{-- <td class="right">{{ formatAngka($d['rata_rata_pembelian_produk']) }}</td> --}}


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 5,
        'shadow': true,
    });
</script> --}}

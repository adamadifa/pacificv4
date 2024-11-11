<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Penjualan Qty {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP PENJUALAN QTY <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
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
        <table class="datatable3">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Produk</th>
                    @foreach ($cbg as $d)
                        <th>{{ textUpperCase($d->nama_cabang) }}</th>
                    @endforeach
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekappenjualan as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        @foreach ($cbg as $c)
                            @php
                                $qty = !empty($d->isi_pcs_dus) ? $d->{"qty_$c->kode_cabang"} / $d->isi_pcs_dus : 0;
                            @endphp
                            <td align="right">{{ formatAngkaDesimal($qty) }}</td>
                        @endforeach
                        <td align="right">
                            @php
                                $total_qty = !empty($d->isi_pcs_dus) ? $d->total_qty / $d->isi_pcs_dus : 0;
                            @endphp
                            {{ formatAngkaDesimal($total_qty) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <br>
        <table class="datatable3">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Produk</th>
                    @foreach ($cbg as $d)
                        <th>{{ textUpperCase($d->nama_cabang) }}</th>
                    @endforeach
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekappenjualan as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        @foreach ($cbg as $c)
                            @php
                                $qty = !empty($d->isi_pcs_dus) ? $d->{"qty_$c->kode_cabang"} / $d->isi_pcs_dus : 0;
                                $harga_rata_rata = !empty($qty) ? $d->{"subtotal_$c->kode_cabang"} / $qty : 0;
                            @endphp
                            <td align="right">{{ formatAngka($harga_rata_rata) }}</td>
                        @endforeach
                        <td align="right">
                            @php
                                $total_qty = !empty($d->isi_pcs_dus) ? $d->total_qty / $d->isi_pcs_dus : 0;
                                $total_harga_rata_rata = !empty($total_qty) ? $d->total_subtotal / $total_qty : 0;
                            @endphp
                            {{ formatAngka($total_harga_rata_rata) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>

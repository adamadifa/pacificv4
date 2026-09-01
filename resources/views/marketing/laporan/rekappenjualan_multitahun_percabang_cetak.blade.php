<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Penjualan Qty per Cabang Multi Tahun {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>

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
            REKAP PENJUALAN QTY PER CABANG MULTI TAHUN <br>
        </h4>
        <h4>TAHUN : {{ implode(', ', $years) }}</h4>
        @if ($cabang != null)
            <h4>
                CABANG: {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="3" style="vertical-align: middle;">No.</th>
                    <th rowspan="3" style="vertical-align: middle;">Cabang</th>
                    @foreach ($years as $year)
                        <th colspan="{{ count($produk) * 12 }}" style="text-align: center;">{{ $year }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($years as $year)
                        @foreach ($produk as $p)
                            <th colspan="12" style="text-align: center; font-size: 10px;">{{ $p->kode_produk }}</th>
                        @endforeach
                    @endforeach
                </tr>
                <tr>
                    @foreach ($years as $year)
                        @foreach ($produk as $p)
                            @for ($m = 1; $m <= 12; $m++)
                                <th style="font-size: 9px; padding: 2px;">{{ $m }}</th>
                            @endfor
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $grand_totals = [];
                    foreach ($years as $year) {
                        foreach ($produk as $p) {
                            for ($m = 1; $m <= 12; $m++) {
                                $grand_totals[$year]['qty'][$p->kode_produk][$m] = 0;
                            }
                        }
                    }
                @endphp
                @foreach ($cabang_list as $cb)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ textUpperCase($cb->nama_cabang) }}</td>
                        @foreach ($years as $year)
                            @foreach ($produk as $p)
                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        $qty = $qty_map[$cb->kode_cabang][$p->kode_produk][$year][$m] ?? 0;
                                        $grand_totals[$year]['qty'][$p->kode_produk][$m] += $qty;
                                    @endphp
                                    <td align="right">{{ $qty > 0 ? formatAngkaDesimal($qty) : '' }}</td>
                                @endfor
                            @endforeach
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #e2e2e2;">
                    <td colspan="2" align="center">TOTAL</td>
                    @foreach ($years as $year)
                        @foreach ($produk as $p)
                            @for ($m = 1; $m <= 12; $m++)
                                <td align="right">{{ formatAngkaDesimal($grand_totals[$year]['qty'][$p->kode_produk][$m]) }}</td>
                            @endfor
                        @endforeach
                    @endforeach
                </tr>
            </tfoot>
        </table>
        <br>
        <div style="font-size: 11px; font-family: sans-serif;">
            <strong>Keterangan Kode Produk:</strong>
            <ul style="list-style-type: none; padding-left: 0; margin-top: 5px; display: flex; flex-wrap: wrap; gap: 15px;">
                @foreach ($produk as $p)
                    <li><strong>{{ $p->kode_produk }}</strong> = {{ $p->nama_produk }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</body>

</html>

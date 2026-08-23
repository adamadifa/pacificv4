<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Penjualan Qty & Netto Multi Tahun {{ date('Y-m-d H:i:s') }}</title>
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

        .status-inactive {
            color: #d9534f;
            font-style: italic;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP PENJUALAN QTY & NETTO MULTI TAHUN <br>
        </h4>
        <h4>TAHUN : {{ implode(', ', $years) }}</h4>
        @if ($cabang != null)
            <h4>
                CABANG: {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($selected_salesman != null)
            <h4>
                SALESMAN: {{ textUpperCase($selected_salesman->nama_salesman) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align: middle;">No.</th>
                    <th rowspan="2" style="vertical-align: middle;">Cabang</th>
                    <th rowspan="2" style="vertical-align: middle;">Salesman</th>
                    @foreach ($years as $year)
                        <th colspan="{{ count($produk) + 1 }}" style="text-align: center;">{{ $year }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($years as $year)
                        @foreach ($produk as $p)
                            <th style="font-size: 10px;">{{ $p->kode_produk }}</th>
                        @endforeach
                        <th>NETTO</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $grand_totals = [];
                    foreach ($years as $year) {
                        $grand_totals[$year]['netto'] = 0;
                        foreach ($produk as $p) {
                            $grand_totals[$year]['qty'][$p->kode_produk] = 0;
                        }
                    }
                @endphp
                @foreach ($salesmen as $salesman)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ textUpperCase($salesman->nama_cabang) }}</td>
                        <td>
                            {{ textUpperCase($salesman->nama_salesman) }}
                            @if ($salesman->status_aktif_salesman == 0)
                                <span class="status-inactive">(Non-Aktif)</span>
                            @endif
                        </td>
                        @foreach ($years as $year)
                            @foreach ($produk as $p)
                                @php
                                    $qty = $qty_map[$salesman->kode_salesman][$p->kode_produk][$year] ?? 0;
                                    $grand_totals[$year]['qty'][$p->kode_produk] += $qty;
                                @endphp
                                <td align="right">{{ $qty > 0 ? formatAngkaDesimal($qty) : '' }}</td>
                            @endforeach
                            @php
                                $netto = $netto_map[$salesman->kode_salesman][$year] ?? 0;
                                $grand_totals[$year]['netto'] += $netto;
                            @endphp
                            <td align="right">{{ $netto > 0 ? formatAngka($netto) : '' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #e2e2e2;">
                    <td colspan="3" align="center">TOTAL</td>
                    @foreach ($years as $year)
                        @foreach ($produk as $p)
                            <td align="right">{{ formatAngkaDesimal($grand_totals[$year]['qty'][$p->kode_produk]) }}</td>
                        @endforeach
                        <td align="right">{{ formatAngka($grand_totals[$year]['netto']) }}</td>
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

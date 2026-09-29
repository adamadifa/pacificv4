<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Cost Ratio Multi Tahun {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP COST RATIO MULTI TAHUN <br>
        </h4>
        <h4>TAHUN : {{ implode(', ', $years) }}</h4>
        @if ($cabang != null)
            <h4>
                CABANG: {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
    </div>
    <div class="content">
        @php
            $item_keys = array_keys($items);
            // Setiap item memiliki 12 kolom bulan, ditambah 12 kolom bulan untuk TOTAL BIAYA
            $total_sub_items = count($items) + 1; // +1 untuk Total Biaya
            $total_cols_per_year = $total_sub_items * 12;
        @endphp
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="3" style="vertical-align: middle;">No.</th>
                    <th rowspan="3" style="vertical-align: middle;">Cabang</th>
                    @foreach ($years as $year)
                        <th colspan="{{ $total_cols_per_year }}" style="text-align: center;">{{ $year }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($years as $year)
                        @foreach ($items as $item_key => $item_name)
                            <th colspan="12" style="text-align: center; font-size: 10px;">{{ strtoupper($item_name) }}</th>
                        @endforeach
                        <th colspan="12" style="text-align: center; font-size: 10px; background-color: #28a745 !important; color: #ffffff !important; font-weight: bold;">TOTAL BIAYA</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($years as $year)
                        @foreach ($items as $item_key => $item_name)
                            @for ($m = 1; $m <= 12; $m++)
                                <th style="font-size: 9px; padding: 2px;">{{ $m }}</th>
                            @endfor
                        @endforeach
                        @for ($m = 1; $m <= 12; $m++)
                            <th style="font-size: 9px; padding: 2px; background-color: #218838 !important; color: #ffffff !important; font-weight: bold;">{{ $m }}</th>
                        @endfor
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $grand_totals = [];
                    foreach ($years as $year) {
                        foreach ($items as $item_key => $item_name) {
                            for ($m = 1; $m <= 12; $m++) {
                                $grand_totals[$year][$item_key][$m] = 0;
                            }
                        }
                        for ($m = 1; $m <= 12; $m++) {
                            $grand_totals[$year]['TOTAL_BIAYA'][$m] = 0;
                        }
                    }
                @endphp
                @foreach ($cabang_list as $cb)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ textUpperCase($cb->nama_cabang) }}</td>
                        @foreach ($years as $year)
                            @php
                                $total_cabang_per_bulan = [];
                                for ($m = 1; $m <= 12; $m++) {
                                    $total_cabang_per_bulan[$m] = 0;
                                }
                            @endphp

                            {{-- Loop per item kategori biaya --}}
                            @foreach ($items as $item_key => $item_name)
                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        $val = $costratio_map[$cb->kode_cabang][$item_key][$year][$m] ?? 0;
                                        $grand_totals[$year][$item_key][$m] += $val;
                                        $total_cabang_per_bulan[$m] += $val;
                                    @endphp
                                    <td align="right">{{ $val > 0 ? formatAngka($val) : '' }}</td>
                                @endfor
                            @endforeach

                            {{-- Kolom Total Biaya Per Bulan untuk Cabang ini --}}
                            @for ($m = 1; $m <= 12; $m++)
                                @php
                                    $tot_val = $total_cabang_per_bulan[$m];
                                    $grand_totals[$year]['TOTAL_BIAYA'][$m] += $tot_val;
                                @endphp
                                <td align="right" style="font-weight: bold; background-color: #e8f5e9 !important; color: #1b5e20 !important;">
                                    {{ $tot_val > 0 ? formatAngka($tot_val) : '' }}
                                </td>
                            @endfor
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #e2e2e2;">
                    <td colspan="2" align="center">TOTAL</td>
                    @foreach ($years as $year)
                        @foreach ($items as $item_key => $item_name)
                            @for ($m = 1; $m <= 12; $m++)
                                <td align="right">
                                    {{ $grand_totals[$year][$item_key][$m] > 0 ? formatAngka($grand_totals[$year][$item_key][$m]) : '' }}
                                </td>
                            @endfor
                        @endforeach
                        @for ($m = 1; $m <= 12; $m++)
                            <td align="right" style="background-color: #28a745 !important; color: #ffffff !important;">
                                {{ $grand_totals[$year]['TOTAL_BIAYA'][$m] > 0 ? formatAngka($grand_totals[$year]['TOTAL_BIAYA'][$m]) : '' }}
                            </td>
                        @endfor
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Analisa Rasio Keuangan {{ $tahun }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .fw-bold {
            font-weight: bold !important;
        }

        .fraction {
            display: inline-block;
            vertical-align: middle;
            margin: 0;
            text-align: center;
            font-size: 10px;
        }

        .fraction>span {
            display: block;
            padding: 0.1em;
            white-space: nowrap;
        }

        .fraction span.numerator {
            border-bottom: 1px solid #000;
        }

        /* Freeze Column CSS */
        .freeze-table {
            overflow: auto;
            max-height: 80vh;
        }

        .datatable3 {
            border-collapse: separate;
            border-spacing: 0;
            width: max-content;
            font-size: 11px;
        }

        .datatable3 th {
            background-color: #004d7a;
            color: white;
            border: 1px solid #ccc;
            padding: 8px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Double header sticky */
        .datatable3 thead tr:nth-child(2) th {
            top: 35px; /* adjust based on first row height */
        }

        .datatable3 td {
            border: 1px solid #ccc;
            padding: 4px;
            vertical-align: middle !important;
            background-color: white;
        }

        /* Sticky Columns */
        .datatable3 th:nth-child(1), .datatable3 td:nth-child(1) { left: 0; z-index: 5; width: 30px; min-width: 30px; }
        .datatable3 th:nth-child(2), .datatable3 td:nth-child(2) { left: 30px; z-index: 5; width: 100px; min-width: 100px; }
        .datatable3 th:nth-child(3), .datatable3 td:nth-child(3) { left: 130px; z-index: 5; width: 150px; min-width: 150px; }
        .datatable3 th:nth-child(4), .datatable3 td:nth-child(4) { left: 280px; z-index: 5; width: 180px; min-width: 180px; }

        .datatable3 td:nth-child(1), .datatable3 td:nth-child(2), .datatable3 td:nth-child(3), .datatable3 td:nth-child(4) {
            position: sticky;
            background-color: #f9f9f9;
        }

        .datatable3 th:nth-child(1), .datatable3 th:nth-child(2), .datatable3 th:nth-child(3), .datatable3 th:nth-child(4) {
            z-index: 15; /* Higher than header row sticky */
        }

        .header {
            margin-bottom: 20px;
            position: relative;
        }
    </style>
</head>

<body>
    <div class="header">
        <div style="float: right; text-align: right;">
            <h4 class="title" style="margin: 0; font-size: 16px;">ANALISA RASIO KEUANGAN</h4>
            <h4 style="margin: 0; font-size: 14px;">PERIODE TAHUN {{ $tahun }}</h4>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">RASIO KEUANGAN</th>
                        <th rowspan="2">JENIS RASIO</th>
                        <th rowspan="2">FORMULA RASIO</th>
                        @for ($i = 1; $i <= 12; $i++)
                            <th colspan="2" class="text-center">{{ strtoupper(config('global.nama_bulan_singkat')[$i]) }}</th>
                        @endfor
                    </tr>
                    <tr>
                        @for ($i = 1; $i <= 12; $i++)
                            <th class="text-center">PERHITUNGAN</th>
                            <th class="text-center">RASIO</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    {{-- 1. LIKUIDITAS --}}
                    <tr>
                        <td class="text-center">1</td>
                        <td class="fw-bold">LIKUIDITAS</td>
                        <td>1. Rasio Lancar (Current Ratio)</td>
                        <td class="text-center">
                            <div class="fraction">
                                <span class="numerator">Aktiva Lancar (Current Assets)</span>
                                <span class="denominator">Hutang Lancar (Current Liabilities)</span>
                            </div>
                        </td>
                        @foreach ($ratios as $r)
                            <td class="text-right">
                                <div class="fraction">
                                    <span class="numerator">{{ $r['current_assets'] != 0 ? formatAngka($r['current_assets']) : '-' }}</span>
                                    <span class="denominator">{{ $r['current_liabilities'] != 0 ? formatAngka($r['current_liabilities']) : '-' }}</span>
                                </div>
                            </td>
                            <td class="text-center fw-bold">{{ round($r['current_ratio'] / 100) }}</td>
                        @endforeach
                    </tr>

                    {{-- 2. SOLVABILITAS --}}
                    <tr>
                        <td rowspan="2" class="text-center">2</td>
                        <td rowspan="2" class="fw-bold">SOLVABILITAS</td>
                        <td>1. Debt to Asset Ratio</td>
                        <td class="text-center">
                            <div class="fraction">
                                <span class="numerator">Total Utang (Total Debt)</span>
                                <span class="denominator">Total Aktiva (Total Assets)</span>
                            </div>
                        </td>
                        @foreach ($ratios as $r)
                            <td class="text-right">
                                <div class="fraction">
                                    <span class="numerator">{{ $r['total_liabilities'] != 0 ? formatAngka($r['total_liabilities']) : '-' }}</span>
                                    <span class="denominator">{{ $r['total_assets'] != 0 ? formatAngka($r['total_assets']) : '-' }}</span>
                                </div>
                            </td>
                            <td class="text-center fw-bold">{{ round($r['dar']) }}%</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>2. Debt to Equity Ratio</td>
                        <td class="text-center">
                            <div class="fraction">
                                <span class="numerator">Total Utang (Total Debt)</span>
                                <span class="denominator">Total Ekuitas (Total Equity)</span>
                            </div>
                        </td>
                        @foreach ($ratios as $r)
                            <td class="text-right">
                                <div class="fraction">
                                    <span class="numerator">{{ $r['total_liabilities'] != 0 ? formatAngka($r['total_liabilities']) : '-' }}</span>
                                    <span class="denominator">{{ $r['total_equity'] != 0 ? formatAngka($r['total_equity']) : '-' }}</span>
                                </div>
                            </td>
                            <td class="text-center fw-bold">{{ round($r['der']) }}%</td>
                        @endforeach
                    </tr>

                    {{-- 3. RENTABILITAS --}}
                    <tr>
                        <td rowspan="2" class="text-center">3</td>
                        <td rowspan="2" class="fw-bold">RENTABILITAS</td>
                        <td>1. Gross Profit Margin (Marjin Laba Kotor)</td>
                        <td class="text-center">
                            <div class="fraction">
                                <span class="numerator">Laba kotor</span>
                                <span class="denominator">Penjualan Bersih</span>
                            </div>
                        </td>
                        @foreach ($ratios as $r)
                            <td class="text-right">
                                <div class="fraction">
                                    <span class="numerator">{{ $r['gross_profit'] != 0 ? formatAngka($r['gross_profit']) : '-' }}</span>
                                    <span class="denominator">{{ $r['sales'] != 0 ? formatAngka($r['sales']) : '-' }}</span>
                                </div>
                            </td>
                            <td class="text-center fw-bold">{{ round($r['gross_profit_margin']) }}%</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>2. Net Profit Margin (Marjin Laba Bersih)</td>
                        <td class="text-center">
                            <div class="fraction">
                                <span class="numerator">Laba Bersih Setelah Pajak</span>
                                <span class="denominator">Penjualan Bersih</span>
                            </div>
                        </td>
                        @foreach ($ratios as $r)
                            <td class="text-right">
                                <div class="fraction">
                                    <span class="numerator">{{ $r['net_profit'] != 0 ? formatAngka($r['net_profit']) : '-' }}</span>
                                    <span class="denominator">{{ $r['sales'] != 0 ? formatAngka($r['sales']) : '-' }}</span>
                                </div>
                            </td>
                            <td class="text-center fw-bold">{{ round($r['net_profit_margin']) }}%</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="footer" style="margin-top: 20px;">
            <p class="fw-bold">KETERANGAN :</p>
        </div>
    </div>
</body>

</html>

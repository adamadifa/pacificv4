<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Analisa Rasio Keuangan {{ $tahun }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        :root {
            --primary-color: #01579b;
            --primary-dark: #002f6c;
            --secondary-color: #f5f5f5;
            --border-color: #e0e0e0;
            --text-color: #333;
            --header-bg: #01579b;
            --sticky-bg: #f9f9f9;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            color: var(--text-color);
            background-color: #fff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 30px;
            border-bottom: 3px solid var(--primary-color);
            padding-bottom: 10px;
        }

        .header-left img {
            height: 50px;
        }

        .header-right {
            text-align: right;
        }

        .header-right h1 {
            margin: 0;
            font-size: 24px;
            color: var(--primary-dark);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-right p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
            font-weight: 600;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .fraction {
            display: inline-flex;
            flex-direction: column;
            vertical-align: middle;
            align-items: center;
            font-size: 10px;
            line-height: 1.2;
        }

        .fraction .numerator {
            border-bottom: 1px solid #333;
            padding-bottom: 1px;
            width: 100%;
            text-align: center;
        }

        .fraction .denominator {
            padding-top: 1px;
            width: 100%;
            text-align: center;
        }

        /* Table Container for Scrolling */
        .table-container {
            position: relative;
            overflow: auto;
            max-height: calc(100vh - 150px);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .datatable3 {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            font-size: 12px;
        }

        /* sticky Header */
        .datatable3 thead th {
            position: sticky;
            top: 0;
            background-color: var(--header-bg);
            color: white;
            padding: 8px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom: none; /* Removed to avoid white gap */
            white-space: nowrap;
            z-index: 20;
            height: 40px; /* Fixed height for first row */
        }

        .datatable3 thead tr:nth-child(2) th {
            top: 40px; /* Match the height above */
            z-index: 19;
            height: 35px;
        }

        .datatable3 tbody td {
            padding: 8px;
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            vertical-align: middle;
            background-color: #fff;
        }

        /* Sticky Columns implementation */
        .sticky-col {
            position: sticky;
            z-index: 10;
            background-color: var(--sticky-bg) !important;
            box-shadow: 2px 0 5px -2px rgba(0,0,0,0.1);
        }

        .sticky-col-header {
            z-index: 30 !important; /* Above head and normal sticky */
            background-color: var(--header-bg) !important;
        }

        /* Specific offsets */
        .col-1 { left: 0; width: 40px; min-width: 40px; }
        .col-2 { left: 40px; width: 120px; min-width: 120px; }
        .col-3 { left: 160px; width: 220px; min-width: 220px; }
        .col-4 { left: 380px; width: 220px; min-width: 220px; }

        /* Row highlighting */
        .datatable3 tbody tr:hover td {
            background-color: #f0f7ff !important;
        }

        .section-header {
            background-color: #e3f2fd !important;
            font-weight: 700;
            color: var(--primary-dark);
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
        }

        .footer p {
            margin: 5px 0;
        }

        @media print {
            body {
                padding: 0;
            }
            .table-container {
                max-height: none;
                overflow: visible;
                border: none;
                box-shadow: none;
            }
            .header {
                border-bottom: 2px solid #000;
            }
            .datatable3 thead th {
                background-color: #eee !important;
                color: #000 !important;
                border: 1px solid #000 !important;
            }
            .datatable3 td {
                border: 1px solid #000 !important;
            }
            .sticky-col {
                position: static !important;
                background-color: transparent !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-left">
            {{-- <img src="{{ asset('assets/img/logo.png') }}" alt="Logo"> --}}
            <div style="font-weight: 800; font-size: 24px; color: var(--primary-color)">PACIFIC</div>
        </div>
        <div class="header-right">
            <h1>Analisa Rasio Keuangan</h1>
            <p>PERIODE TAHUN {{ $tahun }}</p>
        </div>
    </div>

    <div class="table-container">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2" class="sticky-col sticky-col-header col-1">NO</th>
                    <th rowspan="2" class="sticky-col sticky-col-header col-2">RASIO KEUANGAN</th>
                    <th rowspan="2" class="sticky-col sticky-col-header col-3">JENIS RASIO</th>
                    <th rowspan="2" class="sticky-col sticky-col-header col-4">FORMULA RASIO</th>
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
                    <td class="text-center sticky-col col-1">1</td>
                    <td class="fw-bold sticky-col col-2">LIKUIDITAS</td>
                    <td class="sticky-col col-3">1. Rasio Lancar (Current Ratio)</td>
                    <td class="text-center sticky-col col-4">
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
                        <td class="text-center fw-bold" style="color: var(--primary-color)">{{ round($r['current_ratio'] / 100) }}</td>
                    @endforeach
                </tr>

                {{-- 2. SOLVABILITAS --}}
                <tr>
                    <td rowspan="2" class="text-center sticky-col col-1">2</td>
                    <td rowspan="2" class="fw-bold sticky-col col-2">SOLVABILITAS</td>
                    <td class="sticky-col col-3">1. Debt to Asset Ratio</td>
                    <td class="text-center sticky-col col-4">
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
                        <td class="text-center fw-bold" style="color: var(--primary-color)">{{ round($r['dar']) }}%</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="sticky-col col-3">2. Debt to Equity Ratio</td>
                    <td class="text-center sticky-col col-4">
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
                        <td class="text-center fw-bold" style="color: var(--primary-color)">{{ round($r['der']) }}%</td>
                    @endforeach
                </tr>

                {{-- 3. RENTABILITAS --}}
                <tr>
                    <td rowspan="2" class="text-center sticky-col col-1">3</td>
                    <td rowspan="2" class="fw-bold sticky-col col-2">RENTABILITAS</td>
                    <td class="sticky-col col-3">1. Gross Profit Margin</td>
                    <td class="text-center sticky-col col-4">
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
                        <td class="text-center fw-bold" style="color: var(--primary-color)">{{ round($r['gross_profit_margin']) }}%</td>
                    @endforeach
                </tr>
                <tr>
                    <td class="sticky-col col-3">2. Net Profit Margin</td>
                    <td class="text-center sticky-col col-4">
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
                        <td class="text-center fw-bold" style="color: var(--primary-color)">{{ round($r['net_profit_margin']) }}%</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p class="fw-bold">KETERANGAN :</p>
        <p>Laporan ini dihasilkan secara otomatis pada {{ date('d/m/Y H:i') }}</p>
    </div>
</body>

</html>


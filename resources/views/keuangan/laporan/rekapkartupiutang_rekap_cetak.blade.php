<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REKAP KARTU PINJAMAN {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">

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
            REKAP KARTU PINJAMAN <br>
        </h4>
        <h4>PERIODE : {{ $namabulan[$bulan] }} {{ $tahun }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($departemen != null)
            <h4>
                {{ textUpperCase($departemen->nama_dept) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">JENIS</th>
                        <th rowspan="2">SALDO AWAL</th>
                        <th rowspan="2">PENAMBAHAN</th>
                        <th colspan="4">PEMBAYARAN</th>
                        <th rowspan="2">SALDO AKHIR</th>
                    </tr>
                    <tr>
                        <th>GAJI</th>
                        <th>POT. KOMISI</th>
                        <th>TITIPAN</th>
                        <th>LAINNYA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap as $d)
                        <tr>
                            <td class="fw-bold">{{ $d['jenis'] }}</td>
                            <td style="text-align: right">{{ !empty($d['saldo_awal']) ? formatAngka($d['saldo_awal']) : '' }}</td>
                            <td style="text-align: right">{{ !empty($d['penambahan']) ? formatAngka($d['penambahan']) : '' }}</td>
                            <td style="text-align: right">{{ !empty($d['gaji']) ? formatAngka($d['gaji']) : '' }}</td>
                            <td style="text-align: right">{{ !empty($d['pot_komisi']) ? formatAngka($d['pot_komisi']) : '' }}</td>
                            <td style="text-align: right">{{ !empty($d['titipan']) ? formatAngka($d['titipan']) : '' }}</td>
                            <td style="text-align: right">{{ !empty($d['lainnya']) ? formatAngka($d['lainnya']) : '' }}</td>
                            <td style="text-align: right">{{ !empty($d['saldo_akhir']) ? formatAngka($d['saldo_akhir']) : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr bgcolor="#024a75" style="color: white; font-size: 12px; font-weight: bold;">
                        <th>TOTAL</th>
                        <th style="text-align: right">{{ !empty($total['saldo_awal']) ? formatAngka($total['saldo_awal']) : '' }}</th>
                        <th style="text-align: right">{{ !empty($total['penambahan']) ? formatAngka($total['penambahan']) : '' }}</th>
                        <th style="text-align: right">{{ !empty($total['gaji']) ? formatAngka($total['gaji']) : '' }}</th>
                        <th style="text-align: right">{{ !empty($total['pot_komisi']) ? formatAngka($total['pot_komisi']) : '' }}</th>
                        <th style="text-align: right">{{ !empty($total['titipan']) ? formatAngka($total['titipan']) : '' }}</th>
                        <th style="text-align: right">{{ !empty($total['lainnya']) ? formatAngka($total['lainnya']) : '' }}</th>
                        <th style="text-align: right">{{ !empty($total['saldo_akhir']) ? formatAngka($total['saldo_akhir']) : '' }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>

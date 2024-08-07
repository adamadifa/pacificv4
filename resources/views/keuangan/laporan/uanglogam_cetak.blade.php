<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SALDO LOGAM {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">

    <style>
        .text-red {
            background-color: red;
            color: white;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            SALDO LOGAM<br>
        </h4>
        <h4>PERIODE : {{ $namabulan[$bulan] }} {{ $tahun }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>TGL</th>
                        <th class="green">PENERIMAAN LHP</th>
                        <th class="red">PENGELUARAN</th>
                        <th>SALDO</th>
                    </tr>
                    <tr>
                        <th colspan="3">SALDO AWAL</th>
                        <th>{{ formatAngka($saldo_awal->uang_logam) ?? 'BELUM DI SET' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $saldo = $saldo_awal != null ? $saldo_awal->uang_logam : 0;
                        $grandtotal_penerimaan = 0;
                        $grandtotal_pengeluaran = 0;
                    @endphp
                    @foreach ($saldologam as $d)
                        @php
                            $penerimaan = $d['lhp_logam'] + $d['kurang_logam'] - $d['lebih_logam'];
                            $pengeluaran = $d['setoran_logam'] + $d['logamtokertas'];
                            $saldo += $penerimaan - $pengeluaran;
                            $grandtotal_penerimaan += $penerimaan;
                            $grandtotal_pengeluaran += $pengeluaran;
                        @endphp
                        <tr>
                            <td>{{ DateToIndo($d['tanggal']) }}</td>
                            <td class="right">{{ formatAngka($penerimaan) }}</td>
                            <td class="right">{{ formatAngka($pengeluaran) }}</td>
                            <td class="right">{{ formatAngka($saldo) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>TOTAL</th>
                        <th class="right">{{ formatAngka($grandtotal_penerimaan) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_pengeluaran) }}</th>
                        <th class="right">{{ formatAngka($saldo) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

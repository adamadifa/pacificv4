<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Akun Kas Kecil {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style>
    <style>
        .text-red {
            background-color: red;
            color: white;
        }
    </style> --}}
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP AKUN KAS KECIL<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ $cabang->nama_cabang }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Penerimaan</th>
                        <th>Pengeluaran</th>
                        <th>Saldo</th>
                    </tr>
                    <tr>
                        <th colspan="4">SALDO AWAL</th>
                        <th class="right">{{ $saldoawal != null ? formatAngka($saldoawal->saldo_awal) : 0 }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalpenerimaan = 0;
                        $totalpengeluaran = 0;
                        $saldo = $saldoawal != null ? $saldoawal->saldo_awal : 0;
                    @endphp
                    @foreach ($kaskecil as $d)
                        @php
                            $totalpenerimaan += $d->penerimaan;
                            $totalpengeluaran += $d->pengeluaran;
                            $saldo += $d->penerimaan - $d->pengeluaran;
                        @endphp
                        <tr>
                            <td>'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->penerimaan) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->pengeluaran) }}</td>
                            <td class="right">{{ formatAngkaDesimal($saldo) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="right">{{ formatAngkaDesimal($totalpenerimaan) }}</th>
                        <th class="right">{{ formatAngkaDesimal($totalpengeluaran) }}</th>
                        <th class="right">{{ formatAngkaDesimal($saldo) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

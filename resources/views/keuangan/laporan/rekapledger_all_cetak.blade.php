<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ledger {{ date('Y-m-d H:i:s') }}</title>
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
            REKAPLEDGER<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Kode Bank</th>
                        <th>Nama Bank</th>
                        <th>No Rek</th>
                        @if (auth()->user()->hasRole('super admin'))
                            <th>Jumlah Transaksi</th>
                        @endif
                        <th>Debet</th>
                        <th>Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totaltransaksi = 0;
                        $totaldebet = 0;
                        $totalkredit = 0;
                    @endphp
                    @foreach ($ledger as $d)
                        @php
                            $totaltransaksi += $d->jml_transaksi;
                            $totaldebet += $d->jmldebet;
                            $totalkredit += $d->jmlkredit;
                        @endphp
                        <tr>
                            <td>{{ !empty($d->kode_akun) ? "'" . $d->kode_akun : '' }}</td>
                            <td>{{ !empty($d->kode_bank) ? "'" . $d->kode_bank : '' }}</td>
                            <td>{{ $d->nama_bank }}</td>
                            <td>{{ !empty($d->no_rekening) ? "'" . $d->no_rekening : '' }}</td>
                            @if (auth()->user()->hasRole('super admin'))
                                <td class="center">{{ !empty($d->jml_transaksi) ? formatAngka($d->jml_transaksi) : 0 }}</td>
                            @endif
                            <td class="right">{{ formatAngkaDesimal($d->jmldebet) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jmlkredit) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total</th>
                        @if (auth()->user()->hasRole('super admin'))
                            <th class="center">{{ !empty($totaltransaksi) ? formatAngka($totaltransaksi) : 0 }}</th>
                        @endif
                        <th class="right">{{ formatAngkaDesimal($totaldebet) }}</th>
                        <th class="right">{{ formatAngkaDesimal($totalkredit) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

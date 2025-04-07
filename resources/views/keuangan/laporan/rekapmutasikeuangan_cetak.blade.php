<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mutasi Keuangan {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP MUTASI KEUANGAN<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($bank != null)
            <h4>
                {{ $bank->nama_bank }} - {{ $bank->no_rekening }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>Kode Bank</th>
                        <th>Nama Nama Bank</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totaldebet = 0;
                        $totalkredit = 0;
                    @endphp
                    @foreach ($ledger as $d)
                        @php
                            $totaldebet += $d->jmldebet;
                            $totalkredit += $d->jmlkredit;
                        @endphp
                        <tr>
                            <td>'{{ $d->kode_bank }}</td>
                            <td>{{ $d->nama_bank }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jmldebet) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jmlkredit) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="right">{{ formatAngkaDesimal($totaldebet) }}</th>
                        <th class="right">{{ formatAngkaDesimal($totalkredit) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

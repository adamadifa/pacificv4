<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap AKun {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style> --}}
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP AKUN<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <th>KODE AKUN</th>
                    <th>NAMA AKUN</th>
                    <th>TOTAL DEBET</th>
                    <th>TOTAL KREDIT</th>
                </thead>
                <tbody>
                    @php
                        $totaldebet = 0;
                        $totalkredit = 0;
                    @endphp
                    @foreach ($pmb as $key => $d)
                        @php
                            if ($d->kode_transaksi == 'PNJ') {
                                $debet = $d->jurnaldebet;
                                $kredit = $d->total + $d->jurnalkredit;
                            } else {
                                $debet = $d->total + $d->jurnaldebet;
                                $kredit = $d->jurnalkredit;
                            }
                            $totaldebet += $debet;
                            $totalkredit += $kredit;
                        @endphp
                        <tr>
                            <td>'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="right">{{ formatAngkaDesimal($debet) }}</td>
                            <td class="right">{{ formatAngkaDesimal($kredit) }}</td>
                        </tr>
                    @endforeach
                    @php
                        $totalhk = 0;
                        $totalhd = 0;
                    @endphp
                    @foreach ($hutang as $d)
                        @php
                            $hutangkredit = $d->pmb - $d->pnj + $d->jurnalkredit;
                            $hutangdebet = $d->jurnaldebet;
                            $totalhk += $hutangkredit;
                            $totalhd += $hutangdebet;
                        @endphp
                        <tr>
                            <td>'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="right">{{ formatAngkaDesimal($hutangdebet) }}</td>
                            <td class="right">{{ formatAngkaDesimal($hutangkredit) }}</td>
                        </tr>
                    @endforeach
                    @php
                        $totaljurnaldebet = 0;
                        $totaljurnalkredit = 0;
                    @endphp
                    @foreach ($jurnalkoreksi as $d)
                        @php
                            $totaljurnaldebet += $d->jurnaldebet;
                            $totaljurnalkredit += $d->jurnalkredit;
                        @endphp
                        <tr>
                            <td>'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jurnaldebet) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jurnalkredit) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">TOTAL</th>
                        <th class="right">{{ formatAngkaDesimal($totaldebet + $totalhd + $totaljurnaldebet) }}</th>
                        <th class="right">{{ formatAngkaDesimal($totalkredit + $totalhk + $totaljurnalkredit) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 10,
        'shadow': true,
    });
</script> --}}

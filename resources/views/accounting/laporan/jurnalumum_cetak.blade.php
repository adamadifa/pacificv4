<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jurnal Umum {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
    .freeze-table {
      height: auto;
      max-height: 795px;
      overflow: auto;
    }
  </style> --}}
    <style>
        .datatable3 th {
            font-size: 11px !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            JURNAL UMUM <br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th style="width: 10%;">TGL</th>
                        <th>NO BUKTI</th>
                        <th>KETERANGAN</th>
                        <th>PERUNTUKAN</th>
                        <th>KODE AKUN</th>
                        <th>NAMA AKUN</th>
                        <th>DEBET</th>
                        <th>KREDIT</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_debet = 0;
                        $total_kredit = 0;
                    @endphp
                    @foreach ($jurnalumum as $d)
                        @php
                            if ($d->debet_kredit == 'D') {
                                $debet = $d->jumlah;
                                $kredit = 0;
                            } else {
                                $debet = 0;
                                $kredit = $d->jumlah;
                            }
                            $total_debet += $debet;
                            $total_kredit += $kredit;
                        @endphp
                        <tr>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->kode_ju }}</td>
                            <td>{{ $d->keterangan }}</td>
                            <td>{{ $d->kode_peruntukan == 'PC' ? $d->kode_cabang : $d->kode_peruntukan }}</td>
                            <td>'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="right">{{ formatAngka($debet) }}</td>
                            <td class="right">{{ formatAngka($kredit) }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6">TOTAL</th>
                        <th class="right">{{ formatAngka($total_debet) }}</th>
                        <th class="right">{{ formatAngka($total_kredit) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</body>

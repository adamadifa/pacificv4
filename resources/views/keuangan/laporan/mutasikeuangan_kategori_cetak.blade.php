<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mutasi Keuangan {{ date('Y-m-d H:i:s') }}</title>
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
            MUTASI KEUANGAN<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>

    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width:auto !important">
                <thead>
                    <tr>
                        <th style="width: 1%">No</th>
                        <th style="width: 4%">Tanggal</th>
                        <th style="width: 15%">Kategori</th>
                        {{-- <th style="width: 15%">Bank</th>
                        <th style="width: 15%">No Rekening</th> --}}
                        <th>Debet</th>
                        <th>Kredit</th>
                </thead>
                <tbody>
                    @php
                        $totaldebet = 0;
                        $totalkredit = 0;
                    @endphp
                    @foreach ($mutasi_kategori_detail as $d)
                        @php
                            $totaldebet += $d->debet;
                            $totalkredit += $d->kredit;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->tanggal }}</td>
                            <td>{{ $d->nama_kategori }}</td>
                            {{-- <td>{{ $d->nama_bank }}</td>
                            <td>{{ $d->no_rekening }}</td> --}}
                            <td class="right">{{ formatAngkaDesimal($d->debet) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->kredit) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="right">Total</td>
                        <td class="right">{{ formatAngkaDesimal($totaldebet) }}</td>
                        <td class="right">{{ formatAngkaDesimal($totalkredit) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'freezeColumn': false,
    });
</script> --}}

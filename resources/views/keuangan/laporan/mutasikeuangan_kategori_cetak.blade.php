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
                        <th>Debet</th>
                        <th>Kredit</th>
                </thead>
                <tbody>
                    @foreach ($mutasi_kategori_detail as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->tanggal }}</td>
                            <td>{{ $d->nama_kategori }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->debet) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->kredit) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    {{-- <tr>
                        <th colspan="3">TOTAL</th>
                        <th class="right">{{ formatAngka($totaldebet) }}</th>
                        <th class="right">{{ formatAngka($totalkredit) }}</th>
                        <th class="right">{{ formatAngka($saldo) }}</th>
                        <th></th>
                        <th></th>
                    </tr> --}}
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

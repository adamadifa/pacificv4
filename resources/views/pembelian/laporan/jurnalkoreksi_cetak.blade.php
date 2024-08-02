<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Jurnal Koreksi {{ date('Y-m-d H:i:s') }}</title>
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
            JURNAL KOREKSI<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>TGL</th>
                        <th>No Bukti</th>
                        <th>Supplier</th>
                        <th>Nama Barang</th>
                        <th>Keterangan</th>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandtotal = 0;
                        $totalkredit = 0;
                        $totaldebet = 0;
                    @endphp
                    @foreach ($jurnalkoreksi as $d)
                        @php
                            $total = $d->jumlah * $d->harga;
                            if ($d->debet_kredit == 'D') {
                                $debet = $total;
                                $kredit = 0;
                            } else {
                                $debet = 0;
                                $kredit = $total;
                            }

                            $grandtotal += $total;
                            $totaldebet += $debet;
                            $totalkredit += $kredit;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ $d->nama_supplier }}</td>
                            <td>{{ $d->nama_barang }}</td>
                            <td>{{ $d->keterangan }}</td>
                            <td class="center">'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jumlah) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->harga) }}</td>
                            <td class="right">{{ formatAngkaDesimal($total) }}</td>
                            <td class="right">{{ formatAngkaDesimal($debet) }}</td>
                            <td class="right">{{ formatAngkaDesimal($kredit) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="10">TOTAL</th>
                        <th class="right">{{ formatAngkaDesimal($grandtotal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($totaldebet) }}</th>
                        <th class="right">{{ formatAngkaDesimal($totalkredit) }}</th>
                    </tr>
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

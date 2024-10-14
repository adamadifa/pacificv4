<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ratio BS {{ date('Y-m-d H:i:s') }}</title>
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

        .bg-terimauang {
            background-color: #199291 !important;
            color: white !important;
        }

        .orange {
            background-color: orange !important;
            color: white !important;
        }

        .biru1 {
            background-color: #199291 !important;
            color: white !important;
        }

        .bg-warna-campuran1 {
            background-color: #FFD700 !important;
            /* Campuran dari warna kuning dan emas */
            color: white !important;
        }

        .bg-warna-campuran2 {
            background-color: #008080 !important;
            /* Campuran dari warna biru dan hijau */
            color: white !important;
        }

        .bg-warna-campuran3 {
            background-color: #FF6347 !important;
            /* Campuran dari warna oranye dan merah */
            color: white !important;
        }

        .bg-warna-campuran4 {
            background-color: #4CAF50 !important;
            /* Campuran dari warna hijau dan biru */
            color: white !important;
        }

        .bg-warna-campuran5 {
            background-color: #FFA07A !important;
            /* Campuran dari warna oranye dan kuning */
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            RATIO BS <br>
        </h4>
        <h4>BULAN :{{ $namabulan[$bulan] }}</h4>
        <h4>TAHUN :{{ $tahun }}</h4>


    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 180%">
                <thead>
                    <tr>
                        <th rowspan="3">No.</th>
                        <th rowspan="3">Cabang</th>
                        <th colspan="{{ count($produk) * 3 }}">Produk</th>
                        <th rowspan="3">Total</th>
                    </tr>
                    <tr>
                        @foreach ($produk as $p)
                            <th colspan="3">{{ $p->kode_produk }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($produk as $p)
                            <th>Reject</th>
                            <th>Harga</th>
                            <th>Total</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ratiobs as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                            @php
                                $grand_total = 0;
                            @endphp
                            @foreach ($produk as $p)
                                @php
                                    $jml_reject =
                                        $d->{"reject_mobil_$p->kode_produk"} +
                                        $d->{"reject_gudang_$p->kode_produk"} +
                                        $d->{"reject_pasar_$p->kode_produk"} -
                                        $d->{"repack_$p->kode_produk"};

                                    $harga =
                                        $d->{"retur_$p->kode_produk"} > 0
                                            ? $d->{"total_retur_$p->kode_produk"} / ROUND($d->{"retur_$p->kode_produk"}, 2)
                                            : 0;
                                    $total = ROUND($jml_reject, 2) * $harga;
                                    $grand_total += $total;
                                @endphp
                                <td class="center">{{ formatAngkaDesimal($jml_reject) }}</td>
                                <td class="right">{{ formatAngka($harga) }} </td>
                                <td class="right">{{ formatAngka($total) }}</td>
                            @endforeach
                            <td class="right">{{ formatAngka($grand_total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 5,
        'shadow': true,
    });
</script> --}}

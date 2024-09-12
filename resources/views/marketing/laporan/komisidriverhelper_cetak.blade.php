<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Komisi Driver Helper {{ date('Y-m-d H:i:s') }}</title>
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
            KOMISI DRIVER HELPER <br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} - {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif

    </div>
    <div class="content">

        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2">Kode</th>
                    <th rowspan="2">Nama</th>
                    <th rowspan="2">Posisi</th>
                    <th colspan="6">Quantity</th>
                    <th rowspan="2">TOTAL</th>
                </tr>
                <tr>
                    <th class="green">DRIVER</th>
                    <th class="green">RATIO</th>
                    <th class="green">TOTAL</th>
                    <th class="red">HELPER</th>
                    <th class="red">RATIO</th>
                    <th class="red">TOTAL</th>
                </tr>

            </thead>
            <tbody>
                @php
                    $posisi = [
                        'D' => 'Driver',
                        'H' => 'Helper',
                        'G' => 'Gudang',
                    ];
                    $grandtotal_komisi = 0;
                @endphp
                @foreach ($komisi as $d)
                    <tr>
                        <td>{{ $d->kode_driver_helper }}</td>
                        <td>{{ $d->nama_driver_helper }}</td>
                        <td>{{ $posisi[$d->posisi] }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_driver) }}</td>
                        <td class="center">
                            @php
                                $ratio_driver = $d->posisi == 'D' ? $d->ratio_default : 0;
                                $total_komisi_driver = $d->qty_driver * $ratio_driver;
                            @endphp
                            {{ formatAngkaDesimal($ratio_driver) }}
                        </td>
                        <td class="right">{{ formatAngkaDesimal($total_komisi_driver) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_helper) }}</td>
                        <td class="center">
                            @php
                                $ratio_helper = $d->posisi == 'H' ? $d->ratio_default : $d->ratio_helper;
                                $total_komisi_helper = $d->qty_helper * $ratio_helper;
                            @endphp
                            {{ formatAngkaDesimal($ratio_helper) }}
                        </td>
                        <td class="right">{{ formatAngkaDesimal($total_komisi_helper) }}</td>
                        <td class="right" style="font-weight: bold">
                            @php
                                $total_komisi = $total_komisi_driver + $total_komisi_helper;
                            @endphp
                            {{ formatAngkaDesimal($total_komisi) }}
                        </td>
                        @php
                            $grandtotal_komisi += $total_komisi;
                        @endphp
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="9">GRAND TOTAL</th>
                    <th class="right">{{ formatAngkaDesimal($grandtotal_komisi) }}</th>
                </tr>
            </tfoot>
        </table>
        <br>
        <br>
        <br>
        <table class="datatable3">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Posisi</th>
                    <th>Quantity</th>
                    <th>RATIO</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_komisi_gudang = 0;
                @endphp
                @foreach ($komisigudang as $d)
                    @php
                        $total_komisi_gudang += $d->qty_gudang * $d->ratio_default;
                    @endphp
                    <tr>
                        <td>{{ $d->kode_driver_helper }}</td>
                        <td>{{ $d->nama_driver_helper }}</td>
                        <td>{{ $posisi[$d->posisi] }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_gudang) }}</td>
                        <td class="center">{{ formatAngkaDesimal($d->ratio_default) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->qty_gudang * $d->ratio_default) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">GRAND TOTAL</th>
                    <th class="right">{{ formatAngkaDesimal($total_komisi_gudang) }}</th>
                </tr>
            </tfoot>
        </table>
        <br>

        <table class="datatable3">
            <tr>
                <th>TOTAL KOMISI</th>
                <th class="right">{{ formatAngkaDesimal($grandtotal_komisi + $total_komisi_gudang) }}</th>
            </tr>
        </table>
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

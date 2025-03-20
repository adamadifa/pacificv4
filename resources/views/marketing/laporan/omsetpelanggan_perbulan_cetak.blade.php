<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Omset Pelanggan {{ date('Y-m-d H:i:s') }}</title>
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
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP OMSET PELANGGAN <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($salesman != null)
            <h4>
                {{ textUpperCase($salesman->nama_salesman) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">KODE PELANGGAN</th>
                        <th rowspan="2">NAMA PELANGGAN</th>
                        <th rowspan="2">KLASIFIKASI</th>
                        <th rowspan="2">SALESMAN</th>
                        <th rowspan="2">LIMIT</th>
                        @php
                            $start = new DateTime($dari);
                            $end = new DateTime($sampai);
                            $interval = new DateInterval('P1M'); // Interval 1 bulan
                            $period = new DatePeriod($start, $interval, $end->modify('+1 day'));
                            // foreach ($period as $date) {
                            //     $bulan = $date->format('m');
                            //     $tahun = $date->format('Y');
                            // }
                        @endphp
                        @foreach ($period as $date)
                            @php
                                $bulan = $date->format('m');
                                $tahun = $date->format('Y');
                            @endphp
                            <th colspan="2">{{ $namabulan[$bulan * 1] }} {{ $tahun }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($period as $date)
                            @php
                                $bulan = $date->format('m');
                                $tahun = $date->format('Y');
                            @endphp
                            <th>TUNAI</th>
                            <th>KREDIT</th>
                        @endforeach

                    </tr>
                </thead>
                <tbody>
                    @foreach ($omsetpelanggan as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ textUpperCase($d->nama_pelanggan) }}</td>

                            <td>{{ textUpperCase($d->klasifikasi) }}</td>
                            <td>{{ $d->nama_salesman }}</td>
                            <td style="text-align: right">{{ formatAngka($d->limit_pelanggan) }}</td>
                            @php
                                $start = new DateTime($dari);
                                $end = new DateTime($sampai);
                                $interval = new DateInterval('P1M'); // Interval 1 bulan
                                $period = new DatePeriod($start, $interval, $end->modify('+1 day'));
                                // foreach ($period as $date) {
                                //     $bulan = $date->format('m');
                                //     $tahun = $date->format('Y');
                                // }
                            @endphp
                            @foreach ($period as $date)
                                @php
                                    $bulan = $date->format('m');
                                    $tahun = $date->format('Y');
                                    $netto =
                                        $d->{"bruto_$bulan$tahun"} -
                                        $d->{"potongan_$bulan$tahun"} -
                                        $d->{"penyesuaian_$bulan$tahun"} +
                                        $d->{"ppn_$bulan$tahun"};

                                    $netto_tunai =
                                        $d->{"bruto_tunai_$bulan$tahun"} -
                                        $d->{"potongan_tunai_$bulan$tahun"} -
                                        $d->{"penyesuaian_tunai_$bulan$tahun"} +
                                        $d->{"ppn_tunai_$bulan$tahun"};

                                    $netto_kredit =
                                        $d->{"bruto_kredit_$bulan$tahun"} -
                                        $d->{"potongan_kredit_$bulan$tahun"} -
                                        $d->{"penyesuaian_kredit_$bulan$tahun"} +
                                        $d->{"ppn_kredit_$bulan$tahun"};
                                @endphp
                                <td style="text-align: right">{{ formatAngka($netto_tunai) }}</td>
                                <td style="text-align: right">{{ formatAngka($netto_kredit) }}</td>
                            @endforeach
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

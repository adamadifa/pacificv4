<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Insentif OM {{ date('Y-m-d H:i:s') }}</title>
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
            INSENTIF OOM <br>
        </h4>
        <h4>BULAN :{{ $namabulan[$bulan] }}</h4>
        <h4>TAHUN :{{ $tahun }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif

    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 180%">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">CABANG</th>
                        <th colspan="4">OA</th>
                        <th colspan="2">KENDARAAN</th>
                        <th colspan="2">PENJUALAN BERJALAN <br> VS PENJUALAN BULAN LALU</th>
                        <th colspan="2">ROUTING</th>
                        <th colspan="3">LPC H + 1</th>
                        <th colspan="2">CASHIN</th>
                        <th colspan="3">LJT</th>
                        <th colspan="3">COSTRATIO</th>
                        <th colspan="3">RATIO BS</th>
                        <th rowspan="2">TOTAL</th>
                    </tr>
                    <tr>
                        <th>AKTIF</th>
                        <th>BERTRANSAKSI</th>
                        <th>RATIO</th>
                        <th>REWARD</th>
                        <th>REALISASI</th>
                        <th>REWARD</th>
                        <th>REALISASI</th>
                        <th>REWARD</th>
                        <th>REALISASI</th>
                        <th>REWARD</th>
                        <th>LAMA</th>
                        <th>JAM</th>
                        <th>REWARD</th>
                        <th>REALISASI</th>
                        <th>REWARD</th>
                        <th>REALISASI</th>
                        <th>RATIO</th>
                        <th>REWARD</th>
                        <th>REALISASI</th>
                        <th>RATIO</th>
                        <th>REWARD</th>
                        <th>REALISASI</th>
                        <th>RATIO</th>
                        <th>REWARD</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($insentif as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                            <td class="center">{{ formatAngka($d->jml_pelanggan) }}</td>
                            <td class="center">{{ formatAngka($d->jml_pelangan_bertransaksi) }}</td>
                            <td class="center">
                                @php
                                    $ratio_oa = ROUND(!empty($d->jml_pelanggan) ? ($d->jml_pelangan_bertransaksi / $d->jml_pelanggan) * 100 : 0);
                                @endphp
                                {{ $ratio_oa }} %
                            </td>
                            <td class="right">
                                @php
                                    $reward_oa = getreward($ratio_oa);
                                @endphp
                                {{ formatAngka($reward_oa) }}
                            </td>
                            <td class="center">
                                @php
                                    $ratio_kendaraan = ROUND(!empty($d->jml_kapasitas) ? ($d->jml_pengambilan / $d->jml_kapasitas) * 100 : 0);
                                @endphp
                                {{ $ratio_kendaraan }} %
                            </td>
                            <td class="right">
                                @php
                                    $reward_kendaraan = getreward($ratio_kendaraan);
                                @endphp
                                {{ formatAngka($reward_kendaraan) }}
                            </td>
                            <td class="center">
                                @php
                                    $ratio_penjualan = ROUND(
                                        !empty($d->penjualanbulanlalu) ? ($d->penjualanbulanberjalan / $d->penjualanbulanlalu) * 100 : 0,
                                    );
                                @endphp
                                {{ $ratio_penjualan }} %
                            </td>
                            <td class="right">
                                @php
                                    $reward_penjualan = getreward($ratio_penjualan);
                                @endphp
                                {{ formatAngka($reward_penjualan) }}
                            </td>
                            <td class="center">
                                @php
                                    $ratio_routing = ROUND(!empty($d->jmlkunjungan) ? ($d->jmlsesuaijadwal / $d->jmlkunjungan) * 100 : 0);
                                @endphp
                                {{ $ratio_routing }} %
                            </td>
                            <td align="right">
                                @php
                                    //$reward_routing = getreward($d->ratio_routing);
                                    if ($ratio_routing >= 90 && $ratio_routing <= 95) {
                                        $reward_routing = 100000;
                                    } elseif ($ratio_routing > 95) {
                                        $reward_routing = 200000;
                                    } else {
                                        $reward_routing = 0;
                                    }
                                @endphp
                                {{ rupiah($reward_routing) }}
                            </td>
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

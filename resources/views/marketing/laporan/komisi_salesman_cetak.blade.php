<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Komisi Salesman {{ date('Y-m-d H:i:s') }}</title>
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
            KOMISI SALESMAN <br>
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
            <table class="datatable3" style="width: 150%">
                <thead>
                    <tr>
                        <th rowspan="3">No.</th>
                        <th rowspan="3">Kode</th>
                        <th rowspan="3">Nama Salesman</th>
                        @foreach ($kategori_komisi as $d)
                            <th colspan="3" class="green">{{ $d->deskripsi }}</th>
                        @endforeach
                        <th rowspan="2" colspan="2" class="orange">Total Poin</th>
                        <th rowspan="2" colspan="2" class="biru1">KENDARAAN</th>
                        <th rowspan="2" colspan="2" class="bg-warna-campuran1">OA</th>
                        <th rowspan="2" colspan="2" class="bg-warna-campuran2">PENJUALAN VS AVG</th>
                        <th rowspan="2" colspan="2" class="bg-warna-campuran2">CASHIN</th>
                        <th rowspan="2" colspan="3" class="bg-warna-campuran3">LJT</th>
                    </tr>
                    <tr>
                        @foreach ($kategori_komisi as $d)
                            <th colspan="3" class="green">{{ $d->poin }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($kategori_komisi as $d)
                            <th class="green">Target</th>
                            <th class="green">Realisasi</th>
                            <th class="green">Poin</th>
                        @endforeach
                        <th class="orange">REALISASI</th>
                        <th class="orange">REWARD</th>

                        <th class="biru1">REALISASI</th>
                        <th class="biru1">REWARD</th>

                        <th class="bg-warna-campuran1">REALISASI</th>
                        <th class="bg-warna-campuran1">REWARD</th>

                        <th class="bg-warna-campuran2">REALISASI</th>
                        <th class="bg-warna-campuran2">REWARD</th>

                        <th class="bg-warna-campuran2">REALISASI</th>
                        <th class="bg-warna-campuran2">REWARD</th>

                        <th class="bg-warna-campuran3">REALISASI</th>
                        <th class="bg-warna-campuran3">RATIO</th>
                        <th class="bg-warna-campuran3">REWARD</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_poin = 0;
                    @endphp
                    @foreach ($komisi as $d)
                        @php
                            $realisasi_qty_kendaraan = 0;
                        @endphp
                        @foreach ($produk as $p)
                            @php
                                $realisasi_qty_kendaraan += FLOOR($d->{"qty_kendaraan_$p->kode_produk"});
                            @endphp
                        @endforeach
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_salesman }}</td>
                            <td>{{ $d->nama_salesman }}</td>
                            @php
                                $total_poin = 0;
                            @endphp
                            @foreach ($kategori_komisi as $k)
                                @php
                                    $ratio_target = !empty($d->{"target_$k->kode_kategori"})
                                        ? $d->{"realisasi_$k->kode_kategori"} / $d->{"target_$k->kode_kategori"}
                                        : 0;
                                    if ($ratio_target > 1) {
                                        $poin = $k->poin;
                                    } else {
                                        $poin = $ratio_target * $k->poin;
                                    }
                                    $total_poin += $poin;
                                @endphp
                                <td class="center">{{ formatAngka($d->{"target_$k->kode_kategori"}) }}</td>
                                <td class="center">{{ formatAngka($d->{"realisasi_$k->kode_kategori"}) }}</td>
                                <td class="center">{{ formatAngkaDesimal($poin) }}</td>
                            @endforeach
                            <td class="center">{{ formatAngkaDesimal($total_poin) }}</td>
                            <td class="right">
                                @if ($d->status_komisi == 1)
                                    @php
                                        $totalpoin = $total_poin;
                                    @endphp
                                    @if ($totalpoin > 70 && $totalpoin <= 75)
                                        @php
                                            $reward_qty = 1000000;
                                        @endphp
                                    @elseif ($totalpoin > 75 && $totalpoin <= 80)
                                        @php
                                            $reward_qty = 1500000;
                                        @endphp
                                    @elseif ($totalpoin > 80 && $totalpoin <= 85)
                                        @php
                                            $reward_qty = 2000000;
                                        @endphp
                                    @elseif ($totalpoin > 85 && $totalpoin <= 90)
                                        @php
                                            $reward_qty = 2500000;
                                        @endphp
                                    @elseif ($totalpoin > 90 && $totalpoin <= 95)
                                        @php
                                            $reward_qty = 3000000;
                                        @endphp
                                    @elseif ($totalpoin > 95)
                                        @php
                                            $reward_qty = 3500000;
                                        @endphp
                                    @else
                                        @php
                                            $reward_qty = 0;
                                        @endphp
                                    @endif
                                @else
                                    @php
                                        $reward_qty = 0;
                                    @endphp
                                @endif
                                {{ formatAngka($reward_qty) }}
                            </td>
                            <td class="right">{{ formatAngkaDesimal($realisasi_qty_kendaraan) }}</td>
                            <td class="right">
                                @php
                                    $reward_kendaraan = $d->status_komisi == 1 ? $realisasi_qty_kendaraan * 25 : 0;
                                @endphp
                                {{ formatAngka($reward_kendaraan) }}
                            </td>
                            <td class="center">{{ formatAngka($d->realisasi_oa) }}</td>
                            <td class="right">
                                @php
                                    $reward_oa = $d->status_komisi == 1 ? $d->realisasi_oa * 2000 : 0;
                                @endphp
                                {{ formatAngka($reward_oa) }}
                            </td>
                            <td class="center">{{ formatAngka($d->realisasi_penjvsavg) }}</td>
                            <td class="right">
                                @php
                                    $reward_penjvsavg = $d->status_komisi == 1 ? $d->realisasi_penjvsavg * 2000 : 0;
                                @endphp
                                {{ formatAngka($reward_penjvsavg) }}
                            </td>
                            <td class="right">{{ formatAngka($d->realisasi_cashin) }}</td>
                            <td class="right">
                                @php
                                    $ratio_cashin = 0.1;
                                    $reward_cashin = $d->status_komisi == 1 ? $d->realisasi_cashin * ($ratio_cashin / 100) : 0;
                                @endphp
                                {{ formatAngka($reward_cashin) }}
                            </td>
                            <td class="right">{{ formatAngka($d->saldo_akhir_piutang) }}</td>
                            <td class="center">
                                @php
                                    $ratioljt = !empty($d->realisasi_cashin) ? ($d->saldo_akhir_piutang / $d->realisasi_cashin) * 100 : 0;
                                    if ($ratioljt > 0) {
                                        $ratioljt = $ratioljt;
                                    } else {
                                        $ratioljt = 0;
                                    }
                                @endphp
                                {{ formatAngka($ratioljt) }} %
                            </td>
                            <td class="right">
                                @php
                                    if ($d->status_komisi == 1) {
                                        if ($ratioljt >= 0 and $ratioljt <= 0.5) {
                                            $rewardljt = 300000;
                                        } elseif ($ratioljt > 0.5 and $ratioljt <= 1) {
                                            $rewardljt = 225000;
                                        } elseif ($ratioljt > 1 and $ratioljt <= 1.5) {
                                            $rewardljt = 150000;
                                        } elseif ($ratioljt > 1.5 and $ratioljt <= 2) {
                                            $rewardljt = 75000;
                                        } else {
                                            $rewardljt = 0;
                                        }
                                    } else {
                                        $rewardljt = 0;
                                    }
                                @endphp
                                {{ formatAngka($rewardljt) }}
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

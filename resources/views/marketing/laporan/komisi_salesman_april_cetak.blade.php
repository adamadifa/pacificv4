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
            <table class="datatable3" style="width: 180%">
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
                        <th rowspan="2" colspan="2" class="bg-warna-campuran2">ROUTING</th>
                        <th rowspan="2" colspan="2" class="bg-warna-campuran2">CASHIN</th>
                        <th rowspan="2" colspan="3" class="bg-warna-campuran3">LJT</th>
                        <th rowspan="3">TOTAL REWARD</th>
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
                    @foreach ($kategori_komisi as $k)
                        @php
                            ${"total_target_$k->kode_kategori"} = 0;
                            ${"total_realisasi_$k->kode_kategori"} = 0;
                            ${"total_poin_$k->kode_kategori"} = 0;
                            $total_realisasi_kendaraan = 0;
                            $total_reward_kendaraan = 0;
                            $total_reward_oa = 0;
                            $total_realisasi_penjvsavg = 0;
                            $total_reward_penjvsavg = 0;
                            $total_realisasi_cashin = 0;
                            $total_realisasi_ljt = 0;
                            $total_reward_cashin = 0;
                            $total_reward_ljt = 0;
                            $total_reward_qty = 0;
                            $total_reward_routing = 0;
                        @endphp
                    @endforeach
                    @foreach ($komisi as $d)
                        @php
                            $realisasi_qty_kendaraan = 0;
                        @endphp
                        @foreach ($produk as $p)
                            @php
                                $realisasi_qty_kendaraan += FLOOR($d->{"qty_kendaraan_$p->kode_produk"});
                            @endphp
                        @endforeach
                        @php
                            $total_realisasi_kendaraan += $realisasi_qty_kendaraan;
                            $total_realisasi_penjvsavg += $d->realisasi_penjvsavg;
                            $total_realisasi_cashin += $d->realisasi_cashin;
                            $total_realisasi_ljt += $d->saldo_akhir_piutang;
                        @endphp
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

                                    // if (in_array($k->kode_kategori, ['KKQ03', 'KKQ06', 'KKQ07'])) {
                                    //     if ($ratio_target > 1.2) {
                                    //         $poin = 12;
                                    //     } else {
                                    //         $poin = $ratio_target * $k->poin;
                                    //     }
                                    // } else {
                                    //     if ($ratio_target > 1) {
                                    //         $poin = $k->poin;
                                    //     } else {
                                    //         $poin = $ratio_target * $k->poin;
                                    //     }
                                    // }

                                    if ($ratio_target > 1) {
                                        $poin = $k->poin;
                                    } else {
                                        $poin = $ratio_target * $k->poin;
                                    }

                                    $total_poin += $poin;
                                    ${"total_target_$k->kode_kategori"} += $d->{"target_$k->kode_kategori"};
                                    ${"total_realisasi_$k->kode_kategori"} += $d->{"realisasi_$k->kode_kategori"};
                                    ${"total_poin_$k->kode_kategori"} += $poin;
                                @endphp
                                <td class="right">{{ formatAngkaDesimal($d->{"target_$k->kode_kategori"}) }}</td>
                                <td class="right">{{ formatAngkaDesimal($d->{"realisasi_$k->kode_kategori"}) }}</td>
                                <td class="center">{{ formatAngkaDesimal($poin) }}</td>
                            @endforeach
                            <td class="right">{{ formatAngkaDesimal($total_poin) }}</td>
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
                                @php
                                    $total_reward_qty += $reward_qty;
                                @endphp
                                {{ formatAngka($reward_qty) }}
                            </td>
                            <td class="right">{{ formatAngkaDesimal($realisasi_qty_kendaraan) }}</td>
                            <td class="right">
                                @php
                                    $reward_kendaraan = $d->status_komisi == 1 ? $realisasi_qty_kendaraan * 25 : 0;
                                    $total_reward_kendaraan += $reward_kendaraan;
                                @endphp
                                {{ formatAngka($reward_kendaraan) }}
                            </td>
                            <td class="center">{{ formatAngka($d->realisasi_oa) }}</td>
                            <td class="right">
                                @php
                                    $reward_oa = $d->status_komisi == 1 ? $d->realisasi_oa * 2000 : 0;
                                    $total_reward_oa += $reward_oa;
                                @endphp
                                {{ formatAngka($reward_oa) }}
                            </td>
                            <td class="center">{{ formatAngka($d->realisasi_penjvsavg) }}</td>
                            <td class="right">
                                @php
                                    $reward_penjvsavg = $d->status_komisi == 1 ? $d->realisasi_penjvsavg * 2000 : 0;
                                    $total_reward_penjvsavg += $reward_penjvsavg;
                                @endphp
                                {{ formatAngka($reward_penjvsavg) }}
                            </td>
                            <td class="center">
                                @php
                                    $persentaserouting = !empty($d->jmlkunjungan)
                                        ? ($d->jmlsesuaijadwal / $d->jmlkunjungan) * 100
                                        : 0;
                                @endphp
                                {{ formatAngkaDesimal($persentaserouting, 2) }}
                            </td>
                            <td class="right">
                                @php
                                    if ($d->status_komisi == 1) {
                                        if ($persentaserouting >= 90 && $persentaserouting <= 95) {
                                            $reward_routing = 200000;
                                        } elseif ($persentaserouting > 95) {
                                            $reward_routing = 400000;
                                        } else {
                                            $reward_routing = 0;
                                        }
                                    } else {
                                        $reward_routing = 0;
                                    }
                                    $total_reward_routing += $reward_routing;
                                @endphp
                                {{ formatAngka($reward_routing) }}
                            </td>
                            <td class="right">
                                {{ formatAngka($d->realisasi_cashin) }}
                            </td>
                            <td class="right">
                                @php
                                    $ratio_cashin = 0.1;
                                    $reward_cashin =
                                        $d->status_komisi == 1 ? $d->realisasi_cashin * ($ratio_cashin / 100) : 0;
                                    $total_reward_cashin += $reward_cashin;
                                @endphp
                                {{ formatAngka($reward_cashin) }}
                            </td>
                            <td class="right">{{ formatAngka($d->saldo_akhir_piutang) }}</td>
                            <td class="center">
                                @php
                                    $ratioljt = !empty($d->realisasi_cashin)
                                        ? ($d->saldo_akhir_piutang / $d->realisasi_cashin) * 100
                                        : 0;
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

                                    $total_reward_ljt += $rewardljt;
                                @endphp
                                {{ formatAngka($rewardljt) }}
                            </td>
                            <td class="right">
                                @php
                                    $total_reward =
                                        $reward_qty +
                                        $reward_kendaraan +
                                        $reward_oa +
                                        $reward_penjvsavg +
                                        $reward_routing +
                                        $reward_cashin +
                                        $rewardljt;
                                @endphp
                                {{ formatAngka($total_reward) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @if ($cabang->kode_cabang == 'BDG')


                        <tr>
                            <th colspan="3">spv</th>
                            @php
                                $total_poin_spv = 0;
                            @endphp
                            @foreach ($kategori_komisi as $k)
                                <th class="right">{{ formatAngka(${"total_target_$k->kode_kategori"}) }}</th>
                                <th class="right">{{ formatAngka(${"total_realisasi_$k->kode_kategori"}) }}</th>
                                <th>
                                    @php
                                        $poinspv =
                                            (${"total_realisasi_$k->kode_kategori"} /
                                                ${"total_target_$k->kode_kategori"}) *
                                            $k->poin;
                                        $total_poin_spv += $poinspv;
                                    @endphp
                                    {{ formatAngkaDesimal($poinspv) }}
                                </th>
                            @endforeach
                            <th class="right">{{ formatAngkaDesimal($total_poin_spv) }}</th>
                            <th>
                                @php
                                    $reward_qty_spv = $total_reward_qty / count($komisi);
                                @endphp
                                {{ formatAngka($reward_qty_spv) }}
                            </th>
                            <th>{{ formatAngkaDesimal($total_realisasi_kendaraan) }}</th>
                            <th class="right">
                                @php
                                    $reward_kendaraan_spv = $total_reward_kendaraan / count($komisi);
                                @endphp
                                {{ formatAngka($reward_kendaraan_spv) }}
                            </th>
                            <th></th>
                            <th class="right">
                                @php
                                    $reward_oa_spv = $total_reward_oa / count($komisi);
                                @endphp
                                {{ formatAngka($reward_oa_spv) }}
                            </th>
                            <th class="center">{{ $total_realisasi_penjvsavg }}</th>
                            <th class="right">
                                @php
                                    $reward_penjvsavg_spv = $total_reward_penjvsavg / count($komisi);
                                @endphp
                                {{ formatAngka($reward_penjvsavg_spv) }}
                            </th>
                            <th></th>
                            <th class="right">
                                @php
                                    $reward_routing_spv = $total_reward_routing / count($komisi);
                                @endphp
                                {{ formatAngka($reward_routing_spv) }}
                            </th>
                            <th class="right">
                                {{ formatAngka($total_realisasi_cashin) }}
                            </th>

                            <th class="right">
                                @php
                                    $reward_cashin_spv = $total_reward_cashin / count($komisi);
                                @endphp
                                {{ formatAngka($reward_cashin_spv) }}
                            </th>
                            <th class="right">
                                {{ formatAngka($total_realisasi_ljt) }}
                            </th>
                            <th></th>
                            <th class="right">
                                @php
                                    $reward_ljt_spv = $total_reward_ljt / count($komisi);
                                @endphp
                                {{ formatAngka($reward_ljt_spv) }}
                            </th>
                            <th class="right">
                                @php
                                    $total_reward_spv =
                                        $reward_qty_spv +
                                        $reward_kendaraan_spv +
                                        $reward_oa_spv +
                                        $reward_penjvsavg_spv +
                                        $reward_routing_spv +
                                        $reward_cashin_spv +
                                        $reward_ljt_spv;
                                @endphp
                                {{ formatAngka($total_reward_spv) }}
                            </th>
                        </tr>
                    @endif
                    <tr>
                        <th colspan="3">SMM</th>
                        @php
                            $total_poin_smm = 0;
                        @endphp
                        @foreach ($kategori_komisi as $k)
                            <th class="right">{{ formatAngka(${"total_target_$k->kode_kategori"}) }}</th>
                            <th class="right">{{ formatAngka(${"total_realisasi_$k->kode_kategori"}) }}</th>
                            <th>
                                @php
                                    $ratiopoinsmm =
                                        ${"total_realisasi_$k->kode_kategori"} / ${"total_target_$k->kode_kategori"};
                                    if ($ratiopoinsmm > 1) {
                                        $poinsmm = $k->poin;
                                    } else {
                                        $poinsmm = $ratiopoinsmm * $k->poin;
                                    }
                                    $total_poin_smm += $poinsmm;
                                @endphp
                                {{ formatAngkaDesimal($poinsmm) }}
                            </th>
                        @endforeach
                        <th class="right">{{ formatAngkaDesimal($total_poin_smm) }}</th>
                        <th>
                            @php
                                $reward_qty_smm = ($total_reward_qty / count($komisi)) * 2;
                            @endphp
                            {{ formatAngka($reward_qty_smm) }}
                        </th>
                        <th>{{ formatAngkaDesimal($total_realisasi_kendaraan) }}</th>
                        <th class="right">
                            @php
                                $reward_kendaraan_smm = ($total_reward_kendaraan / count($komisi)) * 2;
                            @endphp
                            {{ formatAngka($reward_kendaraan_smm) }}
                        </th>
                        <th></th>
                        <th class="right">
                            @php
                                $reward_oa_smm = ($total_reward_oa / count($komisi)) * 2;
                            @endphp
                            {{ formatAngka($reward_oa_smm) }}
                        </th>
                        <th class="center">{{ $total_realisasi_penjvsavg }}</th>
                        <th class="right">
                            @php
                                $reward_penjvsavg_smm = ($total_reward_penjvsavg / count($komisi)) * 2;
                            @endphp
                            {{ formatAngka($reward_penjvsavg_smm) }}
                        </th>
                        <th></th>
                        <th class="right">
                            @php
                                $reward_routing_smm = ($total_reward_routing / count($komisi)) * 2;
                            @endphp
                            {{ formatAngka($reward_routing_smm) }}
                        </th>
                        <th class="right">
                            {{ formatAngka($total_realisasi_cashin) }}
                        </th>

                        <th class="right">
                            @php
                                $reward_cashin_smm = ($total_reward_cashin / count($komisi)) * 2;
                            @endphp
                            {{ formatAngka($reward_cashin_smm) }}
                        </th>
                        <th class="right">
                            {{ formatAngka($total_realisasi_ljt) }}
                        </th>
                        <th></th>
                        <th class="right">
                            @php
                                $reward_ljt_smm = ($total_reward_ljt / count($komisi)) * 2;
                            @endphp
                            {{ formatAngka($reward_ljt_smm) }}
                        </th>
                        <th class="right">
                            @php
                                $total_reward_smm =
                                    $reward_qty_smm +
                                    $reward_kendaraan_smm +
                                    $reward_oa_smm +
                                    $reward_penjvsavg_smm +
                                    $reward_routing_smm +
                                    $reward_cashin_smm +
                                    $reward_ljt_smm;
                            @endphp
                            {{ formatAngka($total_reward_smm) }}
                        </th>
                    </tr>

                </tfoot>
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

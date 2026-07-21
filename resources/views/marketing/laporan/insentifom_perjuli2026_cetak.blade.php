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
            <table class="datatable3" style="width: 100%">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">CABANG</th>
                        <th colspan="3">LPC H + 1</th>
                        <th colspan="2">CASHIN</th>
                        <th colspan="3">LJT</th>
                        <th colspan="3">COSTRATIO</th>
                        <th colspan="4">RATIO BS</th>
                        <th colspan="2">VALIDASI KUNJUNGAN</th>
                        <th colspan="2">OPNAME HARIAN</th>
                        <th rowspan="2">TOTAL</th>
                    </tr>
                    <tr>
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
                        <th>REJECT</th>
                        <th>OMSET</th>
                        <th>RATIO</th>
                        <th>REWARD</th>
                        <th>REALISASI</th>
                        <th>REWARD</th>
                        <th>REALISASI</th>
                        <th>REWARD</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($insentif as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                            <td class="center">{{ $d->lama_lpc }}</td>
                            <td class="center">{{ $d->jam_lpc }}</td>
                            <td align="right">
                                @if (!empty($d->lama_lpc) && $d->lama_lpc <= 1 && $d->jam_lpc <= '13:00')
                                    @php
                                        $reward_lpc = 350000;
                                    @endphp
                                @else
                                    @php
                                        $reward_lpc = 0;
                                    @endphp
                                @endif
                                {{ formatAngka($reward_lpc) }}
                            </td>
                            <td class="right">{{ formatAngka($d->realisasi_cashin) }}</td>
                            <td style="text-align: right">
                                @php
                                    $reward_cashin = (0.01 / 100) * $d->realisasi_cashin;
                                @endphp
                                {{ formatAngka($reward_cashin) }}
                            </td>
                            <td class="right">{{ formatAngka($d->saldo_akhir_piutang) }}</td>
                            <td align="center">
                                @php
                                    $ratio_ljt = ROUND(!empty($d->realisasi_cashin) ? ($d->saldo_akhir_piutang / $d->realisasi_cashin) * 100 : 0, 2);
                                @endphp
                                {{ $ratio_ljt }}%
                            </td>
                            <td align="right">
                                @php
                                    if ($ratio_ljt <= 0.5) {
                                        $reward_ljt = 300000;
                                    } elseif ($ratio_ljt > 0.5 && $ratio_ljt <= 1) {
                                        $reward_ljt = 225000;
                                    } elseif ($ratio_ljt > 1 && $ratio_ljt <= 1.5) {
                                        $reward_ljt = 150000;
                                    } elseif ($ratio_ljt > 1.5 && $ratio_ljt <= 2) {
                                        $reward_ljt = 75000;
                                    } else {
                                        $reward_ljt = 0;
                                    }
                                @endphp
                                {{ formatAngka($reward_ljt) }}
                            </td>
                            <td class="right">{{ formatAngka($d->jml_biaya) }}</td>
                            <td align="center">
                                @php
                                    if ($d->kode_cabang == 'TSM') {
                                        $cost_ratio =
                                            ROUND(!empty($d->penjualanbulanberjalan) ? ($d->jml_biaya / $d->penjualanbulanberjalan) * 100 : 0) + 4;
                                    } else {
                                        $cost_ratio = ROUND(
                                            !empty($d->penjualanbulanberjalan) ? ($d->jml_biaya / $d->penjualanbulanberjalan) * 100 : 0,
                                        );
                                    }
                                @endphp
                                {{ $cost_ratio }} %
                            </td>
                            <td align="right">
                                @php
                                    if ($cost_ratio <= 6) {
                                        $reward_costratio = 600000;
                                    } elseif ($cost_ratio > 6 && $cost_ratio <= 7) {
                                        $reward_costratio = 500000;
                                    } elseif ($cost_ratio > 7 && $cost_ratio <= 8) {
                                        $reward_costratio = 400000;
                                    } elseif ($cost_ratio > 8 && $cost_ratio <= 9) {
                                        $reward_costratio = 300000;
                                    } elseif ($cost_ratio > 9 && $cost_ratio <= 10) {
                                        $reward_costratio = 200000;
                                    } else {
                                        $reward_costratio = 100000;
                                    }
                                @endphp
                                {{ formatAngka($reward_costratio) }}
                            </td>
                            <td align="right">
                                @php
                                    $ytd_totalharga = 0;
                                    $ytd_d_retur = $ytd_retur[$d->kode_cabang] ?? null;
                                    $ytd_d_mutasi = $ytd_mutasi[$d->kode_cabang] ?? null;
                                    foreach ($produk as $p) {
                                         $ytd_jmlreject =
                                             ($ytd_d_mutasi ? $ytd_d_mutasi->{"reject_pasar_$p->kode_produk"} : 0) +
                                             ($ytd_d_mutasi ? $ytd_d_mutasi->{"reject_mobil_$p->kode_produk"} : 0) +
                                             ($ytd_d_mutasi ? $ytd_d_mutasi->{"reject_gudang_$p->kode_produk"} : 0) -
                                             ($ytd_d_mutasi ? $ytd_d_mutasi->{"repack_$p->kode_produk"} : 0);
                                         $ytd_retur_count = $ytd_d_mutasi ? $ytd_d_mutasi->{"retur_$p->kode_produk"} : 0;
                                         $ytd_retur_total = $ytd_d_retur ? $ytd_d_retur->{"total_retur_$p->kode_produk"} : 0;
                                         $ytd_harga = $ytd_retur_count > 0 ? $ytd_retur_total / $ytd_retur_count : 0;
                                         $ytd_total = ROUND($ytd_jmlreject, 2) * $ytd_harga;
                                         $ytd_totalharga += $ytd_total;
                                    }
                                @endphp
                                {{ formatAngka($ytd_totalharga) }}
                            </td>
                            <td align="right">
                                @php
                                    $ytd_omset = $ytd_netto[$d->kode_cabang] ?? 0;
                                @endphp
                                {{ formatAngka($ytd_omset) }}
                            </td>
                            <td align="center">
                                @php
                                    $ratio_bs = ROUND(!empty($ytd_omset) ? (ROUND($ytd_totalharga) / $ytd_omset) * 100 : 0, 2);
                                @endphp
                                {{ $ratio_bs }}%
                            </td>
                            <td align="right">
                                @if ($ratio_bs <= 0.10)
                                    @php
                                        $reward_bs = 550000;
                                    @endphp
                                @elseif ($ratio_bs > 0.10 && $ratio_bs <= 0.20)
                                    @php
                                        $reward_bs = 500000;
                                    @endphp
                                @elseif ($ratio_bs > 0.20 && $ratio_bs <= 0.30)
                                    @php
                                        $reward_bs = 450000;
                                    @endphp
                                @elseif ($ratio_bs > 0.30 && $ratio_bs <= 0.40)
                                    @php
                                        $reward_bs = 400000;
                                    @endphp
                                @elseif ($ratio_bs > 0.40 && $ratio_bs <= 0.50)
                                    @php
                                        $reward_bs = 350000;
                                    @endphp
                                @elseif ($ratio_bs > 0.50 && $ratio_bs <= 0.60)
                                    @php
                                        $reward_bs = 300000;
                                    @endphp
                                @elseif ($ratio_bs > 0.60 && $ratio_bs <= 0.70)
                                    @php
                                        $reward_bs = 250000;
                                    @endphp
                                @elseif ($ratio_bs > 0.70 && $ratio_bs <= 0.80)
                                    @php
                                        $reward_bs = 200000;
                                    @endphp
                                @elseif ($ratio_bs > 0.80 && $ratio_bs <= 0.90)
                                    @php
                                        $reward_bs = 150000;
                                    @endphp
                                @elseif ($ratio_bs > 0.90 && $ratio_bs <= 1.00)
                                    @php
                                        $reward_bs = 100000;
                                    @endphp
                                @else
                                    @php
                                        $reward_bs = 50000;
                                    @endphp
                                @endif
                                {{ formatAngka($reward_bs) }}
                            </td>
                            <td align="center">{{ formatAngka($d->total_validasi_kunjungan) }}</td>
                            <td align="right">
                                @php
                                    $reward_validasi_kunjungan = ($d->total_validasi_kunjungan ?? 0) * 5000;
                                @endphp
                                {{ formatAngka($reward_validasi_kunjungan) }}
                            </td>
                            <!-- Opname Harian -->
                            <td align="center">{{ formatAngka($d->total_opname_harian ?? 0) }}</td>
                            <td align="right">
                                @php
                                    $reward_opname = ($d->total_opname_harian ?? 0) * 10000;
                                @endphp
                                {{ formatAngka($reward_opname) }}
                            </td>
                            <td align="right">
                                @php
                                    $totalreward =
                                        $reward_lpc +
                                        $reward_cashin +
                                        $reward_ljt +
                                        $reward_costratio +
                                        $reward_bs +
                                        $reward_validasi_kunjungan +
                                        $reward_opname;
                                @endphp
                                {{ formatAngka($totalreward) }}
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

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Komisi Salesman New {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>

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
                            <th colspan="3" class="green">{{ $d->deskripsi }}
                                {{ $d->kode_kategori == 'KKQ07' ? '+ BR500' : '' }}
                            </th>
                        @endforeach
                        <th rowspan="2" colspan="2" class="orange">Total Poin</th>
                        <th rowspan="2" colspan="2" class="bg-warna-campuran2">CASHIN</th>
                        <th rowspan="2" colspan="3" class="bg-warna-campuran3">LJT</th>
                        <th rowspan="2" colspan="3" class="bg-warna-campuran4">OUTLET PESERTA PROGRAM</th>
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


                        <th class="bg-warna-campuran2">REALISASI</th>
                        <th class="bg-warna-campuran2">REWARD</th>

                        <th class="bg-warna-campuran3">REALISASI</th>
                        <th class="bg-warna-campuran3">RATIO</th>
                        <th class="bg-warna-campuran3">REWARD</th>
                        <th class="bg-warna-campuran4">TERCAPAI</th>
                        <th class="bg-warna-campuran4">TDK TERCAPAI</th>
                        <th class="bg-warna-campuran4">REWARD</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_poin = 0;
                        $count_komisi = count($komisi) > 0 ? count($komisi) : 1;
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
                            $total_realisasi_program = 0;
                            $total_tidak_realisasi_program = 0;
                            $total_reward_program = 0;
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
                                    $realisasiBR500 = !empty($d->realisasi_BR500) ? $d->realisasi_BR500 * 500 / 2880 : 0;
                                    $realisasi_kategori = $k->kode_kategori == 'KKQ07' ? ($d->{"realisasi_$k->kode_kategori"} ?? 0) + ($realisasiBR500 ?? 0) : ($d->{"realisasi_$k->kode_kategori"} ?? 0);
                                    $ratio_target = !empty($d->{"target_$k->kode_kategori"})
                                        ? $realisasi_kategori / $d->{"target_$k->kode_kategori"}
                                        : 0;

                                    if ($ratio_target > 1.5) {
                                        $poin = 1.5 * $k->poin;
                                    } else {
                                        $poin = $ratio_target * $k->poin;
                                    }

                                    $total_poin += $poin;
                                    ${"total_target_$k->kode_kategori"} += $d->{"target_$k->kode_kategori"};
                                    ${"total_realisasi_$k->kode_kategori"} += $realisasi_kategori;
                                    ${"total_poin_$k->kode_kategori"} += $poin;
                                @endphp
                                <td class="right">{{ formatAngkaDesimal($d->{"target_$k->kode_kategori"}) }}</td>
                                <td class="right">
                                    {{ formatAngkaDesimal($realisasi_kategori) }}
                                </td>
                                <td class="center">{{ formatAngkaDesimal($poin) }}</td>
                            @endforeach
                            <td class="right">{{ formatAngkaDesimal($total_poin) }}</td>
                            <td class="right">
                                @if ($d->status_komisi == 1)
                                    @php
                                        $totalpoin = $total_poin;
                                    @endphp
                                    @if ($totalpoin >= 70 && $totalpoin < 75)
                                        @php
                                            $reward_qty = 1500000;
                                        @endphp
                                    @elseif ($totalpoin >= 75 && $totalpoin < 80)
                                        @php
                                            $reward_qty = 2000000;
                                        @endphp
                                    @elseif ($totalpoin >= 80 && $totalpoin < 85)
                                        @php
                                            $reward_qty = 2500000;
                                        @endphp
                                    @elseif ($totalpoin >= 85 && $totalpoin < 90)
                                        @php
                                            $reward_qty = 3000000;
                                        @endphp
                                    @elseif ($totalpoin >= 90 && $totalpoin < 95)
                                        @php
                                            $reward_qty = 3500000;
                                        @endphp
                                    @elseif ($totalpoin >= 95)
                                        @php
                                            $reward_qty = 4000000;
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
                            <td class="center">
                                @if($d->total_peserta_tercapai > 0)
                                    @php
                                        $participants = $program_participants[$d->kode_salesman] ?? collect();
                                    @endphp
                                    <a href="#" class="show-detail-program" data-salesman="{{ $d->nama_salesman }} (Tercapai)" data-details="{{ json_encode($participants->values()->toArray()) }}" style="color: blue; text-decoration: underline; font-weight: bold;">
                                        {{ formatAngka($d->total_peserta_tercapai) }}
                                    </a>
                                @else
                                    0
                                @endif
                            </td>
                            <td class="center">
                                @if($d->total_peserta_tidak_tercapai > 0)
                                    @php
                                        $participants_tidak = $program_participants_tidak_tercapai[$d->kode_salesman] ?? collect();
                                    @endphp
                                    <a href="#" class="show-detail-program" data-salesman="{{ $d->nama_salesman }} (Tidak Tercapai)" data-details="{{ json_encode($participants_tidak->values()->toArray()) }}" style="color: red; text-decoration: underline; font-weight: bold;">
                                        {{ formatAngka($d->total_peserta_tidak_tercapai) }}
                                    </a>
                                @else
                                    0
                                @endif
                            </td>
                            <td class="right">
                                @php
                                    $reward_program = ($d->total_peserta_tercapai * 10000) - ($d->total_peserta_tidak_tercapai * 10000);
                                    $total_reward_program += $reward_program;
                                    $total_realisasi_program += $d->total_peserta_tercapai;
                                    $total_tidak_realisasi_program += $d->total_peserta_tidak_tercapai;
                                @endphp
                                {{ formatAngka($reward_program) }}
                            </td>
                            <td class="right">
                                @php
                                    $total_reward = $reward_qty + $reward_cashin + $rewardljt + $reward_program;
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
                                        $target_cat = ${"total_target_$k->kode_kategori"};
                                        $poinspv = $target_cat > 0
                                            ? (${"total_realisasi_$k->kode_kategori"} / $target_cat) * $k->poin
                                            : 0;
                                        $total_poin_spv += $poinspv;
                                    @endphp
                                    {{ formatAngkaDesimal($poinspv) }}
                                </th>
                            @endforeach
                            <th class="right">{{ formatAngkaDesimal($total_poin_spv) }}</th>
                            <th>
                                @php
                                    $reward_qty_spv = $total_reward_qty / $count_komisi;
                                 @endphp
                                {{ formatAngka($reward_qty_spv) }}
                            </th>


                            <th class="right">
                                {{ formatAngka($total_realisasi_cashin) }}
                            </th>

                            <th class="right">
                                @php
                                    $reward_cashin_spv = $total_reward_cashin / $count_komisi;
                                @endphp
                                {{ formatAngka($reward_cashin_spv) }}
                            </th>
                            <th class="right">
                                {{ formatAngka($total_realisasi_ljt) }}
                            </th>
                            <th></th>
                            <th class="right">
                                @php
                                    $reward_ljt_spv = $total_reward_ljt / $count_komisi;
                                @endphp
                                {{ formatAngka($reward_ljt_spv) }}
                            </th>
                            <td class="center">{{ formatAngka($total_realisasi_program) }}</td>
                            <td class="center">{{ formatAngka($total_tidak_realisasi_program) }}</td>
                            <td class="right">
                                @php
                                    $reward_program_spv = $total_reward_program / $count_komisi;
                                @endphp
                                {{ formatAngka($reward_program_spv) }}
                            </td>
                            <td class="right">
                                @php
                                    $total_reward_spv =
                                        $reward_qty_spv + $reward_cashin_spv + $reward_ljt_spv + $reward_program_spv;
                                @endphp
                                {{ formatAngka($total_reward_spv) }}
                            </td>
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
                                    $ratiopoinsmm = !empty(${"total_target_$k->kode_kategori"})
                                        ? ${"total_realisasi_$k->kode_kategori"} / ${"total_target_$k->kode_kategori"}
                                        : 0;
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
                            @if ($total_poin_smm > 70 && $total_poin_smm <= 75)
                                @php
                                    $reward_qty_smm = 2000000;
                                @endphp
                            @elseif ($total_poin_smm > 75 && $total_poin_smm <= 80)
                                @php
                                    $reward_qty_smm = 3000000;
                                @endphp
                            @elseif ($total_poin_smm > 80 && $total_poin_smm <= 85)
                                @php
                                    $reward_qty_smm = 4000000;
                                @endphp
                            @elseif ($total_poin_smm > 85 && $total_poin_smm <= 90)
                                @php
                                    $reward_qty_smm = 5000000;
                                @endphp
                            @elseif ($total_poin_smm > 90 && $total_poin_smm <= 95)
                                @php
                                    $reward_qty_smm = 6000000;
                                @endphp
                            @elseif ($total_poin_smm > 95)
                                @php
                                    $reward_qty_smm = 7000000;
                                @endphp
                            @else
                                @php
                                    $reward_qty_smm = 0;
                                @endphp
                            @endif
                            {{ formatAngka($reward_qty_smm) }}
                        </th>



                        <th class="right">
                            {{ formatAngka($total_realisasi_cashin) }}
                        </th>

                        <th class="right">
                            @php
                                $reward_cashin_smm = ($total_reward_cashin / $count_komisi) * 2;
                            @endphp
                            {{ formatAngka($reward_cashin_smm) }}
                        </th>
                        <th class="right">
                            {{ formatAngka($total_realisasi_ljt) }}
                        </th>
                        <th></th>
                        <th class="right">
                            @php
                                $reward_ljt_smm = ($total_reward_ljt / $count_komisi) * 2;
                            @endphp
                            {{ formatAngka($reward_ljt_smm) }}
                        </th>
                        <th class="center">{{ formatAngka($total_realisasi_program) }}</th>
                        <th class="center">{{ formatAngka($total_tidak_realisasi_program) }}</th>
                        <th class="right">
                            @php
                                $reward_program_smm = ($total_reward_program / $count_komisi) * 2;
                            @endphp
                            {{ formatAngka($reward_program_smm) }}
                        </th>
                        <th class="right">
                            @php
                                $total_reward_smm =
                                    $reward_qty_smm + $reward_cashin_smm + $reward_ljt_smm + $reward_program_smm;
                            @endphp
                            {{ formatAngka($total_reward_smm) }}
                        </th>
                    </tr>

                </tfoot>
            </table>
        </div>
    </div>

    <!-- Simple Modern Modal for Program Outlet Details -->
    <div id="detailProgramModal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); font-family: Arial, sans-serif;">
        <div style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 60%; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 15px;">
                <h3 id="modalTitle" style="margin: 0; color: #333;">Detail Outlet Peserta Program</h3>
                <span id="closeModal" style="color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
            </div>
            <div id="modalBody" style="max-height: 400px; overflow-y: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background-color: #199291; color: white;">
                            <th style="border: 1px solid #ddd; padding: 10px;">No</th>
                            <th style="border: 1px solid #ddd; padding: 10px;">Kode Pelanggan</th>
                            <th style="border: 1px solid #ddd; padding: 10px;">Nama Pelanggan</th>
                            <th style="border: 1px solid #ddd; padding: 10px;">Nama Program</th>
                        </tr>
                    </thead>
                    <tbody id="modalTableBody" style="color: #333;">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".show-detail-program").click(function(e) {
                e.preventDefault();
                var salesman = $(this).data("salesman");
                var details = $(this).data("details");
                
                $("#modalTitle").text("Detail Program Outlet: " + salesman);
                var rows = "";
                $.each(details, function(index, item) {
                    rows += "<tr>" +
                        "<td style='border: 1px solid #ddd; padding: 10px;'>" + (index + 1) + "</td>" +
                        "<td style='border: 1px solid #ddd; padding: 10px;'>" + item.kode_pelanggan + "</td>" +
                        "<td style='border: 1px solid #ddd; padding: 10px;'>" + item.nama_pelanggan + "</td>" +
                        "<td style='border: 1px solid #ddd; padding: 10px;'>" + item.nama_program + "</td>" +
                        "</tr>";
                });
                
                $("#modalTableBody").html(rows);
                $("#detailProgramModal").show();
            });

            $("#closeModal").click(function() {
                $("#detailProgramModal").hide();
            });

            $(window).click(function(e) {
                if (e.target.id == "detailProgramModal") {
                    $("#detailProgramModal").hide();
                }
            });
        });
    </script>
</body>

</html>

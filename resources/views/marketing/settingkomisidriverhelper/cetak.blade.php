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
        <div class="row">
            <div class="col">
                <table class="datatable3">
                    <tr>
                        <th style="text-align: left;">Bulan</th>
                        <td class="text-end">{{ $namabulan[$settingkomisidriverhelper->bulan] }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Tahun</th>
                        <td class="text-end">{{ $settingkomisidriverhelper->tahun }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Total Komisi</th>
                        <td class="right">{{ formatAngkaDesimal($settingkomisidriverhelper->komisi_salesman) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Qty Penjualan</th>
                        <td class="right">
                            @php
                                $qty_penjualan = 0;
                            @endphp
                            @foreach ($produk as $p)
                                @php
                                    $qty_penjualan += FLOOR($detailpenjualan->{"qty_kendaraan_$p->kode_produk"});
                                @endphp
                            @endforeach
                            {{ formatAngkaDesimal($qty_penjualan) }}
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Value/Unit</th>
                        <td class="right">
                            @php
                                $value_unit = ROUND($settingkomisidriverhelper->komisi_salesman / $qty_penjualan, 2);
                            @endphp
                            {{ formatAngkaDesimal($value_unit) }}
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Qty Flat</th>
                        <td class="right">{{ formatAngkaDesimal($settingkomisidriverhelper->qty_flat) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">UMK</th>
                        <td class="right">{{ formatAngkaDesimal($settingkomisidriverhelper->umk) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Persentase</th>
                        <td class="right">{{ formatAngkaDesimal($settingkomisidriverhelper->persentase) }} %</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row" style="margin-top: 15px">
            <div class="col">
                <table class="datatable3">
                    <thead>
                        <tr>
                            <th rowspan="2">Kode</th>
                            <th rowspan="2">Nama</th>
                            <th rowspan="2">Posisi</th>
                            <th colspan="3">Quantity</th>
                            <th rowspan="2">TOTAL</th>
                        </tr>
                        <tr>
                            <th class="green">DRIVER</th>
                            <th class="red">HELPER</th>
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
                            $jml_personil = 0;
                        @endphp

                        @foreach ($komisi as $d)
                            @php
                                $total_qty = ROUND($d->qty_driver + $d->qty_helper);
                                $komisi = ROUND(
                                    ($settingkomisidriverhelper->umk +
                                        $settingkomisidriverhelper->qty_flat +
                                        $value_unit * ($settingkomisidriverhelper->persentase / 100)) *
                                        $total_qty,
                                );
                                $grandtotal_komisi += $komisi;
                                $jml_personil += 1;
                            @endphp
                            <tr>
                                <td>{{ $d->kode_driver_helper }}</td>
                                <td>{{ $d->nama_driver_helper }}</td>
                                <td>{{ $posisi[$d->posisi] }}</td>
                                <td class="right">{{ formatAngkaDesimal($d->qty_driver) }}</td>
                                <td class="right">{{ formatAngkaDesimal($d->qty_helper) }}</td>
                                <td class="right">{{ formatAngka($total_qty) }}</td>
                                <td class="right">{{ formatAngka($komisi) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6">GRAND TOTAL</th>
                            <th class="right">{{ formatAngka($grandtotal_komisi) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <br>

        <table class="datatable3">
            <tr>
                <th rowspan="2">GUDANG</th>
                <th>KOMISI DRIVER HELPER</th>
                <th>JML PERSONIL</th>
                <th>KOMISI GUDANG</th>
            </tr>
            <tr>
                @php
                    // $total_qty = $komisi_gudang->qty_gudang;
                    // $komisi = ROUND(
                    //     ($settingkomisidriverhelper->umk +
                    //         $settingkomisidriverhelper->qty_flat +
                    //         $value_unit * ($settingkomisidriverhelper->persentase / 100)) *
                    //         $total_qty,
                    // );

                    $komisi_gudang = ($grandtotal_komisi / $jml_personil) * 0.6;
                @endphp
                <td>{{ formatAngka($grandtotal_komisi) }}</td>
                <td>{{ formatAngka($jml_personil) }}</td>
                <td>{{ formatAngka($komisi_gudang) }}</td>
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

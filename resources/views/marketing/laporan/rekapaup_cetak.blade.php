<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Analisa Umur Piutang {{ date('Y-m-d H:i:s') }}</title>
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
            LAPORAN ANALISA UMUR PIUTANG <br>
        </h4>
        <h4>SAMPAI DENGAN TANGGAL {{ DateToIndo($tanggal) }}</h4>
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
            <table class="datatable3" style="width: 100%">
                <thead>
                    <tr>

                        <th rowspan="2">Kode Salesman</th>
                        <th rowspan="2">Nama Salesman</th>
                        <th colspan="9">Saldo Piutang</th>
                        <th rowspan="2">Total</th>
                    </tr>
                    <tr>
                        <th> 1 s/d 15 Hari</th>
                        <th> 16 Hari s/d 1 Bulan</th>
                        <th> > 1 Bulan s/d 45 Hari</th>
                        <th> 46 s/d 2 Bulan</th>
                        <th> 2 Bulan s/d 3 Bulan </th>
                        <th> 3 Bulan s/d 6 Bulan </th>
                        <th> 6 Bulan s/d 1 Tahun </th>
                        <th> 1 Tahun s/d 2 Tahun</th>
                        <th> > 2 Tahun</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_umur_0_15 = 0;
                        $total_umur_16_31 = 0;
                        $total_umur_32_45 = 0;
                        $total_umur_46_60 = 0;
                        $total_umur_61_90 = 0;
                        $total_umur_91_180 = 0;
                        $total_umur_181_360 = 0;
                        $total_umur_361_720 = 0;
                        $total_umur_lebih_720 = 0;

                        $subtotal_umur_0_15 = 0;
                        $subtotal_umur_16_31 = 0;
                        $subtotal_umur_32_45 = 0;
                        $subtotal_umur_46_60 = 0;
                        $subtotal_umur_61_90 = 0;
                        $subtotal_umur_91_180 = 0;
                        $subtotal_umur_181_360 = 0;
                        $subtotal_umur_361_720 = 0;
                        $subtotal_umur_lebih_720 = 0;
                        $subtotal = 0;

                        $total = 0;
                    @endphp
                    @foreach ($aup as $key => $d)
                        @php
                            $cbg = @$aup[$key + 1]['kode_cabang'];
                            $total_umur_0_15 += $d['umur_0_15'];
                            $total_umur_16_31 += $d['umur_16_31'];
                            $total_umur_32_45 += $d['umur_32_45'];
                            $total_umur_46_60 += $d['umur_46_60'];
                            $total_umur_61_90 += $d['umur_61_90'];
                            $total_umur_91_180 += $d['umur_91_180'];
                            $total_umur_181_360 += $d['umur_181_360'];
                            $total_umur_361_720 += $d['umur_361_720'];
                            $total_umur_lebih_720 += $d['umur_lebih_720'];

                            $subtotal_umur_0_15 += $d['umur_0_15'];
                            $subtotal_umur_16_31 += $d['umur_16_31'];
                            $subtotal_umur_32_45 += $d['umur_32_45'];
                            $subtotal_umur_46_60 += $d['umur_46_60'];
                            $subtotal_umur_61_90 += $d['umur_61_90'];
                            $subtotal_umur_91_180 += $d['umur_91_180'];
                            $subtotal_umur_181_360 += $d['umur_181_360'];
                            $subtotal_umur_361_720 += $d['umur_361_720'];
                            $subtotal_umur_lebih_720 += $d['umur_lebih_720'];
                            $subtotal += $d['total'];

                            $total += $d['total'];
                        @endphp
                        <tr>
                            <td>{{ $d['kode_salesman'] }}</td>
                            <td>{{ textUpperCase($d['nama_salesman']) }}</td>
                            <td class="right">{{ formatAngka($d['umur_0_15']) }}</td>
                            <td class="right">{{ formatAngka($d['umur_16_31']) }}</td>
                            <td class="right">{{ formatAngka($d['umur_32_45']) }}</td>
                            <td class="right">{{ formatAngka($d['umur_46_60']) }}</td>
                            <td class="right">{{ formatAngka($d['umur_61_90']) }}</td>
                            <td class="right">{{ formatAngka($d['umur_91_180']) }}</td>
                            <td class="right">{{ formatAngka($d['umur_181_360']) }}</td>
                            <td class="right">{{ formatAngka($d['umur_361_720']) }}</td>
                            <td class="right">{{ formatAngka($d['umur_lebih_720']) }}</td>
                            <td class="right">{{ formatAngka($d['total']) }}</td>



                        </tr>
                        @if ($d['kode_cabang'] != $cbg)
                            <tr>
                                <th colspan="2">TOTAL</th>
                                <th class="right">{{ formatAngka($subtotal_umur_0_15) }}</th>
                                <th class="right">{{ formatAngka($subtotal_umur_16_31) }}</th>
                                <th class="right">{{ formatAngka($subtotal_umur_32_45) }}</th>
                                <th class="right">{{ formatAngka($subtotal_umur_46_60) }}</th>
                                <th class="right">{{ formatAngka($subtotal_umur_61_90) }}</th>
                                <th class="right">{{ formatAngka($subtotal_umur_91_180) }}</th>
                                <th class="right">{{ formatAngka($subtotal_umur_181_360) }}</th>
                                <th class="right">{{ formatAngka($subtotal_umur_361_720) }}</th>
                                <th class="right">{{ formatAngka($subtotal_umur_lebih_720) }}</th>
                                <th class="right">{{ formatAngka($subtotal) }}</th>
                            </tr>
                            @php
                                $subtotal_umur_0_15 = 0;
                                $subtotal_umur_16_31 = 0;
                                $subtotal_umur_32_45 = 0;
                                $subtotal_umur_46_60 = 0;
                                $subtotal_umur_61_90 = 0;
                                $subtotal_umur_91_180 = 0;
                                $subtotal_umur_181_360 = 0;
                                $subtotal_umur_361_720 = 0;
                                $subtotal_umur_lebih_720 = 0;
                                $subtotal = 0;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">TOTAL</th>
                        <th class="right">{{ formatAngka($total_umur_0_15) }}</th>
                        <th class="right">{{ formatAngka($total_umur_16_31) }}</th>
                        <th class="right">{{ formatAngka($total_umur_32_45) }}</th>
                        <th class="right">{{ formatAngka($total_umur_46_60) }}</th>
                        <th class="right">{{ formatAngka($total_umur_61_90) }}</th>
                        <th class="right">{{ formatAngka($total_umur_91_180) }}</th>
                        <th class="right">{{ formatAngka($total_umur_181_360) }}</th>
                        <th class="right">{{ formatAngka($total_umur_361_720) }}</th>
                        <th class="right">{{ formatAngka($total_umur_lebih_720) }}</th>
                        <th class="right">{{ formatAngka($total) }}</th>

                    </tr>
                    <tr>
                        <th colspan="2">PERSENTASE</th>
                        <th class="right">{{ formatAngka(($total_umur_0_15 / $total) * 100) }}%</th>
                        <th class="right">{{ formatAngka(($total_umur_16_31 / $total) * 100) }}%</th>
                        <th class="right">{{ formatAngka(($total_umur_32_45 / $total) * 100) }}%</th>
                        <th class="right">{{ formatAngka(($total_umur_46_60 / $total) * 100) }}%</th>
                        <th class="right">{{ formatAngka(($total_umur_61_90 / $total) * 100) }}%</th>
                        <th class="right">{{ formatAngka(($total_umur_91_180 / $total) * 100) }}%</th>
                        <th class="right">{{ formatAngka(($total_umur_181_360 / $total) * 100) }}%</th>
                        <th class="right">{{ formatAngka(($total_umur_361_720 / $total) * 100) }}%</th>
                        <th class="right">{{ formatAngka(($total_umur_lebih_720 / $total) * 100) }}%</th>
                        <th class="right">{{ formatAngka(($total / $total) * 100) }}%</th>
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

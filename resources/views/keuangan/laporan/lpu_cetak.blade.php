<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Penerimaan Uang (LPU) {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">

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
            LAPORAN PENERIMAAN UANG <br>
        </h4>
        <h4>PERIODE : {{ $namabulan[$bulan] }} {{ $tahun }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th colspan="{{ count($salesman) + 2 }}">PENERIMAAN LHP</th>
                        <th style="width:3%; background-color:white; border:none !important"></th>
                        <th colspan="{{ count($salesman) + 2 }}" class="bg-terimauang">PENERIMAAN UANG</th>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        @foreach ($salesman as $d)
                            @php
                                $nama_salesman = explode(' ', $d->nama_salesman);
                                //var_dump($nama_salesman);
                                $nama_depan = $d->nama_salesman != 'NON SALES' ? $nama_salesman[0] : $d->nama_salesman;
                            @endphp
                            <th>{{ $nama_depan }}</th>
                        @endforeach
                        <th>TOTAL</th>
                        <th style="width:3%; background-color:white; border:none !important"></th>
                        <!-- Setoran Kas Besar -->
                        <th class="bg-terimauang">Tanggal</th>
                        @foreach ($salesman as $d)
                            @php
                                $nama_salesman = explode(' ', $d->nama_salesman);
                                //var_dump($nama_salesman);
                                $nama_depan = $d->nama_salesman != 'NON SALES' ? $nama_salesman[0] : $d->nama_salesman;
                            @endphp
                            <th class="bg-terimauang">{{ $nama_depan }}</th>
                        @endforeach
                        <th class="bg-terimauang">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Belum Setor Bulan Lalu-->
                    <tr>
                        <!-- LHP-->
                        <td style="text-transform: uppercase; font-weight:bold"> {{ $namabulan[$lastbulan] }} {{ $lasttahun }}</td>
                        @php
                            $grandtotal_belumsetorbulanlalu = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_belumsetorbulanlalu += $belumsetorbulanlalu->{"belumsetor_$d->kode_salesman"};
                            @endphp
                            <td class="right">{{ formatAngka($belumsetorbulanlalu->{"belumsetor_$d->kode_salesman"}) }}</td>
                        @endforeach
                        <td class="right">{{ formatAngka($grandtotal_belumsetorbulanlalu) }}</td>
                        <td style="width:3%; background-color:white; border:none !important"></td>
                        <!-- Setoran Kas Besar -->
                        <td style="text-transform: uppercase; font-weight:bold">{{ $namabulan[$lastbulan] }} {{ $lasttahun }}</td>
                        @php
                            $grandtotal_belumsetorbulanlalu = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_belumsetorbulanlalu += $belumsetorbulanlalu->{"belumsetor_$d->kode_salesman"};
                            @endphp
                            <td class="right">{{ formatAngka($belumsetorbulanlalu->{"belumsetor_$d->kode_salesman"}) }}</td>
                        @endforeach
                        <td class="right">{{ formatAngka($grandtotal_belumsetorbulanlalu) }}</td>
                        <td style="width:3%; background-color:white; border:none !important"></td>
                    </tr>

                    @foreach ($salesman as $d)
                        @php
                            $total_["lhp_$d->kode_salesman"] = 0;
                            $total_["setoran_$d->kode_salesman"] = 0;
                        @endphp
                    @endforeach
                    @foreach ($lpu as $d)
                        <tr>
                            <!-- LHP-->
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            @php
                                $total_lhp_pertanggal = 0;
                            @endphp
                            @foreach ($salesman as $s)
                                @php
                                    $total_lhp_pertanggal += $d->{"lhp_$s->kode_salesman"};
                                    $total_["lhp_$s->kode_salesman"] += $d->{"lhp_$s->kode_salesman"};
                                @endphp
                                <td class="right">{{ formatAngka($d->{"lhp_$s->kode_salesman"}) }}</td>
                            @endforeach
                            <td class="right">{{ formatAngka($total_lhp_pertanggal) }}</td>

                            <td style="width:3%; background-color:white; border:none !important"></td>
                            <!-- Setoran Kas Besar -->
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            @php
                                $total_setoran_pertanggal = 0;
                            @endphp
                            @foreach ($salesman as $s)
                                @php
                                    $total_setoran_pertanggal += $d->{"setoran_$s->kode_salesman"};
                                    $total_["setoran_$s->kode_salesman"] += $d->{"setoran_$s->kode_salesman"};
                                @endphp
                                <td class="right">{{ formatAngka($d->{"setoran_$s->kode_salesman"}) }}</td>
                            @endforeach
                            <td class="right">{{ formatAngka($total_setoran_pertanggal) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <!-- LHP-->
                        <th style="text-align: left">TOTAL</th>
                        @php
                            $grandtotal_lhp = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_lhp += $total_["lhp_$d->kode_salesman"];
                            @endphp
                            <th class="right">{{ formatAngka($total_["lhp_$d->kode_salesman"]) }}</td>
                        @endforeach
                        <th class="right">{{ formatAngka($grandtotal_lhp) }}</td>

                        <th style="width:3%; background-color:white; border:none !important"></th>

                        <!--Setoran Kas Besar-->
                        <th style="text-align: left" class="bg-terimauang">TOTAL</th>
                        @php
                            $grandtotal_setoran = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_setoran += $total_["setoran_$d->kode_salesman"];
                            @endphp
                            <th class="right bg-terimauang">{{ formatAngka($total_["setoran_$d->kode_salesman"]) }}</td>
                        @endforeach
                        <th class="right bg-terimauang">{{ formatAngka($grandtotal_setoran) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left">GM {{ $namabulan[$lastbulan] }} {{ $lasttahun }}</th>
                        @php
                            $grandtotal_girobulanlalu = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_girobulanlalu += $girobulanlalu->{"giro_$d->kode_salesman"};
                            @endphp
                            <th class="right">{{ formatAngka($girobulanlalu->{"giro_$d->kode_salesman"}) }}</td>
                        @endforeach
                        <th class="right">{{ formatAngka($grandtotal_girobulanlalu) }}</td>

                        <td style="width:3%; background-color:white; border:none !important"></td>
                        <!--Setoran Kas Besar-->
                        <th style="text-align: left" class="bg-terimauang">GM {{ $namabulan[$lastbulan] }} {{ $lasttahun }}</th>
                        @php
                            $grandtotal_girobulanlalu = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_girobulanlalu += $girobulanlalu->{"giro_$d->kode_salesman"};
                            @endphp
                            <th class="right bg-terimauang">{{ formatAngka($girobulanlalu->{"giro_$d->kode_salesman"}) }}</td>
                        @endforeach
                        <th class="right bg-terimauang">{{ formatAngka($grandtotal_girobulanlalu) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left">GMD {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                        @php
                            $grandtotal_girobulanini = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_girobulanini += $girobulanini->{"giro_$d->kode_salesman"};
                            @endphp
                            <th class="right">{{ formatAngka($girobulanini->{"giro_$d->kode_salesman"}) }}</td>
                        @endforeach
                        <th class="right">{{ formatAngka($grandtotal_girobulanini) }}</td>

                        <td style="width:3%; background-color:white; border:none !important"></td>
                        <!--Setoran Kas Besar-->
                        <th style="text-align: left" class="bg-terimauang">GMD {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                        @php
                            $grandtotal_girobulanini = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_girobulanini += $girobulanini->{"giro_$d->kode_salesman"};
                            @endphp
                            <th class="right bg-terimauang">{{ formatAngka($girobulanini->{"giro_$d->kode_salesman"}) }}</td>
                        @endforeach
                        <th class="right bg-terimauang">{{ formatAngka($grandtotal_girobulanini) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left">BELUM SETOR</th>
                        @php
                            $grandtotal_belumsetorbulanini = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_belumsetorbulanini += $belumsetorbulanini->{"belumsetor_$d->kode_salesman"};
                            @endphp
                            <th class="right">{{ formatAngka($belumsetorbulanini->{"belumsetor_$d->kode_salesman"}) }}</td>
                        @endforeach
                        <th class="right">{{ formatAngka($grandtotal_belumsetorbulanini) }}</td>

                        <td style="width:3%; background-color:white; border:none !important"></td>

                        <!--Setoran Kas Besar-->

                        <th style="text-align: left" class="bg-terimauang">BELUM SETOR</th>
                        @php
                            $grandtotal_belumsetorbulanini = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_belumsetorbulanini += $belumsetorbulanini->{"belumsetor_$d->kode_salesman"};
                            @endphp
                            <th class="right bg-terimauang">{{ formatAngka($belumsetorbulanini->{"belumsetor_$d->kode_salesman"}) }}</td>
                        @endforeach
                        <th class="right bg-terimauang">{{ formatAngka($grandtotal_belumsetorbulanini) }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left">GRAND TOTAL</th>
                        @php
                            $grandtotalall_lhp = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_[$d->kode_salesman] =
                                    $total_["lhp_$d->kode_salesman"] +
                                    $belumsetorbulanlalu->{"belumsetor_$d->kode_salesman"} +
                                    $girobulanlalu->{"giro_$d->kode_salesman"} -
                                    $girobulanini->{"giro_$d->kode_salesman"} -
                                    $belumsetorbulanini->{"belumsetor_$d->kode_salesman"};

                                $grandtotalall_lhp += $grandtotal_[$d->kode_salesman];
                            @endphp
                            <th class="right">{{ formatAngka($grandtotal_[$d->kode_salesman]) }}</th>
                        @endforeach
                        <th class="right">{{ formatAngka($grandtotalall_lhp) }}</th>

                        <td style="width:3%; background-color:white; border:none !important"></td>
                        <th style="text-align: left" class="bg-terimauang">GRAND TOTAL</th>
                        @php
                            $grandtotalall_setoran = 0;
                        @endphp
                        @foreach ($salesman as $d)
                            @php
                                $grandtotal_[$d->kode_salesman] =
                                    $total_["setoran_$d->kode_salesman"] +
                                    $belumsetorbulanlalu->{"belumsetor_$d->kode_salesman"} +
                                    $girobulanlalu->{"giro_$d->kode_salesman"} -
                                    $girobulanini->{"giro_$d->kode_salesman"} -
                                    $belumsetorbulanini->{"belumsetor_$d->kode_salesman"};

                                $grandtotalall_setoran += $grandtotal_[$d->kode_salesman];
                            @endphp
                            <th class="right bg-terimauang">{{ formatAngka($grandtotal_[$d->kode_salesman]) }}</th>
                        @endforeach
                        <th class="right bg-terimauang">{{ formatAngka($grandtotalall_setoran) }}</th>
                    </tr>
                </tfoot>
            </table>
            <table style="margin-top: 35px" class="datatable3">
                <tr>
                    <td style="font-weight:bold; background-color:yellow">PENERIMAAN LHP</td>
                    <td style="text-align:right; font-weight:bold;">{{ formatAngka($grandtotalall_lhp) }}</td>
                </tr>
                <tr>
                    <td style="font-weight:bold; background-color:yellow">SETORAN SALES</td>
                    <td style="text-align:right; font-weight:bold;">{{ formatAngka($grandtotalall_setoran) }}</td>
                </tr>
                <tr>
                    <td style="font-weight:bold; background-color:yellow">SELISIH</td>
                    <td style="text-align:right; font-weight:bold;"><?php echo formatAngka($grandtotalall_lhp - $grandtotalall_setoran); ?></td>
                </tr>
            </table>

        </div>
</body>

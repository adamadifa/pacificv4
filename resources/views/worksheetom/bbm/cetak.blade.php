<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan BBM {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">

</head>

<body>

    <div class="header">
        <h4 class="title">
            LAPORAN KONTROL BBM KENDARAAN
        </h4>
        <h4>
            PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}
        </h4>
        @if (!empty($kendaraan))
            <h4>
                KENDARAAN : {{ $kendaraan->no_polisi }} - {{ $kendaraan->tipe_kendaraan }}
            </h4>
        @endif
        @if (!empty($driver))
            <h4>
                DRIVER : {{ $driver->nama_driver_helper }}
            </h4>
        @endif
    </div>


    <div class="content">

        @if ($jenis_laporan == 'detail')

            {{-- ===================== --}}
            {{-- LAPORAN DETAIL --}}
            {{-- ===================== --}}

            <table class="datatable3">

                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">TANGGAL</th>
                        <th rowspan="2">DRIVER</th>
                        <th rowspan="2">TUJUAN KETIKA ISI BBM</th>

                        <th colspan="2">POSISI KILOMETER</th>
                        <th rowspan="2">JARAK TEMPUH (KM)</th>

                        <th colspan="2">JUMLAH</th>
                        <th rowspan="2">RATIO</th>
                        <th rowspan="2">KETERANGAN</th>
                    </tr>

                    <tr>
                        <th>KM AWAL</th>
                        <th>KM AKHIR</th>
                        <th>LITER</th>
                        <th>RUPIAH</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                        $total_jarak = 0;
                        $total_liter = 0;
                    @endphp

                    @foreach ($bbm as $d)
                        @php
                            $jarak = $d->kilometer_akhir - $d->kilometer_awal;
                            $rasio = $d->jumlah_liter > 0 ? $jarak / $d->jumlah_liter : 0;

                            $total_jarak += $jarak;
                            $total_liter += $d->jumlah_liter;
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ textUpperCase($d->nama_driver_helper) }}</td>
                            <td>{{ textUpperCase($d->tujuan) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->kilometer_awal) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->kilometer_akhir) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jarak) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jumlah_liter) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jumlah_harga) }}</td>
                            <td class="right">{{ formatAngkaDesimal($rasio) }}</td>
                            <td>{{ textCamelCase($d->keterangan) }}</td>
                        </tr>
                    @endforeach

                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="6" bgcolor="#024a75">TOTAL</th>
                        <th class="right">{{ formatAngkaDesimal($total_jarak) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_liter) }}</th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>

            </table>
        @else
            {{-- ===================== --}}
            {{-- LAPORAN REKAP --}}
            {{-- ===================== --}}

            <table class="datatable3">

                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">KENDARAAN</th>
                        <th colspan="2">POSISI KILOMETER</th>
                        <th rowspan="2">JARAK TEMPUH (KM)</th>
                        <th colspan="2">JUMLAH</th>
                        <th rowspan="2">RATIO</th>
                    </tr>

                    <tr>
                        <th>KM AWAL</th>
                        <th>KM AKHIR</th>
                        <th>LITER</th>
                        <th>RUPIAH</th>
                    </tr>
                </thead>


                <tbody>

                    @php
                        $total_jarak = 0;
                        $total_liter = 0;
                        $total_rupiah = 0;
                    @endphp

                    @foreach ($bbm as $d)
                        @php

                            $jarak = $d->km_akhir - $d->km_awal;
                            $rasio = $d->total_liter > 0 ? $jarak / $d->total_liter : 0;

                            $total_jarak += $jarak;
                            $total_liter += $d->total_liter;
                            $total_rupiah += $d->total_rupiah;

                        @endphp

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $d->no_polisi }}</td>

                            <td class="right">{{ formatAngkaDesimal($d->km_awal) }}</td>

                            <td class="right">{{ formatAngkaDesimal($d->km_akhir) }}</td>

                            <td class="right">{{ formatAngkaDesimal($jarak) }}</td>

                            <td class="right">{{ formatAngkaDesimal($d->total_liter) }}</td>

                            <td class="right">{{ formatAngkaDesimal($d->total_rupiah) }}</td>

                            <td class="right">{{ formatAngkaDesimal($rasio) }}</td>

                        </tr>
                    @endforeach

                </tbody>


                <tfoot>

                    <tr>

                        <th colspan="4" bgcolor="#024a75">TOTAL</th>

                        <th class="right">
                            {{ formatAngkaDesimal($total_jarak) }}
                        </th>

                        <th class="right">
                            {{ formatAngkaDesimal($total_liter) }}
                        </th>

                        <th class="right">
                            {{ formatAngkaDesimal($total_rupiah) }}
                        </th>

                        <th class="right">
                            {{ $total_liter > 0 ? formatAngkaDesimal($total_jarak / $total_liter) : 0 }}
                        </th>

                    </tr>

                </tfoot>

            </table>

        @endif

    </div>


</body>

</html>

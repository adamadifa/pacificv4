<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Pertumbuhan dan Perkembangan Produk {{ date('Y-m-d H:i:s') }}</title>
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
            DATA PERTUMBUHAN DAN PENGEMBANGAN PRODUK <br>
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
            <table class="datatable3" style="width:600%">
                <thead>
                    <tr>
                        <th rowspan="4">Kode</th>
                        <th rowspan="4">Nama Salesman</th>
                        <th colspan="{{ count($produk) * 10 }}" class="red">Produk</th>
                    </tr>
                    <tr>
                        @foreach ($produk as $p)
                            <th colspan="10" class="red">{{ $p['nama_produk'] }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($produk as $p)
                            <th colspan="5" class="green">{{ textupperCase($namabulan[$bulan]) }}</th>
                            <th colspan="5">s/d {{ textupperCase($namabulan[$bulan]) }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($produk as $p)
                            <th class="green">Real {{ $lastyear }}</th>
                            <th class="green">Target</th>
                            <th class="green">Real {{ $tahun }}</th>
                            <th class="green">Ach(%)</th>
                            <th class="green">Grw(%)</th>

                            <th>Real {{ $lastyear }}</th>
                            <th>Target</th>
                            <th>Real {{ $tahun }}</th>
                            <th>Ach(%)</th>
                            <th>Grw(%)</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dppp as $d)
                        <tr>
                            <td>{{ $d['kode_salesman'] }}</td>
                            <td>{{ textUpperCase($d['nama_salesman']) }}</td>

                            @foreach ($produk as $p)
                                @php
                                    $realisasi_lastyear = $d['realisasi_lastyear_' . $p['kode_produk']] / $p['isi_pcs_dus'];
                                    $realisasi = $d['realisasi_' . $p['kode_produk']] / $p['isi_pcs_dus'];

                                    $realisasi_lastyear_sampaidengan = $d['realisasi_lastyear_sampaidengan_' . $p['kode_produk']] / $p['isi_pcs_dus'];
                                    $realisasi_sampaidengan = $d['realisasi_sampaidengan_' . $p['kode_produk']] / $p['isi_pcs_dus'];

                                    $target = $d['target_' . $p['kode_produk']];
                                    $target_sampaidengan = $d['target_sampaidengan_' . $p['kode_produk']];

                                    $grw = !empty($realisasi_lastyear) ? (($realisasi - $realisasi_lastyear) / $realisasi_lastyear) * 100 : 0;
                                    $ach = !empty($target) ? ($realisasi / $target) * 100 : 0;
                                    $ach_sampaidengan = !empty($target_sampaidengan) ? ($realisasi_sampaidengan / $target_sampaidengan) * 100 : 0;
                                    $grw_sampaidengan = !empty($realisasi_lastyear_sampaidengan)
                                        ? (($realisasi_sampaidengan - $realisasi_lastyear_sampaidengan) / $realisasi_lastyear_sampaidengan) * 100
                                        : 0;

                                    $colorach = $ach < 100 ? 'red' : '';
                                    $colorach_sampaidengan = $ach_sampaidengan < 100 ? 'red' : '';
                                    $colorgrw = $grw < 0 ? 'red' : '';
                                    $colorgrw_sampaidengan = $grw_sampaidengan < 0 ? 'red' : '';

                                @endphp
                                <td class="center">{{ formatAngkaDesimal($realisasi_lastyear) }}</td>
                                <td class="center">{{ formatAngkaDesimal($target) }}</td>
                                <td class="center">{{ formatAngkaDesimal($realisasi) }}</td>
                                <td class="center" style="color:{{ $colorach }}"> {{ formatAngkaDesimal($ach) }}</td>
                                <td class="center" style="color:{{ $colorgrw }}">{{ formatAngkaDesimal($grw) }}</td>
                                <td class="center">{{ formatAngkaDesimal($realisasi_lastyear_sampaidengan) }}</td>
                                <td class="center">{{ formatAngkaDesimal($target_sampaidengan) }}</td>
                                <td class="center">{{ formatAngkaDesimal($realisasi_sampaidengan) }}</td>
                                <td class="center" style="color:{{ $colorach_sampaidengan }}"> {{ formatAngkaDesimal($ach_sampaidengan) }}</td>
                                <td class="center" style="color:{{ $colorgrw_sampaidengan }}">{{ formatAngkaDesimal($grw_sampaidengan) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
<script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 2,
        'shadow': true,
    });
</script>

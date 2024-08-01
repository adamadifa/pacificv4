<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Bad Stok {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP BAD STOK <br>
        </h4>
        <h4> TAHUN {{ $tahun }}</h4>
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2">Kode</th>
                    <th rowspan="2">Nama Produk</th>
                    <th colspan="{{ count($rangeBulan) }}">Bulan {{ $tahun }}</th>
                    <th rowspan="2">TOTAL</th>
                </tr>
                <tr>
                    @foreach ($rangeBulan as $month)
                        <th>{{ $month }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($rekapbadstok as $d)
                    <tr>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        @php
                            $i = 1;
                            $totalperproduk = 0;
                        @endphp
                        @foreach ($rangeBulan as $month)
                            @php
                                $totalperproduk += $d->{"bulan_$i"};
                            @endphp
                            <td class="right">{{ !empty($d->{"bulan_$i"}) ? formatAngka($d->{"bulan_$i"}) : '' }}</td>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                        <td class="right">{{ formatAngka($totalperproduk) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KARTU KASBON{{ date('Y-m-d H:i:s') }}</title>
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
            KARTU KASBON <br>
        </h4>
        <h4>PERIODE : {{ $namabulan[$bulan] }} {{ $tahun }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($departemen != null)
            <h4>
                {{ textUpperCase($departemen->nama_dept) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">NIK</th>
                        <th rowspan="2">NAMA KARYAWAN</th>
                        <th rowspan="2">SALDO AWAL</th>
                        <th colspan="2">PENAMBAHAN</th>
                        <th colspan="2">PEMBAYARAN</th>
                        <th rowspan="2">SALDO AKHIR</th>
                    </tr>
                    <tr>
                        <th>kasbon</th>
                        <th>LAIN LAIN</th>
                        <th>CICILAN</th>
                        <th>LAIN LAIN</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalsaldoawal = 0;
                        $totalpenambahan = 0;
                        $totalpembayaran = 0;
                        $totalsaldoakhir = 0;
                    @endphp

                    @foreach ($kasbon as $d)
                        @php
                            $jumlah_kasbonlast = $d->jumlah_kasbonlast;
                            $jumlah_pelunasanlast = $d->total_pelunasanlast;
                            $jumlah_pembayaranlast = $d->total_pembayaranlast;

                            $jumlah_kasbonnow = $d->jumlah_kasbonnow;
                            $jumlah_pembayarannow = $d->total_pembayarannow;
                            $jumlah_pelunasannow = $d->total_pelunasannow;

                            $saldoawal = $jumlah_kasbonlast - $jumlah_pembayaranlast - $jumlah_pelunasanlast;

                            $totalpembayarannow = $jumlah_pembayarannow + $jumlah_pelunasannow;
                            $saldoakhir = $saldoawal + $jumlah_kasbonnow - $totalpembayarannow;

                            $totalsaldoawal += $saldoawal;
                            $totalsaldoakhir += $saldoakhir;
                            $totalpembayaran += $totalpembayarannow;
                            $totalpenambahan += $jumlah_kasbonnow;

                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ "'" . $d->nik }}</td>
                            <td>{{ $d->nama_karyawan }}</td>
                            <td style="text-align: right">{{ !empty($saldoawal) ? formatAngka($saldoawal) : '' }}</td>
                            <td style="text-align: right">{{ !empty($jumlah_kasbonnow) ? formatAngka($jumlah_kasbonnow) : '' }}</td>
                            <td></td>
                            <td style="text-align: right">{{ !empty($totalpembayarannow) ? formatAngka($totalpembayarannow) : '' }}</td>
                            <td></td>
                            <td style="text-align: right">{{ !empty($saldoakhir) ? formatAngka($saldoakhir) : '' }}</td>
                        </tr>
                    @endforeach
                    <tr bgcolor=" #024a75" style=" color:white; font-size:12;">
                        <th colspan="3">TOTAL</th>
                        <th style="text-align: right">{{ formatAngka($totalsaldoawal) }}</th>
                        <th style="text-align: right">{{ formatAngka($totalpenambahan) }}</th>
                        <th></th>
                        <th style="text-align: right">{{ formatAngka($totalpembayaran) }}</th>
                        <th></th>
                        <th style="text-align: right">{{ formatAngka($totalsaldoakhir) }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

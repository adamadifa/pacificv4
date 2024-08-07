<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>KARTU PJP{{ date('Y-m-d H:i:s') }}</title>
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
            KARTU PJP <br>
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
                        <th rowspan="3">NO</th>
                        <th rowspan="3">NIK</th>
                        <th rowspan="3">NAMA KARYAWAN</th>
                        <th rowspan="3">SALDO AWAL</th>
                        <th colspan="2">PENAMBAHAN</th>
                        <th colspan="3">PEMBAYARAN</th>
                        <th rowspan="3">SALDO AKHIR</th>
                    </tr>
                    <tr>
                        <th rowspan="2">PINJAMAN</th>
                        <th rowspan="2">LAIN LAIN</th>
                        <th colspan="2">CICILAN</th>
                        <th rowspan="2">LAIN LAIN</th>
                    </tr>
                    <tr>
                        <th>GAJI</th>
                        <th>CASH</th>
                    </tr>
                </thead>
                <tbody>

                    @php
                        $totalsaldoawal = 0;
                        $totalpenambahan = 0;
                        $totalpembayaran = 0;
                        $totalsaldoakhir = 0;
                        $totalpmbnow = 0;
                        $totalplnow = 0;
                        $no = 1;
                    @endphp

                    @foreach ($pjp as $d)
                        @php
                            $jumlah_pinjamanlast = $d->jumlah_pinjamanlast;
                            $jumlah_pelunasanlast = $d->total_pelunasanlast;
                            $jumlah_pembayaranlast = $d->total_pembayaranlast;

                            $jumlah_pinjamannow = $d->jumlah_pinjamannow;
                            $jumlah_pembayarannow = $d->total_pembayarannow;
                            $jumlah_pelunasannow = $d->total_pelunasannow;

                            $saldoawal = $jumlah_pinjamanlast - $jumlah_pembayaranlast - $jumlah_pelunasanlast;

                            $totalpembayarannow = $jumlah_pembayarannow + $jumlah_pelunasannow;
                            $totalpmbnow += $jumlah_pembayarannow;
                            $totalplnow += $jumlah_pelunasannow;
                            $saldoakhir = $saldoawal + $jumlah_pinjamannow - $totalpembayarannow;

                            $totalsaldoawal += $saldoawal;
                            $totalsaldoakhir += $saldoakhir;
                            $totalpembayaran += $totalpembayarannow;
                            $totalpenambahan += $jumlah_pinjamannow;

                        @endphp
                        @if (!empty($saldoawal) || !empty($jumlah_pinjamannow))
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ "'" . $d->nik }}</td>
                                <td>{{ $d->nama_karyawan }}</td>
                                <td style="text-align: right">{{ !empty($saldoawal) ? formatAngka($saldoawal) : '' }}</td>
                                <td style="text-align: right">{{ !empty($jumlah_pinjamannow) ? formatAngka($jumlah_pinjamannow) : '' }}</td>
                                <td></td>
                                <td style="text-align: right">{{ !empty($jumlah_pembayarannow) ? formatAngka($jumlah_pembayarannow) : '' }}</td>
                                <td style="text-align: right">{{ !empty($jumlah_pelunasannow) ? formatAngka($jumlah_pelunasannow) : '' }}</td>
                                <td></td>
                                <td style="text-align: right">{{ !empty($saldoakhir) ? formatAngka($saldoakhir) : '' }}</td>
                            </tr>
                            @php
                                $no++;
                            @endphp
                        @endif
                    @endforeach
                    <tr bgcolor=" #024a75" style=" color:white; font-size:12;">
                        <th colspan="3">TOTAL</th>
                        <th style="text-align: right">{{ formatAngka($totalsaldoawal) }}</th>
                        <th style="text-align: right">{{ formatAngka($totalpenambahan) }}</th>
                        <th></th>
                        <th style="text-align: right">{{ formatAngka($totalpmbnow) }}</th>
                        <th style="text-align: right">{{ formatAngka($totalplnow) }}</th>
                        <th></th>
                        <th style="text-align: right">{{ formatAngka($totalsaldoakhir) }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

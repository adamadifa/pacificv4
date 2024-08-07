<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REKAP KARTU PIUTANG {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP KARTU PIUTANG <br>
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
                <tr>
                    <th rowspan="2">NO</th>
                    <th rowspan="2">NIK</th>
                    <th rowspan="2">NAMA KARYAWAN</th>
                    <th rowspan="2">SALDOAWAL</th>
                    <th colspan="3">PENAMBAHAN</th>
                    <th colspan="5">PEMBAYARAN</th>
                    <th rowspan="2">SALDO AKHIR</th>
                </tr>
                <tr>
                    <th>PJP</th>
                    <th>KASBON</th>
                    <th>PIUTANG</th>
                    <th>POT. UPAH</th>
                    <th>CASH</th>
                    <th>POT. KOMISI</th>
                    <th>TITIPAN PELANGGAN</th>
                    <th>LAINNYA</th>
                </tr>

                <tbody>
                    @php
                        //Pinjaman
                        $pjp_totalsaldoawal = 0;
                        $pjp_totalpenambahan = 0;
                        $pjp_totalpembayaran = 0;
                        $pjp_totalsaldoakhir = 0;
                        $pjp_totalpmbnow = 0;
                        $pjp_totalplnow = 0;

                        //Kasbon
                        $kasbon_totalsaldoawal = 0;
                        $kasbon_totalpenambahan = 0;
                        $kasbon_totalpembayaran = 0;
                        $kasbon_totalsaldoakhir = 0;
                        $kasbon_totalpmbnow = 0;
                        $kasbon_totalplnow = 0;

                        $piutang_totalsaldoawal = 0;
                        $piutang_totalpenambahan = 0;
                        $piutang_totalpembayaran = 0;
                        $piutang_totalsaldoakhir = 0;
                        $piutang_totalpmbnow = 0;
                        $piutang_totalpotongkomisi = 0;
                        $piutang_totaltitipan = 0;
                        $piutang_totallainnya = 0;
                        $piutang_totalplnow = 0;

                        $total_all_saldoawal = 0;
                        $total_all_upah = 0;
                        $total_all_cash = 0;
                        $total_all_saldoakhir = 0;
                    @endphp
                    @foreach ($piutangkaryawan as $d)
                        @php
                            $pjp_jumlah_pinjamanlast = $d->pjp_jumlah_pinjamanlast;
                            $pjp_jumlah_pelunasanlast = $d->pjp_total_pelunasanlast;
                            $pjp_jumlah_pembayaranlast = $d->pjp_total_pembayaranlast;

                            $pjp_jumlah_pinjamannow = $d->pjp_jumlah_pinjamannow;
                            $pjp_jumlah_pembayarannow = $d->pjp_total_pembayarannow;
                            $pjp_jumlah_pelunasannow = $d->pjp_total_pelunasannow;

                            $pjp_saldoawal = $pjp_jumlah_pinjamanlast - $pjp_jumlah_pembayaranlast - $pjp_jumlah_pelunasanlast;

                            $pjp_totalpembayarannow = $pjp_jumlah_pembayarannow + $pjp_jumlah_pelunasannow;
                            $pjp_totalpmbnow += $pjp_jumlah_pembayarannow;
                            $pjp_totalplnow += $pjp_jumlah_pelunasannow;
                            $pjp_saldoakhir = $pjp_saldoawal + $pjp_jumlah_pinjamannow - $pjp_totalpembayarannow;

                            $pjp_totalsaldoawal += $pjp_saldoawal;
                            $pjp_totalsaldoakhir += $pjp_saldoakhir;
                            $pjp_totalpembayaran += $pjp_totalpembayarannow;
                            $pjp_totalpenambahan += $pjp_jumlah_pinjamannow;

                            //KASBON

                            $kasbon_jumlah_kasbonlast = $d->kasbon_jumlah_kasbonlast;
                            $kasbon_jumlah_pelunasanlast = $d->kasbon_total_pelunasanlast;
                            $kasbon_jumlah_pembayaranlast = $d->kasbon_total_pembayaranlast;

                            $kasbon_jumlah_kasbonnow = $d->kasbon_jumlah_kasbonnow;
                            $kasbon_jumlah_pembayarannow = $d->kasbon_total_pembayarannow;
                            $kasbon_jumlah_pelunasannow = $d->kasbon_total_pelunasannow;

                            $kasbon_saldoawal = $kasbon_jumlah_kasbonlast - $kasbon_jumlah_pembayaranlast - $kasbon_jumlah_pelunasanlast;

                            $kasbon_totalpembayarannow = $kasbon_jumlah_pembayarannow + $kasbon_jumlah_pelunasannow;
                            $kasbon_totalpmbnow += $kasbon_jumlah_pembayarannow;
                            $kasbon_totalplnow += $kasbon_jumlah_pelunasannow;
                            $kasbon_saldoakhir = $kasbon_saldoawal + $kasbon_jumlah_kasbonnow - $kasbon_totalpembayarannow;

                            $kasbon_totalsaldoawal += $kasbon_saldoawal;
                            $kasbon_totalsaldoakhir += $kasbon_saldoakhir;
                            $kasbon_totalpembayaran += $kasbon_totalpembayarannow;
                            $kasbon_totalpenambahan += $kasbon_jumlah_kasbonnow;

                            //PIUTANG
                            $piutang_jumlah_pinjamanlast = $d->piutang_jumlah_pinjamanlast;
                            $piutang_jumlah_pelunasanlast = $d->piutang_total_pelunasanlast;
                            $piutang_jumlah_pembayaranlast = $d->piutang_total_pembayaranlast;

                            $piutang_jumlah_pinjamannow = $d->piutang_jumlah_pinjamannow;
                            $piutang_jumlah_pembayarannow =
                                $d->piutang_total_pembayarannow + $d->piutang_total_pembayaranpotongkomisi + $d->piutang_total_pembayarantitipan;
                            $piutang_jumlah_pembayaranpotongkomisi = $d->piutang_total_pembayaranpotongkomisi;
                            $piutang_jumlah_pembayarantitipan = $d->piutang_total_pembayarantitipan;
                            $piutang_jumlah_pembayaranlainnya = $d->piutang_total_pembayaranlainnya;
                            $piutang_jumlah_pelunasannow = $d->piutang_total_pelunasannow;

                            $piutang_saldoawal = $piutang_jumlah_pinjamanlast - $piutang_jumlah_pembayaranlast - $piutang_jumlah_pelunasanlast;

                            $piutang_totalpembayarannow = $piutang_jumlah_pembayarannow + $piutang_jumlah_pelunasannow;
                            $piutang_totalpmbnow += $piutang_jumlah_pembayarannow;
                            $piutang_totalplnow += $piutang_jumlah_pelunasannow;

                            $piutang_totalpotongkomisi += $piutang_jumlah_pembayaranpotongkomisi;
                            $piutang_totaltitipan += $piutang_jumlah_pembayarantitipan;
                            $piutang_totallainnya += $piutang_jumlah_pembayaranlainnya;

                            $piutang_saldoakhir = $piutang_saldoawal + $piutang_jumlah_pinjamannow - $piutang_totalpembayarannow;

                            $piutang_totalsaldoawal += $piutang_saldoawal;
                            $piutang_totalsaldoakhir += $piutang_saldoakhir;
                            $piutang_totalpembayaran += $piutang_totalpembayarannow;
                            $piutang_totalpenambahan += $piutang_jumlah_pinjamannow;

                            $all_saldoawal = $pjp_saldoawal + $kasbon_saldoawal + $piutang_saldoawal;
                            // $all_saldoawal = $piutang_saldoawal;;
                            $upah_all = $pjp_jumlah_pembayarannow + $kasbon_jumlah_pembayarannow + $d->piutang_total_pembayarannow;
                            $cash_all = $pjp_jumlah_pelunasannow + $kasbon_jumlah_pelunasannow;
                            $all_saldoakhir =
                                $all_saldoawal +
                                $pjp_jumlah_pinjamannow +
                                $kasbon_jumlah_kasbonnow +
                                $piutang_jumlah_pinjamannow -
                                $upah_all -
                                $cash_all -
                                $piutang_jumlah_pembayaranpotongkomisi -
                                $piutang_jumlah_pembayarantitipan -
                                $piutang_jumlah_pembayaranlainnya;

                            $total_all_saldoawal += $all_saldoawal;
                            $total_all_upah += $upah_all;
                            $total_all_cash += $cash_all;
                            $total_all_saldoakhir += $all_saldoakhir;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>'{{ $d->nik }}</td>
                            <td>{{ $d->nama_karyawan }}</td>
                            <td align="right">
                                {{ !empty($all_saldoawal) ? formatAngka($all_saldoawal) : '' }}
                            </td>
                            <td style="text-align: right">{{ !empty($pjp_jumlah_pinjamannow) ? formatAngka($pjp_jumlah_pinjamannow) : '' }}</td>
                            <td style="text-align: right">{{ !empty($kasbon_jumlah_kasbonnow) ? formatAngka($kasbon_jumlah_kasbonnow) : '' }}</td>
                            <td style="text-align: right">{{ !empty($piutang_jumlah_pinjamannow) ? formatAngka($piutang_jumlah_pinjamannow) : '' }}
                            </td>
                            <td style="text-align: right">{{ !empty($upah_all) ? formatAngka($upah_all) : '' }}</td>
                            <td style="text-align: right">{{ !empty($cash_all) ? formatAngka($cash_all) : '' }}</td>
                            <td style="text-align: right">
                                {{ !empty($piutang_jumlah_pembayaranpotongkomisi) ? formatAngka($piutang_jumlah_pembayaranpotongkomisi) : '' }}</td>
                            <td style="text-align: right">
                                {{ !empty($piutang_jumlah_pembayarantitipan) ? formatAngka($piutang_jumlah_pembayarantitipan) : '' }}
                            </td>
                            <td style="text-align: right">
                                {{ !empty($piutang_jumlah_pembayaranlainnya) ? formatAngka($piutang_jumlah_pembayaranlainnya) : '' }}
                            </td>
                            <td style="text-align: right">{{ !empty($all_saldoakhir) ? formatAngka($all_saldoakhir) : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tr bgcolor=" #024a75" style=" color:white; font-size:12;">
                    <th colspan="3">TOTAL</th>
                    <th style="text-align: right">{{ formatAngka($total_all_saldoawal) }}</th>
                    <th style="text-align: right">{{ formatAngka($pjp_totalpenambahan) }}</th>
                    <th style="text-align: right">{{ formatAngka($kasbon_totalpenambahan) }}</th>
                    <th style="text-align: right">{{ formatAngka($piutang_totalpenambahan) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_upah) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_cash) }}</th>
                    <th style="text-align: right">{{ formatAngka($piutang_totalpotongkomisi) }}</th>
                    <th style="text-align: right">{{ formatAngka($piutang_totaltitipan) }}</th>
                    <th style="text-align: right">{{ formatAngka($piutang_totallainnya) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_saldoakhir) }}</th>
                </tr>
            </table>
        </div>
    </div>
</body>

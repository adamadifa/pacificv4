<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Mutasi Produksi {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN MUTASI PRODUKSI<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="body">
        <table class="datatable3">
            <thead>
                <tr>

                    <th rowspan="2">Tanggal</th>
                    <th colspan="3">BUKTI</th>
                    <th colspan="2" class="green">IN</th>
                    <th colspan="2" class="red">OUT</th>
                    <th rowspan="2" rowspan="2">SALDO AKHIR
                    </th>
                </tr>
                <tr>
                    <th>BPBJ</th>
                    <th>FSTHP</th>
                    <th>LAIN LAIN</th>
                    <th class="green">BPBJ</th>
                    <th class="green">LAINNYA</th>
                    <th class="red">GUDANG</th>
                    <th class="red">LAINNYA</th>
                </tr>
                <tr>
                    <th colspan="4">SALDO AWAL</th>
                    <th colspan="4"></th>
                    <th style="text-align: right">{{ formatAngka($saldoawal) }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $saldo_akhir = $saldoawal;
                    $total_bpbj = 0;
                    $total_fsthp = 0;
                @endphp
                @foreach ($mutasi as $d)
                    @if ($d->jenis_mutasi == 'BPBJ')
                        @php
                            $no_bpbj = $d->no_mutasi;
                            $jml_bpbj = $d->jumlah;
                        @endphp
                    @else
                        @php
                            $no_bpbj = '';
                            $jml_bpbj = 0;
                        @endphp
                    @endif

                    @if ($d->jenis_mutasi == 'FSTHP')
                        @php
                            $no_fsthp = $d->no_mutasi;
                            $jml_fsthp = $d->jumlah;
                        @endphp
                    @else
                        @php
                            $no_fsthp = '';
                            $jml_fsthp = 0;
                        @endphp
                    @endif



                    @php
                        $total_bpbj += $jml_bpbj;
                        $total_fsthp += $jml_fsthp;
                        $saldo_akhir += $jml_bpbj - $jml_fsthp;
                    @endphp
                    <tr>
                        <td>{{ DateToIndo($d->tanggal_mutasi) }}</td>
                        <td>{{ $no_bpbj }}</td>
                        <td>{{ $no_fsthp }}</td>
                        <td></td>
                        <td class="right">{{ !empty($jml_bpbj) ? formatAngka($jml_bpbj) : '' }}</td>
                        <td></td>
                        <td class="right">{{ !empty($jml_fsthp) ? formatAngka($jml_fsthp) : '' }}</td>
                        <td></td>
                        <td class="right">{{ !empty($saldo_akhir) ? formatAngka($saldo_akhir) : '' }}</td>
                    </tr>
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">TOTAL</th>
                    <th style="text-align: right">{{ formatAngka($total_bpbj) }}</th>
                    <th style="text-align: right"></th>
                    <th style="text-align: right">{{ formatAngka($total_fsthp) }}</th>
                    <th style="text-align: right"></th>
                    <th style="text-align: right">{{ formatAngka($saldo_akhir) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>

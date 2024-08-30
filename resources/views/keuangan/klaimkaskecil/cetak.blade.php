<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ledger {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style>
    <style>
        .text-red {
            background-color: red;
            color: white;
        }
    </style> --}}
</head>

<body>
    <div class="header">
        <table class="datatable3">
            <tr>
                <td>Kode</td>
                <td>:</td>
                <td>{{ $klaim->kode_klaim }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ DateToIndo($klaim->tanggal) }}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>{{ $klaim->keterangan }}</td>
            </tr>
        </table>

    </div>
    <div class="content" style="margin-top: 30px">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Bukti</th>
                        <th>Keterangan</th>
                        <th>Penerimaan</th>
                        <th>Pengeluaran</th>
                        <th>Saldo</th>
                    </tr>
                    <tr>
                        <th>Saldo Awal</th>
                        <th colspan="4"></th>
                        <th>{{ $saldoawal ? formatAngka($saldoawal->saldo_akhir) : 0 }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $saldo = $saldoawal ? $saldoawal->saldo_akhir : 0;
                        $saldo_awal = $saldoawal ? $saldoawal->saldo_akhir : 0;
                        $totalpenerimaan = 0;
                        $totalpenerimaannonpusat = 0;
                        $totalpengeluaran = 0;

                    @endphp
                    @foreach ($detail as $d)
                        @php
                            $penerimaan = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                            $pengeluaran = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                            $saldo += $penerimaan - $pengeluaran;
                            $totalpenerimaan += $penerimaan;
                            if ($d->keterangan != 'Penerimaan Kas Kecil') {
                                $totalpenerimaannonpusat += $penerimaan;
                            }
                            $totalpengeluaran += $pengeluaran;
                        @endphp
                        <tr>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ $d->keterangan }}</td>
                            <td class="right" style="color: green">{{ formatAngka($penerimaan) }}</td>
                            <td class="right" style="color: red">{{ formatAngka($pengeluaran) }}</td>
                            <td class="right" style="color: blue">{{ formatAngka($saldo) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="3">Total</td>
                        <td class="right" style="font-weight: bold; color: green">{{ formatAngka($totalpenerimaan) }}</td>
                        <td class="right" style="font-weight: bold; color: red">{{ formatAngka($totalpengeluaran) }}</td>
                        <td class="right" style="font-weight: bold; color: blue">{{ formatAngka($saldo) }}</td>
                    </tr>
                    <tr>
                        <td>Penggantian Kas Kecil</td>
                        <td class="right" style="font-weight: bold" colspan="2">
                            @php
                                $penggantian = $totalpengeluaran - $totalpenerimaannonpusat;
                            @endphp
                            {{ formatAngka($penggantian) }}
                        </td>
                        <td>Saldo Awal</td>
                        <td class="right" style="font-weight:bold" colspan="2">{{ formatAngka($saldoawal ? $saldoawal->saldo_akhir : 0) }}</td>
                    </tr>
                    <tr>
                        <td>Terbilang</td>
                        <td class="right" style="font-weight: bold" colspan="2"><i>{{ textCamelCase(terbilang($penggantian)) }}</i></td>
                        <td>Penerimaan Pusat</td>
                        <td class="right" style="font-weight: bold" colspan="2">{{ formatAngka($totalpenerimaan) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td>Total</td>
                        <td class="right" style="font-weight: bold" colspan="2">
                            @php
                                $total = $saldo_awal + $totalpenerimaan - $totalpenerimaannonpusat;
                            @endphp
                            {{ formatAngka($total) }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td>Pengeluaran Kas Kecil</td>
                        <td class="right" style="font-weight: bold" colspan="2">{{ formatAngka($totalpengeluaran) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td>Saldo Akhir</td>
                        <td class="right" style="font-weight: bold" colspan="2">{{ formatAngka($saldo) }}</td>
                    </tr>

                </tfoot>
            </table>
        </div>
    </div>
</body>

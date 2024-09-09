<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kas Kecil {{ date('Y-m-d H:i:s') }}</title>
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
        <h4 class="title">
            KAS KECIL<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ $cabang->nama_cabang }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>No. Bukti</th>
                        <th>Keterangan</th>
                        <th>Kode Akun</th>
                        <th>Akun</th>
                        <th>Penerimaan</th>
                        <th>Pengeluaran</th>
                        <th>Saldo</th>
                        <th rowspan="2">Dibuat</th>
                    </tr>
                    <tr>
                        <th colspan="8"><b>SALDO AWAL</b></th>
                        <th class="right">{{ $saldoawal != null ? formatAngka($saldoawal->saldo_awal) : 0 }}</th>
                    </tr>
                <tbody>
                    @php
                        $saldo = $saldoawal != null ? $saldoawal->saldo_awal : 0;
                        $total_penerimaan = 0;
                        $total_pengeluaran = 0;
                    @endphp
                    @foreach ($kaskecil as $d)
                        @php
                            $penerimaan = $d->debet_kredit == 'K' ? $d->jumlah : 0;
                            $pengeluaran = $d->debet_kredit == 'D' ? $d->jumlah : 0;
                            $color = $d->debet_kredit == 'K' ? 'green' : 'red';
                            $saldo += $penerimaan - $pengeluaran;
                            $total_penerimaan += $penerimaan;
                            $total_pengeluaran += $pengeluaran;
                            $colorklaim = !empty($d->kode_klaim) ? 'background-color: green; color: white' : '';
                        @endphp
                        <tr>
                            <td style="{{ $colorklaim }}">{{ $loop->iteration }}</td>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ $d->keterangan }}</td>
                            <td>'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="right" style="color: {{ $color }}">{{ formatAngka($penerimaan) }}</td>
                            <td class="right" style="color: {{ $color }}">{{ formatAngka($pengeluaran) }}</td>
                            <td class="right">{{ formatAngka($saldo) }}</td>
                            <td>{{ !empty($d->created_at) ? date('d-m-Y H:i:s', strtotime($d->created_at)) : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="6">TOTAL</th>
                        <th class="right">{{ formatAngka($total_penerimaan) }}</th>
                        <th class="right">{{ formatAngka($total_pengeluaran) }}</th>
                        <th class="right">{{ formatAngka($saldo) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

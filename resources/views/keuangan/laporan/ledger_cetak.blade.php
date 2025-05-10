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
    </style> --}}
    <style>
        .text-red {
            background-color: red;
            color: white;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            LEDGER<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($bank != null)
            <h4>
                {{ $bank->nama_bank }} - {{ $bank->no_rekening }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th style="width: 1%">No</th>
                        <th style="width: 4%">TGL</th>
                        <th style="width: 4%">No Bukti</th>
                        {{-- <th>No.Ref</th> --}}
                        <th style="width: 5%">Tgl Terima</th>
                        <th style="width: 10%">Pelanggan</th>
                        <th style="width: 15%">Keterangan</th>
                        <th style="width: 4%">Peruntukan</th>
                        <th style="width: 5%">Kode Akun</th>
                        <th style="width: 10%">Akun</th>
                        <th>Debet</th>
                        <th>Kredit</th>
                        <th>Saldo</th>
                        <th rowspan="2">Sumber</th>
                        <th rowspan="2">Dibuat</th>
                    <tr>
                        @php
                            $saldoawal = $saldo_awal != null ? $saldo_awal->jumlah : 'BELUM DI SET';
                            $color_saldoawal = $saldo_awal != null ? '' : 'text-red';
                        @endphp
                        <th colspan='11'>SALDO AWAL</th>
                        <th style="text-align:right" class="{{ $color_saldoawal }}">
                            {{ $saldo_awal != null ? formatAngkaDesimal($saldoawal) : 0 }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totaldebet = 0;
                        $totalkredit = 0;
                        $saldo = $saldo_awal != null ? $saldoawal : 0;
                    @endphp
                    @foreach ($ledger as $d)
                        @php
                            if ($d->debet_kredit == 'K') {
                                $kredit = $d->jumlah;
                                $debet = 0;
                            } else {
                                $debet = $d->jumlah;
                                $kredit = 0;
                            }

                            $totaldebet += $debet;
                            $totalkredit += $kredit;
                            $saldo = $saldo - $debet + $kredit;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ formatIndo($d->tanggal_penerimaan) }}</td>
                            <td>{{ $d->pelanggan }}</td>
                            <td>{{ $d->keterangan }}</td>
                            <td class="center">{{ $d->kode_peruntukan }}
                                {{ $d->kode_peruntukan != 'MP' ? $d->keterangan_peruntukan : '' }}</td>
                            <td class="center">'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="right">{{ formatAngkaDesimal($debet) }}</td>
                            <td class="right">{{ formatAngkaDesimal($kredit) }}</td>
                            <td class="right">{{ formatAngkaDesimal($saldo) }}</td>
                            <td>{{ $d->nama_bank }} - {{ $d->no_rekening }}</td>
                            <td>{{ date('d-m-Y H:i:s', strtotime($d->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="9">TOTAL</th>
                        <th class="right">{{ formatAngka($totaldebet) }}</th>
                        <th class="right">{{ formatAngka($totalkredit) }}</th>
                        <th class="right">{{ formatAngka($saldo) }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'freezeColumn': false,
    });
</script> --}}

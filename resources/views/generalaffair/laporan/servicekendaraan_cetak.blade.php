<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Service Kendaraan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN SERVICE KENDARAAN<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($kendaraan != null)
            <h4>
                {{ $kendaraan->no_polisi }} {{ $kendaraan->merek }} {{ $kendaraan->tipe }} {{ $kendaraan->tipe_kendaraan }}
            </h4>
        @endif
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th>No. Invoice</th>
                    <th>Tanggal</th>
                    <th>No. Polisi</th>
                    <th>Kendraan</th>
                    <th>Bengkel</th>
                    <th>Cabang</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $arr = [];
                    foreach ($service as $row) {
                        $arr[$row->kode_service][] = $row;
                    }
                    $grandtotal = 0;
                @endphp
                @foreach ($arr as $key => $val)
                    @foreach ($val as $k => $d)
                        <tr>
                            @if ($k == 0)
                                <td rowspan="{{ count($val) }}">{{ $d->no_invoice }}</td>
                                <td rowspan="{{ count($val) }}">{{ DateToIndo($d->tanggal) }}</td>
                                <td rowspan="{{ count($val) }}">{{ $d->no_polisi }}</td>
                                <td rowspan="{{ count($val) }}">{{ $d->merek }} {{ $d->tipe_kendaraan }} {{ $d->tipe }}</td>
                                <td rowspan="{{ count($val) }}">{{ $d->nama_bengkel }}</td>
                                <td rowspan="{{ count($val) }}">{{ $d->kode_cabang }}</td>
                            @endif
                            @php
                                $subtotal = $d->jumlah * $d->harga;
                            @endphp
                            <td>{{ $d->nama_item }}</td>
                            <td style="text-align: center">{{ $d->jumlah }}</td>
                            <td style="text-align: right">{{ formatAngka($d->harga) }}</td>
                            <td style="text-align: right">{{ formatAngka($subtotal) }}</td>
                            @if ($k == 0)
                                @php
                                    $grandtotal += $d->total;
                                @endphp
                                <td rowspan="{{ count($val) }}" style="text-align:right">{{ formatAngka($d->total) }}</td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <th colspan="10">TOTAL</th>
                    <th style="text-align: right">{{ formatAngka($grandtotal) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</body>

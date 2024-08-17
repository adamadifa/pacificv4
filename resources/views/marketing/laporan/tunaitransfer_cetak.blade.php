<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Tunai Transfer {{ date('Y-m-d H:i:s') }}</title>
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
            LAPORAN TUNAI TRANSFER <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($salesman != null)
            <h4>
                {{ textUpperCase($salesman->nama_salesman) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Faktur</th>
                        <th>Tanggal</th>
                        <th>Kode Pelanggan</th>
                        <th>Nama Pelanggan</th>
                        <th>Salesman</th>
                        <th>Total</th>
                        <th>Retur</th>
                        <th>Netto</th>
                        <th>Pembayaran</th>
                        <th>Sisa Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_penjualan = 0;
                        $total_retur = 0;
                        $total_netto = 0;
                        $total_sisabayar = 0;
                        $total_bayar = 0;
                    @endphp
                    @foreach ($tunaitransfer as $d)
                        @php
                            $netto = $d->total - $d->totalretur;
                            $sisabayar = $netto - $d->totalbayar;

                            $total_penjualan += $d->total;
                            $total_retur += $d->totalretur;
                            $total_netto += $netto;
                            $total_sisabayar += $sisabayar;
                            $total_bayar += $d->totalbayar;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->no_faktur }}</td>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ textUpperCase($d->nama_pelanggan) }}</td>
                            <td>{{ textUpperCase($d->nama_salesman) }}</td>
                            <td class="right">{{ formatAngka($d->total) }}</td>
                            <td class="right">{{ formatAngka($d->totalretur) }}</td>
                            <td class="right">{{ formatAngka($netto) }}</td>
                            <td class="right">{{ formatAngka($d->totalbayar) }}</td>
                            <td class="right">{{ formatAngka($sisabayar) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6">TOTAL</th>
                        <th class="right">{{ formatAngka($total_penjualan) }}</th>
                        <th class="right">{{ formatAngka($total_retur) }}</th>
                        <th class="right">{{ formatAngka($total_netto) }}</th>
                        <th class="right">{{ formatAngka($total_bayar) }}</th>
                        <th class="right">{{ formatAngka($total_sisabayar) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 5,
        'shadow': true,
    });
</script> --}}

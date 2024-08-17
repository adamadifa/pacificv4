<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan LEbih 1 Faktur {{ date('Y-m-d H:i:s') }}</title>
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
            LEBIH 1 FAKTUR <br>
        </h4>
        <h4>SAMPAI DENGAN TANGGAL {{ DateToIndo($tanggal) }}</h4>
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
                        <th>Tanggal Penjualan</th>
                        <th>No Faktur</th>
                        <th>Kode Pelanggan</th>
                        <th>Nama Pelanggan</th>
                        <th>Pasar / Daerah</th>
                        <th>Saldo Piutang</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $jmlfaktur = 0;
                        $subtotal_piutang = 0;
                    @endphp
                    @foreach ($lebihsatufaktur as $key => $d)
                        @php
                            $pelanggan = @$lebihsatufaktur[$key + 1]->kode_pelanggan;
                            $jmlfaktur += 1;
                            $subtotal_piutang += $d->sisa_piutang;
                        @endphp
                        <tr>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_faktur }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ textUpperCase($d->nama_pelanggan) }}</td>
                            <td>{{ $d->nama_wilayah }}</td>
                            <td class="right">{{ formatAngka($d->sisa_piutang) }}</td>
                            <td>{{ $d->keterangan }}</td>
                        </tr>
                        @if ($pelanggan != $d->kode_pelanggan)
                            <tr>
                                <th colspan="5"></th>
                                <th class="right">{{ formatAngka($subtotal_piutang) }}</th>
                                <th></th>
                            </tr>
                            @php
                                $jmlfaktur = 0;
                                $subtotal_piutang = 0;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
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

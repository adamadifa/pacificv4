<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Pelanggan {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP PELANGGAN <br>
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
                        <th rowspan="2">KODE PELANGGAN</th>
                        <th rowspan="2">NAMA PELANGGAN</th>
                        <th rowspan="2">PASAR/DAERAH</th>
                        <th rowspan="2">SALESMAN</th>
                        <th rowspan="2">KLASIFIKASI</th>
                        <th colspan="{{ count($produk) }}">PRODUK</th>
                        <th rowspan="2">SKU</th>
                        <th rowspan="2">OMSET</th>
                    </tr>
                    <tr>
                        @foreach ($produk as $d)
                            <th>{{ $d->kode_produk }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $d)
                        @php
                            ${"total_qty_$d->kode_produk"} = 0;
                        @endphp
                    @endforeach
                    @php
                        $total_bruto = 0;
                    @endphp
                    @foreach ($rekappelanggan as $d)
                        @php
                            $total_bruto += $d->total_bruto;
                        @endphp
                        <tr>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td>{{ $d->nama_wilayah }}</td>
                            <td>{{ $d->nama_salesman }}</td>
                            <td>{{ $d->klasifikasi }}</td>
                            @foreach ($produk as $p)
                                @php
                                    $qty = $d->{"qty_$p->kode_produk"} / $p->isi_pcs_dus;
                                    ${"total_qty_$p->kode_produk"} += $d->{"qty_$p->kode_produk"};
                                @endphp
                                <td class="right">{{ formatAngkaDesimal($qty) }}</td>
                            @endforeach
                            <td class="center">{{ $d->total_sku }}</td>
                            <td class="right">{{ formatAngka($d->total_bruto) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">TOTAL</th>
                        @foreach ($produk as $d)
                            @php
                                $total_qty = ${"total_qty_$d->kode_produk"} / $d->isi_pcs_dus;
                            @endphp
                            <th class="right">{{ formatAngkaDesimal($total_qty) }}</th>
                        @endforeach
                        <th class="right">{{ formatAngka($total_bruto) }}</th>
                    </tr>
                </tfoot>
            </table>
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

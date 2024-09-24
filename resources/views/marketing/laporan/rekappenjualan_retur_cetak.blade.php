<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Retur {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP RETUR <br>
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
        <table class="datatable3" style="width: 150%">
            <thead>
                <tr>
                    <th rowspan="3">No.</th>
                    <th rowspan="3">Kode</th>
                    <th rowspan="3">Salesman</th>
                    <th colspan="{{ count($produk) * 2 }}">Produk</th>
                    <th rowspan="3">Total</th>
                    <th rowspan="3">Penyesuaian</th>
                    <th rowspan="3" class="green">Netto</th>
                </tr>
                <tr>
                    @foreach ($produk as $p)
                        <th colspan="2">{{ $p->nama_produk }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($produk as $p)
                        <th>Qty</th>
                        <th>Total(Rp)</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($produk as $p)
                    @php
                        ${"total_qty_$p->kode_produk"} = 0;
                        ${"grandtotal_qty_$p->kode_produk"} = 0;
                        ${"total_subtotal_$p->kode_produk"} = 0;
                        ${"grandtotal_subtotal_$p->kode_produk"} = 0;
                        $sub_total_retur = 0;
                        $grand_sub_total_retur = 0;
                        $sub_total_retur_pf = 0;
                        $grand_sub_total_retur_pf = 0;
                        $sub_total_retur_gb = 0;
                        $grand_sub_total_retur_gb = 0;
                    @endphp
                @endforeach
                @foreach ($rekappenjualan as $key => $d)
                    @php
                        $cbg = @$rekappenjualan[$key + 1]->kode_cabang;
                        $sub_total_retur += $d->total_retur;
                        $grand_sub_total_retur += $d->total_retur;
                        $sub_total_retur_pf += $d->total_retur_pf;
                        $grand_sub_total_retur_pf += $d->total_retur_pf;
                        $sub_total_retur_gb += $d->total_retur_gb;
                        $grand_sub_total_retur_gb += $d->total_retur_gb;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->kode_salesman }}</td>
                        <td>{{ textUpperCase($d->nama_salesman) }}</td>
                        @foreach ($produk as $p)
                            <td class="center">
                                @php
                                    $qty = $d->{"qty_$p->kode_produk"} / $p->isi_pcs_dus;
                                    ${"total_qty_$p->kode_produk"} += $d->{"qty_$p->kode_produk"};
                                    ${"grandtotal_qty_$p->kode_produk"} += $d->{"qty_$p->kode_produk"};
                                @endphp
                                {{ formatAngkaDesimal($qty) }}
                            </td>
                            <td class="right">
                                @php
                                    $total_retur = $d->{"subtotal_$p->kode_produk"};
                                    ${"total_subtotal_$p->kode_produk"} += $d->{"subtotal_$p->kode_produk"};
                                    ${"grandtotal_subtotal_$p->kode_produk"} += $d->{"subtotal_$p->kode_produk"};
                                @endphp
                                {{ formatAngka($total_retur) }}
                            </td>
                        @endforeach
                        <td class="right">{{ formatAngka($d->total_retur) }}</td>
                        <td class="right">{{ formatAngka($d->total_retur_gb) }}</td>
                        <td class="right">{{ formatAngka($d->total_retur_pf) }}</td>
                    </tr>
                    @if ($cbg != $d->kode_cabang)
                        <tr>
                            <th colspan="3">TOTAL</th>
                            @foreach ($produk as $p)
                                <th class="center">
                                    @php
                                        $total_qty = ${"total_qty_$p->kode_produk"} / $p->isi_pcs_dus;
                                        $total_subtotal = ${"total_subtotal_$p->kode_produk"};
                                    @endphp
                                    {{ formatAngkaDesimal($total_qty) }}
                                </th>
                                <th class="right">{{ formatAngka($total_subtotal) }}</th>
                                @php
                                    ${"total_qty_$p->kode_produk"} = 0;
                                    ${"total_subtotal_$p->kode_produk"} = 0;
                                @endphp
                            @endforeach
                            <th class="right">{{ formatAngka($sub_total_retur) }}</th>
                            <th class="right">{{ formatAngka($sub_total_retur_gb) }}</th>
                            <th class="right">{{ formatAngka($sub_total_retur_pf) }}</th>
                            @php
                                $sub_total_retur = 0;
                                $sub_total_retur_pf = 0;
                                $sub_total_retur_gb = 0;
                            @endphp
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <th colspan="3">GRAND TOTAL</th>
                    @php
                        $totalreturqty = 0;
                    @endphp
                    @foreach ($produk as $p)
                        <th class="center">
                            @php
                                $grandtotal_qty = ${"grandtotal_qty_$p->kode_produk"} / $p->isi_pcs_dus;
                                $totalreturqty += $grandtotal_qty;
                                $grnadtotal_subtotal = ${"grandtotal_subtotal_$p->kode_produk"};
                            @endphp
                            {{ formatAngkaDesimal($grandtotal_qty) }}
                        </th>
                        <th class="right">{{ formatAngka($grnadtotal_subtotal) }}</th>
                    @endforeach
                    <th class="right">{{ formatAngka($grand_sub_total_retur) }}</th>
                    <th class="right">{{ formatAngka($grand_sub_total_retur_gb) }}</th>
                    <th class="right">{{ formatAngka($grand_sub_total_retur_pf) }}</th>
                </tr>
            </tbody>
        </table>
        <br>
        <br>
        <br>
        <table class="datatable3" style="width: 130%">
            <tr>
                <th colspan="2">Penyesuaian Retur</th>
                @foreach ($produk as $p)
                    <th>{{ $p->nama_produk }}</th>
                @endforeach
            </tr>
            <tr>
                <td>Total Qty</td>
                <td class="right">{{ formatAngkaDesimal($totalreturqty) }}</td>
                @php
                    $average = !empty($totalreturqty) ? $grand_sub_total_retur_gb / $totalreturqty : 0;
                @endphp
                @foreach ($produk as $p)
                    @php
                        $qty = ${"grandtotal_qty_$p->kode_produk"} / $p->isi_pcs_dus;
                        $averageharga = $qty * $average;
                    @endphp
                    <td class="right" rowspan="3">{{ formatAngka($averageharga) }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Penyesuaian Harga</td>
                <td class="right">{{ formatAngka($grand_sub_total_retur_gb) }}</td>
            </tr>
            <tr>
                <td>Average</td>
                <td class="right">
                    {{ formatAngka($average) }}
                </td>
            </tr>
        </table>
    </div>
</body>

</html>

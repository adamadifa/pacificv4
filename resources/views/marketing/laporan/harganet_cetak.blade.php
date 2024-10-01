<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Harga Net {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    {{-- <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script> --}}
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
            HARGA NET <br>
        </h4>
        <h4>BULAN :{{ $namabulan[$bulan] }}</h4>
        <h4>TAHUN :{{ $tahun }}</h4>


    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">Keterangan</th>
                        <th colspan="{{ count($produk) }}">PRODUK</th>
                    </tr>
                    <tr>
                        @foreach ($produk as $p)
                            <th class="text-center">{{ $p->kode_produk }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PENJUALAN BRUTO</td>
                        @foreach ($produk as $p)
                            @php
                                $bruto_tunai =
                                    $detail->{"bruto_tunai_$p->kode_produk"} +
                                    ($detail->{"bruto_tunai_$p->kode_produk"} / $detail->bruto_total_tunai) * $penjualan->ppn_total_tunai;
                                $bruto_kredit =
                                    $detail->{"bruto_kredit_$p->kode_produk"} +
                                    ($detail->{"bruto_kredit_$p->kode_produk"} / $detail->bruto_total_kredit) * $penjualan->ppn_total_kredit;
                                $bruto = $bruto_tunai + $bruto_kredit;
                            @endphp
                            <td class="right">{{ formatAngka($bruto) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>DISKON QTY / PRODUK</td>
                        @foreach ($produk as $p)
                            @php
                                if ($p->kode_kategori_produk == 'P01') {
                                    $diskon = !empty($detail->qtyAida)
                                        ? ($penjualan->potongan_aida / $detail->qtyAida) * $detail->{"qtydus_$p->kode_produk"}
                                        : 0;

                                    $cek = 1;
                                } else {
                                    $diskon = !empty($detail->qtySwan)
                                        ? ($penjualan->potongan_swan / $detail->qtySwan) * $detail->{"qtydus_$p->kode_produk"}
                                        : 0;

                                    $cek = 2;
                                }
                            @endphp
                            <td class="right">{{ formatAngka($diskon) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>PENYESUAIAN HARGA</td>
                        @php
                            $ratio_penyesuaian = !empty($detail->qtyTotal) ? $penjualan->penyesuaian / $detail->qtyTotal : 0;
                            $ratio_penyesuaian = $ratio_penyesuaian > 0 ? ROUND($ratio_penyesuaian) : 0;
                        @endphp
                        @foreach ($produk as $p)
                            @php
                                $penyesuaian = ($detail->{"qty_$p->kode_produk"} / $p->isi_pcs_dus) * $ratio_penyesuaian;
                            @endphp
                            <td class="right">{{ formatAngkaDesimal($penyesuaian) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>PENJUALAN QTY DUS</td>
                        @foreach ($produk as $p)
                            @php
                                $qty = $detail->{"qty_$p->kode_produk"} / $p->isi_pcs_dus;
                            @endphp
                            <td class="right">{{ formatAngkaDesimal($qty) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="left">HARGA NET TANPA RETUR</th>
                        @foreach ($produk as $p)
                            @php

                                if ($p->kode_kategori_produk == 'P01') {
                                    $diskon = !empty($detail->qtyAida)
                                        ? ($penjualan->potongan_aida / $detail->qtyAida) * $detail->{"qtydus_$p->kode_produk"}
                                        : 0;
                                } else {
                                    $diskon = !empty($detail->qtySwan)
                                        ? ($penjualan->potongan_swan / $detail->qtySwan) * $detail->{"qtydus_$p->kode_produk"}
                                        : 0;
                                }

                                $penyesuaian = ($detail->{"qty_$p->kode_produk"} / $p->isi_pcs_dus) * $ratio_penyesuaian;
                                $bruto_tunai =
                                    $detail->{"bruto_tunai_$p->kode_produk"} +
                                    ($detail->{"bruto_tunai_$p->kode_produk"} / $detail->bruto_total_tunai) * $penjualan->ppn_total_tunai;

                                $bruto_kredit =
                                    $detail->{"bruto_kredit_$p->kode_produk"} +
                                    ($detail->{"bruto_kredit_$p->kode_produk"} / $detail->bruto_total_kredit) * $penjualan->ppn_total_kredit;

                                $bruto = $bruto_tunai + $bruto_kredit;

                                $qty = $detail->{"qty_$p->kode_produk"} / $p->isi_pcs_dus;

                                $harganet = ($bruto - $diskon - $penyesuaian) / $qty;
                            @endphp
                            <th class="right">{{ formatAngka($harganet) }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <td>RETUR</td>
                        @foreach ($produk as $p)
                            <td class="right">{{ formatAngka($retur->{"retur_total_$p->kode_produk"}) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>PENYESUAIAN RETUR</td>
                        @foreach ($produk as $p)
                            <td class="right">{{ formatAngka($retur->{"retur_gb_$p->kode_produk"}) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>RETUR NET</td>
                        @foreach ($produk as $p)
                            @php
                                $returnet = $retur->{"retur_total_$p->kode_produk"} - $retur->{"retur_gb_$p->kode_produk"};
                            @endphp
                            <td class="right">{{ formatAngka($returnet) }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="left">HARGA NET </th>
                        @foreach ($produk as $p)
                            @php

                                if ($p->kode_kategori_produk == 'P01') {
                                    $diskon = !empty($detail->qtyAida)
                                        ? ($penjualan->potongan_aida / $detail->qtyAida) * $detail->{"qtydus_$p->kode_produk"}
                                        : 0;
                                } else {
                                    $diskon = !empty($detail->qtySwan)
                                        ? ($penjualan->potongan_swan / $detail->qtySwan) * $detail->{"qtydus_$p->kode_produk"}
                                        : 0;
                                }
                                $penyesuaian = ($detail->{"qty_$p->kode_produk"} / $p->isi_pcs_dus) * $ratio_penyesuaian;
                                $bruto_tunai =
                                    $detail->{"bruto_tunai_$p->kode_produk"} +
                                    ($detail->{"bruto_tunai_$p->kode_produk"} / $detail->bruto_total_tunai) * $penjualan->ppn_total_tunai;
                                $bruto_kredit =
                                    $detail->{"bruto_kredit_$p->kode_produk"} +
                                    ($detail->{"bruto_kredit_$p->kode_produk"} / $detail->bruto_total_kredit) * $penjualan->ppn_total_kredit;
                                $bruto = $bruto_tunai + $bruto_kredit;

                                $qty = $detail->{"qty_$p->kode_produk"} / $p->isi_pcs_dus;
                                $returnet = $retur->{"retur_total_$p->kode_produk"} - $retur->{"retur_gb_$p->kode_produk"};
                                $harganetwithretur = ($bruto - $diskon - $penyesuaian - $returnet) / $qty;
                            @endphp
                            <th class="right">{{ formatAngka($harganetwithretur) }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="left">HARGA NET EXCLUDE DISKON</th>
                        @foreach ($produk as $p)
                            @php

                                if ($p->kode_kategori_produk == 'P01') {
                                    $diskon = !empty($detail->qtyAida)
                                        ? ($penjualan->potongan_aida / $detail->qtyAida) * $detail->{"qtydus_$p->kode_produk"}
                                        : 0;
                                } else {
                                    $diskon = !empty($detail->qtySwan)
                                        ? ($penjualan->potongan_swan / $detail->qtySwan) * $detail->{"qtydus_$p->kode_produk"}
                                        : 0;
                                }

                                $penyesuaian = ($detail->{"qty_$p->kode_produk"} / $p->isi_pcs_dus) * $ratio_penyesuaian;
                                $bruto_tunai =
                                    $detail->{"bruto_tunai_$p->kode_produk"} +
                                    ($detail->{"bruto_tunai_$p->kode_produk"} / $detail->bruto_total_tunai) * $penjualan->ppn_total_tunai;
                                $bruto_kredit =
                                    $detail->{"bruto_kredit_$p->kode_produk"} +
                                    ($detail->{"bruto_kredit_$p->kode_produk"} / $detail->bruto_total_kredit) * $penjualan->ppn_total_kredit;
                                $bruto = $bruto_tunai + $bruto_kredit;

                                $qty = $detail->{"qty_$p->kode_produk"} / $p->isi_pcs_dus;
                                $returnet = $retur->{"retur_total_$p->kode_produk"} - $retur->{"retur_gb_$p->kode_produk"};
                                $harganetwithreturexclude = ($bruto - $penyesuaian - $returnet) / $qty;
                            @endphp
                            <th class="right">{{ formatAngka($harganetwithreturexclude) }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="left">HARGA NET INCLUDE DISKON</th>
                        @foreach ($produk as $p)
                            @php

                                if ($p->kode_kategori_produk == 'P01') {
                                    $diskon = !empty($detail->qtyAida)
                                        ? ($penjualan->potongan_aida / $detail->qtyAida) * $detail->{"qtydus_$p->kode_produk"}
                                        : 0;
                                } else {
                                    $diskon = !empty($detail->qtySwan)
                                        ? ($penjualan->potongan_swan / $detail->qtySwan) * $detail->{"qtydus_$p->kode_produk"}
                                        : 0;
                                }

                                $penyesuaian = ($detail->{"qty_$p->kode_produk"} / $p->isi_pcs_dus) * $ratio_penyesuaian;
                                $bruto_tunai =
                                    $detail->{"bruto_tunai_$p->kode_produk"} +
                                    ($detail->{"bruto_tunai_$p->kode_produk"} / $detail->bruto_total_tunai) * $penjualan->ppn_total_tunai;
                                $bruto_kredit =
                                    $detail->{"bruto_kredit_$p->kode_produk"} +
                                    ($detail->{"bruto_kredit_$p->kode_produk"} / $detail->bruto_total_kredit) * $penjualan->ppn_total_kredit;
                                $bruto = $bruto_tunai + $bruto_kredit;

                                $qty = $detail->{"qty_$p->kode_produk"} / $p->isi_pcs_dus;
                                $returnet = $retur->{"retur_total_$p->kode_produk"} - $retur->{"retur_gb_$p->kode_produk"};
                                $harganetwithreturexclude = ($bruto - $penyesuaian - $returnet) / $qty;
                                $harganetwithreturinclude = $harganetwithreturexclude - $diskon / $qty;
                            @endphp
                            <th class="right">{{ formatAngka($harganetwithreturinclude) }}</th>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 2,
        'shadow': true,
    });
</script> --}}

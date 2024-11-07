<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Penjualan {{ date('Y-m-d H:i:s') }}</title>
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
            LAPORAN PENJUALAN <br>
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
            <table class="datatable3" style="width: 350%">
                <thead>
                    <tr>
                        <th rowspan="3">No.</th>
                        <th rowspan="3">Tanggal</th>
                        <th rowspan="3">No. Faktur</th>
                        <th rowspan="3">Kode</th>
                        <th rowspan="3">Nama Pelanggan</th>
                        <th rowspan="3">Nama Salesman</th>
                        <th rowspan="3">Hari</th>
                        <th rowspan="3">Klasifikasi</th>
                        <th rowspan="3">Wilayah</th>
                        <th colspan="{{ count($produk) }}">PRODUK</th>
                        <th rowspan="3">Bruto</th>
                    </tr>
                    <tr>
                        @foreach ($produk as $d)
                            <th colspan="7">{{ $d->nama_produk }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($produk as $d)
                            <th>Dus</th>
                            <th>Harga</th>
                            <th>Pack</th>
                            <th>Harga</th>
                            <th>Pcs</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_faktur }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ textupperCase($d->nama_pelanggan) }}</td>
                            <td>{{ formatName($d->nama_salesman) }}</td>
                            <td>{{ $d->hari }}</td>
                            <td>{{ $d->klasifikasi }}</td>
                            <td>{{ $d->nama_wilayah }}</td>
                            @foreach ($produk as $p)
                                @php
                                    $qty = convertToduspackpcsv2($p->isi_pcs_dus, $p->isi_pcs_pack, $d->{"qty_$p->kode_produk"});
                                    $jml = explode('|', $qty);
                                    $dus = $jml[0];
                                    $pack = $jml[1];
                                    $pcs = $jml[2];

                                    $qty_promosi = convertToduspackpcsv2($p->isi_pcs_dus, $p->isi_pcs_pack, $d->{"qty_promosi_$p->kode_produk"});
                                    $jml_promosi = explode('|', $qty_promosi);
                                    $dus_promosi = $jml_promosi[0];
                                    $pack_promosi = $jml_promosi[1];
                                    $pcs_promosi = $jml_promosi[2];

                                    if (empty($d->{"qty_$p->kode_produk"})) {
                                        $color = '#c9c9c9';
                                    } else {
                                        $color = '';
                                    }

                                @endphp
                                <td class="center" style="background-color: {{ $color }}">
                                    {{ formatAngka($dus) }}
                                    {!! !empty($dus_promosi) ? '+<span style="color:Red; font-weight:bold">' . formatAngka($dus_promosi) . '</span>' : '' !!}
                                </td>
                                <td class="right" style="background-color: {{ $color }}">
                                    {{ !empty($dus) ? formatAngka($d->{"harga_dus_$p->kode_produk"}) : '' }}
                                </td>
                                <td class="center" style="background-color: {{ $color }}">
                                    {{ formatAngka($pack) }}
                                    {!! !empty($pack_promosi) ? '+<span style="color:Red; font-weight:bold">' . formatAngka($pack_promosi) . '</span>' : '' !!}
                                </td>
                                <td class="right" style="background-color: {{ $color }}">
                                    {{ !empty($pack) ? formatAngka($d->{"harga_pack_$p->kode_produk"}) : '' }}</td>
                                <td class="center" style="background-color: {{ $color }}">
                                    {{ formatAngka($pcs) }}
                                    {!! !empty($pcs_promosi) ? '+<span style="color:Red; font-weight:bold">' . formatAngka($pcs_promosi) . '</span>' : '' !!}
                                </td>
                                <td class="right" style="background-color: {{ $color }}">
                                    {{ !empty($pcs) ? formatAngka($d->{"harga_pcs_$p->kode_produk"}) : '' }}</td>
                                <td class="right" style="background-color: #a2d8fc; font-weight:bold">
                                    {{ formatAngka($d->{"subtotal_$p->kode_produk"}) }}
                                </td>
                            @endforeach
                            <td class="right">{{ formatAngka($d->bruto) }}</td>
                        </tr>
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
        'columnNum': 9,
        'shadow': true,
    });
</script> --}}

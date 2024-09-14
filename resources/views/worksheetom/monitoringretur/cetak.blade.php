<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Monitoring Retur {{ date('Y-m-d H:i:s') }}</title>
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
            LAPORAN MONITORING RETUR <br>
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
                        {{-- <th rowspan="2">No</th> --}}
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">No Retur</th>
                        <th rowspan="2">No Faktur</th>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2">Nama Pelanggan</th>
                        <th rowspan="2">Nama Produk</th>
                        <th colspan="3">Retur</th>
                        <th colspan="3" class="green">Pelunasan</th>
                        <th colspan="3" class="red">Sisa</th>
                        <th rowspan="2">Status</th>

                        {{-- <th colspan="{{ count($validasi_item) }}" align="center">Validasi</th> --}}
                    </tr>
                    <tr>
                        <th>Dus</th>
                        <th>Pack</th>
                        <th>Pcs</th>

                        <th class="green">Dus</th>
                        <th class="green">Pack</th>
                        <th class="green">Pcs</th>

                        <th class="red">Dus</th>
                        <th class="red">Pack</th>
                        <th class="red">Pcs</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        @php
                            $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                            $jumlah_dus = $jumlah[0];
                            $jumlah_pack = $jumlah[1];
                            $jumlah_pcs = $jumlah[2];

                            $jumlah_pelunasan = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah_pelunasan));
                            $jumlah_pelunasan_dus = $jumlah_pelunasan[0];
                            $jumlah_pelunasan_pack = $jumlah_pelunasan[1];
                            $jumlah_pelunasan_pcs = $jumlah_pelunasan[2];

                            $sisa = $d->jumlah - $d->jumlah_pelunasan;
                            $sisa_retur = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $sisa));
                            $sisa_retur_dus = $sisa_retur[0];
                            $sisa_retur_pack = $sisa_retur[1];
                            $sisa_retur_pcs = $sisa_retur[2];
                        @endphp
                        <tr>
                            <td>{{ $d->tanggal }}</td>
                            <td>{{ $d->no_retur }}</td>
                            <td>{{ $d->no_faktur }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td>{{ $d->nama_produk }}</td>
                            <td class="center">{{ !empty($jumlah_dus) ? $jumlah_dus : '' }}</td>
                            <td class="center">{{ !empty($jumlah_pack) ? $jumlah_pack : '' }}</td>
                            <td class="center">{{ !empty($jumlah_pcs) ? $jumlah_pcs : '' }}</td>

                            <td class="center">{{ !empty($jumlah_pelunasan_dus) ? $jumlah_pelunasan_dus : '' }}</td>
                            <td class="center">{{ !empty($jumlah_pelunasan_pack) ? $jumlah_pelunasan_pack : '' }}</td>
                            <td class="center">{{ !empty($jumlah_pelunasan_pcs) ? $jumlah_pelunasan_pcs : '' }}</td>

                            <td class="center">{{ !empty($sisa_retur_dus) ? $sisa_retur_dus : '' }}</td>
                            <td class="center">{{ !empty($sisa_retur_pack) ? $sisa_retur_pack : '' }}</td>
                            <td class="center">{{ !empty($sisa_retur_pcs) ? $sisa_retur_pcs : '' }}</td>
                            <td>
                                @if ($sisa == 0)
                                    <span style="color:green">LUNAS</span>
                                @else
                                    <span style="color: red">BELUM LUNAS</span>
                                @endif
                            </td>
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
        'columnNum': 5,
        'shadow': true,
    });
</script> --}}

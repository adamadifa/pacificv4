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
            <table class="datatable3">
                <thead>
                    <tr>
                        {{-- <th>No.</th> --}}
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">No. Faktur</th>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2">Nama Pelanggan</th>
                        <th rowspan="2">Nama Salesman</th>
                        <th rowspan="2">Hari</th>
                        <th rowspan="2">Klasifikasi</th>
                        <th rowspan="2">Wilayah</th>
                        <th rowspan="2">Nama Produk</th>
                        <th colspan="7">Qty</th>
                    </tr>
                    <tr>
                        <th>Dus</th>
                        <th>Harga</th>
                        <th>Pack</th>
                        <th>Harga</th>
                        <th>Pcs</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $arr = [];
                        foreach ($penjualan as $row) {
                            $arr[$row->no_faktur][] = $row;
                        }
                        $grandtotal = 0;
                        $total = 0;
                    @endphp
                    @foreach ($arr as $key => $val)
                        @php
                            $subtotalPerFaktur = 0; // Inisialisasi subtotal untuk setiap faktur
                        @endphp
                        @foreach ($val as $k => $d)
                            @php
                                $qty = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah);
                                $jml = explode('|', $qty);
                                $dus = $jml[0];
                                $pack = $jml[1];
                                $pcs = $jml[2];
                                $total += $d->subtotal;
                                $subtotalPerFaktur += $d->subtotal; // Menjumlahkan subtotal untuk setiap faktur
                            @endphp
                            <tr>
                                @if ($k == 0)
                                    <td rowspan="{{ count($val) }}">{{ formatIndo($d->tanggal) }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->no_faktur }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->kode_pelanggan }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->nama_pelanggan }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->nama_salesman }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->hari }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->klasifikasi }}</td>
                                    <td rowspan="{{ count($val) }}">{{ $d->nama_wilayah }}</td>
                                @endif
                                <td>{{ $d->nama_produk }}</td>
                                <td class="center">{{ formatAngka($dus) }}</td>
                                <td class="right">{{ !empty($dus) ? formatAngka($d->harga_dus) : '' }}</td>
                                <td class="center">{{ formatAngka($pack) }}</td>
                                <td class="right">{{ !empty($pack) ? formatAngka($d->harga_pack) : '' }}</td>
                                <td class="center">{{ formatAngka($pcs) }}</td>
                                <td class="right">{{ !empty($pcs) ? formatAngka($d->harga_pcs) : '' }}</td>
                                <td class="right">{{ formatAngka($d->subtotal) }}</td>
                                <td class="right">{{ formatAngka($subtotalPerFaktur) }}</td>
                                <td>{{ $k }}</td>
                                @if ($k == 0)
                                    <!-- Untuk Menjumlahkan Subtotal Per faktur-->
                                    <td rowspan="{{ count($val) }}" class="right">{{ formatAngka($subtotalPerFaktur) }}</td>
                                @endif
                            </tr>
                        @endforeach
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
</script> --}

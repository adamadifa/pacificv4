<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Tunai Kredit {{ date('Y-m-d H:i:s') }}</title>
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
            LAPORAN TUNAI KREDIT <br>
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
                        <th rowspan="3" align="center">Kode</th>
                        <th rowspan="3" align="center">Nama Barang</th>
                        <th colspan="4" align="center">Penjualan Tunai</th>
                        <th colspan="4" align="center">Penjualan Kredit</th>
                        <th colspan="4">Total Penjualan Tunai Kredit</th>

                    </tr>
                    <tr>
                        <th colspan="3" align="center">Qty</th>
                        <th rowspan="2" align="center">Total</th>
                        <th colspan="3" align="center">Qty</th>
                        <th rowspan="2" align="center">Total</th>
                        <th colspan="3" align="center">Total Qty</th>
                        <th rowspan="2" align="center">Total Penjualan</th>
                    </tr>
                    <tr>
                        <th align="center">Dus</th>
                        <th align="center">Pack</th>
                        <th align="center">Pcs</th>
                        <th align="center">Dus</th>
                        <th align="center">Pack</th>
                        <th align="center">Pcs</th>
                        <th align="center">Dus</th>
                        <th align="center">Pack</th>
                        <th align="center">Pcs</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualan as $d)
                        <!-- Tunai-->
                        @php
                            $qty_tunai = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->qty_tunai);
                            $jml_tunai = explode('|', $qty_tunai);
                            $dus_tunai = $jml_tunai[0];
                            $pack_tunai = $jml_tunai[1];
                            $pcs_tunai = $jml_tunai[2];

                            //Kredit
                            $qty_kredit = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->qty_kredit);
                            $jml_kredit = explode('|', $qty_kredit);
                            $dus_kredit = $jml_kredit[0];
                            $pack_kredit = $jml_kredit[1];
                            $pcs_kredit = $jml_kredit[2];

                            //Total
                            $qty_total = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->qty_total);
                            $jml_total = explode('|', $qty_total);
                            $dus_total = $jml_total[0];
                            $pack_total = $jml_total[1];
                            $pcs_total = $jml_total[2];
                        @endphp
                        <tr>
                            <td>{{ $d->kode_produk }}</td>
                            <td>{{ $d->nama_produk }}</td>
                            <td class="center">{{ formatAngka($dus_tunai) }}</td>
                            <td class="center">{{ formatAngka($pack_tunai) }}</td>
                            <td class="center">{{ formatAngka($pcs_tunai) }}</td>
                            <td class="right">{{ formatAngka($d->bruto_tunai) }}</td>
                            <td class="center">{{ formatAngka($dus_kredit) }}</td>
                            <td class="center">{{ formatAngka($pack_kredit) }}</td>
                            <td class="center">{{ formatAngka($pcs_kredit) }}</td>
                            <td class="right">{{ formatAngka($d->bruto_kredit) }}</td>
                            <td class="center">{{ formatAngka($dus_total) }}</td>
                            <td class="center">{{ formatAngka($pack_total) }}</td>
                            <td class="center">{{ formatAngka($pcs_total) }}</td>
                            <td class="right">{{ formatAngka($d->bruto_total) }}</td>

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

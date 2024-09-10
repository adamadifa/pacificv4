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
            LAPORAN REKONSILIASI BJ <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        {{-- @if ($salesman != null)
            <h4>
                {{ textUpperCase($salesman->nama_salesman) }}
            </h4>
        @endif --}}
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2">Produk</th>
                        <th colspan="3">TUNAI KREDIT</th>
                        <th colspan="3">PERSEDIAAN</th>
                        <th colspan="3">SELISIH</th>
                    </tr>
                    <tr>
                        <th>DUS</th>
                        <th>PACK</th>
                        <th>PCS</th>

                        <th>DUS</th>
                        <th>PACK</th>
                        <th>PCS</th>

                        <th>DUS</th>
                        <th>PACK</th>
                        <th>PCS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekonsiliasi as $d)
                        @php
                            $qty_total = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->total);
                            $jml_total = explode('|', $qty_total);
                            $dus_total = $jml_total[0];
                            $pack_total = $jml_total[1];
                            $pcs_total = $jml_total[2];

                            $qty_persediaan = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->totalpersediaan);
                            $jml_persediaan = explode('|', $qty_persediaan);
                            $dus_persediaan = $jml_persediaan[0];
                            $pack_persediaan = $jml_persediaan[1];
                            $pcs_persediaan = $jml_persediaan[2];

                            $selisih = $d->total - $d->totalpersediaan;

                            $qty_selisih = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $selisih);
                            $jml_selisih = explode('|', $qty_selisih);
                            $dus_selisih = $jml_selisih[0];
                            $pack_selisih = $jml_selisih[1];
                            $pcs_selisih = $jml_selisih[2];
                        @endphp
                        <tr>
                            <td>{{ $d->kode_produk }}</td>
                            <td>{{ $d->nama_produk }}</td>
                            <td class="center">{{ !empty($dus_total) ? formatAngka($dus_total) : '' }}</td>
                            <td class="center">{{ !empty($pack_total) ? formatAngka($pack_total) : '' }}</td>
                            <td class="center">{{ !empty($pcs_total) ? formatAngka($pcs_total) : '' }}</td>
                            <td class="center">{{ !empty($dus_persediaan) ? formatAngka($dus_persediaan) : '' }}</td>
                            <td class="center">{{ !empty($pack_persediaan) ? formatAngka($pack_persediaan) : '' }}</td>
                            <td class="center">{{ !empty($pcs_persediaan) ? formatAngka($pcs_persediaan) : '' }}</td>
                            <td class="center">{{ !empty($dus_selisih) ? formatAngka($dus_selisih) : '' }}</td>
                            <td class="center">{{ !empty($pack_selisih) ? formatAngka($pack_selisih) : '' }}</td>
                            <td class="center">{{ !empty($pcs_selisih) ? formatAngka($pcs_selisih) : '' }}</td>

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

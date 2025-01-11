<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Omset Pelanggan {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP OMSET PELANGGAN <br>
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
                        <th>NO</th>
                        <th>KODE PELANGGAN</th>
                        <th>NAMA PELANGGAN</th>
                        <th>PASAR</th>
                        <th>KLASIFIKASI</th>
                        {{-- <th>TOTAL OMSET</th> --}}
                        <th>OMSET</th>
                        <th>SWAN</th>
                        <th>AIDA</th>
                        {{-- <th>SALESMAN</th> --}}
                        <th>RETUR</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_omset = 0;
                        $total_swan = 0;
                        $total_aida = 0;
                        $total_bruto = 0;
                        $grandtotal_aida = 0;
                        $grandtotal_swan = 0;
                        $total_retur = 0;
                    @endphp
                    @foreach ($omsetpelanggan as $d)
                        @php
                            $total_omset += $d->total_netto;
                            $total_swan += $d->total_netto_swan;
                            $total_aida += $d->total_netto_aida;
                            $total_bruto += $d->total_bruto;
                            $total_aida_swan = $d->total_netto_swan + $d->total_netto_aida;
                            $potongan_istimewa_swan =
                                $total_aida_swan == 0 ? 0 : ($d->total_netto_swan / $total_aida_swan) * $d->total_potongan_istimewa;
                            $potongan_istimewa_aida =
                                $total_aida_swan == 0 ? 0 : ($d->total_netto_aida / $total_aida_swan) * $d->total_potongan_istimewa;
                            $ppn_swan = $total_aida_swan == 0 ? 0 : ($d->total_netto_swan / $total_aida_swan) * $d->total_ppn;
                            $ppn_aida = $total_aida_swan == 0 ? 0 : ($d->total_netto_aida / $total_aida_swan) * $d->total_ppn;

                            $retur_swan = $total_aida_swan == 0 ? 0 : ($d->total_netto_swan / $total_aida_swan) * $d->total_retur;
                            $retur_aida = $total_aida_swan == 0 ? 0 : ($d->total_netto_aida / $total_aida_swan) * $d->total_retur;

                            $total_swan_fix = $d->total_netto_swan - $potongan_istimewa_swan + $ppn_swan - $retur_swan;
                            $total_aida_fix = $d->total_netto_aida - $potongan_istimewa_aida + $ppn_aida - $retur_aida;
                            $grandtotal_aida += $total_aida_fix;
                            $grandtotal_swan += $total_swan_fix;
                            $total_retur += $d->total_retur;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td>{{ $d->nama_wilayah }}</td>
                            <td>{{ $d->klasifikasi }}</td>
                            {{-- <td class="right">{{ formatAngka($d->total_netto) }}</td> --}}
                            <td class="right">{{ formatAngka($d->total_bruto) }}</td>
                            <td class="right">{{ formatAngka($total_swan_fix) }}</td>
                            <td class="right">{{ formatAngka($total_aida_fix) }}</td>
                            {{-- <td>{{ $d->nama_salesman }}</td> --}}
                            <td class="right">{{ formatAngka($d->total_retur) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">TOTAL</th>
                        {{-- <th class="right">{{ formatAngka($total_omset) }}</th> --}}
                        <th class="right">{{ formatAngka($total_bruto) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_swan) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_aida) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            {{ $total_retur }}
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

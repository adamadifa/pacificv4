<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Penjualan All Cabang {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style>

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
            REKAP PENJULAN ALL CABANG <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>CABANG</th>
                        <th>TOTAL BRUTO</th>
                        <th>TOTAL RETUR</th>
                        <th>PENYESUAIAN</th>
                        <th>POTONGAN</th>
                        <th>POTONGAN ISTIMEWA</th>
                        <th>PPN</th>
                        <th>TOTAL NETTO</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandtotal_bruto = 0;
                        $grandtotal_retur = 0;
                        $grandtotal_penyesuaian = 0;
                        $grandtotal_potongan = 0;
                        $grandtotal_potongan_istimewa = 0;
                        $grandtotal_ppn = 0;
                        $grandtotal_netto = 0;
                    @endphp
                    @foreach ($penjualan as $d)
                        @php
                            $total_netto =
                                $d->total_bruto -
                                $d->total_retur -
                                $d->total_penyesuaian -
                                $d->total_potongan -
                                $d->total_potongan_istimewa +
                                $d->total_ppn;
                            $grandtotal_bruto += $d->total_bruto;
                            $grandtotal_retur += $d->total_retur;
                            $grandtotal_penyesuaian += $d->total_penyesuaian;
                            $grandtotal_potongan += $d->total_potongan;
                            $grandtotal_potongan_istimewa += $d->total_potongan_istimewa;
                            $grandtotal_ppn += $d->total_ppn;
                            $grandtotal_netto += $total_netto;
                        @endphp
                        <tr>
                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                            <td class="right">{{ formatAngka($d->total_bruto) }}</td>
                            <td class="right">{{ formatAngka($d->total_retur) }}</td>
                            <td class="right">{{ formatAngka($d->total_penyesuaian) }}</td>
                            <td class="right">{{ formatAngka($d->total_potongan) }}</td>
                            <td class="right">{{ formatAngka($d->total_potongan_istimewa) }}</td>
                            <td class="right">{{ formatAngka($d->total_ppn) }}</td>
                            <td class="right">{{ formatAngka($total_netto) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>TOTAL</th>
                        <th class="right">{{ formatAngka($grandtotal_bruto) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_retur) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_penyesuaian) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_potongan) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_potongan_istimewa) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_ppn) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_netto) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

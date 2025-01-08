<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Penjualan Produk {{ date('Y-m-d H:i:s') }}</title>
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
            REKAP PENJUALAN PRODUK <br>
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
        <table style="font-family: Arial, Helvetica, sans-serif">
            <thead>
                <tr>
                    <th align="left" style="font-weight: bold">PENJUALAN</th>
                    <th align="right" style="font-weight: bold">JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $kategori = '';
                    $jumlahperkategori = 0;
                    $jumlahall = 0;
                @endphp
                @foreach ($rekappenjualan as $key => $r)
                    @php
                        $kat = @$rekappenjualan[$key + 1]->kode_jenis_produk;

                        $jumlahperkategori = $jumlahperkategori + $r->jumlah;
                        $jumlahall = $jumlahall + $r->jumlah;
                    @endphp
                    @if ($kategori != $r->kode_jenis_produk)
                        <tr>
                            <td colspan="2" style="font-weight: bold">
                                <i>{{ $r->nama_jenis_produk }}</i>
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td>{{ $r->nama_produk }}</td>
                        <td class="right">{{ formatAngka($r->jumlah) }}</td>
                    </tr>
                    @php
                        $kategori = $r->kode_jenis_produk;
                    @endphp
                    @if ($kat != $r->kode_jenis_produk)
                        <tr>
                            <td><b><i>JUMLAH</i></b></td>
                            <td align="right"><b><i>{{ formatAngka($jumlahperkategori) }}</i></b></td>
                        </tr>
                        @php
                            $jumlahperkategori = 0;
                        @endphp
                        <tr>
                            <td style="height: 20px" colspan="2"></td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>POTONGAN</td>
                    <td align="right"><b><i>{{ formatAngka($penjualan->potongan) }}</i></b></td>
                </tr>
                <tr>
                    <td>POTONGAN ISTIMEWA</td>
                    <td align="right"><b><i>{{ formatAngka($penjualan->potongan_istimewa) }}</i></b></td>
                </tr>
                <tr>
                    <td>PENYESUAIAN</td>
                    <td align="right"><b><i>{{ formatAngka($penjualan->penyesuaian) }}</i></b></td>
                </tr>
                <tr>
                    <td>PPN</td>
                    <td align="right"><b><i>{{ formatAngka($penjualan->ppn) }}</i></b></td>
                </tr>
                <tr>
                    <td>RETUR</td>
                    <td align="right"><b><i>{{ formatAngka($retur->total_retur) }}</i></b></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td><b>PENJUALAN BERSIH</b></td>
                    <td align="right">
                        @php
                            $total_netto =
                                $jumlahall -
                                $penjualan->potongan -
                                $penjualan->potongan_istimewa -
                                $penjualan->penyharga +
                                $penjualan->ppn -
                                $retur->total_retur -
                                $penjualan->penyesuaian;
                        @endphp
                        <b><i>{{ formatAngka($total_netto) }}</i></b>
                    </td>
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

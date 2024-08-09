<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Penjualan PO{{ date('Y-m-d H:i:s') }}</title>
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
            LAPORAN PENJUALAN PO <br>
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
            <table class="datatable3" style="width: 190%">
                <thead>
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">No. Faktur</th>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2" style="width: 6%">Nama Pelanggan</th>
                        <th rowspan="2">Nama Salesman</th>
                        <th rowspan="2">Hari</th>
                        <th rowspan="2">Klasifikasi</th>
                        <th rowspan="2">Wilayah</th>
                        <th colspan="{{ count($produk) }}">PRODUK</th>
                        <th rowspan="2">Bruto</th>
                        <th rowspan="2">Retur</th>
                        <th colspan="6" class="red">Potongan</th>
                        <th rowspan="2">Pot. Istimewa</th>
                        <th rowspan="2">Penyesuaian</th>
                        <th rowspan="2">PPN</th>
                        <th rowspan="2">Netto</th>
                        <th rowspan="2">T/K</th>
                        {{-- <th rowspan="2">Ket</th> --}}
                        <th rowspan="2">Status</th>


                    </tr>
                    <tr>
                        @foreach ($produk as $d)
                            <th>{{ $d->kode_produk }}</th>
                        @endforeach
                        <th class="red">AIDA</th>
                        <th class="red">SWAN</th>
                        <th class="red">STICK</th>
                        <th class="red">SP</th>
                        <th class="red">SC</th>
                        <th class="red">TOTAL</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $d)
                        @php
                            ${"total_$d->kode_produk"} = 0;
                        @endphp
                    @endforeach
                    @php
                        $grandtotal_bruto = 0;
                        $grandtotal_retur = 0;
                        $grandtotal_potongan_aida = 0;
                        $grandtotal_potongan_swan = 0;
                        $grandtotal_potongan_stick = 0;
                        $grandtotal_potongan_sp = 0;
                        $grandtotal_potongan_sc = 0;
                        $grandtotal_potongan = 0;
                        $grandtotal_potongan_istimewa = 0;
                        $grandtotal_penyesuaian = 0;
                        $grandtotal_ppn = 0;
                        $grandtotal_netto = 0;
                    @endphp
                    @foreach ($penjualan as $d)
                        @php
                            $total_potongan = $d->potongan_swan + $d->potongan_aida + $d->potongan_sp + $d->potongan_stick + $d->potongan_sambal;
                            $netto = $d->bruto - $d->total_retur - $d->potongan - $d->potongan_istimewa - $d->penyesuaian + $d->ppn;
                            $grandtotal_bruto += $d->bruto;
                            $grandtotal_retur += $d->total_retur;
                            $grandtotal_potongan_aida += $d->potongan_aida;
                            $grandtotal_potongan_swan += $d->potongan_swan;
                            $grandtotal_potongan_stick += $d->potongan_stick;
                            $grandtotal_potongan_sp += $d->potongan_sp;
                            $grandtotal_potongan_sc += $d->potongan_sambal;
                            $grandtotal_potongan += $d->potongan;
                            $grandtotal_potongan_istimewa += $d->potongan_istimewa;
                            $grandtotal_penyesuaian += $d->penyesuaian;
                            $grandtotal_ppn += $d->ppn;
                            $grandtotal_netto += $netto;
                            if ($d->status == '1') {
                                $color = 'green';
                            } else {
                                $color = 'red';
                            }
                        @endphp
                        @if (substr($d->no_fak_penj, 3, 2) == 'PR')
                            @php
                                $color = 'red';
                                $tgl_kirim = '';
                                $ket = 'Belum Di Proses';
                            @endphp
                        @else
                            @php
                                $color = 'green';
                                $tgl_kirim = '';
                                $ket = 'Sudah Di Proses';
                            @endphp
                        @endif
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
                                    $qty = $d->{"qty_$p->kode_produk"} / $p->isi_pcs_dus;
                                    ${"total_$p->kode_produk"} += $d->{"qty_$p->kode_produk"};
                                @endphp
                                <td class="center">{{ formatAngkaDesimal($qty) }}</td>
                            @endforeach
                            <td class="right">{{ formatAngka($d->bruto) }}</td>
                            <td class="right">{{ formatAngka($d->total_retur) }}</td>
                            <td class="right">{{ formatAngka($d->potongan_aida) }}</td>
                            <td class="right">{{ formatAngka($d->potongan_swan) }}</td>
                            <td class="right">{{ formatAngka($d->potongan_stick) }}</td>
                            <td class="right">{{ formatAngka($d->potongan_sp) }}</td>
                            <td class="right">{{ formatAngka($d->potongan_sambal) }}</td>
                            <td class="right">{{ formatAngka($total_potongan) }}</td>
                            <td class="right">{{ formatAngka($d->potongan_istimewa) }}</td>
                            <td class="right">{{ formatAngka($d->penyesuaian) }}</td>
                            <td class="right">{{ formatAngka($d->ppn) }}</td>
                            <td class="right">{{ formatAngka($netto) }}</td>
                            <td class="center";>
                                @if ($d->jenis_transaksi == 'T')
                                    TUNAI
                                @else
                                    KREDIT
                                @endif
                            </td>

                            {{-- <td style="background-color: {{ $color }}; color:white" class="center">
                                @php
                                    $status = $d->status == '1' ? 'L' : 'BL';
                                @endphp
                                {{ $status }}
                            </td> --}}
                            <td style="background-color: {{ $color }}; color:white" class="center">

                                {{ $ket }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="9">TOTAL</th>
                        @foreach ($produk as $p)
                            @php
                                ${"grandtotal_$p->kode_produk"} = ${"total_$p->kode_produk"} / $p->isi_pcs_dus;
                            @endphp
                            <th class="right">{{ formatAngkaDesimal(${"grandtotal_$p->kode_produk"}) }}</th>
                        @endforeach
                        <th class="right">{{ formatAngka($grandtotal_bruto) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_retur) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_potongan_aida) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_potongan_swan) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_potongan_stick) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_potongan_sp) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_potongan_sc) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_potongan) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_potongan_istimewa) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_penyesuaian) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_ppn) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_netto) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>

</html>
<script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 5,
        'shadow': true,
    });
</script>

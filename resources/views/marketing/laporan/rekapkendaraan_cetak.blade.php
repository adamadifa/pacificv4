<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Kendaraan {{ date('Y-m-d H:i:s') }}</title>
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
            LAPORAN KENDARAAN <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($kendaraan != null)
            <h4>
                {{ $kendaraan->no_polisi }} {{ $kendaraan->merek }} {{ $kendaraan->tipe }} {{ $kendaraan->tipe_kendaraan }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2" align="center">KODE PRODUK</th>
                        <th rowspan="2" align="center">NAMA PRODUK</th>
                        <th rowspan="2" align="center">PENGAMBILAN</th>
                        <th colspan="5" class="green">BARANG KELUAR</th>
                        <th rowspan="2" class="green">TOTAL</th>
                        <th rowspan="2" class="red">SISA</th>
                    </tr>
                    <tr>
                        <th align="center" class="green">PENJUALAN</th>
                        <th align="center" class="green">GANTI BARANG</th>
                        <th align="center" class="green">PROMOSI</th>
                        <th align="center" class="green">TTR</th>
                        <th align="center" class="green">PL HUTANG KIRIM</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_ambil = 0;
                        $total_penjualan = 0;
                        $total_gantibarang = 0;
                        $total_promosi = 0;
                        $total_ttr = 0;
                        $total_pelunasanhutangkirim = 0;
                        $total_barangkeluar = 0;
                        $total_sisa = 0;
                    @endphp
                    @foreach ($rekapkendaraan as $d)
                        @php
                            $jml_ambil = $d->jml_ambil / $d->isi_pcs_dus;
                            $jml_penjualan = $d->jml_penjualan / $d->isi_pcs_dus;
                            $jml_gantibarang = $d->jml_gantibarang / $d->isi_pcs_dus;
                            $jml_promosi = $d->jml_promosi / $d->isi_pcs_dus;
                            $jml_ttr = $d->jml_ttr / $d->isi_pcs_dus;
                            $jml_pelunasanhutangkirim = $d->jml_pelunasanhutangkirim / $d->isi_pcs_dus;

                            $jml_barangkeluar =
                                ($d->jml_penjualan + $d->jml_gantibarang + $d->jml_promosi + $d->jml_ttr + $d->jml_pelunasanhutangkirim) /
                                $d->isi_pcs_dus;
                            $sisa =
                                ($d->jml_ambil -
                                    $d->jml_penjualan -
                                    $d->jml_gantibarang -
                                    $d->jml_promosi -
                                    $d->jml_ttr -
                                    $d->jml_pelunasanhutangkirim) /
                                $d->isi_pcs_dus;

                            $total_ambil += $jml_ambil;
                            $total_penjualan += $jml_penjualan;
                            $total_gantibarang += $jml_gantibarang;
                            $total_promosi += $jml_promosi;
                            $total_ttr += $jml_ttr;
                            $total_pelunasanhutangkirim += $jml_pelunasanhutangkirim;
                            $total_barangkeluar += $jml_barangkeluar;
                            $total_sisa += $sisa;
                        @endphp
                        <tr>
                            <td>{{ $d->kode_produk }}</td>
                            <td>{{ $d->nama_produk }}</td>
                            <td class="right">{{ formatAngkaDesimal($jml_ambil) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jml_penjualan) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jml_gantibarang) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jml_promosi) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jml_ttr) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jml_pelunasanhutangkirim) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jml_barangkeluar) }}</td>
                            <td class="right">{{ formatAngkaDesimal($sisa) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <th colspan="2">TOTAL</th>
                    <th class="right">{{ formatAngkaDesimal($total_ambil) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_penjualan) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_gantibarang) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_promosi) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_ttr) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_pelunasanhutangkirim) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_barangkeluar) }}</th>
                    <th class="right">{{ formatAngkaDesimal($total_sisa) }}</th>
                </tfoot>
            </table>
        </div>
        <div style="margin-top: 20px; display:flex">
            <div>
                <table class="datatable3">
                    <thead>
                        <tr>
                            <th colspan="2">HISTORI KEBERANGKATAN</th>
                        </tr>
                        <tr>
                            <th>TANGGAL</th>
                            <th>JML PENGAMBILAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_hari = 0;
                            $total_pengambilan = 0;
                        @endphp
                        @foreach ($historikendaraan as $d)
                            @php
                                $total_hari++;
                                $total_pengambilan += $d->jml_ambil;
                            @endphp
                            <tr>
                                <td>{{ formatIndo($d->tanggal_ambil) }}</td>
                                <td class="center">{{ $d->jml_ambil }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th>{{ $total_hari }} Hari</th>
                        <th>{{ $total_pengambilan }} x Pengambilan</th>
                    </tfoot>
                </table>
            </div>
            <div style="margin-left: 20px">
                <table class="datatable3">
                    <thead>
                        <tr>
                            <th colspan="2">RATA RATA BARANG KELUAR</td>
                        </tr>
                        <tr>
                            <th class="left">TOTAL BARANG KELUAR</td>
                            <td align="right">{{ formatAngkaDesimal($total_barangkeluar) }}</td>
                        </tr>
                        <tr>
                            <th class="left">JUMLAH KEBERANGKATAN</th>
                            <td align="right">{{ formatAngkaDesimal($total_pengambilan) }}</td>
                        </tr>

                        <tr>
                            <th class="left">KAPASITAS</th>
                            <td align="right">{{ formatAngkaDesimal($kendaraan->kapasitas) }}</td>
                        </tr>
                        <tr>
                            <th class="left">RATA RATA</th>
                            <td align="right">
                                @php
                                    if (!empty($total_pengambilan)) {
                                        $rataratapengambilan = $total_barangkeluar / $total_pengambilan;
                                    } else {
                                        $rataratapengambilan = 0;
                                    }
                                    if (!empty($kendaraan->kapasitas)) {
                                        $persentase = ($rataratapengambilan / $kendaraan->kapasitas) * 100;
                                    } else {
                                        $persentase = 0;
                                    }
                                @endphp
                                {{ formatAngkaDesimal($rataratapengambilan) }} ( {{ formatAngkaDesimal($persentase) }} % )
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
            <div style="margin-left: 20px">
                <table class="datatable3">
                    <thead>
                        <tr>
                            <th colspan="2">RATA RATA PENJUALAN</td>
                        </tr>
                        <tr>
                            <th class="left">TOTAL PENJUALAN</td>
                            <td align="right">{{ formatAngkaDesimal($total_penjualan) }}</td>
                        </tr>
                        <tr>
                            <th class="left">JUMLAH KEBERANGKATAN</th>
                            <td align="right">{{ formatAngkaDesimal($total_pengambilan) }}</td>
                        </tr>

                        <tr>
                            <th class="left">KAPASITAS</th>
                            <td align="right">{{ formatAngkaDesimal($kendaraan->kapasitas) }}</td>
                        </tr>
                        <tr>
                            <th class="left">RATA RATA</th>
                            <td align="right">
                                @php
                                    if (!empty($total_penjualan)) {
                                        $rataratapenjualan = $total_penjualan / $total_pengambilan;
                                    } else {
                                        $rataratapenjualan = 0;
                                    }
                                    if (!empty($kendaraan->kapasitas)) {
                                        $persentase = ($rataratapenjualan / $kendaraan->kapasitas) * 100;
                                    } else {
                                        $persentase = 0;
                                    }
                                @endphp
                                {{ formatAngkaDesimal($rataratapenjualan) }} ( {{ formatAngkaDesimal($persentase) }} % )
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
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

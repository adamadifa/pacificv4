<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Faktur {{ $penjualan->no_faktur }} {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        body {
            letter-spacing: 0px;
            font-family: Calibri;
            font-size: 14px;
        }

        table {
            font-family: Tahoma;
            font-size: 14px;
        }

        .garis5,
        .garis5 td,
        .garis5 tr,
        .garis5 th {
            border: 2px solid black;
            border-collapse: collapse;

        }

        .table {
            border: solid 1px #000000;
            width: 100%;
            font-size: 12px;
            margin: auto;
        }

        .table th {
            border: 1px #000000;
            font-size: 12px;

            font-family: Arial;
        }

        .table td {
            border: solid 1px #000000;
        }
    </style>
</head>

<body>
    <table border="0" width="100%">
        <tr>
            <td style="width:10%">
                <table class="garis5">
                    <tr>
                        <td>FAKTUR</td>
                    </tr>
                    <tr>
                        <td>NOMOR {{ $penjualan->no_faktur }}</td>
                    </tr>
                </table>
            </td>
            <td colspan="6" align="left">
                @if ($penjualan->mp == '1')
                    <b>CV MAKMUR PERMATA </b><br>
                    <b>Jln. Perintis Kemerdekaan RT 001 / RW 003 Kelurahan Karsamenak Kecamatan Kawalu Kota Tasikmalaya
                        46182 <br>
                        NPWP : 863860342425000
                    </b>
                @else
                    <b>{{ textUpperCase($cabang->nama_pt) }} </b><br>
                    {{ textCamelCase($cabang->alamat_cabang) }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="7" align="center">
                <hr>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ DateToIndo($penjualan->tanggal) }}</td>
            <td style="width: 20%"></td>
            <td>Nama Customer</td>
            <td>:</td>
            <td>{{ $penjualan->nama_pelanggan }}</td>
        </tr>
        <tr>
            <td>Jenis Transaksi</td>
            <td>:</td>
            <td>{{ textUpperCase($penjualan->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT') }}</td>
            <td style="width: 20%"></td>
            <td>Alamat</td>
            <td>:</td>
            <td>
                @if (!empty($penjualan->alamat_toko))
                    {{ $penjualan->alamat_toko }}
                @else
                    {{ $penjualan->alamat_pelanggan }}
                @endif
                @if ($penjualan->kode_cabang == 'BDG')
                    ({{ $penjualan->nama_wilayah }})
                @endif
            </td>
        </tr>
    </table>
    <table class="garis5" width="100%" style="margin-top:30px">
        <thead>
            <tr style="padding: 10px">
                <th rowspan="2">NO</th>
                <th rowspan="2">KODE BARANG</th>
                <th rowspan="2">NAMA BARANG</th>
                <th rowspan="2">HARGA</th>
                <th colspan="3">JUMLAH</th>
                <th rowspan="2">TOTAL</th>
            </tr>
            <tr>
                <th>DUS</th>
                <th>PACK</th>
                <th>PCS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
            @endphp
            @foreach ($detail as $d)
                @php
                    $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                    $jumlah_dus = $jumlah[0];
                    $jumlah_pack = $jumlah[1];
                    $jumlah_pcs = $jumlah[2];
                    $subtotal += $d->subtotal;
                @endphp
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td align="left">{{ $d->kode_harga }}</td>
                    <td align="left">{{ $d->nama_produk }}</td>
                    <td align="right">{{ formatAngka($d->harga_dus) }}</td>
                    <td align="center">{{ formatAngka($jumlah_dus) }}</td>
                    <td align="center">{{ formatAngka($jumlah_pack) }}</td>
                    <td align="center">{{ formatAngka($jumlah_pcs) }}</td>
                    <td align="right">{{ formatAngka($d->subtotal) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"></td>
                <td colspan="3" align="center">Jumlah</td>
                <td align="right">{{ formatAngka($subtotal) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="3" align="center">Diskon</td>
                <td align="right">{{ formatAngka($penjualan->potongan) }}</td>
            </tr>
            @if ($penjualan->potongan_istimewa != 0)
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">Potongan Istimewa</td>
                    <td align="right">{{ formatAngka($penjualan->potongan_istimewa) }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="4"></td>
                <td colspan="3" align="center">Penyesuaian</td>
                <td align="right">{{ formatAngka($penjualan->penyesuaian) }}</td>
            </tr>
            @if (!empty($penjualan->ppn))
                @php
                    $dpp = $penjualan->total_bruto - $penjualan->potongan - $penjualan->penyesuaian - $penjualan->potongan_istimewa;
                @endphp
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">DPP</td>
                    <td align="right">
                        {{ formatAngka($dpp) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">PPN</td>
                    <td align="right">{{ formatAngka($penjualan->ppn) }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="4"></td>
                <td colspan="3" align="center">Retur</td>
                <td align="right">{{ formatAngka($penjualan->total_retur) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="3" align="center">Total Pembayaran</td>
                <td align="right">
                    @php
                        $total_netto =
                            $penjualan->total_bruto -
                            $penjualan->total_retur -
                            $penjualan->potongan -
                            $penjualan->potongan_istimewa -
                            $penjualan->penyesuaian +
                            $penjualan->ppn;
                    @endphp
                    {{ formatAngka($total_netto) }}
                </td>
            </tr>
            @if (auth()->user()->kode_cabang == 'BDG' || auth()->user()->kode_cabang == 'PST')
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">Terbilang</td>
                    <td align="right"><i>{{ ucwords(terbilang($total_netto)) }}</i></td>
                </tr>
            @endif
        </tbody>
    </table>
    <div style="page-break-before:always;"></div>
    <table border="0" width="100%">
        <tr>
            <td style="width:10%">
                <table class="garis5">
                    <tr>
                        <td>FAKTUR</td>
                    </tr>
                    <tr>
                        <td>NOMOR {{ $penjualan->no_faktur }}</td>
                    </tr>
                </table>
            </td>
            <td colspan="6" align="left">
                @if ($penjualan->mp == '1')
                    <b>CV MAKMUR PERMATA </b><br>
                    <b>Jln. Perintis Kemerdekaan RT 001 / RW 003 Kelurahan Karsamenak Kecamatan Kawalu Kota Tasikmalaya
                        46182 <br>
                        NPWP : 863860342425000
                    </b>
                @else
                    <b>{{ textUpperCase($cabang->nama_pt) }} </b><br>
                    {{ textCamelCase($cabang->alamat_cabang) }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="7" align="center">
                <hr>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ DateToIndo($penjualan->tanggal) }}</td>
            <td style="width: 20%"></td>
            <td>Nama Customer</td>
            <td>:</td>
            <td>{{ $penjualan->nama_pelanggan }}</td>
        </tr>
        <tr>
            <td>Jenis Transaksi</td>
            <td>:</td>
            <td>{{ textUpperCase($penjualan->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT') }}</td>
            <td style="width: 20%"></td>
            <td>Alamat</td>
            <td>:</td>
            <td>
                @if (!empty($penjualan->alamat_toko))
                    {{ $penjualan->alamat_toko }}
                @else
                    {{ $penjualan->alamat_pelanggan }}
                @endif
                @if ($penjualan->kode_cabang == 'BDG')
                    ({{ $penjualan->nama_wilayah }})
                @endif
            </td>
        </tr>
    </table>
    <table class="garis5" width="100%" style="margin-top:30px">
        <thead>
            <tr style="padding: 10px">
                <th rowspan="2">NO</th>
                <th rowspan="2">KODE BARANG</th>
                <th rowspan="2">NAMA BARANG</th>
                <th rowspan="2">HARGA</th>
                <th colspan="3">JUMLAH</th>
                <th rowspan="2">TOTAL</th>
            </tr>
            <tr>
                <th>DUS</th>
                <th>PACK</th>
                <th>PCS</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
            @endphp
            @foreach ($detail as $d)
                @php
                    $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                    $jumlah_dus = $jumlah[0];
                    $jumlah_pack = $jumlah[1];
                    $jumlah_pcs = $jumlah[2];
                    $subtotal += $d->subtotal;
                @endphp
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td align="left">{{ $d->kode_harga }}</td>
                    <td align="left">{{ $d->nama_produk }}</td>
                    <td align="right">{{ formatAngka($d->harga_dus) }}</td>
                    <td align="center">{{ formatAngka($jumlah_dus) }}</td>
                    <td align="center">{{ formatAngka($jumlah_pack) }}</td>
                    <td align="center">{{ formatAngka($jumlah_pcs) }}</td>
                    <td align="right">{{ formatAngka($d->subtotal) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"></td>
                <td colspan="3" align="center">Jumlah</td>
                <td align="right">{{ formatAngka($subtotal) }}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="3" align="center">Diskon</td>
                <td align="right">{{ formatAngka($penjualan->potongan) }}</td>
            </tr>


            @if (!empty($penjualan->ppn))
                @php
                    $dpp = $penjualan->total_bruto - $penjualan->potongan - $penjualan->penyesuaian - $penjualan->potongan_istimewa;
                @endphp
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">DPP</td>
                    <td align="right">
                        {{ formatAngka($dpp) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">PPN</td>
                    <td align="right">{{ formatAngka($penjualan->ppn) }}</td>
                </tr>
            @endif

            <tr>
                <td colspan="4"></td>
                <td colspan="3" align="center">Total Pembayaran</td>
                <td align="right">
                    @php
                        $total_netto =
                            $penjualan->total_bruto -
                            $penjualan->total_retur -
                            $penjualan->potongan -
                            $penjualan->potongan_istimewa -
                            $penjualan->penyesuaian +
                            $penjualan->ppn;
                    @endphp
                    {{ formatAngka($total_netto) }}
                </td>
            </tr>
            @if (auth()->user()->kode_cabang == 'BDG' || auth()->user()->kode_cabang == 'PST')
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">Terbilang</td>
                    <td align="right"><i>{{ ucwords(terbilang($total_netto)) }}</i></td>
                </tr>
            @endif
        </tbody>
    </table>
    @if (empty($penjualan->ppn))
        <div style="page-break-before:always;"></div>
        <table border="0" width="100%">
            <tr>
                <td style="width:10%">
                    <table class="garis5">
                        <tr>
                            <td>FAKTUR</td>
                        </tr>
                        <tr>
                            <td>NOMOR {{ $penjualan->no_faktur }}</td>
                        </tr>
                    </table>
                </td>
                <td colspan="6" align="left">
                    @if ($penjualan->mp == '1')
                        <b>CV MAKMUR PERMATA </b><br>
                        <b>Jln. Perintis Kemerdekaan RT 001 / RW 003 Kelurahan Karsamenak Kecamatan Kawalu Kota Tasikmalaya
                            46182 <br>
                            NPWP : 863860342425000
                        </b>
                    @else
                        <b>{{ textUpperCase($cabang->nama_pt) }} </b><br>
                        {{ textCamelCase($cabang->alamat_cabang) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="7" align="center">
                    <hr>
                </td>
            </tr>
        </table>
        <table style="width: 100%">
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ DateToIndo($penjualan->tanggal) }}</td>
                <td style="width: 20%"></td>
                <td>Nama Customer</td>
                <td>:</td>
                <td>{{ $penjualan->nama_pelanggan }}</td>
            </tr>
            <tr>
                <td>Jenis Transaksi</td>
                <td>:</td>
                <td>{{ textUpperCase($penjualan->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT') }}</td>
                <td style="width: 20%"></td>
                <td>Alamat</td>
                <td>:</td>
                <td>
                    @if (!empty($penjualan->alamat_toko))
                        {{ $penjualan->alamat_toko }}
                    @else
                        {{ $penjualan->alamat_pelanggan }}
                    @endif
                    @if ($penjualan->kode_cabang == 'BDG')
                        ({{ $penjualan->nama_wilayah }})
                    @endif
                </td>
            </tr>
        </table>
        <table class="garis5" width="100%" style="margin-top:30px">
            <thead>
                <tr style="padding: 10px">
                    <th rowspan="2">NO</th>
                    <th rowspan="2">KODE BARANG</th>
                    <th rowspan="2">NAMA BARANG</th>
                    <th rowspan="2">HARGA</th>
                    <th colspan="3">JUMLAH</th>
                    <th rowspan="2">TOTAL</th>
                </tr>
                <tr>
                    <th>DUS</th>
                    <th>PACK</th>
                    <th>PCS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandtotal = 0;
                @endphp
                @foreach ($detail as $d)
                    @php
                        $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                        $jumlah_dus = $jumlah[0];
                        $jumlah_pack = $jumlah[1];
                        $jumlah_pcs = $jumlah[2];
                        $subtotal += $d->subtotal;

                        $harga_dus_dpp = (100 / 111) * $d->harga_dus;
                        $harga_pack_dpp = (100 / 111) * $d->harga_pack;
                        $harga_pcs_dpp = (100 / 111) * $d->harga_pcs;

                        $subtotal_dus_dpp = $jumlah_dus * $harga_dus_dpp;
                        $subtotal_pack_dpp = $jumlah_pack * $harga_pack_dpp;
                        $subtotal_pcs_dpp = $jumlah_pcs * $harga_pcs_dpp;
                        $subtotal = $subtotal_dus_dpp + $subtotal_pcs_dpp + $subtotal_pcs_dpp;

                        $grandtotal += $subtotal;
                        $potongan_dpp = (100 / 111) * $penjualan->potongan;
                        $dpp = $subtotal - $potongan_dpp - $penjualan->penyharga - $penjualan->potistimewa;
                        $ppn = (11 / 100) * $dpp;
                    @endphp
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td align="left">{{ $d->kode_harga }}</td>
                        <td align="left">{{ $d->nama_produk }}</td>
                        <td align="right">{{ formatAngka($harga_dus_dpp) }}</td>
                        <td align="center">{{ formatAngka($jumlah_dus) }}</td>
                        <td align="center">{{ formatAngka($jumlah_pack) }}</td>
                        <td align="center">{{ formatAngka($jumlah_pcs) }}</td>
                        <td align="right">{{ formatAngka($subtotal) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">Jumlah</td>
                    <td align="right">{{ formatAngka($grandtotal) }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">Diskon</td>
                    <td align="right">{{ formatAngka($potongan_dpp) }}</td>
                </tr>
                @if ($penjualan->potongan_istimewa != 0)
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="3" align="center">Potongan Istimewa</td>
                        <td align="right">{{ formatAngka($penjualan->potongan_istimewa) }}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">Penyesuaian</td>
                    <td align="right">{{ formatAngka($penjualan->penyesuaian) }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">DPP</td>
                    <td align="right">
                        {{ formatAngka($dpp) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">PPN</td>
                    <td align="right">{{ formatAngka($ppn) }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">Retur</td>
                    <td align="right">{{ formatAngka($penjualan->total_retur) }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="3" align="center">Total Pembayaran</td>
                    <td align="right">
                        @php
                            $total_netto =
                                $penjualan->total_bruto -
                                $penjualan->total_retur -
                                $penjualan->potongan -
                                $penjualan->potongan_istimewa -
                                $penjualan->penyesuaian +
                                $penjualan->ppn;
                        @endphp
                        {{ formatAngka($total_netto) }}
                    </td>
                </tr>
                @if (auth()->user()->kode_cabang == 'BDG' || auth()->user()->kode_cabang == 'PST')
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="3" align="center">Terbilang</td>
                        <td align="right"><i>{{ ucwords(terbilang($total_netto)) }}</i></td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif

</body>

</html>

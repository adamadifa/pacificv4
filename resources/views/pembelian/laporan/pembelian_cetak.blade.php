<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Pembelian {{ date('Y-m-d H:i:s') }}</title>
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
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN PEMBELIAN<br>
        </h4>
        <h4> PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($supplier != null)
            <h4>
                {{ $supplier->kode_supplier }} - {{ $supplier->nama_supplier }}
            </h4>
        @endif
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 125%">
                <thead>
                    <tr>
                        <th style="width:1%">NO</th>
                        <th style="width:4%">TGL</th>
                        <th style="width:4%">NO BUKTI</th>
                        <th style="width:10%">SUPPLIER</th>
                        <th style="width:10%">NAMA BARANG</th>
                        <th style="width:10%">KETERANGAN</th>
                        <th style="width:2%">JT</th>
                        <th style="width:2%">PCF/MP</th>
                        <th style="width:3%">AKUN</th>
                        <th style="width:8%">JURNAL</th>
                        <th style="width:2%">PPN</th>
                        <th style="width:4%">QTY</th>
                        <th style="width:5%">HARGA</th>
                        <th style="width:5%">SUBTOTAL</th>
                        <th style="width: 3%">PENY</th>
                        <th>TOTAL</th>
                        <th>DEBET</th>
                        <th>KREDIT</th>
                        <th>KATEGORI</th>
                        <th style="width: 5%">DIBUAT</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal_transaksi = 0;
                        $total_debet = 0;
                        $total_kredit = 0;
                        $total_dk = 0;
                        $grandtotal = 0;
                    @endphp
                    @foreach ($pembelian as $key => $d)
                        @php
                            $no_bukti = @$pembelian[$key + 1]->no_bukti;
                            $subtotal = ROUND($d->jumlah * $d->harga, 2);
                            $total = $subtotal + $d->penyesuaian;
                            if ($d->ppn == '1') {
                                $cekppn = '&#10004;';
                                $bgcolor = '#ececc8';
                                // $dpp = (100 / 110) * $totalharga;
                                // $ppn = (10 / 100) * $dpp;
                            } else {
                                $bgcolor = '';
                                $cekppn = '';
                                // $dpp = '';
                                // $ppn = '';
                            }

                            if ($d->kode_transaksi == 'PNJ') {
                                $totalharga = -$total;
                                $debet = 0;
                                $kredit = $total;
                                $namabarang = $d->ket_penjualan;
                            } else {
                                $totalharga = $total;
                                $debet = $total;
                                $kredit = 0;
                                $namabarang = $d->nama_barang;
                            }

                            if ($d->kode_asal_pengajuan != 'GDB') {
                                $akun = '2-1300';
                                $namaakun = 'Hutang Lainnya';
                            } else {
                                $akun = '2-1200';
                                $namaakun = 'Hutang Dagang';
                            }
                            $subtotal_transaksi += $totalharga;
                            $total_debet += $debet;
                            $total_kredit += $kredit;
                            $total_dk += $totalharga;

                            $grandtotal += $total;
                        @endphp
                        <tr style="background-color: {{ $bgcolor }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_bukti }}</td>
                            <td>{{ $d->nama_supplier }}</td>
                            <td>{{ $namabarang }}</td>
                            <td>{{ $d->keterangan ?? $d->keterangan_penjualan }}</td>
                            <td class="center">{{ $d->jenis_transaksi }}</td>
                            <td class="center">{{ $d->kode_cabang }}</td>
                            <td class="center">'{{ $d->kode_akun }}</td>
                            <td>{{ $d->nama_akun }}</td>
                            <td class="center">{!! $cekppn !!}</td>
                            <td class="right">{{ formatAngkaDesimal($d->jumlah) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->harga) }}</td>
                            <td class="right">{{ formatAngkaDesimal($subtotal) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->penyesuaian) }}</td>
                            <td class="right">{{ formatAngkaDesimal($total) }}</td>
                            <td class="right">{{ formatAngkaDesimal($debet) }}</td>
                            <td class="right">{{ formatAngkaDesimal($kredit) }}</td>
                            <td class="center">{{ $d->kategori_transaksi }}</td>
                            <td>{{ date('d-m-Y H:i', strtotime($d->created_at)) }}</td>

                        </tr>

                        @if ($no_bukti != $d->no_bukti)
                            <tr bgcolor="#a7efe4" style="color:black; font-weight:bold">
                                <td></td>
                                <td></td>
                                <td>{{ $d->no_bukti }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="center">{{ $d->jenis_transaksi }}</td>
                                <td></td>
                                <td class="center">{{ $akun }}</td>
                                <td>{{ $namaakun }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="right">{{ formatAngkaDesimal($subtotal_transaksi) }}</td>
                                <td></td>
                                <td></td>

                            </tr>
                            @php
                                $subtotal_transaksi = 0;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th colspan="14" align="center"><b>TOTAL</b></td>
                        <th align="right"><b></b></td>
                        <th class="right">{{ formatAngkaDesimal($grandtotal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_debet) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_kredit + $total_dk) }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
<script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 10,
        'shadow': true,
    });
</script>

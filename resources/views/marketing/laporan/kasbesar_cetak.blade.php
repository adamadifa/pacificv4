<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Kas Besar Penjualan {{ date('Y-m-d H:i:s') }}</title>
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
            LAPORAN KAS BESAR PENJUALAN <br>
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
            <table class="datatable3" style="width: 150%">
                <thead>
                    <tr>
                        <th rowspan="2">Tgl</th>
                        <th rowspan="2">No Faktur</th>
                        <th rowspan="2">JT</th>
                        <th rowspan="2">Tgl Faktur</th>
                        <th rowspan="2">LJT</th>
                        <th rowspan="2">Salesman</th>
                        <th rowspan="2">(Penagih)</th>
                        <th rowspan="2">Kode Pel.</th>
                        <th rowspan="2" style="width: 8%">Nama Pelanggan</th>
                        <th rowspan="2">Wilayah</th>
                        <th rowspan="2">TUNAI</th>
                        <th rowspan="2">TITIP BAYAR</th>
                        <th rowspan="2">TAGIHAN</th>
                        <th colspan="3">Giro/Transfer</th>
                        <th rowspan="2">Total</th>
                        <th rowspan="2">Saldo Akhir</th>
                        <th rowspan="2">Keterangan</th>
                        <th rowspan="2">Create</th>
                        <th rowspan="2">Update</th>
                        <th rowspan="2">User</th>
                    </tr>
                    <tr>
                        <th>Cek/BG</th>
                        <th>Nama Bank</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $saldo = 0;
                        $total_tunai = 0;
                        $total_titipan = 0;
                        $total_tagihan = 0;
                        $total_girotransfer = 0;
                        $total_bayar = 0;
                        $total_ljt = 0;

                    @endphp
                    @foreach ($kasbesar as $d)
                        @php
                            $bgcolorljt = $d->ljt > 15 ? 'text-red' : '';

                            $total_penjualan = $d->total_bruto - $d->potongan - $d->penyesuaian - $d->potongan_istimewa - $d->total_retur + $d->ppn;

                            if ($d->jenis_bayar == 'TN') {
                                $tunai = $d->jmlbayar;
                                $tagihan = 0;
                                $titipan = 0;
                                $girotransfer = 0;
                                $no_giro = '';
                                $bankpengirim = '';
                                $bayar = $tunai;
                            } elseif ($d->jenis_bayar == 'TP') {
                                if ($d->totalbayar >= $total_penjualan) {
                                    $tunai = 0;
                                    $tagihan = $d->jmlbayar;
                                    $titipan = 0;
                                    $girotransfer = 0;
                                    $no_giro = '';
                                    $bankpengirim = '';
                                    $bayar = $tagihan;
                                } else {
                                    $tunai = 0;
                                    $tagihan = 0;
                                    $titipan = $d->jmlbayar;
                                    $girotransfer = 0;
                                    $no_giro = '';
                                    $bankpengirim = '';
                                    $bayar = $titipan;
                                }
                            } else {
                                $tunai = 0;
                                $tagihan = 0;
                                $titipan = 0;
                                $girotransfer = $d->jmlbayar;

                                if (!empty($d->no_giro)) {
                                    $no_giro = $d->no_giro;
                                    $bankpengirim = $d->bank_pengirim_giro;
                                } else {
                                    $no_giro = 'TRANSFER';
                                    $bankpengirim = $d->bank_pengirim_transfer;
                                }
                                $bayar = $girotransfer;
                            }

                            $saldo += $bayar;
                            $total_tunai += $tunai;
                            $total_titipan += $titipan;
                            $total_tagihan += $tagihan;
                            $total_girotransfer += $girotransfer;
                            $total_bayar += $bayar;
                            if ($d->ljt > 15) {
                                $total_ljt += $bayar;
                            }
                        @endphp
                        <tr>
                            <td>{{ formatIndo($d->tglbayar) }}</td>
                            <td>{{ $d->no_faktur }}</td>
                            <td>{{ $d->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}</td>
                            <td>{{ formatIndo($d->tgltransaksi) }}</td>
                            <td class="center {{ $bgcolorljt }}">{{ $d->ljt }}</td>
                            <td>{{ $d->nama_salesman }}</td>
                            <td>{{ $d->penagih }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ textUpperCase($d->nama_pelanggan) }}</td>
                            <td>{{ $d->nama_wilayah }}</td>
                            <td class="right">{{ formatAngka($tunai) }}</td>
                            <td class="right">{{ formatAngka($titipan) }}</td>
                            <td class="right">{{ formatAngka($tagihan) }}</td>
                            <td>{{ $no_giro }}</td>
                            <td>{{ textUpperCase($bankpengirim) }}</td>
                            <td class="right">{{ formatAngka($girotransfer) }}</td>
                            <td class="right">{{ formatAngka($bayar) }}</td>
                            <td class="right">{{ formatAngka($saldo) }}</td>
                            <td>{{ $d->giro_to_cash == '1' ? 'Penggantian Giro Ke Cash' : '' }}</td>
                            <td>{{ date('Y-m-d H:i:s', strtotime($d->created_at)) }}</td>
                            <td>{{ !empty($d->updated_at) ? date('Y-m-d H:i:s', strtotime($d->updated_at)) : '' }}</td>
                            <td>{{ $d->nama_user }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="10">TOTAL</th>
                        <th class="right">{{ formatAngka($total_tunai) }}</th>
                        <th class="right">{{ formatAngka($total_titipan) }}</th>
                        <th class="right">{{ formatAngka($total_tagihan) }}</th>
                        <th></th>
                        <th></th>
                        <th class="right">{{ formatAngka($total_girotransfer) }}</th>
                        <th class="right">{{ formatAngka($total_bayar) }}</th>
                        <th class="right">{{ formatAngka($saldo) }}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>

                </tfoot>
            </table>

        </div>
        <div style="margin-top: 20px">
            <table class="datatable3">
                <tr>
                    <td style="font-weight: bold">TOTAL PEMBAYARAN LEBIH 14 HARI DARI TANGGAL TRANSAKSI</td>
                    <td style="font-weight: bold; background:red; color:white">{{ formatAngka($total_ljt) }}</td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 20px">
            <b style="font-size:14px; font-family:Calibri">
                PEMBAYARAN VOUCHER<br>
                PERIODE <?php echo DateToIndo($dari) . ' s/d ' . DateToIndo($sampai); ?><br>
            </b>
        </div>

        <div style="margin-top: 20px">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>No Faktur</th>
                        <th>Kode Pelanggan</th>
                        <th>Nama Pelanggan</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_voucher = 0;
                    @endphp
                    @foreach ($voucher as $d)
                        @php
                            $total_voucher += $d->jmlbayar;
                        @endphp
                        <tr>
                            <td>{{ formatIndo($d->tglbayar) }}</td>
                            <td>{{ $d->no_faktur }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ textUpperCase($d->nama_pelanggan) }}</td>
                            <td>{{ $d->nama_voucher }}</td>
                            <td class="right">{{ formatAngka($d->jmlbayar) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="5">TOTAL</th>
                        <th class="right">{{ formatAngka($total_voucher) }}</th>
                    </tr>
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

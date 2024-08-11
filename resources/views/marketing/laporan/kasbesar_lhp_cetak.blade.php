<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Kas Besar LHP {{ date('Y-m-d H:i:s') }}</title>
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

        .text-green {
            background-color: green;
            color: white;
        }

        .text-orange {
            background-color: orange;
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
            LAPORAN KAS BESAR PENJUALAN LHP<br>
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
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">No Faktur</th>
                        <th rowspan="2">Kode Pel.</th>
                        <th rowspan="2">Nama Pelanggan</th>
                        <th rowspan="2">TUNAI</th>
                        <th rowspan="2">TAGIHAN</th>
                        <th rowspan="2">GANTI GIRO KE CASH</th>

                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_tunai = 0;
                        $total_tagihan = 0;
                        $total_giro_to_cash = 0;
                    @endphp
                    @foreach ($kasbesar as $d)
                        @php
                            if ($d->jenis_bayar == 'TN') {
                                $tunai = $d->jmlbayar;
                                $tagihan = 0;
                                $giro_to_cash = 0;
                            } elseif ($d->giro_to_cash == '1') {
                                $tunai = 0;
                                $tagihan = 0;
                                $giro_to_cash = $d->jmlbayar;
                            } else {
                                $tunai = 0;
                                $tagihan = $d->jmlbayar;
                                $giro_to_cash = 0;
                            }

                            $total_tunai += $tunai;
                            $total_tagihan += $tagihan;
                            $total_giro_to_cash += $giro_to_cash;

                        @endphp
                        <tr>
                            <td>{{ formatIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_faktur }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ textUpperCase($d->nama_pelanggan) }}</td>
                            <td class="right">{{ formatAngka($tunai) }}</td>
                            <td class="right">{{ formatAngka($tagihan) }}</td>
                            <td class="right">{{ formatAngka($giro_to_cash) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">TOTAL</th>
                        <th class="right">{{ formatAngka($total_tunai) }}</th>
                        <th class="right">{{ formatAngka($total_tagihan) }}</th>
                        <th class="right">{{ formatAngka($total_giro_to_cash) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div style="margin-toop: 20px">
        <h4>LIST GIRO TANGGAL : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div style="margin-toop: 20px">
        <table class="datatable3">
            <thead>
                <tr>
                    <th>No Giro</th>
                    <th>Tgl Giro</th>
                    <th>No Faktur</th>
                    <th>Kode Pel.</th>
                    <th>Nama Pelanggan</th>
                    <th>Nama Bank</th>
                    <th>Jumlah</th>
                    <th>Jatuh tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_giro = 0;
                @endphp
                @foreach ($kasbesargiro as $d)
                    @php
                        $total_giro += $d->jmlbayar;
                        if ($d->status === '0') {
                            $bgcolor = 'text-orange';
                            $keterangan = 'Pending';
                        } elseif ($d->status == '1') {
                            $bgcolor = 'text-green';
                            $keterangan = 'Diterima';
                        } else {
                            $bgcolor = 'text-red';
                            $keterangan = 'Ditolak';
                        }
                    @endphp
                    <tr>
                        <td>{{ $d->no_giro }}</td>
                        <td>{{ formatIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_faktur }}</td>
                        <td>{{ $d->kode_pelanggan }}</td>
                        <td>{{ textUpperCase($d->nama_pelanggan) }}</td>
                        <td>{{ textUpperCase($d->bank_pengirim) }}</td>
                        <td class="right">{{ formatAngka($d->jmlbayar) }}</td>
                        <td>{{ formatIndo($d->jatuh_tempo) }}</td>
                        <td class="{{ $bgcolor }}">{{ $keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">TOTAL</th>
                    <th class="right">{{ formatAngka($total_giro) }}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div style="margin-toop: 20px">
        <h4>LIST TRANSFER TANGGAL : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div style="margin-toop: 20px">
        <table class="datatable3">
            <thead>
                <tr>
                    <th>Kode Transfer</th>
                    <th>Tanggal</th>
                    <th>No Faktur</th>
                    <th>Kode Pel.</th>
                    <th>Nama Pelanggan</th>
                    <th>Nama Bank</th>
                    <th>Jatuh tempo</th>
                    <th>Jumlah</th>
                    <th>Ganti Giro Ke Transfer</th>
                    <th>Status</th>
                    <th>Ket</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_transfer = 0;
                    $total_giro_to_transfer = 0;
                @endphp
                @foreach ($kasbesartransfer as $d)
                    @php

                        if ($d->status === '0') {
                            $bgcolor = 'text-orange';
                            $keterangan = 'Pending';
                        } elseif ($d->status == '1') {
                            $bgcolor = 'text-green';
                            $keterangan = 'Diterima';
                        } else {
                            $bgcolor = 'text-red';
                            $keterangan = 'Ditolak';
                        }

                        if ($d->giro_to_cash == '1') {
                            $jmltransfer = 0;
                            $giro_to_transfer = $d->jmlbayar;
                        } else {
                            $jmltransfer = $d->jmlbayar;
                            $giro_to_transfer = 0;
                        }

                        $total_transfer += $jmltransfer;
                        $total_giro_to_transfer += $giro_to_transfer;
                    @endphp
                    <tr>
                        <td>{{ $d->kode_transfer }}</td>
                        <td>{{ formatIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_faktur }}</td>
                        <td>{{ $d->kode_pelanggan }}</td>
                        <td>{{ textUpperCase($d->nama_pelanggan) }}</td>
                        <td>{{ textUpperCase($d->bank_pengirim) }}</td>
                        <td>{{ formatIndo($d->jatuh_tempo) }}</td>
                        <td class="right">{{ formatAngka($jmltransfer) }}</td>
                        <td class="right">{{ formatAngka($giro_to_transfer) }}</td>
                        <td class="{{ $bgcolor }}">{{ $keterangan }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7">TOTAL</th>
                    <th class="right">{{ formatAngka($total_transfer) }}</th>
                    <th class="right">{{ formatAngka($total_giro_to_transfer) }}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div style="margin-top: 20px">
        <h4>PEMBAYARAN VOUCHER : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
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
                @foreach ($kasbesarvoucher as $d)
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
    <div style="margin-top: 20px">
        <table class="datatable3">

            <tr>
                <th class="left">Penjualan Tunai</th>
                <td style="text-align: right; font-size:12px; font-weight:bold">{{ formatAngka($total_tunai) }}</td>
            </tr>
            <tr>
                <th class="left">Tagihan</th>
                <td style="text-align: right; font-size:12px; font-weight:bold">{{ formatAngka($total_tagihan) }}</td>
            </tr>
            <tr>
                <th class="left">Voucher</th>
                <td style="text-align: right; font-size:12px; font-weight:bold">{{ formatAngka($total_voucher) }}</td>
            </tr>
            <tr>
                <th class="left">Giro</th>
                <td style="text-align: right; font-size:12px; font-weight:bold">{{ formatAngka($total_giro) }}</td>
            </tr>
            <tr>
                <th class="left">Transfer</th>
                <td style="text-align: right; font-size:12px; font-weight:bold">{{ formatAngka($total_transfer) }}</td>
            </tr>
            <tr>
                <th class="left">Ganti Giro Ke Cash</th>
                <td style=" background-color:red; color:white; text-align: right; font-size:12px; font-weight:bold">
                    {{ formatAngka($total_giro_to_cash) }}
                </td>
            </tr>
            <tr>
                <th class="left">Ganti Giro Ke Transfer</th>
                <td style=" background-color:red; color:white; text-align: right; font-size:12px; font-weight:bold">
                    {{ formatAngka($total_giro_to_transfer) }}
                </td>
            </tr>
            <tr>
                <th class="left">TOTAL</th>
                <td style="background-color:green; color:white; text-align: right; font-size:12px; font-weight:bold">
                    @php
                        $total_summary = $total_tunai + $total_tagihan + $total_voucher + $total_giro + $total_transfer;
                    @endphp
                    {{ formatAngka($total_summary) }}
                </td>
            </tr>

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

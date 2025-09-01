<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LHP {{ formatIndo($tanggal) }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&display=swap');

        .sheet.padding-5mm {
            padding: 5mm
        }

        body.A4 .sheet {
            height: auto !important;
            width: 290mm !important;
        }

        .sheet {
            overflow: auto !important;
        }

        @page {
            size: A4
        }

        .header {
            display: flex;
            justify-content: space-between;
            border: 1px solid black;
        }

        .centerheader {
            line-height: 0;
        }

        .centerheader h3 {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 0;
        }

        .centerheader h5 {
            text-align: center;
            text-transform: uppercase;
        }
    </style>
</head>

<body class="A4">
    <section class="sheet padding-5mm">
        <div class="header">
            <div class="leftheader"></div>
            <div class="centerheader">
                <h3>{{ $cabang->nama_pt }}</h3>
                <h5>LAPORAN HARIAN PENJUALAN</h5>
            </div>
            <div class="rightheader"></div>
        </div>
        <div class="subtitle" style="display:flex; justify-content: space-between; margin-top:20px">
            <div class="rute">
                Rute : ___________________________
            </div>
            <div class="tanggal">
                {{ DateToIndo($tanggal) }}
            </div>
        </div>
        <div class="content" style="margin-top: 20px">
            <table class="datatable5">
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">PELANGGAN</th>
                        <th rowspan="2">NO. FAKTUR</th>
                        @if (!$produk->isEmpty())
                            <th colspan="{{ count($produk) }}">PRODUK</th>
                        @else
                            <th rowspan="2">PRODUK</th>
                        @endif
                        <th colspan="2">PENJUALAN</th>
                        <th rowspan="2">TITIPAN</th>
                        <th rowspan="2">TRANSFER</th>
                        <th rowspan="2">GIRO</th>
                        <th rowspan="2">VOUCHER</th>
                    </tr>


                    <tr>

                        @foreach ($produk as $d)
                            <th>{{ $d->kode_produk }}</th>
                        @endforeach
                        <th>TUNAI</th>
                        <th>KREDIT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $d)
                        @php
                            ${"total_qty_$d->kode_produk"} = 0;
                            ${"total_qty_batal_$d->kode_produk"} = 0;
                        @endphp
                    @endforeach
                    @php
                        $total_tunai = 0;
                        $total_titipan = 0;
                        $total_transfer = 0;
                        $total_giro = 0;
                        $total_voucher = 0;
                        $total_kredit = 0;

                        $total_tunai_batal = 0;
                        $total_titipan_batal = 0;
                        $total_transfer_batal = 0;
                        $total_giro_batal = 0;
                        $total_voucher_batal = 0;
                        $total_kredit_batal = 0;
                    @endphp
                    @foreach ($lhp as $d)
                        @php

                            if ($d['status_batal'] == 1) {
                                $color = 'red';
                                $total_tunai_batal += $d['jml_tunai'];
                                $total_titipan_batal += $d['jml_titipan'];
                                $total_voucher_batal += $d['jml_voucher'];
                                $total_kredit_batal += $d['jml_kredit'];
                                $total_transfer_batal += $d['jml_transfer'];
                                $total_giro_batal += $d['jml_giro'];

                                $total_tunai += 0;
                                $total_titipan += 0;
                                $total_voucher += 0;
                                $total_kredit += 0;
                                $total_transfer += 0;
                                $total_giro += 0;
                            } else {
                                $color = '';
                                $total_tunai += $d['jml_tunai'];
                                $total_titipan += $d['jml_titipan'];
                                $total_voucher += $d['jml_voucher'];
                                $total_kredit += $d['jml_kredit'];
                                $total_transfer += $d['jml_transfer'];
                                $total_giro += $d['jml_giro'];

                                $total_tunai_batal += 0;
                                $total_titipan_batal += 0;
                                $total_voucher_batal += 0;
                                $total_kredit_batal += 0;
                                $total_transfer_batal += 0;
                                $total_giro_batal += 0;
                            }
                        @endphp
                        <tr style="background-color: {{ $color }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d['nama_pelanggan'] }}</td>
                            <td>{{ $d['no_faktur'] }}</td>
                            @if (!$produk->isEmpty())
                                @foreach ($produk as $p)
                                    @php
                                        if ($d['status_batal'] == 1) {
                                            ${"total_qty_batal_$p->kode_produk"} += $d["qty_$p->kode_produk"];
                                        } else {
                                            ${"total_qty_$p->kode_produk"} += $d["qty_$p->kode_produk"];
                                        }
                                        $qty = $d["qty_$p->kode_produk"] / $p->isi_pcs_dus;
                                    @endphp
                                    <td class="center">{{ formatAngkaDesimal($qty) }}</td>
                                @endforeach
                            @else
                                <td class="center" style="background-color: red"></td>
                            @endif

                            <td class="right">{{ formatAngka($d['jml_tunai']) }}</td>
                            <td class="right">{{ formatAngka($d['jml_kredit']) }}</td>
                            <td class="right">{{ formatAngka($d['jml_titipan']) }}</td>
                            <td class="right">{{ formatAngka($d['jml_transfer']) }}</td>
                            <td class="right">{{ formatAngka($d['jml_giro']) }}</td>
                            <td class="right">{{ formatAngka($d['jml_voucher']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">TERJUAL</th>
                        @if (!$produk->isEmpty())
                            @foreach ($produk as $d)
                                @php
                                    $total_qty = ${"total_qty_$d->kode_produk"} / $d->isi_pcs_dus;
                                @endphp
                                <th class="center">{{ formatAngkaDesimal($total_qty) }}</th>
                            @endforeach
                        @else
                            <th style="background-color: red"></th>
                        @endif
                        <th class="right">{{ formatAngka($total_tunai) }}</th>
                        <th class="right">{{ formatAngka($total_kredit) }}</th>
                        <th class="right">{{ formatAngka($total_titipan) }}</th>
                        <th class="right">{{ formatAngka($total_transfer) }}</th>
                        <th class="right">{{ formatAngka($total_giro) }}</th>
                        <th class="right">{{ formatAngka($total_voucher) }}</th>
                    </tr>
                    <tr>
                        <th colspan="3">BATAL</th>
                        @if (!$produk->isEmpty())
                            @foreach ($produk as $d)
                                @php
                                    $total_qty = ${"total_qty_batal_$d->kode_produk"} / $d->isi_pcs_dus;
                                @endphp
                                <th class="center">{{ formatAngkaDesimal($total_qty) }}</th>
                            @endforeach
                        @else
                            <th style="background-color: red"></th>
                        @endif
                        <th class="right">{{ formatAngka($total_tunai_batal) }}</th>
                        <th class="right">{{ formatAngka($total_kredit_batal) }}</th>
                        <th class="right">{{ formatAngka($total_titipan_batal) }}</th>
                        <th class="right">{{ formatAngka($total_transfer_batal) }}</th>
                        <th class="right">{{ formatAngka($total_giro_batal) }}</th>
                        <th class="right">{{ formatAngka($total_voucher_batal) }}</th>

                    </tr>
                    <tr>
                        <th colspan="3">BS</th>
                        @if (!$produk->isEmpty())
                            @foreach ($produk as $d)
                                <th></th>
                            @endforeach
                        @else
                            <th style="background-color: red"></th>
                        @endif
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="footer" style="display: flex; margin-top: 20px;">
            <div class="detailproduk" style="width: 30%">
                <table class="datatable5">
                    <thead>
                        <tr>
                            <th rowspan="2">KODE</th>
                            <th rowspan="2">PRODUK</th>
                            <th colspan="3">QTY</th>
                        </tr>
                        <tr>
                            <th>Dus</th>
                            <th>Pack</th>
                            <th>Pcs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detailproduk as $d)
                            @php
                                $qty = convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->qty);
                                $jml = explode('|', $qty);
                                $dus = $jml[0];
                                $pack = $jml[1];
                                $pcs = $jml[2];
                            @endphp
                            <tr>
                                <td>{{ $d->kode_produk }}</td>
                                <td>{{ $d->nama_produk }}</td>
                                <td class="center">{{ formatAngka($dus) }}</td>
                                <td class="center">{{ formatAngka($pack) }}</td>
                                <td class="center">{{ formatAngka($pcs) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="detailuang" style="display: flex; border: 1px solid black; margin-left: 20px; width:70%">
                <div class="detailuangleft" style="width: 50%">
                    <table class="datatable4" style="width: 100%">
                        <tr>
                            <td>Uang Kertas</td>
                            <td>:</td>
                            <td>Rp.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Uang Logam</td>
                            <td>:</td>
                            <td>Rp.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Cek / BG</td>
                            <td>:</td>
                            <td>Rp.</td>
                            <td class="right" style="font-weight: bold">{{ formatAngka($total_giro) }}</td>
                        </tr>
                        <tr>
                            <td>Transfer</td>
                            <td>:</td>
                            <td>Rp.</td>
                            <td class="right" style="font-weight: bold">{{ formatAngka($total_transfer) }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah</td>
                            <td>:</td>
                            <td>Rp.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Setor</td>
                            <td>:</td>
                            <td>Rp.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Selisih</td>
                            <td>:</td>
                            <td>Rp.</td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="detailuangright" style="width: 50%">
                    <table class="datatable4" style="width: 100%">
                        <tr>
                            <td>Penjualan Tunai</td>
                            <td>:</td>
                            <td>Rp.</td>
                            <td class="right" style="font-weight: bold">{{ formatAngka($total_tunai) }}</td>
                        </tr>
                        <tr>
                            <td>Tagihan</td>
                            <td>:</td>
                            <td>Rp.</td>
                            <td class="right" style="font-weight: bold">{{ formatAngka($total_titipan) }}</td>
                        </tr>
                        <tr>
                            <td>Dikurangi</td>
                            <td>:</td>
                            <td>Rp.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Retur / BS</td>
                            <td>:</td>
                            <td>Rp.</td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="tandatangan" style=" border: 1px solid black; margin-top: 20px; width:99%">
            <table class="datatable4" style="width: 100%">
                <tr>
                    <td class="center">Dibuat Oleh,</td>
                    <td colspan="2" class="center">Mengetahui</td>
                </tr>
                <tr>
                    <td style="height: 30px"></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="center">
                        <u>{{ textCamelCase($salesman->nama_salesman) }}</u>
                        <br>
                        Salesman
                    </td>
                    <td class="center">
                        <u>(------------------)</u>
                        <br>
                        SPV Salesman
                    </td>
                    <td class="center">
                        <u>(------------------)</u>
                        <br>
                        Kepala Penjualan
                    </td>
                </tr>
            </table>
        </div>
    </section>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Kontrabon </title>
    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        body {
            font-family: 'Arial';
            font-size: 14px
        }

        @page {
            size: A4
        }


        .judul {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 20px;
            text-align: center;
            color: #005e2f
        }

        .judul2 {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 16px;


        }

        .huruf {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .ukuranhuruf {
            font-size: 12px;
        }


        hr.style2 {
            border-top: 3px double #8c8b8b;
        }
    </style>
</head>

<body>

    <body class="A4">

        <!-- Each sheet element should have the class "sheet" -->
        <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
        <section class="sheet padding-10mm">
            <h1 style="text-align: center; letter-spacing:4px">KONTRABON</h1>
            <table class="datatable3" style="width:100%">
                <tr>
                    <td style="width: 50%">
                        <b>TERIMA DARI :</b> {{ strtoupper($kontrabon->nama_supplier) }}
                    </td>
                    <td style="text-align: center">
                        <b>TANGGAL</b>
                        <br>
                        {{ DateToIndo($kontrabon->tanggal) }}
                    </td>
                    <td style="text-align: center">
                        <b>NO. KONTRABON</b>
                        <br>
                        {{ $kontrabon->no_kontrabon }}
                    </td>
                </tr>
            </table>
            <table class="datatable5" style="margin-top: 10px">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No. BPPB</th>
                    <th>No. Surat Jalan</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                </tr>
                @php
                    $totalpembelian = 0;
                @endphp
                @foreach ($detail as $d)
                    @php
                        $total = $d->qty * $d->harga + $d->penyesuaian;
                        $totalpembelian += $total;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d-m-y', strtotime($d->tgl_pembelian)) }}</td>
                        <td>{{ $d->no_bukti }}</td>
                        <td></td>
                        <td>{{ $d->nama_barang }}</td>
                        <td align="center">{{ formatAngkaDesimal($d->qty) }}</td>
                        <td align="right"> {{ formatAngkaDesimal($d->harga) }}</td>
                        <td align="right"> {{ formatAngkaDesimal($total) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="7">JUMLAH</th>
                    <th align="right"> {{ formatAngkaDesimal($totalpembelian) }}</th>
                </tr>
            </table>
            <table class="datatable4" style="margin-top:15px">
                <tr>
                    <td>TERBILANG</td>
                    <td><i>{{ ucwords(strtolower(terbilang($totalpembelian))) }}</i></td>
                </tr>
            </table>

            <table class="datatable3" style="margin-top: 10px; width:100%; font-size:16px">
                <tr>
                    <td style="font-size: 14px">DIBAYAR TANGGAL</td>
                    <td style="font-size: 14px">
                        {{ DateToIndo($kontrabon->tanggal) }}
                    </td>
                </tr>
            </table>
            <div style="display: flex; justify-content:space-between; margin-top:15px; ">
                <div style="width: 20%;">
                    Tembusan<br>
                    <ol>
                        <li>Rekanan</li>
                        <li>Accounting</li>
                        <li>Adm</li>
                    </ol>
                </div>
                <div style="width: 50% ;">
                    <table class="datatable3" style="width: 100%">
                        <tr>
                            <td style="font-weight: bold">DITERIMA</td>
                            <td style="font-weight: bold">DIBUAT</td>
                            <td style="font-weight: bold">DIPERIKSA</td>
                        </tr>
                        <tr>
                            <td style="height: 100px"></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
    </body>

</html>

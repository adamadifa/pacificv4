<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Ajuan Program Ikatan </title>
    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        @page {
            size: A4
        }

        body {
            font-family: 'Times New Roman';
            font-size: 14px
        }


        hr.style2 {
            border-top: 3px double #8c8b8b;
        }

        h4 {
            line-height: 1.1rem !important;
            margin: 0 0 5px 0 !important;
        }

        p {
            margin: 3px !important;
            line-height: 1.1rem;
        }

        ol {
            line-height: 1.2rem;
            margin: 0;
        }

        h3 {
            margin: 5px;
        }
    </style>
</head>

<body>

    <body class="A4">

        <!-- Each sheet element should have the class "sheet" -->
        <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
        <section class="sheet padding-10mm">
            <table class="datatable3">
                <tr>
                    <td>Kode Pencairan</td>
                    <td class="right">{{ $pencairanprogram->kode_pencairan }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td class="right">{{ DateToIndo($pencairanprogram->tanggal) }}</td>
                </tr>
                <tr>
                    <td>Periode Penjualan</td>
                    <td class="right">{{ $namabulan[$pencairanprogram->bulan] }} {{ $pencairanprogram->tahun }}</td>
                </tr>
                <tr>
                    <td>Program</td>
                    <td class="right">{{ $pencairanprogram->kode_program == 'PR001' ? 'BB & DP' : 'AIDA' }}</td>
                </tr>
                <tr>
                    <td>Cabang</td>
                    <td class="right">{{ $pencairanprogram->kode_cabang }}</td>
                </tr>

            </table>
            <br>
            <br>
            <table class="datatable3" width="100%">
                <thead style="background-color: #055b90; color:white">
                    <tr>
                        <td rowspan="2" class="text-center" valign="middle">No</td>
                        <td rowspan="2" class="text-center" valign="middle">Kode</td>
                        <td rowspan="2" valign="middle"> Pelanggan</td>
                        <td rowspan="2" class="text-center" valign="middle">Qty</td>
                        <td colspan="2" class="text-center" valign="middle">Diskon</td>
                        <td rowspan="2" class="text-center" valign="middle">Cashback</td>
                        <td rowspan="2" class="text-center" valign="middle">T/TF/V</td>
                        <td rowspan="2" class="text-center" valign="middle">Bank</td>
                        <td rowspan="2" class="text-center" valign="middle">No. Rek</td>
                        <td rowspan="2" class="text-center" valign="middle">Pemilik</td>

                    </tr>
                    <tr>
                        <td>Reguler</td>
                        <td>Kumulatif</td>
                    </tr>
                </thead>
                <tbody id="loaddetailpencairan">
                    @php
                        $metode_pembayaran = [
                            'TN' => 'Tunai',
                            'TF' => 'Transfer',
                            'VC' => 'Voucher',
                        ];
                    @endphp
                    @foreach ($detailpencairan as $d)
                        @php
                            $cashback = $d->diskon_kumulatif - $d->diskon_reguler;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td class="right">{{ formatAngka($d->jumlah) }}</td>
                            <td class="right">{{ formatAngka($d->diskon_reguler) }}</td>
                            <td class="right">{{ formatAngka($d->diskon_kumulatif) }}</td>
                            <td class="right">{{ formatAngka($cashback) }}</td>
                            <td>{{ !empty($d->metode_bayar) ? $metode_pembayaran[$d->metode_bayar] : '' }}</td>
                            <td>{{ $d->bank }}</td>
                            <td>{{ $d->no_rekening }}</td>
                            <td>{{ $d->pemilik_rekening }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </section>
    </body>

</html>

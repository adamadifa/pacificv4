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

        .sheet {
            overflow: auto !important;
            height: auto !important;
        }
    </style>
</head>

<body>

    <body class="A4 landscape">

        <!-- Each sheet element should have the class "sheet" -->
        <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
        <section class="sheet padding-10mm">
            <table class="datatable3">
                <tr>
                    <td>No. Pengajuan</td>
                    <td class="right">{{ $programikatan->no_pengajuan }}</td>
                </tr>
                <tr>
                    <td>No. Dokumen</td>
                    <td class="right">{{ $programikatan->nomor_dokumen }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td class="right">{{ DateToIndo($programikatan->tanggal) }}</td>
                </tr>
                <tr>
                    <td>Periode Penjualan</td>
                    <td class="right">{{ DateToIndo($programikatan->periode_dari) }} s.d
                        {{ DateToIndo($programikatan->periode_sampai) }}</td>
                </tr>
                <tr>
                    <td>Program</td>
                    <td class="right">{{ $programikatan->nama_program }}</td>
                </tr>
                <tr>
                    <td>Cabang</td>
                    <td class="right">{{ $programikatan->kode_cabang }}</td>
                </tr>

            </table>
            <br>
            <br>
            <br>
            <table class="datatable3" style="width: 100%">
                <thead style="background-color: #055b90; color:white">
                    <tr>
                        <td>No.</td>
                        <td>Kode</td>
                        <td>Pelanggan</td>
                        <td class="text-center">TOTAL<br>PENJUALAN</td>
                        <td class="text-center">Target</td>
                        <td class="text-center">%</td>
                        <td>Reward</td>
                        <td>Budget</td>
                        <td>T/TF/V</td>
                        <td>Rekening</td>
                        <td>Pemilik</td>
                        <td>Bank</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $metode_pembayaran = [
                            'TN' => 'Tunai',
                            'TF' => 'Transfer',
                            'VC' => 'Voucher',
                        ];
                    @endphp
                    @foreach ($detail as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_rata_rata) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                            <td class="text-center">
                                @php
                                    $kenaikan = $d->qty_target - $d->qty_rata_rata;
                                    $persentase = $d->qty_rata_rata == 0 ? 0 : ($kenaikan / $d->qty_rata_rata) * 100;
                                    $persentase = number_format($persentase, 2);
                                @endphp
                                {{ $persentase }}%
                            </td>
                            <td class="text-end">{{ formatAngka($d->reward) }}</td>
                            <td>{{ $d->budget }}</td>
                            <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>
                            <td>{{ $d->no_rekening }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </body>

</html>

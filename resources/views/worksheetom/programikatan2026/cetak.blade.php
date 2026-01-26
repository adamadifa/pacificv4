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
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Nama Pelanggan</th>
                        <th class="text-center">Rata-Rata</th>
                        <th class="text-center">Target<br>(Tambahan)</th>
                        <th class="text-center">Total</th>
                        <th class="text-end">Ach (%)</th>
                        <th class="text-end">TOP</th>
                        <th>Metode</th>
                        <th class="text-end">Pencairan</th>
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
                            <td class="text-center">{{ formatAngka($d->qty_avg) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_avg + $d->qty_target) }}</td>
                            <td class="text-end">
                                @php
                                    $kenaikan = $d->qty_target;
                                    $persentase = $d->qty_avg == 0 ? 0 : ($kenaikan / $d->qty_avg) * 100;
                                    $persentase = formatAngkaDesimal($persentase);
                                @endphp
                                {{ $persentase }}%
                            </td>
                            <td class="text-end">{{ $d->top }}</td>
                            <td>{{ $metode_pembayaran[$d->metode_pembayaran] ?? $d->metode_pembayaran }}</td>
                            <td class="text-end">{{ formatAngka($d->periode_pencairan) }} Bulan</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </body>

</html>

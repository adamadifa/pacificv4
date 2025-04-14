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
                    <td>Kode Pencairan</td>
                    <td class="right">{{ $pencairansimpanan->kode_pencairan }}</td>
                </tr>
                <tr>
                    <th>Pelanggan</th>
                    <td class="right">{{ $pencairansimpanan->kode_pelanggan }} - {{ $pencairansimpanan->nama_pelanggan }}
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td class="right">{{ DateToIndo($pencairansimpanan->tanggal) }}</td>
                </tr>
                <tr>
                    <td>Cabang</td>
                    <td class="right">{{ $pencairansimpanan->kode_cabang }}</td>
                </tr>
                <tr>
                    <td>Jumlah</td>
                    <td class="right">{{ formatAngka($pencairansimpanan->jumlah) }}</td>
                </tr>
            </table>

        </section>
    </body>

</html>

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
            width: auto !important;
            margin-left: 10px;
            margin-right: 10px;
        }

        .text-center {
            text-align: center;
        }

        .tabelpending thead th {
            background-color: #ecb00a !important;
            color: black !important;
        }

        .tabelpending tfoot th {
            background-color: #ecb00a !important;
            color: black !important;
        }

        .tabelpending tbody th {
            background-color: #ecb00a !important;
            color: black !important;

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
                    <th>Kode Pencairan</th>
                    <td class="text-end">{{ $pencairanprogram->kode_pencairan }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($pencairanprogram->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Periode Penjualan</th>
                    <td class="text-end">
                        @if ($pencairanprogram->semester == 1)
                            @php
                                $periode_start = $pencairanprogram->tahun . '-01-01';
                                $periode_end = date('Y-m-t', strtotime($pencairanprogram->tahun . '-06-01'));
                            @endphp
                        @endif
                        @if ($pencairanprogram->semester == 2)
                            @php
                                $periode_start = $pencairanprogram->tahun . '-07-01';
                                $periode_end = date('Y-m-t', strtotime($pencairanprogram->tahun . '-12-01'));
                            @endphp
                        @endif
                        {{ DateToIndo($periode_start) }} s/d {{ DateToIndo($periode_end) }}
                    </td>
                </tr>
                <tr>
                    <th>No. Dokumen</th>
                    <td class="text-end">{{ $pencairanprogram->nomor_dokumen }}</td>
                </tr>
                <tr>
                    <th>Program</th>
                    <td class="text-end">{{ $pencairanprogram->nama_program }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ strtoupper($pencairanprogram->nama_cabang) }}</td>
                </tr>

            </table>
            <br>
            <br>

            <table id="example" class="datatable3" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2">Nama Pelanggan</th>
                        {{-- <th colspan="3" class="text-center">Budget</th> --}}
                        <th rowspan="2" class="text-center">Target</th>
                        <th class="text-center" colspan="3">Realisasi</th>
                        <th class="text-center">Reward</th>

                        <th rowspan="2">Pembayaran</th>
                        <th rowspan="2">No. Rekening</th>
                        <th rowspan="2">Pemilik</th>
                        <th rowspan="2">Bank</th>


                    </tr>
                    <tr>
                        {{-- <th>SMM</th>
                        <th>RSM</th>
                        <th>GM</th> --}}
                        <th>Tunai</th>
                        <th>Kredit</th>
                        <th>Total</th>
                        {{-- <th>Tunai</th>
                                <th>Kredit</th> --}}
                        <th>Total</th>
                    </tr>

                </thead>
                <tbody id="loaddetailpencairan">
                    @php
                        $metode_pembayaran = [
                            'TN' => 'Tunai',
                            'TF' => 'Transfer',
                            'VC' => 'Voucher',
                        ];
                        $subtotal_reward = 0;
                        $grandtotal_reward = 0;
                        $bb_dep = ['PRIK004', 'PRIK001'];
                    @endphp
                    @foreach ($detail as $key => $d)
                        @php
                            $next_metode_pembayaran = @$detail[$key + 1]->metode_pembayaran;
                            $total_reward = $d->total_reward;
                            $subtotal_reward += $total_reward;
                            $grandtotal_reward += $total_reward;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_tunai) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_kredit) }}</td>
                            <td class="text-center">{{ formatAngka($d->jumlah) }}</td>
                            <td style="text-align: right">{{ formatAngka($total_reward) }}</td>
                            <td>{{ $d->metode_pembayaran }}</td>
                            <td>{{ $d->no_rekening }}</td>
                            <td>{{ $d->pemilik_rekening }}</td>
                            <td>{{ $d->bank }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="7">GRAND TOTAL REWARD </td>
                        <td style="text-align: right">{{ formatAngka($grandtotal_reward) }}</td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
            <br>
            <br>
            {{-- <table class="datatable3">
                <tr>
                    <th colspan="2">REKAP PEMBAYARAN</th>
                </tr>
                <tr>
                    <th>TRANSFER</th>
                    <td class="right">{{ formatAngka($grandtotal_transfer) }}</td>
                </tr>
                <tr>
                    <th>TUNAI</th>
                    <td class="right">{{ formatAngka($grandtotal_tunai) }}</td>
                </tr>
            </table> --}}
            <br>

            <br>
            <br>

        </section>

    </body>

</html>

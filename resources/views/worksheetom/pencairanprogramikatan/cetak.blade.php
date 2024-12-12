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
                    <td>No. Dokumen</td>
                    <td class="right">{{ $pencairanprogram->nomor_dokumen }}</td>
                </tr>
                <tr>
                    <td>Program</td>
                    <td class="right">{{ $pencairanprogram->nama_program }}</td>
                </tr>
                <tr>
                    <td>Cabang</td>
                    <td class="right">{{ strtoupper($pencairanprogram->nama_cabang) }}</td>
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
                        <td class="text-center">Target</td>
                        <td class="text-center">Realisasi</td>
                        <td>Reward</td>
                        <td>T/TF/V</td>
                        <td>No. Rekening</td>
                        <td>Pemilik</td>
                        <td>Bank</td>
                        <td>Total</td>
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
                    @endphp
                    @foreach ($detail as $key => $d)
                        @php
                            $next_metode_pembayaran = @$detail[$key + 1]->metode_pembayaran;
                            $total_reward = $d->reward * $d->jumlah;
                            $subtotal_reward += $total_reward;
                            $grandtotal_reward += $total_reward;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                            <td class="text-center">{{ formatAngka($d->jumlah) }}</td>
                            <td class="right">{{ formatAngka($d->reward) }}</td>

                            <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>
                            <td>{{ $d->no_rekening }}</td>
                            <td>{{ $d->pemilik_rekening }}</td>
                            <td>{{ $d->bank }}</td>
                            <td class="right">{{ formatAngka($total_reward) }}</td>

                        </tr>
                        @if ($d->metode_pembayaran != $next_metode_pembayaran)
                            <tr class="table-dark" style="background-color: #055b90; color:white">
                                <td colspan="10">TOTAL REWARD </td>
                                <td class="right">{{ formatAngka($subtotal_reward) }}</td>
                            </tr>
                            @php
                                $subtotal_reward = 0;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot class="table-dark" style="background-color: #055b90; color:white">
                    <tr>
                        <td colspan="10">GRAND TOTAL REWARD </td>
                        <td class="right">{{ formatAngka($grandtotal_reward) }}</td>

                    </tr>
                </tfoot>
            </table>
        </section>
    </body>

</html>

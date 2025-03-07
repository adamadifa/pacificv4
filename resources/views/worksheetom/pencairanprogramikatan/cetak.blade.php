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
                    <td class="right">{{ $pencairanprogram->nama_program }}</td>
                </tr>
                <tr>
                    <td>Cabang</td>
                    <td class="right">{{ strtoupper($pencairanprogram->nama_cabang) }}</td>
                </tr>
            </table>
            <br>
            <br>

            <table class="datatable3" style="width: 100%">
                <thead style="background-color: #055b90; color:white">
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2">Nama Pelanggan</th>
                        <th colspan="3" class="text-center">Budget</th>
                        <th rowspan="2" class="text-center">Target</th>
                        <th class="text-center" colspan="3">Realisasi</th>
                        <th colspan="3" class="text-center">Reward</th>

                        <th rowspan="2">Pembayaran</th>
                        <th rowspan="2">No. Rekening</th>
                        <th rowspan="2">Pemilik</th>
                        <th rowspan="2">Bank</th>
                    </tr>
                    <tr>
                        <th>SMM</th>
                        <th>RSM</th>
                        <th>GM</th>
                        <th>Tunai</th>
                        <th>Kredit</th>
                        <th>Total</th>
                        <th>Tunai</th>
                        <th>Kredit</th>
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
                        $grandtotal_reward_tunai = 0;
                        $grandtotal_reward_kredit = 0;
                        $subtotal_reward_tunai = 0;
                        $subtotal_reward_kredit = 0;
                        $grandtotal_transfer = 0;
                        $grandtotal_tunai = 0;

                        $bb_dep = ['PRIK004', 'PRIK001'];
                    @endphp
                    @foreach ($detail as $key => $d)
                        @php
                            $next_metode_pembayaran = @$detail[$key + 1]->metode_pembayaran;
                            $total_reward = $d->total_reward > 1000000 && !in_array($d->kode_program, $bb_dep) ? 1000000 : $d->total_reward;
                            $subtotal_reward_tunai += $d->reward_tunai;
                            $subtotal_reward_kredit += $d->reward_kredit;
                            $subtotal_reward += $total_reward;
                            $grandtotal_reward += $total_reward;
                            $grandtotal_reward_tunai += $d->reward_tunai;
                            $grandtotal_reward_kredit += $d->reward_kredit;
                            $bgcolor = $d->status_pencairan == 0 ? 'yellow' : '';
                            $grandtotal_transfer += $d->metode_pembayaran == 'TF' ? $total_reward : 0;
                            $grandtotal_tunai += $d->metode_pembayaran == 'TN' ? $total_reward : 0;
                        @endphp
                        <tr style="background-color: {{ $bgcolor }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td class="right">{{ formatAngka($d->budget_smm) }}</td>
                            <td class="right">{{ formatAngka($d->budget_rsm) }}</td>
                            <td class="right">{{ formatAngka($d->budget_gm) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_tunai) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_kredit) }}</td>
                            <td class="text-center">{{ formatAngka($d->jumlah) }}</td>
                            <td class="right">{{ formatAngka($d->reward_tunai) }}</td>
                            <td class="right">{{ formatAngka($d->reward_kredit) }}</td>
                            <td class="right">{{ formatAngka($total_reward) }}</td>
                            <td>{{ $metode_pembayaran[$d->metode_pembayaran] }}</td>

                            <td>'{{ $d->no_rekening }}</td>
                            <td>{{ $d->pemilik_rekening }}</td>
                            <td>{{ $d->bank }}</td>
                        </tr>
                        @if ($d->metode_pembayaran != $next_metode_pembayaran)
                            <tr class="table-dark" style="background-color: #055b90; color:white">
                                <th colspan="10">TOTAL REWARD </th>
                                <th class="right">{{ formatAngka($subtotal_reward_tunai) }}</th>
                                <th class="right">{{ formatAngka($subtotal_reward_kredit) }}</th>
                                <th class="right">{{ formatAngka($subtotal_reward) }}</th>
                                <th colspan="4"></th>
                            </tr>
                            @php
                                $subtotal_reward = 0;
                                $subtotal_reward_tunai = 0;
                                $subtotal_reward_kredit = 0;
                            @endphp
                        @endif
                    @endforeach
                </tbody>
                <tfoot class="table-dark" style="background-color: #055b90; color:white">
                    <tr>
                        <th colspan="10">GRAND TOTAL REWARD </th>
                        <th class="right">{{ formatAngka($grandtotal_reward_tunai) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_reward_kredit) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_reward) }}</th>
                        <th colspan="4"></th>
                    </tr>
                </tfoot>
            </table>
            <br>
            <br>
            <table class="datatable3">
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
            </table>
            <br>

            <br>
            <br>

        </section>
        <section class="sheet padding-10mm">
            <table class="datatable3 tabelpending" style="width: 100%">
                <thead style="background-color: #ecb00a;">
                    <tr>
                        <th colspan="13">Pencairan Yang di tangguhkan dan disimpan sebagai Saldo</th>
                    </tr>
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2">Kode</th>
                        <th rowspan="2">Nama Pelanggan</th>
                        <th colspan="3" class="text-center">Budget</th>
                        <th rowspan="2" class="text-center">Target</th>
                        <th class="text-center" colspan="3">Realisasi</th>
                        <th colspan="3" class="text-center">Reward</th>
                    </tr>
                    <tr>
                        <th>SMM</th>
                        <th>RSM</th>
                        <th>GM</th>
                        <th>Tunai</th>
                        <th>Kredit</th>
                        <th>Total</th>
                        <th>Tunai</th>
                        <th>Kredit</th>
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

                        $bb_dep = ['PRIK004', 'PRIK001'];
                        $subtotal_reward = 0;
                        $grandtotal_reward = 0;
                        $grandtotal_reward_tunai = 0;
                        $grandtotal_reward_kredit = 0;
                        $subtotal_reward_tunai = 0;
                        $subtotal_reward_kredit = 0;
                        $grandtotal_transfer = 0;
                        $grandtotal_tunai = 0;
                    @endphp
                    @foreach ($detail_hold as $key => $d)
                        @php
                            $next_metode_pembayaran = @$detail[$key + 1]->metode_pembayaran;
                            $total_reward = $d->total_reward > 1000000 && !in_array($d->kode_program, $bb_dep) ? 1000000 : $d->total_reward;
                            $subtotal_reward_tunai += $d->reward_tunai;
                            $subtotal_reward_kredit += $d->reward_kredit;
                            $subtotal_reward += $total_reward;
                            $grandtotal_reward += $total_reward;
                            $grandtotal_reward_tunai += $d->reward_tunai;
                            $grandtotal_reward_kredit += $d->reward_kredit;
                            $bgcolor = $d->status_pencairan == 0 ? 'yellow' : '';
                            $grandtotal_transfer += $d->metode_pembayaran == 'TF' ? $total_reward : 0;
                            $grandtotal_tunai += $d->metode_pembayaran == 'TN' ? $total_reward : 0;
                        @endphp
                        <tr style="background-color: {{ $bgcolor }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_pelanggan }}</td>
                            <td>{{ $d->nama_pelanggan }}</td>
                            <td class="right">{{ formatAngka($d->budget_smm) }}</td>
                            <td class="right">{{ formatAngka($d->budget_rsm) }}</td>
                            <td class="right">{{ formatAngka($d->budget_gm) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_target) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_tunai) }}</td>
                            <td class="text-center">{{ formatAngka($d->qty_kredit) }}</td>
                            <td class="text-center">{{ formatAngka($d->jumlah) }}</td>
                            <td class="right">{{ formatAngka($d->reward_tunai) }}</td>
                            <td class="right">{{ formatAngka($d->reward_kredit) }}</td>
                            <td class="right">{{ formatAngka($total_reward) }}</td>
                        </tr>
                        {{-- @if ($d->metode_pembayaran != $next_metode_pembayaran)
                            <tr class="table-dark" style="background-color: #ecb00a;">
                                <th colspan="10">TOTAL REWARD </th>
                                <th class="right">{{ formatAngka($subtotal_reward_tunai) }}</th>
                                <th class="right">{{ formatAngka($subtotal_reward_kredit) }}</th>
                                <th class="right">{{ formatAngka($subtotal_reward) }}</th>
                            </tr>
                            @php
                                $subtotal_reward = 0;
                            @endphp
                        @endif --}}
                    @endforeach
                </tbody>
                <tfoot style="background-color: #ecb00a;">
                    <tr>
                        <th colspan="10">GRAND TOTAL REWARD </th>
                        <th class="right">{{ formatAngka($grandtotal_reward_tunai) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_reward_kredit) }}</th>
                        <th class="right">{{ formatAngka($grandtotal_reward) }}</th>
                    </tr>
                </tfoot>
            </table>
        </section>
    </body>

</html>

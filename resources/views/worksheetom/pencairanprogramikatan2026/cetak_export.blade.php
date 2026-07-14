<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export Pencairan Program Ikatan 2026</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
        }
        .datatable3 {
            border: 1px solid #ccc;
            border-collapse: collapse;
            font-family: sans-serif;
        }
        .datatable3 td, .datatable3 th {
            border: 1px solid #ccc;
            padding: 5px;
        }
        .text-center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td><strong>Kode Pencairan</strong></td>
            <td>{{ $pencairanprogram->kode_pencairan }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td>{{ DateToIndo($pencairanprogram->tanggal) }}</td>
        </tr>
        <tr>
            <td><strong>Program & Cabang</strong></td>
            <td>{{ $pencairanprogram->nama_program }} ({{ strtoupper($pencairanprogram->nama_cabang) }})</td>
        </tr>
        <tr>
            <td><strong>Periode Penjualan</strong></td>
            <td>Semester {{ $pencairanprogram->semester }} {{ $pencairanprogram->tahun }}</td>
        </tr>
    </table>
    <br>
    <table class="datatable3" width="100%">
        <thead>
            <tr style="background-color: #055b90; color:white">
                <th>No</th>
                <th>Kode Pelanggan</th>
                <th>Nama Pelanggan</th>
                <th>AVG</th>
                <th>Target</th>
                <th>Total</th>
                <th>Increment</th>
                <th>Realisasi</th>
                <th>Kredit Melebihi TOP</th>
                <th>Rate</th>
                <th>Reward</th>
                <th>Bank</th>
                <th>No. Rekening</th>
                <th>Pemilik Rekening</th>
                <th>Metode Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @php
                $metode_pembayaran = [
                    'TN' => 'Tunai',
                    'TF' => 'Transfer',
                    'VC' => 'Voucher',
                ];
                $total_all_reward = 0;
            @endphp
            @foreach ($detail as $key => $d)
                @php
                    $total_reward = $d->reward;
                    $total_all_reward += $total_reward;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $d->kode_pelanggan }}</td>
                    <td>{{ $d->nama_pelanggan }}</td>
                    <td class="right">{{ formatAngka($d->avg ?? 0) }}</td>
                    <td class="right">{{ formatAngka($d->target_perbulan ?? 0) }}</td>
                    <td class="right">{{ formatAngka(($d->avg ?? 0) + ($d->target_perbulan ?? 0)) }}</td>
                    <td class="right">{{ formatAngka($d->kenaikan_per_bulan ?? 0) }}</td>
                    <td class="right">{{ formatAngka($d->realisasi ?? 0) }}</td>
                    <td class="right text-danger">{{ formatAngka($d->kredit_melebihi_top ?? 0) }}</td>
                    <td class="right">{{ formatAngka($d->rate ?? 0) }}</td>
                    <td class="right">{{ formatAngka($total_reward) }}</td>
                    <td>{{ $d->bank }}</td>
                    <td>'{{ $d->no_rekening }}</td>
                    <td>{{ $d->pemilik_rekening }}</td>
                    <td>{{ isset($metode_pembayaran[$d->metode_pembayaran]) ? $metode_pembayaran[$d->metode_pembayaran] : $d->metode_pembayaran }}</td>
                </tr>
            @endforeach
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td colspan="10" class="text-center">GRAND TOTAL REWARD</td>
                <td class="right">{{ formatAngka($total_all_reward) }}</td>
                <td colspan="4"></td>
            </tr>
        </tbody>
    </table>
</body>

</html>

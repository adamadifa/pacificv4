<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visit Pelanggan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4>Visit Pelanggan</h4>
        <h4>{{ $cabang != null ? textUpperCase($cabang->nama_pt) . '(' . textUpperCase($cabang->nama_cabang) . ')' : '' }}</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="body">
        <table class="datatable3" border="1">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Faktur</th>
                    <th>Kode Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>Tgl Faktur</th>
                    <th>Nilai Faktur</th>
                    <th>Tunai/Kredit</th>
                    <th>Jenis Kunjungan</th>
                    <th>C1: OTS?</th>
                    <th>C2: Limit/Diskon?</th>
                    <th>Valid OM?</th>
                    <th>Hasil Konfirmasi</th>
                    <th>Note</th>
                    <th>Saran / Keluhan Produk</th>
                    <th>Act OM</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($visit as $d)
                    @php
                        $is_ots = $d->jenis_kunjungan === 'OTS';
                        $is_faktur_valid = false;
                        if ($d->jenis_transaksi === 'K') {
                            $is_faktur_valid = $d->total_netto >= 1000000;
                        } elseif ($d->jenis_transaksi === 'T') {
                            $is_faktur_valid = $d->potongan > 0 || $d->potongan_istimewa > 0;
                        }
                        $is_valid_om = $is_ots && $is_faktur_valid;
                    @endphp
                    <tr>
                        <td>{{ formatIndo($d->tanggal) }}</td>
                        <td>{{ $d->no_faktur }}</td>
                        <td>{{ $d->kode_pelanggan }}</td>
                        <td>{{ $d->nama_pelanggan }}</td>
                        <td>{{ $d->alamat_pelanggan }}</td>
                        <td>{{ formatIndo($d->tanggal_faktur) }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ formatRupiah($d->total_netto) }}</td>
                        <td>{{ $d->jenis_transaksi == 'K' ? 'Kredit' : 'Tunai' }}</td>
                        <td>{{ $d->jenis_kunjungan }}</td>
                        <td style="text-align: center; background-color: {{ $is_ots ? '#d4edda' : '#f8d7da' }}">{{ $is_ots ? 'YA' : 'TIDAK' }}</td>
                        <td style="text-align: center; background-color: {{ $is_faktur_valid ? '#d4edda' : '#f8d7da' }}">{{ $is_faktur_valid ? 'YA' : 'TIDAK' }}</td>
                        <td style="text-align: center; font-weight: bold; background-color: {{ $is_valid_om ? '#c3e6cb' : '#f5c6cb' }}">{{ $is_valid_om ? 'YA' : 'TIDAK' }}</td>
                        <td>{{ $d->hasil_konfirmasi }}</td>
                        <td>{{ $d->note }}</td>
                        <td>{{ $d->saran }}</td>
                        <td>{{ $d->act_om }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
